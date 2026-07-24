<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    public function getWeatherForCoordinates(float $lat, float $lng): ?array
    {
        $apiKey = env('OPENWEATHER_API_KEY');

        if (!$apiKey || $apiKey === 'mock_openweather_key') {
            return [
                'summary' => 'Ensolarado ' . rand(21, 27) . '°C',
                'icon' => '01d',
                'temp' => round(22 + (rand(-10, 50) / 10), 1),
            ];
        }

        try {
            $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'lat' => $lat,
                'lon' => $lng,
                'units' => 'metric',
                'lang' => 'pt_br',
                'appid' => $apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'summary' => ucfirst($data['weather'][0]['description'] ?? 'Tempo bom') . ' ' . round($data['main']['temp']) . '°C',
                    'icon' => $data['weather'][0]['icon'] ?? '01d',
                    'temp' => $data['main']['temp'] ?? 24.0,
                ];
            }
        } catch (\Exception $e) {
            Log::error("Erro na API OpenWeather: " . $e->getMessage());
        }

        return [
            'summary' => 'Parcialmente Nublado 23°C',
            'icon' => '02d',
            'temp' => 23.0,
        ];
    }
}
