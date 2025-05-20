<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller {

    public function handleWebhook(Request $request) {
        // Registrar o JSON recebido para debug
        Log::info('Dialogflow Request:', $request->all());

        // Capturar os dados enviados pelo Dialogflow
        $data = $request->all();

        // Verificar se os dados são válidos
        if (!isset($data['queryResult']['intent']['displayName'])) {
            return response()->json(["fulfillmentText" => "Erro ao processar requisição."]);
        }

        // Capturar o Intent acionado
        $intent = $data['queryResult']['intent']['displayName'];

        // Definir resposta padrão
        $responseText = "Desculpe, não entendi sua solicitação.";

        // Tratar diferentes intents
        switch ($intent) {
            case "Saudação":
                $responseText = "Olá! Como posso te ajudar no WhatsApp?";
                break;

            case "Preço do Produto":
                $produto = $data['queryResult']['parameters']['produto'] ?? "produto não informado";
                $responseText = "O preço do $produto é R$ 100,00.";
                break;

            case "Horário de Funcionamento":
                $responseText = "Nosso horário de funcionamento é de segunda a sexta, das 8h às 18h.";
                break;

            default:
                $responseText = "Desculpe, não entendi.";
        }

        // Retornar resposta ao Dialogflow
        return response()->json(["fulfillmentText" => $responseText]);
    }
}
