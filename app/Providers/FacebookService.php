<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use App\Models\GeneralSetting;

class FacebookService extends ServiceProvider
{
    protected $client;
    protected $accessToken;
    protected $catalogId;

    public function __construct()
    {
        $setting = GeneralSetting::where('id',1)->first();
        $this->client = new Client(['base_uri' => 'https://graph.facebook.com/v17.0/']);
        $this->accessToken =$setting->facebook_access_token;
        $this->catalogId = $setting->facebook_catalog_id;
    }

    public function createVehicle($vehicleData)
    {
        $response = $this->client->post("{$this->catalogId}/products", [
            'query' => [
                'access_token' => $this->accessToken,
            ],
            'json' => $vehicleData,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function uploadImage($productId, $imageUrl)
    {
        $response = $this->client->post("{$productId}/images", [
            'query' => [
                'access_token' => $this->accessToken,
            ],
            'json' => [
                'image_url' => $imageUrl,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
    
    public function deleteVehicle($productId)
    {
        try {
            $response = $this->client->delete("{$productId}", [
                'query' => [
                    'access_token' => $this->accessToken,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
