<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * Obter clima em tempo real baseado em Latitude e Longitude.
     * Suporta OpenWeatherMap (com API Key) e Open-Meteo (API pública global sem chave).
     * Armazena os dados em Cache por 2 horas para máxima performance.
     */
    public function getWeatherForCoordinates(float $lat, float $lng): array
    {
        $cacheKey = 'weather:' . md5(round($lat, 3) . ',' . round($lng, 3));

        return Cache::remember($cacheKey, now()->addHours(2), function () use ($lat, $lng) {
            $apiKey = env('OPENWEATHER_API_KEY');

            // 1. Tentar OpenWeatherMap se houver chave configurada
            if ($apiKey && $apiKey !== 'mock_openweather_key') {
                $openWeatherData = $this->fetchFromOpenWeather($lat, $lng, $apiKey);
                if ($openWeatherData) {
                    return $openWeatherData;
                }
            }

            // 2. Fallback para Open-Meteo API (API Global em tempo real sem necessidade de chave)
            $openMeteoData = $this->fetchFromOpenMeteo($lat, $lng);
            if ($openMeteoData) {
                return $openMeteoData;
            }

            // 3. Fallback Padrão Simulado se nenhuma API responder
            return [
                'description' => 'Tempo Limpo',
                'icon' => '01d',
                'temp' => 24.0,
            ];
        });
    }

    /**
     * Requisição para a API do OpenWeatherMap
     */
    protected function fetchFromOpenWeather(float $lat, float $lng, string $apiKey): ?array
    {
        try {
            $response = Http::timeout(5)->get('https://api.openweathermap.org/data/2.5/weather', [
                'lat' => $lat,
                'lon' => $lng,
                'units' => 'metric',
                'lang' => 'pt_br',
                'appid' => $apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $temp = round($data['main']['temp'] ?? 24, 1);
                $desc = ucfirst($data['weather'][0]['description'] ?? 'Tempo Bom');
                $icon = $data['weather'][0]['icon'] ?? '01d';

                return [
                    'description' => $desc,
                    'icon' => $icon,
                    'temp' => $temp,
                ];
            }
        } catch (\Throwable $e) {
            Log::warning("OpenWeather API falhou: {$e->getMessage()}. Alternando para Open-Meteo.");
        }

        return null;
    }

    /**
     * Requisição para a API gratuita Open-Meteo (Real-time Weather)
     */
    protected function fetchFromOpenMeteo(float $lat, float $lng): ?array
    {
        try {
            $response = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $lat,
                'longitude' => $lng,
                'current_weather' => 'true',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $current = $data['current_weather'] ?? [];
                $temp = round($current['temperature'] ?? 23.5, 1);
                $code = (int) ($current['weathercode'] ?? 0);

                $weatherInfo = $this->mapWmoCodeToWeather($code);

                return [
                    'description' => $weatherInfo['summary'],
                    'icon' => $weatherInfo['icon'],
                    'temp' => $temp,
                ];
            }
        } catch (\Throwable $e) {
            Log::error("Erro na API Open-Meteo: {$e->getMessage()}");
        }

        return null;
    }

    /**
     * Mapeamento de códigos WMO Weather para descrições em Português e ícones
     */
    protected function mapWmoCodeToWeather(int $code): array
    {
        $map = [
            0 => ['summary' => 'Céu Limpo', 'icon' => '01d'],
            1 => ['summary' => 'Ensolarado', 'icon' => '01d'],
            2 => ['summary' => 'Parcialmente Nublado', 'icon' => '02d'],
            3 => ['summary' => 'Nublado', 'icon' => '04d'],
            45 => ['summary' => 'Nevoeiro', 'icon' => '50d'],
            48 => ['summary' => 'Nevoeiro', 'icon' => '50d'],
            51 => ['summary' => 'Garoa Leve', 'icon' => '09d'],
            53 => ['summary' => 'Garoa', 'icon' => '09d'],
            55 => ['summary' => 'Garoa Intensa', 'icon' => '09d'],
            61 => ['summary' => 'Chuva Leve', 'icon' => '10d'],
            63 => ['summary' => 'Chuva Moderada', 'icon' => '10d'],
            65 => ['summary' => 'Chuva Forte', 'icon' => '10d'],
            80 => ['summary' => 'Pancadas de Chuva', 'icon' => '10d'],
            81 => ['summary' => 'Chuva Forte', 'icon' => '10d'],
            95 => ['summary' => 'Tempestade', 'icon' => '11d'],
        ];

        return $map[$code] ?? ['summary' => 'Tempo Limpo', 'icon' => '01d'];
    }
}
