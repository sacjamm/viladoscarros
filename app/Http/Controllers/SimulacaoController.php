<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeadSimulacaoMail;

class SimulacaoController extends Controller {

    public function receber(Request $request) {
        // Captura o conteúdo bruto
        $jsonRaw = $request->getContent();
        $json_arr = json_decode($jsonRaw, true);

        if (!$json_arr || !isset($json_arr['event'])) {
            return response()->json(['status' => 400, 'message' => 'JSON inválido'], 400);
        }
        if ($json_arr['event'] !== 'processed_simulation') {
            return response()->json(['status' => 204, 'message' => 'Evento não processado']);
        }
        if (!isset($json_arr['simulation']['lead']['payload']['store_id'], $json_arr['simulation']['lead']['id'])) {
            return response()->json(['status' => 400, 'message' => 'Dados incompletos'], 400);
        }

        $lojaId = $json_arr['simulation']['lead']['payload']['store_id'];
        $vehicle_id = $json_arr['simulation']['lead']['id'];

        Storage::append('logs/simulacoes-leads-laravel.log', "[" . now() . "]\nDECODED:\n" . json_encode($json_arr['simulation']['lead'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n");

        $loja = User::where('loja_credere', $lojaId)->first();
        $estoque = Listing::where('estoqueCredere_id', $vehicle_id)->where('user_id', $loja->id ?? 0)->first();

        $listing_name = $estoque ? $estoque->listing_name : '';

        $user_name = $loja->name ?? '';
        $cnpj = $loja->cnpj_credere ?? '';
        $model_name = $json_arr['simulation']['vehicle']['vehicle_model']['model_name'] ?? '';
        $version = $json_arr['simulation']['vehicle']['vehicle_model']['version'] ?? '';

        $data_vehicle = $cnpj . '-' . $model_name . ' ' . $version;

        $mensagem = "
            DADOS DO LEAD <br><br>
            Nome: {$json_arr['simulation']['lead']['name']} <br>
            Telefone: {$json_arr['simulation']['lead']['phone_number']} <br><br>
            Mensagem:  {$user_name},<br>{$data_vehicle}
        ";

        /* $headers = "MIME-Version: 1.0\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8\r\n";
          $headers .= "From: Vila dos Carros <viladoscarrosmkt@gmail.com>\r\n";

          $email_enviado2 = mail('viladoscarros@contact2sale.com', 'Credere LEADS', $mensagem, $headers); */

        $email_enviado2 = Mail::to('viladoscarros@contact2sale.com')->send(new LeadSimulacaoMail($mensagem));

        if (!$email_enviado2) {
            Storage::append('logs/simulacoes-leads-mail-laravel.log', "[" . now() . "] ERRO AO ENVIAR EMAIL\n");
        }

        return response()->json([
                    'status' => 200,
                    'message' => 'Simulação processada com sucesso',
                    'mensagem' => $mensagem,
        ]);
        /* unset($json_arr['simulation']['conditions'], $json_arr['simulation']['items'], $json_arr['simulation']['payload']);

          $data_vehicle = '';
          if ($json_arr && $json_arr['event'] === 'processed_simulation') {

          $loja = User::where('loja_credere', $lojaId)->first();
          $estoque = Listing::where('estoqueCredere_id', $vehicle_id)->where('user_id', $loja->id)->first();
          if ($estoque) {
          //$listing_url = '<a href="' . route('front_listing_detail', [$estoque->id, $estoque->listing_slug]) . '">' . route('front_listing_detail', [$estoque->id, $estoque->listing_slug]) . '</a>';
          $listing_name = $estoque->listing_name;
          }
          if (isset($json_arr['simulation']['vehicle']['vehicle_model']['model_name'])) {
          $model_name = $json_arr['simulation']['vehicle']['vehicle_model']['model_name'];
          }
          if (isset($json_arr['simulation']['vehicle']['vehicle_model']['version'])) {
          $version = $json_arr['simulation']['vehicle']['vehicle_model']['version'];
          }

          if (isset($loja)) {
          $cnpj = $loja->cnpj_credere;
          $user_name = $loja->name;
          }
          $assunto = 'Credere LEADS';
          $cnpj = '';
          $user_name = '';
          $model_name = '';
          $version = '';

          $data_vehicle = $cnpj . '-' . $model_name . ' ' . $version;
          $mensagem = "
          DADOS DO LEAD <br><br>
          Nome: {$json_arr['simulation']['lead']['name']} <br>
          Telefone: {$json_arr['simulation']['lead']['phone_number']} <br>

          Mensagem:  {$user_name},
          <br>{$data_vehicle}";

          $headers = "MIME-Version: 1.0" . "\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          $headers .= "From: Vila dos Carros <viladoscarrosmkt@gmail.com>" . "\r\n";

          $email_enviado2 = mail('viladoscarros@contact2sale.com', $assunto, $mensagem, $headers);
          if (!$email_enviado2) {
          Storage::append('logs/simulacoes-leads-email-laravel.log', "[" . now() . "] ERRO AO ENVIAR EMAIL\n");
          }
          // ===== Salvar ou processar dados como necessário aqui =====

          return response()->json([
          'status' => 200,
          'message' => 'Simulação processada com sucesso',
          'mensagem' => $mensagem,
          ]);
          } */
    }
}
