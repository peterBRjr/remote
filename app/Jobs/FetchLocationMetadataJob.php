<?php

namespace App\Jobs;

use App\Models\Location;
use App\Services\FoursquareService;
use App\Services\WeatherService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FetchLocationMetadataJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Location $location)
    {
    }

    public function handle(FoursquareService $foursquareService, WeatherService $weatherService): void
    {
        Log::info("Processando Job de Metadata em Background para o local ID: {$this->location->id} ({$this->location->name})");

        // 1. Buscar Foto no Foursquare
        $photoUrl = $foursquareService->getPlacePhoto(
            $this->location->name,
            $this->location->latitude,
            $this->location->longitude
        );

        // 2. Buscar Clima no OpenWeather
        $weatherData = $weatherService->getWeatherForCoordinates(
            $this->location->latitude,
            $this->location->longitude
        );

        // 3. Atualizar no banco de dados de forma assíncrona
        $this->location->update([
            'image_url' => $photoUrl ?? $this->location->image_url,
            'weather_summary' => $weatherData['summary'] ?? $this->location->weather_summary,
            'weather_icon' => $weatherData['icon'] ?? $this->location->weather_icon,
            'weather_temp' => $weatherData['temp'] ?? $this->location->weather_temp,
        ]);

        Log::info("Job de Metadata finalizado com sucesso para o local ID: {$this->location->id}");
    }
}
