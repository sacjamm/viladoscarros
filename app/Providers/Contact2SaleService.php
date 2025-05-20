<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class Contact2SaleService extends ServiceProvider
{
    protected $apiUrl;
    protected $jwtToken;

    public function __construct()
    {
        $this->apiUrl = config('services.contact2sale.url');
        $this->jwtToken = config('services.contact2sale.token');
    }

    /**
     * Envia um lead para o Contact2Sale.
     *
     * @param array $leadData
     * @return array
     * @throws \Exception
     */ 
    public function sendLead(array $leadData): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->jwtToken,
            ])->post($this->apiUrl, [
                'data' => [
                    'type' => 'lead',
                    'attributes' => $leadData
                ]
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            // Em caso de erro, lança uma exceção com a mensagem de erro da API
            throw new \Exception('Erro ao enviar lead: ' . $response->body());
        } catch (\Exception $e) {
            // Aqui você pode registrar o erro em um log para rastrear problemas
            \Log::error('Contact2Sale Error: ' . $e->getMessage());
            throw $e; // Relança a exceção para que o controlador saiba que houve um problema
        }
    }
}
