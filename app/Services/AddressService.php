<?php

namespace App\Services;

// Importando a classe Http
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\Utils;

class AddressService {

    public function getCityByCepCache($cep, $listingId = 0) {
        // Valida o formato do CEP (com ou sem hífen)
        if (!preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep)) {
            return ['error' => 'CEP inválido']; // Retornando um array com erro
        }

        // Remover o hífen do CEP, caso exista
        $cep = str_replace('-', '', $cep);

        // Verifica se o CEP já está em cache
        $cacheKey = "cep_{$cep}";
        $cachedData = Cache::get($cacheKey);

        if ($cachedData) {
            return $cachedData; // Retorna dados do cache se existir
        }

        // Consulta o CEP na API do ViaCEP
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        // Verifica se a resposta foi bem-sucedida
        if ($response->successful()) {
            $data = $response->json();

            // Verifica se houve um erro na busca do CEP
            if (isset($data['erro'])) {
                return ['error' => 'CEP não encontrado']; // Retornando um array com erro
            }

            // Salva no cache por um período (por exemplo, 1 hora)
            Cache::put($cacheKey, [
                'cidade' => $data['localidade'],
                'estado' => $data['uf']
                    ], 3600); // 3600 segundos = 1 hora

            return [
                'cidade' => $data['localidade'],
                'estado' => $data['uf']
            ];
        }

        // Retorna erro caso a consulta à API falhe
        return ['error' => 'Erro ao consultar o CEP']; // Retornando um array com erro
    }

    public function getCitiesByCeps($ceps) {
        // Verifica se $ceps é uma instância de SimpleXMLElement e converte para array
        if ($ceps instanceof SimpleXMLElement) {
            $ceps = (array) $ceps;
        }

        // Se $ceps for uma string, converte para um array com um único valor
        if (is_string($ceps)) {
            $ceps = [$ceps];
        }

        // Verifica se o parâmetro é um array após a conversão
        if (!is_array($ceps)) {
            return ['error' => 'Formato inválido de CEPs'];
        }

        $client = new Client();
        $promises = [];

        foreach ($ceps as $cep) {
            // Valida e formata o CEP
            if (preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep)) {
                $cep = str_replace('-', '', $cep);
                $promises[$cep] = $client->getAsync("https://viacep.com.br/ws/{$cep}/json/");
            }
        }

        // Espera todas as promessas serem resolvidas usando Utils::settle
        $responses = Utils::settle($promises)->wait();
        $result = [];

        foreach ($responses as $cep => $response) {
            if ($response['state'] === 'fulfilled') {
                $data = json_decode($response['value']->getBody(), true);

                // Verifica se a resposta contém os dados esperados
                $cidade = $data['localidade'] ?? null;
                $estado = $data['uf'] ?? null;

                if (!$cidade || !$estado) {
                    $result[$cep] = ['error' => 'Dados incompletos para o CEP'];
                } else {
                    $result[$cep] = [
                        'cidade' => $cidade,
                        'estado' => $estado
                    ];
                }
            } else {
                $result[$cep] = ['error' => 'Erro ao consultar o CEP'];
            }
        }

        return $result; // Retorna todas as cidades e estados
    }

    public function getCityByCep($cep, $listingId = 0) {
        // Valida o formato do CEP (com ou sem hífen)
        if (!preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep)) {
            return ['error' => 'CEP inválido'];
        }

        $cep = str_replace('-', '', $cep);
        $maxRetries = 3;
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['erro'])) {
                    return ['error' => 'CEP não encontrado'];
                }

                return [
                    'cidade' => $data['localidade'],
                    'estado' => $data['uf']
                ];
            }

            $retryCount++;
            sleep(2); // Aguarda um segundo antes de tentar novamente
        }

        return ['error' => 'Erro ao consultar o CEP após várias tentativas'];
    }
}
