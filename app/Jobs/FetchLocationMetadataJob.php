<?php

namespace App\Jobs;

use App\Models\Location;
use App\Services\FoursquareService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FetchLocationMetadataJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Location $location)
    {
    }

    public function handle(FoursquareService $foursquareService): void
    {
        Log::info("Processando Job de Metadata em Background para o local ID: {$this->location->id} ({$this->location->name})");

        // 1. Buscar Foto no Foursquare
        $photoUrl = $foursquareService->getPlacePhoto(
            $this->location->name,
            $this->location->latitude,
            $this->location->longitude
        );

        if ($photoUrl) {
            $this->location->update([
                'image_url' => $photoUrl,
            ]);
        }

        Log::info("Job de Metadata finalizado com sucesso para o local ID: {$this->location->id}");
    }
}
