<?php

namespace App\Console\Commands;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Amenity;
use App\Models\Listing;
use App\Models\ListingBrand;
use App\Models\Transmission;
use App\Models\Combustivel;
use App\Models\Color;
use App\Models\Versao;
use App\Models\ListingLocation;
use App\Models\ListingAdditionalFeature;
use App\Models\ListingPhoto;
use App\Models\ListingAmenity;
use App\Models\GeneralSetting;
use SimpleXMLElement;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use finfo;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImportLojas extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'import:lojas';
    protected $signature = 'import:lojas {--create-table=} {--add-field=} {--add-field-versoes=} {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa lojas de um arquivo XML e cadastra usuários no Laravel';
    public $canal = 'dsautoestoque';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function limparCNPJ($cnpj) {
        return preg_replace('/[^0-9]/', '', $cnpj);
    }

    public function montarUrlComCnpjs() {
        // Consulta os CNPJs da tabela users com status 'Active'
        $cnpjs = User::where('status', 'Active')->pluck('cnpj')->toArray();

        // Filtra os CNPJs para remover valores vazios ou inválidos
        $cnpjs = array_filter($cnpjs);

        // Limpa os CNPJs usando a função limparCNPJ
        $cnpjs = array_map([$this, 'limparCNPJ'], $cnpjs); // Use $this para chamar a função
        // Monta a string de CNPJs
        $cnpjString = implode(',', $cnpjs);

        // Monta a URL
        $url = "https://xml.dsautoestoque.com/?l={$cnpjString}&v=2";

        return $url; // Retorna a URL ou faz algo com ela, como redirecionar
    }

    public function handle() {

        $canal = 'dsautoestoque';
        $this->canal = $canal;
        $url = $this->montarUrlComCnpjs();

        $xmlContent = @file_get_contents($url);
        if ($xmlContent === FALSE || empty($xmlContent)) {
            $this->error("Erro ao acessar a URL ou XML vazio: " . $url);
            return;
        }

        $xmlContent = preg_replace('/&(?!amp;|lt;|gt;|quot;|apos;|#\d+;)/', '&amp;', $xmlContent);

        try {
            libxml_use_internal_errors(true);

            $xml = new SimpleXMLElement($xmlContent, LIBXML_NOCDATA);

            foreach ($xml->veiculo as $veiculo) {
                if (empty($veiculo->loja)) {
                    continue;
                }
                $this->processVehicle($veiculo, $canal);
            }
            $this->info("Importação concluída com sucesso.");
        } catch (Exception $e) {
            $this->error("Erro ao processar o XML: " . $e->getMessage());

            // Exibir erros de XML, se houver
            foreach (libxml_get_errors() as $error) {
                $this->error("Erro XML: " . $error->message);
            }
            libxml_clear_errors();
        }
    }

    private function processVehicle($veiculo, $canal = 'dsautoestoque') {
        $loja = $veiculo->loja;
        $veiculo_id = $veiculo->id;
        $veiculo_data = (array) $veiculo;
        $cnpj = $this->limpaCPF_CNPJ($loja->cnpj);
        $data_cnpj = $this->mask($cnpj, '##.###.###/####-##');
        $cep = $loja->endereco->cep;
        $cidade = $loja->endereco->cidade;
        if (isset($loja->endereco->cidade) && $loja->endereco->cidade == 'Sao Vicente' || $loja->endereco->cidade == 'São Vicente') {
            $cidade = 'Praia Grande';
        }

        $userData = [
            'cnpj' => (string) $data_cnpj,
            'name' => (string) $loja->nomefantasia,
            'email' => (string) $loja->contato->email ?? '',
            'phone' => (string) $loja->contato->telefone ?? '',
            'address' => (string) $loja->endereco->logradouro ?? '',
            'numero' => (string) $loja->endereco->numero ?? '',
            'bairro' => (string) $loja->endereco->bairro ?? '',
            'zip' => (string) $cep ?? '',
            'website' => (string) $loja->contato->site ?? '',
            'city' => (string) $cidade ?? '',
            'state' => (string) $loja->endereco->uf ?? '',
        ];

        $userid = $this->isCnpjRegistered($userData['cnpj']);
        $title = $this->montaTituloVeiculo($veiculo_data, $veiculo_id, $canal);

        if (!empty($userid)) {
            $user_id = $userid;
            $this->registerOrUpdateUser($userData, $user_id);
            if ($user_id) {
                $this->prepareVehicleData($veiculo, $veiculo_id, $title, $user_id, $canal, $userData);
            }
        } else {
            try {
                $userData['status'] = 'Active';
                //$userData['senha'] = Hash::make('123mudar123');
                $user_id = $this->registerUser($userData);
                if ($user_id) {
                    $this->prepareVehicleData($veiculo, $veiculo_id, $title, $user_id, $canal, $userData);
                }
                $this->info("Agência " . $userData['name'] . " cadastrada com sucesso!");
            } catch (Exception $e) {
                $this->error("Erro ao cadastrar loja: " . $userData['name'] . " - " . $userData['cnpj'] . " - " . $e->getMessage());
            }
        }
    }

    private function UpdateOrCreateTransmission($cambioId, $cambio = null) {
        $slug = Str::slug($cambio);
        $brandId = $this->isTransmissionRegistered($cambioId);
        $brandData = [
            'transmission_name' => ($cambio),
            'transmission_slug' => $slug,
            'cambioId' => $cambioId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if ($brandId) {
            Transmission::where('id', $brandId)->update($brandData);
            return $brandId;
        } else {
            $brand = new Transmission();

            $brand->fill($brandData)->save();
            return $brand->id;
        }
    }

    private function UpdateOrCreateCombustivel($combustivelId, $combustivel = null) {
        $slug = Str::slug($combustivel);
        $brandId = $this->isCombustivelRegistered($combustivelId);
        $brandData = [
            'combustivel_name' => ($combustivel),
            'combustivel_slug' => $slug,
            'combustivelId' => $combustivelId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if ($brandId) {
            Combustivel::where('id', $brandId)->update($brandData);
            return $brandId;
        } else {
            $brand = new Combustivel();

            $brand->fill($brandData)->save();
            return $brand->id;
        }
    }

    private function UpdateOrCreateColor($colorId, $color = null) {
        $slug = Str::slug($color);
        $brandId = $this->isColorRegistered($colorId);
        $brandData = [
            'color_name' => ($color),
            'color_slug' => $slug,
            'colorId' => $colorId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if ($brandId) {
            Color::where('id', $brandId)->update($brandData);
            return $brandId;
        } else {
            $brand = new Color();

            $brand->fill($brandData)->save();
            return $brand->id;
        }
    }

    private function UpdateOrCreateVersao($versaoId, $versao = null, $marcaId = 0, $modeloId = 0, $canal = 'dsautoestoque') {
        $slug = Str::slug($versao);
        $brandId = $this->isVersaoRegistered($versaoId);
        $brandData = [
            'versao_name' => ($versao),
            'versao_slug' => $slug,
            'versaoId' => $versaoId,
            'marcaId' => $marcaId,
            'modeloId' => $modeloId,
            'canal' => $canal,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if ($brandId) {
            Versao::where('id', $brandId)->update($brandData);
            return $brandId;
        } else {
            $brand = new Versao();

            $brand->fill($brandData)->save();
            return $brand->id;
        }
    }

    function PT_limpaCPF_CNPJ($valor) {
        $valor = trim($valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        $valor = str_replace("(", "", $valor);
        $valor = str_replace(")", "", $valor);
        $valor = str_replace("%", "", $valor);
        $valor = str_replace("*", "", $valor);
        $valor = str_replace("&", "", $valor);
        $valor = str_replace("¨", "", $valor);
        $valor = str_replace("$", "", $valor);
        $valor = str_replace("#", "", $valor);
        $valor = str_replace("@", "", $valor);
        $valor = str_replace("!", "", $valor);
        $valor = str_replace(" ", "", $valor);
        $valor = str_replace(" ", "", $valor);
        return $valor;
    }

    private function prepareVehicleData($veiculo, $veiculo_id = 0, $title = '', $user_id = 0, $canal = 'dsautoestoque', $userData) {

        $loja = $veiculo->loja;
        $cambioId = 0;
        if (isset($veiculo->cambio['id'])) {
            $cambioId = $veiculo->cambio['id'];
        }
        $combustivelId = 0;
        if (isset($veiculo->combustivel['id'])) {
            $combustivelId = $veiculo->combustivel['id'];
        }
        $colorId = 0;
        if (isset($veiculo->cor['id'])) {
            $colorId = $veiculo->cor['id'];
        }

        $cidade = $loja->endereco->cidade;
        /* if (isset($loja->endereco->cidade) && $loja->endereco->cidade === 'Sao Vicente') {
          $cidade = 'Praia Grande';
          } */
        $data_formatada_created = null;
        if (!empty($veiculo->cadastro)) {
            list($data, $hora) = explode(' ', $veiculo->cadastro);
            list($dia, $mes, $ano) = explode('/', $data);
            $data_formatada_created = "$ano-$mes-$dia $hora";
        }
        /* $data_formatada_updated = null;
          if (!empty($veiculo->alteracao)) {
          list($data, $hora) = explode(' ', $veiculo->alteracao);
          list($dia, $mes, $ano) = explode('/', $data);
          $data_formatada_updated = "$ano-$mes-$dia $hora";
          } */

        $data_formatada_updated = null;

        if (!empty($veiculo->alteracao)) {
            $alteracao = trim((string) $veiculo->alteracao);

            // Garante que há pelo menos uma data e hora separadas por espaço
            if (str_contains($alteracao, ' ')) {
                list($data, $hora) = explode(' ', $alteracao, 2); // Limita a 2 partes
                // Verifica se a data contém barras e tem o formato esperado
                if (str_contains($data, '/') && substr_count($data, '/') == 2) {
                    $partesData = explode('/', $data);

                    if (count($partesData) === 3) {
                        list($dia, $mes, $ano) = $partesData;
                        $data_formatada_updated = "$ano-$mes-$dia $hora";
                    }
                } else {
                    // $this->warning("Formato de data inesperado: '$data' no veículo.");
                }
            } else {
                //$this->warning("Campo 'alteracao' inválido: '$alteracao' no veículo.");
            }
        }

        $listingData = [
            'listing_slug' => Str::slug($title),
            'user_id' => (int) $user_id,
            'admin_id' => 0,
            'veiculo_id' => $veiculo_id,
            'listing_name' => $title,
            'seo_title' => $title,
            'seo_meta_description' => !empty($veiculo->observacao) ? trim($veiculo->observacao) : '***',
            'listing_status' => 'Active',
            'canal' => $canal,
            'placa' => $veiculo->placa,
            'tipomotor' => $veiculo->tipomotor,
            'anofabricacao' => $veiculo->anofabricacao,
            'is_featured' => 'Yes',
            'created_at' => $data_formatada_created,
            'updated_at' => $data_formatada_updated,
            'listing_description' => !empty($veiculo->observacao) ? trim($veiculo->observacao) : '***',
            'listing_price' => $this->extractVehiclePrice($veiculo->preco ?? 0),
            'listing_type' => empty($veiculo->zerokm) || $veiculo->zerokm === 'Y' ? 'Novo' : 'Usado',
        ];

        $cep = $loja->endereco->cep;
        $listingData['cep'] = $cep;
        if ($cambioId > 0) {
            $cambio_id = $this->UpdateOrCreateTransmission($cambioId, $veiculo->cambio);
        }
        if ($combustivelId > 0) {
            $combustivel_id = $this->UpdateOrCreateCombustivel($combustivelId, $veiculo->combustivel);
        }
        if ($colorId > 0) {
            $color_id = $this->UpdateOrCreateColor($colorId, $veiculo->cor);
        }

        $listing_Id = $this->isVehicleRegistered($veiculo_id, $canal);
        $listingBrandId = $this->processBrands($veiculo->marca, $canal);
        $listingModeloId = $this->processModelos($veiculo->modelo, $listingBrandId);

        $versao_id = $this->processVersao($veiculo->versao, $listingBrandId, $listingModeloId, $canal);

        $listingData = array_merge($listingData, [
            'listing_transmission' => $veiculo->cambio,
            'listing_transmission_id' => $cambio_id,
            'listing_exterior_color' => $veiculo->cor,
            'listing_exterior_color_id' => $color_id,
            'listing_fuel_type' => $veiculo->combustivel,
            'listing_fuel_type_id' => $combustivel_id,
            'listing_body' => strtoupper($this->PT_limpaCPF_CNPJ($veiculo->carroceria)),
            'versao' => $veiculo->versao,
            'versao_id' => $versao_id,
            'listing_mileage' => $veiculo->km,
            'listing_model_year' => $veiculo->anomodelo,
            'listing_door' => $veiculo->portas,
            'listing_phone' => (string) ($loja->contato->telefone ?? ''),
            'listing_email' => (string) ($loja->contato->email ?? ''),
            'listing_website' => (string) ($loja->contato->site ?? ''),
            'listing_brands' => isset($veiculo->marca) ? json_encode((array) $veiculo->marca) : null,
            'listing_amenities' => isset($veiculo->acessorios) ? json_encode($veiculo->acessorios) : null,
            'listing_photos' => isset($veiculo->fotos) ? json_encode((array) $veiculo->fotos) : null,
            'listing_locations' => isset($veiculo->loja) ? json_encode((array) $veiculo->loja) : null,
            'listing_additional_features' => isset($veiculo->opcionais) ? json_encode($veiculo->opcionais) : null,
            'vehicleMake' => $veiculo->marca,
            'vehicleModel' => trim($veiculo->modelo),
            'vehicleModelYear' => $veiculo->anomodelo,
            'vehicleManufactureYear' => $veiculo->anofabricacao,
            'vehicleValue' => $this->extractVehiclePrice($veiculo->preco ?? 0),
            'newVehicle' => $veiculo->zerokm,
            'listing_tipo_veiculo' => $veiculo->tipoveiculo,
        ]);

        $listingData['listing_brand_id'] = $listingBrandId;
        $listingData['listing_modelo_id'] = $listingModeloId;

        if ((isset($loja->endereco->cidade) && ($loja->endereco->cidade == 'Sao Vicente' || $loja->endereco->cidade == 'São Vicente'))) {
            $cidade = 'Praia Grande';
        } elseif ((isset($loja->endereco->cep) && $loja->endereco->cep == '11726-500') &&
                (!isset($loja->endereco->cidade) || trim($loja->endereco->cidade) == '')) {
            $cidade = 'Praia Grande';
        } else {
            $cidade = $loja->endereco->cidade;
        }
        $uf = trim($loja->endereco->uf);
        $listingData['listing_location_id'] = $this->processLocations($veiculo, $canal);

        $Local = ListingLocation::where('id', $listingData['listing_location_id'])->first();
        if (isset($Local) && $Local->listing_location_slug == 'praia-grande') {
            $address = trim('Av. Pres. Kennedy, 3113 - Aviação, ' . $cidade . ' - SP, 11702-480');
        } else
        if (isset($Local) && $Local->listing_location_slug == 'santos') {
            $address = trim('Av. Washington Luis, 238, ' . $cidade . ', SP, 11050-201');
        } else {
            $address = $loja->endereco->logradouro . ', ' . $loja->endereco->numero . ', ' . $cidade . ' - ' . $loja->endereco->uf . ', ' . $loja->endereco->cep;
        }

        $listingData['listing_address'] = $address;
        if ($uf <= 0 || $uf <= '0') {
            $listingData['listing_uf'] = 'SP';
        } else {
            $listingData['listing_uf'] = 'SP';
        }
        $enderecoUrl = urlencode($address);

        /* $apiKey = 'AIzaSyBJxA9UBypN4xmq_j7xNiR_MQm6tMVlNVk';

          $listingData['listing_map'] = '<iframe
          src="https://www.google.com/maps/embed/v1/place?key=' . $apiKey . '&q=' . urlencode($enderecoUrl) . '"
          width="100%"
          height="200"
          style="border:0;"
          allowfullscreen
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
          </iframe>'; */

        $listingData['listing_map'] = '<iframe 
          src="https://www.google.com/maps?q=' . urlencode($enderecoUrl) . '&output=embed"
          width="100%"
          height="200"
          style="border:0;"
          allowfullscreen
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
          </iframe>';

        $listing = new Listing();

        if (!empty($listing_Id)) {
            Listing::where('id', $listing_Id)->update($listingData);
            $listingId = $listing_Id;
        } else {
            $listing->fill($listingData)->save();
            $listingId = $listing->id;
        }
        $this->processAmenities($veiculo->acessorios ?? [], $listingId);
        $this->processAdditionalFeatures($veiculo->opcionais ?? [], $listingId);
        //$this->processPhotosFull($veiculo->fotos ?? [], $listingId, $canal);
        $this->processPhotosFullWeb($veiculo->fotos ?? [], $listingId, $canal);
        /* $cnpj = $this->limpaCPF_CNPJ($loja->cnpj);
          $this->processPhotosFullWebP($veiculo->fotos->foto ?? [], $listingId, $canal, $cnpj); */

        return $listingData;
    }

    private function processLocations($veiculo, $canal = 'dsautoestoque') {
        $loja = $veiculo->loja;

        /* if ((isset($loja->endereco->cidade) && ($loja->endereco->cidade == 'Sao Vicente' || $loja->endereco->cidade == 'São Vicente'))) {
          $cidade = 'Praia Grande';
          } elseif ((isset($loja->endereco->cep) && $loja->endereco->cep == '11726-500') &&
          (!isset($loja->endereco->cidade) || trim($loja->endereco->cidade) == '')) {
          $cidade = 'Praia Grande';
          } else { */
        $cidade = $loja->endereco->cidade;
        /* } */
        $name = $cidade;
        $slug = Str::slug($name);

        $cep = strip_tags(trim($loja->endereco->cep));

        $locationIdReg = $this->isLocationRegistered($slug);
        $locationData = [
            'listing_location_name' => $name,
            'listing_location_slug' => $slug,
            'cep' => $cep,
            'listing_location_photo' => 'images/sem-localizacao.png',
            'seo_title' => $name,
            'canal' => $canal,
            'seo_meta_description' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($locationIdReg) {
            $locationId = $locationIdReg;
            ListingLocation::where('id', $locationId)->update($locationData);
        } else {
            $location = new ListingLocation();
            $location->fill($locationData)->save();
            $locationId = $location->id;
        }
        return $locationId;
    }

    private function processBrands($name, $canal = '') {
        $slug = Str::slug($name);
        $brandId = $this->isBrandRegistered($slug);
        $brandData = [
            'listing_brand_name' => strtoupper($name),
            'listing_brand_slug' => $slug,
            'canal' => $canal,
            'seo_title' => strtoupper($name),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if ($brandId) {
            ListingBrand::where('id', $brandId)->update($brandData);
            return $brandId;
        } else {
            $brand = new ListingBrand();

            $brand->fill($brandData)->save();
            return $brand->id;
        }
    }

    private function processModelos($name, $marca_id) {
        $slug = Str::slug($name);
        $modeloId = $this->isModeloRegistered($slug);
        $modeloData = [
            'modelo_name' => strtoupper($name),
            'modelo_slug' => $slug,
            'marca_id' => $marca_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (!empty($modeloId) && $modeloId > 0) {
            \App\Models\Modelo::where('id', $modeloId)->update($modeloData);
            return $modeloId;
        } else {
            $modelo = new \App\Models\Modelo();
            $modelo->fill($modeloData)->save();
            return $modelo->id;
        }
    }

    private function processVersao($name, $marca_id = 0, $modelo_id = 0, $canal = 'dsautoestoque') {
        $slug = Str::slug($name);
        $versaoId = $this->isVersaoRegistered($slug, $modelo_id);
        $modeloData = [
            'versao_name' => ($name),
            'versao_slug' => $slug,
            'marcaId' => $marca_id,
            'modeloId' => $modelo_id,
            'canal' => $canal,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (!empty($versaoId) && $versaoId > 0) {
            \App\Models\Versao::where('id', $versaoId)->update($modeloData);
            return $versaoId;
        } else {
            $modelo = new \App\Models\Versao();
            $modelo->fill($modeloData)->save();
            return $modelo->id;
        }
    }

    private function processAdditionalFeatures($opcionais, $listingId, $canal = 'dsautoestoque') {
        if ($opcionais) {
            foreach ($opcionais as $item) {
                if (isset($item->opcional)) {
                    foreach ($item->opcional as $opcional) {
                        $idOpcionaisID = json_encode($opcional->attributes()['id']);
                        $id = (int) json_decode($idOpcionaisID)->{0};
                        $name = (string) $opcional;
                        $slug = Str::slug($name);

                        $additional = \App\Models\Additional::where('opcional_id', $id)->where('additional_slug', $slug)->first();
                        if (isset($additional) && !empty($additional->id)) {
                            $additionalId = $additional->id;
                        } else {
                            $additionalFeature = \App\Models\Additional::create(
                                    [
                                        'opcional_id' => $id, // Condição de busca para evitar duplicação
                                        'additional_name' => $name,
                                        'additional_slug' => $slug
                                    ]
                            );
                            $additionalId = $additionalFeature->id;
                        }
                        ListingAdditionalFeature::updateOrCreate(
                                ['listing_id' => $listingId, 'additional_id' => $additionalId], // Condição de busca
                                ['listing_id' => $listingId, 'additional_id' => $additionalId, 'additional_feature_name' => $name, 'additional_feature_value' => !empty($name) ? 'Sim' : 'Não', 'canal' => $canal, 'created_at' => now(), 'updated_at' => now()]
                        );
                    }
                }
            }
        }
    }

    private function processAmenities($acessorios, $listingId) {
        if ($acessorios) {
            foreach ($acessorios as $item) {
                if (isset($item->acessorio)) {
                    foreach ($item->acessorio as $acessorio) {
                        $idAcessoriosID = json_encode($acessorio->attributes()['id']);
                        $id = (int) json_decode($idAcessoriosID)->{0};
                        $name = (string) $acessorio;
                        $slug = Str::slug($name);

                        $amenitie = Amenity::where('acessorio_id', $id)->where('amenity_slug', $slug)->first();
                        if (isset($amenitie) && !empty($amenitie->id)) {
                            $amenityId = $amenitie->id;
                        } else {
                            $amenity = Amenity::create(
                                    [
                                        'acessorio_id' => $id, // Condição de busca para evitar duplicação
                                        'amenity_name' => $name,
                                        'amenity_slug' => $slug
                                    ]
                            );
                            $amenityId = $amenity->id;
                        }
                        ListingAmenity::updateOrCreate(
                                ['listing_id' => $listingId, 'amenity_id' => $amenityId], // Condição de busca
                                ['listing_id' => $listingId, 'amenity_id' => $amenityId, 'created_at' => now(), 'updated_at' => now()]
                        );
                    }
                }
            }
        }
    }

    private function processPhotosFull($fotos, $listingId, $canal = 'dsautoestoque') {
        if (!empty($fotos) && isset($fotos->foto)) {
            $fotosImg = (array) $fotos->foto;
            foreach ($fotosImg as $index => $img) {
                $cleaned_url = $this->trata_img($img);
                $img_destaque = $this->trata_img($fotosImg[0]);
                if (!$this->isPhotoExists($cleaned_url, $listingId, $canal)) {
                    DB::update('UPDATE listings SET listing_featured_photo = ?, listing_image_alterada_admin = ? WHERE id = ?', [$img_destaque, 0, $listingId]);
                    $this->savePhoto($cleaned_url, $listingId, $canal);
                } else {
                    DB::update('UPDATE listings SET listing_featured_photo = ? WHERE id = ? AND listing_image_alterada_admin = ?', [$img_destaque, $listingId, 0]);
                    $this->savePhoto($cleaned_url, $listingId, $canal);
                }
            }
        } else {
            $img_destaque = 'images/sem-veiculo.jpg';
            DB::update('UPDATE listings SET listing_featured_photo = ?, listing_image_alterada_admin = ? WHERE id = ?', [$img_destaque, 0, $listingId]);
        }
    }

    private function processPhotosFullWeb($fotos, $listingId, $canal = 'dsautoestoque') {
        if (!empty($fotos) && isset($fotos->foto)) {
            $fotosImg = (array) $fotos->foto;

            foreach ($fotosImg as $index => $img) {
                $cleaned_url = $this->trata_img($img);
                $img_destaque = $this->trata_img($fotosImg[0]);

                $name_file_destaque = $this->extract_name_file($img_destaque);
                $name_file_media = $this->extract_name_file($cleaned_url);

                $ArquivoDestaque = $name_file_destaque . '.webp';
                $ArquivoMedia = $name_file_media . '.webp';

                // Verifica se imagem já existe no banco e pasta
                $exists = $this->isPhotoExists($ArquivoMedia, $listingId, $canal, $name_file_media);
                $outputPath = public_path('uploads/listing_featured_photos/') . $ArquivoDestaque;
                $outputThumbPath = public_path('uploads/listing_featured_photos_thumbs/') . 'thumb_' . $ArquivoDestaque;
                $outputMediaPath = public_path('uploads/listing_photos/') . $ArquivoMedia;

                if ($index === 0) {
                    // Atualiza imagem destaque apenas se ainda não foi salva
                    if (!$exists && file_exists($outputPath) && file_exists($outputThumbPath)) {
                        DB::update('UPDATE listings SET listing_featured_photo = ?, listing_image_alterada_admin = ? WHERE id = ?', [$ArquivoDestaque, 0, $listingId]);
                    } elseif (!$exists) {
                        DB::update('UPDATE listings SET listing_featured_photo = ? WHERE id = ? AND listing_image_alterada_admin = ?', [$ArquivoDestaque, $listingId, 0]);
                    }
                }

                // Só processa a imagem se ainda não existe no disco e banco
                if (!$exists || !file_exists($outputPath) || !file_exists($outputThumbPath) || !file_exists($outputMediaPath)) {
                    $this->savePhoto($ArquivoMedia, $listingId, $canal, $name_file_media, $img_destaque, $cleaned_url, $ArquivoDestaque, $ArquivoMedia);
                }

                /* if (!$this->isPhotoExists($ArquivoMedia, $listingId, $canal, $name_file_media)) {
                  DB::update('UPDATE listings SET listing_featured_photo = ?, listing_image_alterada_admin = ? WHERE id = ?', [$ArquivoDestaque, 0, $listingId]);
                  } else {
                  DB::update('UPDATE listings SET listing_featured_photo = ? WHERE id = ? AND listing_image_alterada_admin = ?', [$ArquivoDestaque, $listingId, 0]);
                  }
                  $this->savePhoto($ArquivoMedia, $listingId, $canal, $name_file_media, $img_destaque, $cleaned_url, $ArquivoDestaque, $ArquivoMedia); */
            }
        } else {
            $img_destaque = 'images/sem-veiculo.jpg';
            DB::update('UPDATE listings SET listing_featured_photo = ?, listing_image_alterada_admin = ? WHERE id = ?', [$img_destaque, 0, $listingId]);
        }
    }

    private function extract_name_file($img) {
        $parsed_url = parse_url($img);

        // Pega o caminho do arquivo
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';

        // Extrai apenas o nome do arquivo com extensão
        $filename = basename($path); // Ex: BZA-7E21_01.jpg
        // Remove a extensão
        $name_without_extension = pathinfo($filename, PATHINFO_FILENAME); // Ex: BZA-7E21_01

        return $name_without_extension;
    }

    private function trata_img($img) {
        $image_url = $img;
        $parsed_url = parse_url($image_url);

        if (isset($parsed_url['query'])) {
            // Transforma a query string em um array
            parse_str($parsed_url['query'], $query_params);

            // Remove o parâmetro 'u'
            unset($query_params['u']);

            // Reconstrói a query string sem o parâmetro 'u'
            $new_query = http_build_query($query_params);

            // Reconstrói a URL sem o parâmetro 'u'
            $cleaned_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'];
            if (!empty($new_query)) {
                $cleaned_url .= '?' . $new_query;
            }
        } else {
            // Se não há parâmetros, a URL original permanece a mesma
            $cleaned_url = $image_url;
        }
        return $cleaned_url;
    }

    private function savePhoto($media, $listingId, $canal = 'dsautoestoque', $name = '', $img_destaque = '', $cleaned_url = '', $ArquivoDestaque = '', $ArquivoMedia = '') {
        $outputPath = public_path('uploads/listing_featured_photos/');
        $outputThumbPath = public_path('uploads/listing_featured_photos_thumbs/');
        $outputPathC = public_path('uploads/listing_photos/');
        $ArquivoThumb = 'thumb_' . $ArquivoDestaque;

        // Criação das pastas se não existirem
        foreach ([$outputPath, $outputThumbPath, $outputPathC] as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        }

        $photo = ListingPhoto::where('listing_id', $listingId)
                ->where('photo_name_original', $name)
                ->where('canal', $canal)
                ->first();

        $caminhoDestaque = $outputPath . $ArquivoDestaque;
        $caminhoThumb = $outputThumbPath . $ArquivoThumb;
        $caminhoMedia = $outputPathC . $ArquivoMedia;

        // Evita reprocessamento se os arquivos já existem fisicamente e no banco
        if (
                file_exists($caminhoDestaque) &&
                file_exists($caminhoThumb) &&
                file_exists($caminhoMedia) &&
                $photo
        ) {
            return; // já existe tudo, então não precisa salvar ou sobrescrever
        }

        if (!$photo) {
            $photo = new ListingPhoto();
            $photo->listing_id = $listingId;
            $photo->created_at = now();
            $photo->listing_image_alterada_admin = 0;
            $photo->canal = $canal;
        }

        // Processa imagens apenas se os arquivos ainda não existem
        if (!file_exists($caminhoDestaque)) {
            \App\Helpers\Helper::convertUrlImageToWebp($img_destaque, $caminhoDestaque);
        }

        if (!file_exists($caminhoThumb)) {
            \App\Helpers\Helper::resizeAndConvertToWebp($caminhoDestaque, $caminhoThumb, 400, 300, 80);
        }

        if (!file_exists($caminhoMedia)) {
            \App\Helpers\Helper::convertUrlImageToWebp($cleaned_url, $caminhoMedia);
        }

        $photo->photo = $ArquivoMedia;
        if (!empty($name)) {
            $photo->photo_name_original = $name;
        }
        $photo->updated_at = now();
        $photo->save();
        /* if ($photo) {
          // Apaga imagem principal antiga
          if (!empty($photo->photo)) {
          $oldPhotoPath = public_path($photo->photo);
          if (file_exists($oldPhotoPath)) {
          unlink($oldPhotoPath);
          }
          }
          // Apaga imagem thumb antiga
          $oldThumbPath = $outputThumbPath . 'thumb_' . basename($photo->photo);
          if (file_exists($oldThumbPath)) {
          unlink($oldThumbPath);
          }
          // Apaga imagem de mídia (media)
          $oldMediaPath = $outputPathC . basename($photo->photo);
          if (file_exists($oldMediaPath)) {
          unlink($oldMediaPath);
          }
          } else {

          // Criar nova entrada
          $photo = new ListingPhoto();
          $photo->listing_id = $listingId;
          $photo->created_at = now();
          $photo->listing_image_alterada_admin = 0;
          $photo->canal = $canal;
          } */
        // Processar novas imagens
        /* \App\Helpers\Helper::convertUrlImageToWebp($img_destaque, $outputPath . $ArquivoDestaque);
          \App\Helpers\Helper::resizeAndConvertToWebp($outputPath . $ArquivoDestaque, $outputThumbPath . $ArquivoThumb, 400, 300, 80);
          \App\Helpers\Helper::convertUrlImageToWebp($cleaned_url, $outputPathC . $ArquivoMedia);

          $photo->photo = $ArquivoMedia;
          if (!empty($name)) {
          $photo->photo_name_original = $name;
          }
          $photo->updated_at = now();
          $photo->save();

          if (!empty($name)) {
          $photo->photo_name_original = $name;
          }
          $photo->save(); */
    }

    private function isPhotoExists($media, $listingId, $canal = 'dsautoestoque', $name = '') {
        $result = ListingPhoto::where('listing_id', $listingId)->where('canal', $canal)->where('photo', $media)->where('photo_name_original', $name)->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }

    private function savePhotoss($media, $listingId, $canal = 'dsautoestoque', $name = '', $img_destaque = '', $cleaned_url = '', $ArquivoDestaque = '', $ArquivoMedia = '') {
        $outputPath = public_path('uploads/listing_featured_photos/');
        $outputThumbPath = public_path('uploads/listing_featured_photos_thumbs/');
        $outputPathC = public_path('uploads/listing_photos/');
        $ArquivoThumb = 'thumb_' . $ArquivoDestaque;

        foreach ([$outputPath, $outputThumbPath, $outputPathC] as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        }

        $photo = ListingPhoto::where('listing_id', $listingId)
                ->where('photo_name_original', $name)
                ->where('canal', $canal)
                ->first();

        if ($photo) {
            // Remove antiga
            foreach ([$photo->photo, 'thumb_' . basename($photo->photo)] as $file) {
                foreach ([$outputPath, $outputThumbPath, $outputPathC] as $dir) {
                    $filePath = $dir . $file;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        } else {
            $photo = new ListingPhoto();
            $photo->listing_id = $listingId;
            $photo->created_at = now();
            $photo->listing_image_alterada_admin = 0;
            $photo->canal = $canal;
        }

        // 📸 INICIA INTERVENTION
        $manager = new ImageManager(new Driver());

        try {
            // IMAGEM DESTAQUE - CONVERTE PARA .webp
            $image = $manager->read($img_destaque);
            $image->resize(800, null)->toWebp(80)->save($outputPath . $ArquivoDestaque);

            // THUMB DA IMAGEM DESTAQUE
            $imageThumb = $manager->read($img_destaque);
            $imageThumb->resize(400, 300)->toWebp(80)->save($outputThumbPath . $ArquivoThumb);

            // OUTRAS IMAGENS
            $mediaImage = $manager->read($cleaned_url);
            $mediaImage->resize(800, null)->toWebp(80)->save($outputPathC . $ArquivoMedia);
        } catch (\Exception $e) {
            // ⚠️ ERRO DE DOWNLOAD OU PROCESSAMENTO
            \Log::error('Erro ao processar imagem: ' . $e->getMessage());
            return;
        }

        // SALVAR NO BANCO
        $photo->photo = $ArquivoMedia;
        $photo->photo_name_original = $name;
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

    private function isCombustivelRegistered($slug) {
        $result = Combustivel::where('combustivelId', $slug)->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }

    private function isColorRegistered($slug) {
        $result = Color::where('colorId', $slug)->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }

    private function isTransmissionRegistered($slug) {
        $result = Transmission::where('cambioId', $slug)->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }

    private function isVersaoRegistered($slug, $modelo_id = 0) {
        $result = Versao::where('versao_slug', $slug)->where('modeloId', $modelo_id)->first();
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

    private function isModeloRegistered($slug) {
        $result = \App\Models\Modelo::where('modelo_slug', $slug)->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }

    private function isCnpjRegistered($cnpj) {
        $result = User::where('cnpj', $cnpj)->where('status', 'Active')->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }

    private function isVehicleRegistered($veiculo_id, $canal = 'dsautoestoque') {
        $vehicle = Listing::where('veiculo_id', $veiculo_id)->where('listing_status', 'Active')->where('canal', $canal)->first();
        if ($vehicle) {
            return $vehicle->id;
        } else {
            return 0;
        }
    }

    private function registerUser($userData) {
        $token = hash('sha256', time());
        $slug = Str::slug($userData['name']);
// Cria um novo usuário
        $user = new User();
        $user->name = $userData['name'];
        //$user->email = $userData['email'];
        $user->cnpj = $userData['cnpj'];
        $user->phone = $userData['phone'];
        $user->address = $userData['address'];
        $user->state = $userData['state'];
        $user->country = 'BRA';
        $user->city = $userData['city'];
        $user->zip = $userData['zip'];
        $user->website = $userData['website'];
        $user->slug_user = $slug;
        if (isset($userData['senha'])) {
            $user->password = $userData['senha']; // Defina uma senha padrão ou gere uma aleatória
        }
        $user->token = $token;
        if (isset($userData['status']) && $userData['status'] == 'Active') {
            $user->status = $userData['status'];
        }
        if ($user->save()) {
            return $user->id;
        } else {
            return false;
        }
    }

    private function registerOrUpdateUser($userData, $user_id = false) {
        $token = hash('sha256', time());
        $slug = Str::slug($userData['name']);
        // Tenta encontrar um usuário pelo CNPJ ou outro identificador
        $user = User::where('cnpj', $userData['cnpj'])->first();

        if (!$user) {
            // Se não encontrar, cria um novo usuário
            $user = new User();
            if (isset($userData['senha'])) {
                $user->password = $userData['senha']; // Defina uma senha padrão ou gere uma aleatória
            }
            if (isset($userData['status']) && $userData['status'] == 'Active') {
                $user->status = 'Active';
            }
        }

        // Preenche os campos com os dados fornecidos
        $user->name = $userData['name'];
        //$user->email = $userData['email'];
        $user->cnpj = $userData['cnpj'];
        $user->phone = $userData['phone'];
        $user->address = $userData['address'];
        $user->state = $userData['state'];
        $user->country = 'BRA';
        $user->city = $userData['city'];
        $user->zip = $userData['zip'];
        $user->website = $userData['website'];
        $user->website = $userData['website'];
        $user->token = $token;
        $user->slug_user = $slug;

        // Salva ou atualiza o usuário
        $user->save();

        // Retorna o ID do usuário
        return $user->id ? $user->id : $user_id;
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
        for ($i = 0;
                $i < strlen($mask);
                $i++) {
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
        return trim(preg_replace('/\s+/', ' ', "$marca $modelo $versao"));
    }

    function verificaString($valor) {
        return is_string($valor) ? trim($valor) : '';
    }

    public function cep($cep) {
        // Verifica se o CEP está no cache
        $cacheKey = "cep_{$cep}";
        $cachedResponse = Cache::get($cacheKey);

        if ($cachedResponse) {
            // Retorna a resposta do cache
            return $cachedResponse;
        }

        sleep(2);

        // Se não estiver em cache, faz a consulta
        $response = $this->getCityByCep($cep);

        // Verifica se a resposta foi bem-sucedida
        if ($response->getStatusCode() === 200) {
            // Armazena a resposta no cache por 1 hora (3600 segundos)
            Cache::put($cacheKey, $response, 3600);
        }

        return $response;
    }

    private function getCityByCep($cep) {
        // Valida o formato do CEP (com ou sem hífen)
        if (!preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep)) {
            return response()->json(['error' => 'CEP inválido'], 400);
        }

        // Remover o hífen do CEP, caso exista
        $cep = str_replace('-', '', $cep);

        try {
            // Consulta o CEP na API do ViaCEP
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

            // Verifica se a resposta foi bem-sucedida
            if ($response->successful()) {
                $data = $response->json();

                // Verifica se houve um erro na busca do CEP
                if (isset($data['erro'])) {
                    return response()->json(['error' => 'CEP não encontrado'], 404);
                }

                // Retorna a cidade e o estado como uma resposta JSON com status 200
                return response()->json([
                            'cidade' => $data['localidade'],
                            'estado' => $data['uf']
                                ], 200);
            }
        } catch (\Exception $e) {
            // Retorna erro caso ocorra uma falha na conexão com a API
            return response()->json(['error' => 'Não foi possível conectar ao serviço de CEP. Tente novamente mais tarde.'], 500);
        }

        // Retorna erro genérico caso a consulta à API falhe por outros motivos
        return response()->json(['error' => 'Erro ao consultar o CEP'], 500);
    }

    /**
     * Cria uma nova tabela no banco de dados.
     */
    private function createTable($table, $fields) {
        if (Schema::hasTable($table)) {
            $this->error("A tabela '{$table}' já existe.");
            return Command::FAILURE;
        }

        $fieldsArray = explode(',', $fields);

        Schema::create($table, function ($table) use ($fieldsArray) {
            $table->id();
            foreach ($fieldsArray as $field) {
                [$name, $type] = explode(':', $field);
                $this->addColumnToTable($table, $name, $type);
            }
            $table->timestamps();
        });

        $this->info("Tabela '{$table}' criada com sucesso.");
    }

    /**
     * Adiciona um campo à tabela existente.
     */
    private function addField($table, $fields) {
        if (!Schema::hasTable($table)) {
            $this->error("A tabela '{$table}' não existe.");
            return Command::FAILURE;
        }

        $fieldsArray = explode(',', $fields);

        Schema::table($table, function ($table) use ($fieldsArray) {
            foreach ($fieldsArray as $field) {
                if (!str_contains($field, ':')) {
                    $this->error("O campo '{$field}' está mal formatado. Use o formato campo:tipo.");
                    continue;
                }

                [$name, $type] = explode(':', $field);

                if (empty($name) || empty($type)) {
                    $this->error("O campo '{$field}' contém informações inválidas.");
                    continue;
                }

                $this->addColumnToTable($table, $name, $type);
            }
        });

        $this->info("Campos adicionados à tabela '{$table}' com sucesso.");
        return Command::SUCCESS;
    }

    /**
     * Adiciona uma coluna à tabela dinamicamente.
     */
    private function addColumnToTable($table, $name, $type) {
        switch ($type) {
            case 'string':
                $table->string($name)->nullable();
                break;
            case 'integer':
                $table->integer($name)->nullable();
                break;
            case 'boolean':
                $table->boolean($name)->default(false);
                break;
            case 'text':
                $table->text($name)->nullable();
                break;
            case 'timestamp':
                $table->timestamp($name)->nullable();
                break;
            default:
                throw new \InvalidArgumentException("Tipo '{$type}' não suportado.");
        }
    }

    public function importSQL() {
        $filePath = $this->argument('file');

        if ($filePath) {
            if (!file_exists($filePath)) {
                $this->error("File not found: $filePath");
                return;
            }

            try {
                $handle = fopen($filePath, 'r');
                $query = '';

                while (($line = fgets($handle)) !== false) {
                    $line = trim($line);

                    // Ignorar comentários
                    if (empty($line) || str_starts_with($line, '--') || str_starts_with($line, '/*')) {
                        continue;
                    }

                    $query .= $line;

                    // Executa a query quando encontrar o final do comando SQL
                    if (substr($query, -1) === ';') {
                        DB::unprepared($query);
                        $query = '';
                    }
                }

                fclose($handle);

                $this->info('SQL file imported successfully!');
            } catch (\Exception $e) {
                $this->error('Failed to import SQL file: ' . $e->getMessage());
            }
        } else {
            $this->info('No file provided. Proceeding with other options.');
        }
    }
}
