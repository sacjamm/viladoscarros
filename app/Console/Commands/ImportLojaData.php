<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use SimpleXMLElement;

class ImportLojaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    //protected $description = 'Command description';
    protected $signature = 'import:loja-data';
    protected $description = 'Importa dados da loja a partir de um XML';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    { 
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = 'https://xml.dsautoestoque.com/?l=30245440000170,14037313000129,26161155000101,09375326000500,41905893000100,53973129000142,16561376000105,85054249000132,41424430000118,21777072000110,28654819000191,38174151000139,58030937000190,32263796000161,39311270000159,49673090000170,12911519000453,18727923000458,44602040000189,28415064000172,45955141000104,58030937000190,28654819000191,21777072000110,71973518000150,41905893000100,09375326000500,26161155000101,408505090148&v=2';

        // Faz a requisição à URL
        $response = Http::get($url);

        // Verifica se a requisição foi bem-sucedida
        if ($response->successful()) {
            // Carrega o XML
            $xml = new SimpleXMLElement($response->body());

            // Percorre cada veículo no XML
            foreach ($xml->veiculo as $veiculo) {
                $loja = $veiculo->loja;

                // Extrai dados da loja
                $lojaData = [
                    'name' => (string) $loja->nomefantasia,
                    'cnpj' => (string) $loja->cnpj,
                    'phone' => (string) $loja->contato->telefone,
                    'address' => (string) $loja->endereco->logradouro . ' ' . (string) $loja->endereco->numero,
                    'city' => (string) $loja->endereco->cidade,
                    'state' => (string) $loja->endereco->uf,
                    'zip' => (string) $loja->endereco->cep,
                    'website' => (string) $loja->contato->site,
                ];  

                // Verifica se o CNPJ já existe no banco de dados
                $user = User::where('cnpj', $lojaData['cnpj'])->first();

                // Se não existir, cria um novo registro
                if (!$user) {
                    User::create($lojaData);
                    $this->info("Loja {$lojaData['name']} criada com sucesso.");
                } else {
                    // Se já existir, atualiza os dados
                    $user->update($lojaData);
                    $this->info("Loja {$lojaData['name']} atualizada com sucesso.");
                }
            }
        } else {
            $this->error("Erro ao obter dados da URL: {$response->status()}");
        }
    }
}
