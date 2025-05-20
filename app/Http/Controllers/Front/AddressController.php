<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AddressController extends Controller {

    public function getCityByCep($cep) {
        // Valida o formato do CEP (com ou sem hífen)
        if (!preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep)) {
            return response()->json(['error' => 'CEP inválido'], 400);
        }

        // Remover o hífen do CEP, caso exista
        $cep = str_replace('-', '', $cep);

        // Consulta o CEP na API do ViaCEP
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        // Verifica se a resposta foi bem-sucedida
        if ($response->successful()) {
            $data = $response->json();

            // Verifica se houve um erro na busca do CEP
            if (isset($data['erro'])) {
                return response()->json(['error' => 'CEP não encontrado'], 404);
            }

            // Retorna a cidade e o estado
            return response()->json([
                        'cidade' => $data['localidade'],
                        'estado' => $data['uf']
            ]);
        }

        // Retorna erro caso a consulta à API falhe
        return response()->json(['error' => 'Erro ao consultar o CEP'], 500);
    }
    
   
}
