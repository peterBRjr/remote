<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Jobs\FetchLocationMetadataJob;
use App\Models\Location;
use App\Services\GeocodingService;
use App\Services\SupabaseService;
use App\Services\WeatherService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(Request $request, SupabaseService $supabaseService): View
    {
        // Sempre sincronizar e carregar as localizações cadastradas no Supabase
        if ($supabaseService->isConfigured()) {
            try {
                $supabaseService->syncAllFromSupabase();
            } catch (\Throwable $e) {
                // Log de falha de conexão silencioso
            }
        }

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
        $totalSpots = Location::count();

        return view('locations.index', compact('locations', 'totalSpots'));
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

    public function store(
        StoreLocationRequest $request, 
        GeocodingService $geocodingService,
        SupabaseService $supabaseService
    ): RedirectResponse {
        $validated = $request->validated();

        // 1. Converter Endereço em Coordenadas Lat/Lng (Geocoding com Cache)
        $coords = $geocodingService->geocodeAddress($validated['address']);

        $locationPayload = [
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
        ];

        // 3. Salvar o Local no Banco Local / Supabase
        $location = Location::create($locationPayload);

        // 4. Sincronizar em tempo real com o Supabase via REST API (resolvendo o ID do usuário no Supabase)
        if ($supabaseService->isConfigured()) {
            $supabasePayload = $locationPayload;

            if (auth()->check()) {
                $authUser = auth()->user();
                $supabaseUsers = $supabaseService->fetchTable('users', ['email' => "eq.{$authUser->email}"]);

                if (!empty($supabaseUsers)) {
                    $supabasePayload['created_by_user_id'] = $supabaseUsers[0]['id'];
                } else {
                    // Se o usuário ainda não existir no Supabase, criar primeiro
                    $supabaseService->insertTable('users', [
                        'name' => $authUser->name,
                        'email' => $authUser->email,
                        'google_id' => $authUser->google_id,
                        'avatar' => $authUser->avatar,
                        'password' => $authUser->password,
                        'created_at' => now()->toIso8601String(),
                    ]);

                    $supabaseUsersRetry = $supabaseService->fetchTable('users', ['email' => "eq.{$authUser->email}"]);
                    if (!empty($supabaseUsersRetry)) {
                        $supabasePayload['created_by_user_id'] = $supabaseUsersRetry[0]['id'];
                    } else {
                        unset($supabasePayload['created_by_user_id']);
                    }
                }
            } else {
                unset($supabasePayload['created_by_user_id']);
            }

            $supabaseService->insertTable('locations', $supabasePayload);
        }

        // 5. Disparar Job em Fila em Background para buscar imagens adicionais do local
        FetchLocationMetadataJob::dispatch($location);

        return redirect()->route('locations.show', $location)
            ->with('success', 'Spot cadastrado com sucesso! Dados sincronizados com o Supabase.');
    }
}
