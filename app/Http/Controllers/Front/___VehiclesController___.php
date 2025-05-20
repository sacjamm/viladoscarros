<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Amenity;
use App\Models\Listing;
use App\Models\ListingBrand;
use App\Models\ListingLocation;
use App\Models\ListingSocialItem;
use App\Models\ListingAdditionalFeature;
use App\Models\ListingPhoto;
use App\Models\ListingVideo;
use App\Models\ListingAmenity;
use App\Models\Package;
use App\Models\PackagePurchase;
use App\Models\Review;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Hash;
use SimpleXMLElement;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VehiclesController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function xml() {
        $canal = 'dsautoestoque';
        $url = 'https://xml.dsautoestoque.com/?l=18727923000458,55012209000167,41905893000100,9375326000500,14037313000129,39311270000159,85054249000132,11965101000113,44602040000189,28654819000191,49673090000170,2616115,5000101,71973518000150,57498706000142,41424430000118,58030937000190,0245440000170,32263796000161,53973129000142,33345127000282,45955141000104,38174151000139&v=2';
        // Tenta consumir o conteúdo do XML
        $xmlContent = @file_get_contents($url);

        if ($xmlContent === FALSE) {
            //$this->error("Erro ao acessar a URL: " . $url);
            return;
        }
        try {
            // Parse do XML
            $xml = new SimpleXMLElement($xmlContent, LIBXML_NOCDATA);
            // Processa cada loja no XML
            foreach ($xml->veiculo as $veiculo) {
                if (empty($veiculo->loja)) {
                    continue; // Pula se a tag loja não existir
                }
                $this->processVehicle($veiculo, $canal);
            }
            //$this->info("Importação concluída com sucesso.");
        } catch (Exception $e) {
            //$this->error("Erro ao processar o XML: " . $e->getMessage());
        }
    }

    private function processVehicle($veiculo, $canal = 'dsautoestoque') {
        $loja = $veiculo->loja;
        $veiculo_id = $veiculo->id;
        $veiculo_data = (array) $veiculo;
        // Validação de CNPJ
        $cnpj = $this->limpaCPF_CNPJ($loja->cnpj);
        $data_cnpj = $this->mask($cnpj, '##.###.###/####-##');
        $userData = [
            'cnpj' => (string) $data_cnpj,
            'name' => (string) $loja->nomefantasia,
            'email' => (string) $loja->contato->email ?? '',
            'phone' => (string) $loja->contato->telefone ?? '',
            'address' => (string) $loja->endereco->logradouro ?? '',
            'state' => (string) $loja->endereco->uf ?? '',
            'city' => (string) $loja->endereco->cidade ?? '',
            'zip' => (string) $loja->endereco->cep ?? '',
            'website' => (string) $loja->contato->site ?? '',
        ];
        $userid = $this->isCnpjRegistered($userData['cnpj']);
        $title = $this->montaTituloVeiculo($veiculo_data, $veiculo_id, $canal);

        if (!empty($userid)) {
            $user_id = $userid;
            if ($user_id) {
                $this->prepareVehicleData($veiculo, $veiculo_id, $title, $user_id, $canal);
            }
        } else {
            // Cadastra o usuário
            try {
                $user_id = $this->registerUser($userData);
                if ($user_id) {
                    $this->prepareVehicleData($veiculo, $veiculo_id, $title, $user_id, $canal);
                }
                //$this->info("Agência " . $userData['name'] . " cadastrada com sucesso!");
            } catch (Exception $e) {
                //$this->error("Erro ao cadastrar loja: " . $userData['name'] . " - " . $userData['cnpj'] . " - " . $e->getMessage());
            }
        }
    }

    private function prepareVehicleData($veiculo, $veiculo_id, $title, $user_id, $canal) {
        $loja = $veiculo->loja;

        // Preparando os dados do anúncio
        $listingData = [
            'listing_slug' => Str::slug($title),
            'user_id' => (int) $user_id,
            'admin_id' => 0,
            'veiculo_id' => $veiculo_id,
            'listing_name' => $title,
            'seo_title' => $title,
            'listing_status' => 'Active',
            'canal' => $canal,
            'is_featured' => 'Yes',
            'listing_description' => !empty($veiculo->observacao) ? trim($veiculo->observacao) : '***',
            'listing_price' => $this->extractVehiclePrice($veiculo->preco ?? 0),
            'listing_type' => empty($veiculo->zerokm) || $veiculo->zerokm === 'Y' ? 'Novo' : 'Usado',
            'listing_featured_photo' => 'sem-veiculo.jpg',
        ];

        // Preparando os dados do usuário
        $userData = [
            'name' => (string) $loja->nomefantasia,
            'email' => (string) ($loja->contato->email ?? ''),
            'phone' => (string) ($loja->contato->telefone ?? ''),
            'address' => (string) ($loja->endereco->logradouro ?? ''),
            'state' => (string) ($loja->endereco->uf ?? ''),
            'city' => (string) ($loja->endereco->cidade ?? ''),
            'zip' => (string) ($loja->endereco->cep ?? ''),
            'website' => (string) ($loja->contato->site ?? ''),
        ];

        // Construindo o endereço do anúncio
        $listingData['listing_address'] = implode(', ', [
            $userData['address'],
            $userData['city'] . '-' . $userData['state'],
            $userData['zip']
        ]);

        // Adicionando dados do veículo ao anúncio
        $listingData = array_merge($listingData, [
            'listing_transmission' => $veiculo->cambio,
            'listing_exterior_color' => $veiculo->cor,
            'listing_fuel_type' => $veiculo->combustivel,
            'listing_body' => $veiculo->carroceria,
            'listing_mileage' => $veiculo->km,
            'listing_model_year' => $veiculo->anomodelo,
            'listing_door' => $veiculo->portas,
            'listing_tipo_veiculo' => $veiculo->tipoveiculo,
            'listing_phone' => $userData['phone'],
            'listing_email' => $userData['email'],
            'listing_website' => $userData['website'],
            'listing_brands' => isset($veiculo->marca) ? json_encode((array) $veiculo->marca) : null,
            'listing_amenities' => isset($veiculo->acessorios) ? json_encode((array) $veiculo->acessorios) : null,
            'listing_photos' => isset($veiculo->fotos) ? json_encode((array) $veiculo->fotos) : null,
            'listing_locations' => isset($veiculo->loja) ? json_encode((array) $veiculo->loja) : null,
            'listing_additional_features' => isset($veiculo->opcionais) ? json_encode((array) $veiculo->opcionais) : null,
        ]);

        // Processando IDs de veículos, marcas e locais
        $listing_Id = $this->isVehicleRegistered($veiculo_id, $canal);
        $listingData['listing_brand_id'] = $this->processBrands($veiculo->marca);
        $listingData['listing_location_id'] = $this->processLocations($veiculo);

        // Criando ou atualizando o anúncio
        $listing = new Listing();

        if (!empty($listing_Id)) {
            $listingId = $listing_Id;
        } else {
            $listing->fill($listingData)->save();
            $listingId = $listing->id;
        }
        // Processando acessórios, fotos e recursos adicionais
        $this->processAmenities($veiculo->acessorios ?? [], $listingId);
        $this->processPhotos($veiculo->fotos ?? [], $listingId);
        $this->processAdditionalFeatures($veiculo->opcionais ?? [], $listingId);

        return $listingData;
    }

    private function prepareVehicleDataOld($veiculo, $veiculo_id, $title, $user_id, $canal) {
        $loja = $veiculo->loja;
        $listingData = [
            'listing_slug' => Str::slug($title),
            'user_id' => (int) $user_id,
            'admin_id' => 0,
            'veiculo_id' => $veiculo_id,
            'listing_name' => $title,
            'seo_title' => $title,
            'listing_status' => 'Active',
            'canal' => $canal,
            'is_featured' => 'Yes',
            'listing_description' => !empty($veiculo->observacao) ? (string) trim($veiculo->observacao) : '***',
            'listing_price' => !empty($veiculo->preco) ? $this->extractVehiclePrice($veiculo->preco) : 0,
            'listing_type' => !empty($veiculo->zerokm) && $veiculo->zerokm == 'N' ? 'Usado' : 'Novo',
            'listing_featured_photo' => 'sem-veiculo.jpg',
        ];

        $userData = [
            'name' => (string) $loja->nomefantasia,
            'email' => (string) $loja->contato->email ?? '',
            'phone' => (string) $loja->contato->telefone ?? '',
            'address' => (string) $loja->endereco->logradouro ?? '',
            'state' => (string) $loja->endereco->uf ?? '',
            'city' => (string) $loja->endereco->cidade ?? '',
            'zip' => (string) $loja->endereco->cep ?? '',
            'website' => (string) $loja->contato->site ?? '',
        ];

        $listingData['listing_address'] = implode(', ', [
            $userData['address'],
            $userData['city'] . '-' . $userData['state'],
            $userData['zip']
        ]);

        $listingData['listing_transmission'] = $veiculo->cambio;
        $listingData['listing_exterior_color'] = $veiculo->cor;
        $listingData['listing_fuel_type'] = $veiculo->combustivel;
        $listingData['listing_body'] = $veiculo->carroceria;
        $listingData['listing_mileage'] = $veiculo->km;
        $listingData['listing_model_year'] = $veiculo->anomodelo;
        $listingData['listing_door'] = $veiculo->portas;
        $listingData['listing_tipo_veiculo'] = $veiculo->tipoveiculo;

        $listingData['listing_phone'] = $userData['phone'];
        $listingData['listing_email'] = $userData['email'];
        $listingData['listing_website'] = $userData['website'];

        $listingData['listing_brands'] = isset($veiculo->marca) ? json_encode((array) $veiculo->marca) : null;
        $listingData['listing_amenities'] = isset($veiculo->acessorios) ? json_encode((array) $veiculo->acessorios) : null;
        $listingData['listing_photos'] = isset($veiculo->fotos) ? json_encode((array) $veiculo->fotos) : null;
        $listingData['listing_locations'] = isset($veiculo->loja) ? json_encode((array) $veiculo->loja) : null;
        $listingData['listing_additional_features'] = isset($veiculo->opcionais) ? json_encode((array) $veiculo->opcionais) : null;

        $vehicleId = $this->isVehicleRegistered($veiculo_id, $canal);

        $brand_id = $this->processBrands($veiculo->marca);
        $listingData['listing_brand_id'] = $brand_id;

        $location_id = $this->processLocations($veiculo);
        $listingData['listing_location_id'] = $location_id;

        $listing = new Listing();

        if (!empty($vehicleId)) {
            $listingId = $vehicleId;
        } else {
            $listing->fill($listingData)->save();
            $listingId = $listing->id;
        }

        $this->processAmenities($veiculo->acessorios ? $veiculo->acessorios : [], $listingId);
        $this->processPhotos($veiculo->fotos ? $veiculo->fotos : [], $listingId);
        $this->processAdditionalFeatures($veiculo->opcionais ? $veiculo->opcionais : [], $listingId);

        return $listingData;
    }

    private function processLocations($veiculo) {
        $loja = $veiculo->loja;
        $name = $loja->endereco->cidade;
        $slug = Str::slug($loja->endereco->cidade);

        $locationId = $this->isLocationRegistered($slug);
        if ($locationId) {
            return $locationId;
        } else {
            $location = new ListingLocation();
            $locationData = [
                'listing_location_name' => $name,
                'listing_location_slug' => $slug,
                'listing_location_photo' => 'sem-localizacao.png',
                'seo_title' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $location->fill($locationData)->save();
            return $location->id;
        }
    }

    private function processBrands($name) {
        $slug = Str::slug($name);
        $brandId = $this->isBrandRegistered($slug);
        if ($brandId) {
            return $brandId;
        } else {
            $brand = new ListingBrand();
            $brandData = [
                'listing_brand_name' => $name,
                'listing_brand_slug' => $slug,
                'listing_brand_photo' => 'sem-veiculo.jpg',
                'seo_title' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $brand->fill($brandData)->save();
            return $brand->id;
        }
    }

    private function processAdditionalFeatures($opcionais, $listingId) {
        if (isset($opcionais->opcional)) {
            $opcion = (array) $opcionais->opcional;
            unset($opcion["@attributes"]);
            $conta = count($opcion);
            if ($conta > 0) {
                $opcionais_array = array();
                foreach ($opcion as $opcional) {
                    $opcionais_array[] = $opcional;
                }
                for ($i = 0; $i < count($opcionais_array); $i++) {
                    $obj = new ListingAdditionalFeature;
                    $obj->listing_id = $listingId;
                    $obj->additional_feature_name = $opcionais_array[$i];
                    $obj->additional_feature_value = !empty($opcionais_array[$i]) ? 'Sim' : 'Não';
                    $obj->created_at = now();
                    $obj->updated_at = now();
                    $obj->save();
                }
            }
        }
    }

    private function processAmenities($acessorios, $listingId) {
        if ($acessorios) {
            $acessorios_array = array();
            foreach ($acessorios as $item) {
                if (isset($item->acessorio)) {
                    $acessorios_array[] = $item->acessorio;
                }
            }
            for ($i = 0; $i < count($acessorios_array); $i++) {
                $amenity = new Amenity();
                $amenityData = [
                    'amenity_name' => $acessorios_array[$i],
                    'amenity_slug' => Str::slug($acessorios_array[$i]),
                ];
                $amenity->fill($amenityData)->save();
                // Cria a relação com o Listing
                $listingAmenity = new ListingAmenity();
                $listingAmenity->listing_id = $listingId;
                $listingAmenity->amenity_id = $amenity->id;
                $listingAmenity->save();
            }
        }
    }

    private function processPhotos($fotos, $listingId) {
        $fotos_array = [];
        // Verifica se existem fotos e a propriedade "foto" está presente
        if (!empty($fotos) && isset($fotos->foto)) {
            // Converte o objeto SimpleXMLElement em um array para fácil manipulação
            $fotosImg = (array) $fotos->foto; //is_array($fotos->foto) ? $fotos->foto : [$fotos->foto];
            // Itera sobre cada foto para salvar no banco de dados
            foreach ($fotosImg as $img) {
                $fotos_array[] = $img;
                $this->savePhoto($img, $listingId);
            }
        }
    }

// Função auxiliar para salvar cada foto
    private function savePhoto($media, $listingId) {
        // Cria uma nova instância de ListingPhoto e salva a foto
        $photo = new ListingPhoto();
        $photo->listing_id = $listingId;
        $photo->photo = $media;
        $photo->created_at = now();
        $photo->updated_at = now();
        $photo->save();
    }

    private function extractVehiclePrice($preco) {
        if (empty($preco)) {
            return 0;
        } else {
            $p = explode(' ', $preco);
            if (isset($p[1])) {
                $pr = explode(',', $p[1]);
                if (isset($pr[0])) {
                    return $this->limpaCPF_CNPJ($pr[0]);
                }
            } else {
                return 0;
            }
        }
    }

    private function isLocationRegistered($slug) {
        $result = ListingLocation::where('listing_location_slug', $slug)->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }

    private function isBrandRegistered($slug) {
        $result = ListingBrand::where('listing_brand_slug', $slug)->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }

    private function isCnpjRegistered($cnpj) {
        $result = User::where('cnpj', $cnpj)->first();
        if (!empty($result)) {
            return $result->id;
        } else {
            return 0;
        }
    }

    private function isVehicleRegistered($veiculo_id, $canal = 'dsautoestoque') {
        $vehicle = Listing::where('veiculo_id', $veiculo_id)->where('canal', $canal)->first();
        if ($vehicle) {
            return $vehicle->id;
        } else {
            return 0;
        }
    }

    private function registerUser($userData) {
        $token = hash('sha256', time());
        $password = $this->generatePassword();
        // Cria um novo usuário
        $user = new User();
        $user->name = $userData['name'];
        $user->email = $userData['email'];
        $user->cnpj = $userData['cnpj'];
        $user->phone = $userData['phone'];
        $user->address = $userData['address'];
        $user->state = $userData['state'];
        $user->country = 'BRA';
        $user->city = $userData['city'];
        $user->zip = $userData['zip'];
        $user->website = $userData['website'];
        $user->password = Hash::make($password); // Defina uma senha padrão ou gere uma aleatória
        $user->token = $token;
        $user->status = 'Active';
        $user->save();

        $lastInsertedId = $user->id;
        if ($lastInsertedId) {
            return $lastInsertedId;
        } else {
            return false;
        }
    }

    private function generatePassword() {
        // Gera uma senha aleatória de 10 caracteres
        return bin2hex(random_bytes(5));
    }

    private function limpaCPF_CNPJ($value) {
        // Remove caracteres especiais de CPF/CNPJ
        return preg_replace('/[^0-9]/', '', $value);
    }

    private function mask($val, $mask) {
        // Aplica uma máscara ao CPF/CNPJ
        $masked = '';
        $k = 0;
        for ($i = 0; $i < strlen($mask); $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $masked .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $masked .= $mask[$i];
                }
            }
        }
        return $masked;
    }

    function montaTituloVeiculo($veiculo, $ID = 0, $canal = 'dsautoestoque') {
        $marca = $this->verificaString($veiculo['marca']);
        $modelo = $this->verificaString($veiculo['modelo']);
        $versao = $this->verificaString($veiculo['versao']);
        $anomodelo = $this->verificaString($veiculo['anomodelo']);
        return trim(preg_replace('/\s+/', ' ', "$marca $modelo $versao $anomodelo"));
    }

    function verificaString($valor) {
        return is_string($valor) ? trim($valor) : '';
    }
}
