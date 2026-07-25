<?php

namespace App\Services;

use App\Models\User;
use App\Models\Location;
use App\Models\Review;
use App\Models\Favorite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    protected string $url;
    protected string $key;
    protected string $table;

    public function __construct()
    {
        $rawUrl = config('services.supabase.url', env('SUPABASE_URL', ''));
        $this->url = $this->normalizeUrl($rawUrl);
        $this->key = config('services.supabase.key', env('SUPABASE_KEY', env('SUPABASE_ANON_KEY', '')));
        $this->table = config('services.supabase.table', env('SUPABASE_TABLE', 'locations'));
    }

    /**
     * Normalizar a URL do Supabase (trata links do dashboard ou sem protocolo)
     */
    public function normalizeUrl(string $url): string
    {
        if (empty($url)) {
            return '';
        }

        // Se for URL do dashboard (ex: https://supabase.com/dashboard/project/REF)
        if (preg_match('/project\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return "https://{$matches[1]}.supabase.co";
        }

        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $url = "https://{$url}";
        }

        return rtrim($url, '/');
    }

    /**
     * Verificar se as credenciais do Supabase estão preenchidas
     */
    public function isConfigured(): bool
    {
        return !empty($this->url) 
            && !empty($this->key) 
            && !str_contains($this->url, 'your-project.supabase.co');
    }

    /**
     * Obter a URL base da API
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Buscar dados de uma tabela via REST API do Supabase
     */
    public function fetchTable(?string $table = null, array $queryParams = ['select' => '*']): array
    {
        if (!$this->isConfigured()) {
            Log::info('Supabase REST API não configurada ou com credenciais padrão.');
            return [];
        }

        $tableName = $table ?? $this->table;
        $endpoint = "{$this->url}/rest/v1/{$tableName}";

        try {
            $response = Http::withHeaders([
                'apikey' => $this->key,
                'Authorization' => "Bearer {$this->key}",
                'Accept' => 'application/json',
            ])
            ->timeout(10)
            ->get($endpoint, $queryParams);

            if ($response->successful()) {
                $data = $response->json();
                return is_array($data) ? $data : [];
            }

            Log::warning("Supabase API respondeu com status {$response->status()}: {$response->body()}");
        } catch (\Throwable $e) {
            Log::error("Erro na conexão com Supabase: {$e->getMessage()}");
        }

        return [];
    }

    /**
     * Inserir um registro em uma tabela do Supabase via REST API
     */
    public function insertTable(string $table, array $data): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $endpoint = "{$this->url}/rest/v1/{$table}";

        try {
            $response = Http::withHeaders([
                'apikey' => $this->key,
                'Authorization' => "Bearer {$this->key}",
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation',
            ])
            ->timeout(10)
            ->post($endpoint, $data);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error("Erro ao inserir no Supabase ({$table}): {$e->getMessage()}");
        }

        return false;
    }

    /**
     * Buscar locais cadastrados no Supabase
     */
    public function fetchLocations(): array
    {
        return $this->fetchTable('locations');
    }

    /**
     * Sincronizar todos os dados do Supabase (Users, Locations, Reviews, Favorites)
     */
    public function syncAllFromSupabase(): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        Log::info('Iniciando sincronização completa a partir do Supabase...');

        // 1. Sincronizar Usuários (preservando a senha criptografada)
        $usersData = $this->fetchTable('users');
        foreach ($usersData as $u) {
            $userPayload = [
                'name' => $u['name'] ?? 'Usuário RemoteSpot',
                'google_id' => $u['google_id'] ?? null,
                'avatar' => $u['avatar'] ?? null,
            ];

            if (!empty($u['password'])) {
                $userPayload['password'] = $u['password'];
            }

            User::updateOrCreate(
                ['email' => $u['email']],
                $userPayload
            );
        }

        // 2. Sincronizar Locais
        $locationsData = $this->fetchTable('locations');
        $geocodingService = app(GeocodingService::class);
        foreach ($locationsData as $item) {
            $lat = isset($item['latitude']) ? (float) $item['latitude'] : null;
            $lng = isset($item['longitude']) ? (float) $item['longitude'] : null;

            // Se as coordenadas vierem vazias ou com a localização genérica antiga, geocodificar pelo endereço
            if (empty($lat) || empty($lng) || (abs($lat - (-23.5505)) < 0.05 && abs($lng - (-46.6333)) < 0.05) || (abs($lat - (-23.5315)) < 0.05 && abs($lng - (-46.6243)) < 0.05)) {
                if (!empty($item['address'])) {
                    $coords = $geocodingService->geocodeAddress($item['address']);
                    $lat = $coords['latitude'];
                    $lng = $coords['longitude'];
                }
            }

            Location::updateOrCreate(
                ['name' => $item['name'], 'address' => $item['address']],
                [
                    'latitude' => $lat ?? -23.5505,
                    'longitude' => $lng ?? -46.6333,
                    'category' => $item['category'] ?? 'cafe',
                    'wifi_speed_mbps' => (int) ($item['wifi_speed_mbps'] ?? $item['wifi_speed'] ?? 100),
                    'noise_level' => $item['noise_level'] ?? 'moderate',
                    'outlet_density' => $item['outlet_density'] ?? 'abundant',
                    'description' => $item['description'] ?? 'Ambiente preparado para trabalho remoto.',
                    'image_url' => $item['image_url'] ?? $item['photo_url'] ?? null,
                    'weather_summary' => $item['weather_summary'] ?? 'Ensolarado 24°C',
                    'weather_icon' => $item['weather_icon'] ?? '01d',
                    'weather_temp' => (float) ($item['weather_temp'] ?? 24.5),
                    'created_by_user_id' => (!empty($item['created_by_user_id']) && User::where('id', $item['created_by_user_id'])->exists()) ? $item['created_by_user_id'] : null,
                ]
            );
        }

        // 3. Sincronizar Avaliações
        $reviewsData = $this->fetchTable('reviews');
        foreach ($reviewsData as $r) {
            if (isset($r['user_id'], $r['location_id'])) {
                Review::updateOrCreate(
                    ['user_id' => $r['user_id'], 'location_id' => $r['location_id']],
                    [
                        'rating' => (int) ($r['rating'] ?? 5),
                        'comment' => $r['comment'] ?? 'Excelente local.',
                        'wifi_rating' => (int) ($r['wifi_rating'] ?? 5),
                        'comfort_rating' => (int) ($r['comfort_rating'] ?? 5),
                    ]
                );
            }
        }

        // 4. Sincronizar Favoritos
        $favoritesData = $this->fetchTable('favorites');
        foreach ($favoritesData as $f) {
            if (isset($f['user_id'], $f['location_id'])) {
                Favorite::firstOrCreate([
                    'user_id' => $f['user_id'],
                    'location_id' => $f['location_id'],
                ]);
            }
        }

        return true;
    }
}
