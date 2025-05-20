<?php

namespace App\Http\Controllers;

use App\Providers\FacebookService;
use App\Models\Listing;
use Illuminate\Http\Request;

class FacebookVehicleController extends Controller {

    protected $facebookService;

    public function __construct(FacebookService $facebookService) {
        $this->facebookService = $facebookService;
    }

    public function sendVehiclesToFacebook() {
        // Obtenha os veículos ativos do banco de dados
        $vehicles = Listing::where('status', 'Active')->get();

        foreach ($vehicles as $vehicle) {
            $vehicleData = [
                'name' => $vehicle->listing_name,
                'description' => $vehicle->listing_description,
                'price' => $vehicle->listing_price . ' BRL', // Inclua a moeda
                'url' => route('front_listing_detail', [$vehicle->id, $vehicle->listing_slug]), // URL do veículo no seu site
                'availability' => 'in stock', // ou 'out of stock'
                'condition' => $vehicle->listing_type, // Ex: 'new' ou 'used'
                'brand' => $vehicle->vehicleMake,
                'image_url' => $vehicle->listing_featured_photo, // URL da imagem principal do veículo
            ];

            // Enviar o veículo ao Facebook
            $response = $this->facebookService->createVehicle($vehicleData);

            // Verifica a resposta e associa imagens adicionais, se necessário
            if (isset($response['id'])) {
                $productId = $response['id'];
                
                $data['facebook_product_id']=$productId;
                Listing::where('id',$vehicle->id)->update($data);

                // Se houver imagens adicionais
                $result = ListingPhoto::where('listing_id', $vehicle->id)->get();
                if ($result) {
                    foreach ($result as $image) {
                        $this->facebookService->uploadImage($productId, $image->photo);
                    }
                }
            }
        }

        return response()->json(['message' => 'Veículos enviados com sucesso!']);
    }
}
