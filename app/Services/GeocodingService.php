<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    /**
     * Converter endereço digitado em coordenadas (Lat, Lng) com Cache Redis de 30 dias.
     */
    public function geocodeAddress(string $address): array
    {
        $cacheKey = 'geocoding:' . md5(strtolower(trim($address)));

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($address) {
            $apiKey = config('services.google.maps_api_key', env('GOOGLE_MAPS_API_KEY'));

            if (!$apiKey || $apiKey === 'mock_google_maps_key') {
                // Fallback para dev/demo se a chave real não for informada
                Log::info("Geocoding usando coordenadas simuladas para: {$address}");
                return [
                    'latitude' => -23.5505 + (mt_rand(-50, 50) / 1000),
                    'longitude' => -46.6333 + (mt_rand(-50, 50) / 1000),
                ];
            }

            try {
                $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'address' => $address,
                    'key' => $apiKey,
                ]);

                if ($response->successful() && !empty($response['results'])) {
                    $location = $response['results'][0]['geometry']['location'];
                    return [
                        'latitude' => $location['lat'],
                        'longitude' => $location['lng'],
                    ];
                }
            } catch (\Exception $e) {
                Log::error("Erro na API de Geocoding Google Maps: " . $e->getMessage());
            }

            // Default São Paulo Centro
            return [
                'latitude' => -23.5505,
                'longitude' => -46.6333,
            ];
        });
    }
}
