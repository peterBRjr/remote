<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FoursquareService
{
    public function getPlacePhoto(string $name, float $lat, float $lng): ?string
    {
        $apiKey = env('FOURSQUARE_API_KEY');

        $sampleImages = [
            'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1521017432531-fbd92d768814?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=1200&q=80',
        ];

        if (!$apiKey || $apiKey === 'mock_foursquare_key') {
            return $sampleImages[array_rand($sampleImages)];
        }

        try {
            $searchResponse = Http::withHeaders([
                'Authorization' => $apiKey,
                'accept' => 'application/json',
            ])->get('https://api.foursquare.com/v3/places/search', [
                'query' => $name,
                'll' => "{$lat},{$lng}",
                'limit' => 1,
            ]);

            if ($searchResponse->successful() && !empty($searchResponse->json()['results'])) {
                $fsqId = $searchResponse->json()['results'][0]['fsq_id'];

                $photoResponse = Http::withHeaders([
                    'Authorization' => $apiKey,
                    'accept' => 'application/json',
                ])->get("https://api.foursquare.com/v3/places/{$fsqId}/photos", [
                    'limit' => 1,
                ]);

                if ($photoResponse->successful() && !empty($photoResponse->json())) {
                    $photo = $photoResponse->json()[0];
                    return $photo['prefix'] . 'original' . $photo['suffix'];
                }
            }
        } catch (\Exception $e) {
            Log::error("Erro na API do Foursquare: " . $e->getMessage());
        }

        return $sampleImages[array_rand($sampleImages)];
    }
}
