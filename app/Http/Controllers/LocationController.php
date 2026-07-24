<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Jobs\FetchLocationMetadataJob;
use App\Models\Location;
use App\Services\GeocodingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Location::with(['reviews', 'favoritedBy']);

        // Filtro por Categoria
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtro por Ruído
        if ($request->filled('noise_level')) {
            $query->where('noise_level', $request->noise_level);
        }

        // Filtro por Tomadas
        if ($request->filled('outlet_density')) {
            $query->where('outlet_density', $request->outlet_density);
        }

        // Busca por Nome ou Endereço
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $locations = $query->latest()->paginate(9)->withQueryString();

        return view('locations.index', compact('locations'));
    }

    public function show(Location $location): View
    {
        $location->load(['reviews.user', 'creator']);
        $isFavorited = auth()->check() ? auth()->user()->favorites->contains('id', $location->id) : false;

        return view('locations.show', compact('location', 'isFavorited'));
    }

    public function create(): View
    {
        return view('locations.create');
    }

    public function store(StoreLocationRequest $request, GeocodingService $geocodingService): RedirectResponse
    {
        $validated = $request->validated();

        // 1. Converter Endereço em Coordenadas Lat/Lng (Geocoding com Cache)
        $coords = $geocodingService->geocodeAddress($validated['address']);

        // 2. Salvar o Local no Banco
        $location = Location::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'latitude' => $coords['latitude'],
            'longitude' => $coords['longitude'],
            'category' => $validated['category'],
            'wifi_speed_mbps' => $validated['wifi_speed_mbps'] ?? 50,
            'noise_level' => $validated['noise_level'],
            'outlet_density' => $validated['outlet_density'],
            'description' => $validated['description'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
            'created_by_user_id' => auth()->id(),
        ]);

        // 3. Disparar Job em Fila (Background Queue) para enriquecer o local com Foursquare + OpenWeather
        FetchLocationMetadataJob::dispatch($location);

        return redirect()->route('locations.show', $location)
            ->with('success', 'Local cadastrado com sucesso! As fotos e clima do local estão sendo processadas em background.');
    }
}
