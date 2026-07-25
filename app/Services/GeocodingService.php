<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    /**
     * Converter endereço digitado em coordenadas exatas (Lat, Lng) com Cache Redis de 30 dias.
     * Suporta Google Maps Geocoding API e OpenStreetMap Nominatim API como fallback de alta precisão.
     */
    public function geocodeAddress(string $address): array
    {
        $cacheKey = 'geocoding:' . md5(strtolower(trim($address)));

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($address) {
            $apiKey = config('services.google.maps_api_key', env('GOOGLE_MAPS_API_KEY'));

            // 1. Tentar Geocoding via Google Maps API (caso chave seja real)
            if ($apiKey && $apiKey !== 'mock_google_maps_key') {
                try {
                    $response = Http::timeout(5)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                        'address' => $address,
                        'key' => $apiKey,
                    ]);

                    if ($response->successful() && !empty($response['results'])) {
                        $location = $response['results'][0]['geometry']['location'];
                        Log::info("Geocoding preciso via Google Maps para [{$address}]: Lat {$location['lat']}, Lng {$location['lng']}");
                        return [
                            'latitude' => (float) $location['lat'],
                            'longitude' => (float) $location['lng'],
                        ];
                    }
                } catch (\Exception $e) {
                    Log::warning("Falha na API Google Maps Geocoding: " . $e->getMessage());
                }
            }

            // 2. Geocoding de Alta Precisão Gratuito via OpenStreetMap (Nominatim API)
            try {
                $response = Http::timeout(5)->withHeaders([
                    'User-Agent' => 'RemoteSpotApp/1.0 (contact@remotespot.com)',
                ])->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $address,
                    'format' => 'json',
                    'limit' => 1,
                ]);

                if ($response->successful() && !empty($response->json())) {
                    $first = $response->json()[0];
                    $lat = (float) $first['lat'];
                    $lng = (float) $first['lon'];

                    Log::info("Geocoding preciso via OpenStreetMap para [{$address}]: Lat {$lat}, Lng {$lng}");

                    return [
                        'latitude' => $lat,
                        'longitude' => $lng,
                    ];
                }
            } catch (\Exception $e) {
                Log::warning("Falha na API OpenStreetMap Nominatim: " . $e->getMessage());
            }

            // 3. Coordenadas padrão (São Paulo Centro) em caso extremo de falha de conectividade
            return [
                'latitude' => -23.5505,
                'longitude' => -46.6333,
            ];
        });
    }
}
