<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;
use App\Models\ListingAmenity;
use App\Models\ListingSocialItem;
use App\Models\ListingVideo;
use App\Models\ListingAdditionalFeature;
use App\Models\ListingPhoto;
use App\Models\User;
use SimpleXMLElement;
use Exception; // Certifique-se de importar a classe Exception

class RemoverVeiculosCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'veiculos:remove-veiculos-nao-encontrado';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove veículos não encontrados no XML';
    protected $facebookService;

    /**
     * Create a new command instance.
     *
     * @param FacebookService $facebookService
     * @return void
     */
    public function __construct() {
        parent::__construct();
        //$this->facebookService = $facebookService;
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
        $cnpjs = User::where('status', 'Active')->pluck('cnpj')->toArray();
        $cnpjs = array_filter($cnpjs);
        $cnpjs = array_map([$this, 'limparCNPJ'], $cnpjs);
        $cnpjString = implode(',', $cnpjs);
        $url = "https://xml.dsautoestoque.com/?l={$cnpjString}&v=2";
        return $url;
    }

    public function handle() {
        $this->remover_veiculos_nao_encontrados_no_xml();
    }

    public function xml() {
        $url = $this->montarUrlComCnpjs();
        $xmlContent = @file_get_contents($url);
        if ($xmlContent === FALSE) {
            $this->error("Erro ao acessar a URL: " . $url);
            return;
        }
         $xmlContent = preg_replace('/&(?!amp;|lt;|gt;|quot;|apos;|#\d+;)/', '&amp;', $xmlContent);
        try {
            $xml = new SimpleXMLElement($xmlContent, LIBXML_NOCDATA);
            return $xml;
        } catch (Exception $e) {
            $this->error("Erro ao processar o XML: " . $e->getMessage());
        }
    }

    function remover_veiculos_nao_encontrados_no_xml() {
        $xml = $this->xml();

        if ($xml instanceof SimpleXMLElement && isset($xml->veiculo)) {
            $veiculoAPI_array = array();
            foreach ($xml->veiculo as $veiculo) {
                if (isset($veiculo->id)) {
                    $veiculoAPI_array[] = (int) $veiculo->id;
                }
            }

            if (!empty($veiculoAPI_array)) {
                $list = Listing::where('canal', 'dsautoestoque')->get();

                foreach ($list as $row) {
                    $post_veiculo_id = $row->veiculo_id;

                    if (!in_array($post_veiculo_id, $veiculoAPI_array)) {
                        $this->error('O ID do veículo ' . $post_veiculo_id . ' não existe, pode deletar do banco de dados');
                        error_log('O ID do veículo ' . $post_veiculo_id . ' não existe, pode deletar do banco de dados');
                        $this->destroy($row->id);
                        
                        if (!empty($row->facebook_product_id)) {
                            // Remove o anúncio do Facebook usando o FacebookService
                            $result = $this->facebookService->deleteVehicle($row->facebook_product_id);
                            // Verifica se ocorreu um erro ao deletar do Facebook
                            if (isset($result['error'])) {
                                $this->error('Erro ao deletar o anúncio do Facebook: ' . $result['error']);
                                error_log('Erro ao deletar o anúncio do Facebook: ' . $result['error']);
                            } else {
                                $this->info('Anúncio do Facebook deletado com sucesso para o veículo ' . $post_veiculo_id);
                                error_log('Anúncio do Facebook deletado com sucesso para o veículo ' . $post_veiculo_id);
                            }
                        }
                    }
                }
            } else {
                $this->error('Nenhum veículo encontrado no XML.');
                error_log('Nenhum veículo encontrado no XML.');
            }
        } else {
            $this->error('Falha ao carregar XML ou estrutura inválida.');
            error_log('Falha ao carregar XML ou estrutura inválida.');
        }
        $this->info("Veículos removidos com sucesso.");
    }

    public function destroy($id) {
        $listing = Listing::findOrFail($id);
        $currentFeaturedPath = public_path('uploads/listing_featured_photos/' . $listing->listing_featured_photo);
        if (file_exists($currentFeaturedPath)) {
            unlink($currentFeaturedPath);
        }
        $listing->delete();

        ListingAmenity::where('listing_id', $id)->delete();
        ListingSocialItem::where('listing_id', $id)->delete();
        ListingVideo::where('listing_id', $id)->delete();
        ListingAdditionalFeature::where('listing_id', $id)->delete();

        $all_photos = ListingPhoto::where('listing_id', $id)->get();
        foreach ($all_photos as $item) {
            $currentItemPath = public_path('uploads/listing_photos/' . $item->photo);
            if (file_exists($currentItemPath)) {
                unlink($currentItemPath);
            }
        }
        ListingPhoto::where('listing_id', $id)->delete();
    }
}
