<?php

namespace App\Listeners;

use App\Events\UserRegistered; 
use App\Models\Listing; // Model do veículo
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegisterVehicleForUser {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event) {
        // Supondo que os dados do veículo já estão disponíveis em uma variável/serviço
        $vehicleData = $this->getVehicleDataFromXML();

        // Verifica se o veículo já existe com base no VIN
        $vehicleExists = Listing::where('listing_vin', $vehicleData['vin'])->exists();

        if (!$vehicleExists) {
            // Cria o veículo associado ao usuário recém-cadastrado
            Listing::create([
                'listing_name' => $vehicleData['name'],
                'listing_slug' => $vehicleData['slug'],
                'listing_description' => $vehicleData['description'],
                'listing_address' => $vehicleData['address'],
                'listing_phone' => $vehicleData['phone'],
                'listing_email' => $vehicleData['email'],
                'listing_website' => $vehicleData['website'],
                'listing_price' => $vehicleData['price'],
                'listing_exterior_color' => $vehicleData['exterior_color'],
                'listing_interior_color' => $vehicleData['interior_color'],
                'listing_cylinder' => $vehicleData['cylinder'],
                'listing_fuel_type' => $vehicleData['fuel_type'],
                'listing_transmission' => $vehicleData['transmission'],
                'listing_engine_capacity' => $vehicleData['engine_capacity'],
                'listing_vin' => $vehicleData['vin'],
                'listing_model_year' => $vehicleData['model_year'],
                'listing_type' => $vehicleData['type'],
                'listing_featured_photo' => $vehicleData['featured_photo'],
                'listing_brand_id' => $vehicleData['brand_id'],
                'listing_location_id' => $vehicleData['location_id'],
                'user_id' => $event->user->id, // Associa o veículo ao usuário recém-cadastrado
                'admin_id' => 1, // Exemplo de admin associado
                'listing_status' => 'active',
                'is_featured' => $vehicleData['is_featured'],
            ]);
        }
    }

    /**
     * Simula a leitura dos dados do XML já processado.
     * Você pode ajustar isso para acessar de fato o local onde os dados do XML estão armazenados.
     */
    private function getVehicleDataFromXML() {
        return [
            'name' => 'Carro Exemplo',
            'slug' => 'carro-exemplo',
            'description' => 'Descrição do carro',
            'address' => 'Endereço do carro',
            'phone' => '123456789',
            'email' => 'exemplo@exemplo.com',
            'website' => 'http://exemplo.com',
            'price' => '100000',
            'exterior_color' => 'Preto',
            'interior_color' => 'Bege',
            'cylinder' => '4',
            'fuel_type' => 'Gasolina',
            'transmission' => 'Automática',
            'engine_capacity' => '2000',
            'vin' => '1HGCM82633A123456',
            'model_year' => '2023',
            'type' => 'Sedan',
            'featured_photo' => 'carro.jpg',
            'brand_id' => 1,
            'location_id' => 1,
            'is_featured' => 'yes',
        ];
    }
}
