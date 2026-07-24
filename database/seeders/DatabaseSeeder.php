<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use App\Models\Review;
use App\Models\Favorite;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User de demonstração para o Tech Lead
        $demoUser = User::factory()->create([
            'name' => 'Tech Lead Demo',
            'email' => 'techlead@remotespot.com',
            'avatar' => 'https://ui-avatars.com/api/?name=Tech+Lead&background=6366f1&color=fff',
        ]);

        // Usuários adicionais
        $users = User::factory(5)->create();

        // 1. Spot Real 1: Vila Madalena Cafe
        $spot1 = Location::create([
            'name' => 'Coffee Lab & Work',
            'address' => 'R. Fradique Coutinho, 1340 - Pinheiros, São Paulo - SP',
            'latitude' => -23.5582,
            'longitude' => -46.6890,
            'category' => 'cafe',
            'wifi_speed_mbps' => 250,
            'noise_level' => 'quiet',
            'outlet_density' => 'abundant',
            'description' => 'Café especial focado em alta produtividade para devs. Tomadas em todas as mesas, café coado de lote exclusivo e ambiente climatizado com iluminação suave.',
            'image_url' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80',
            'weather_summary' => 'Ensolarado 24°C',
            'weather_icon' => '01d',
            'weather_temp' => 24.5,
            'created_by_user_id' => $demoUser->id,
        ]);

        // 2. Spot Real 2: WeWork Paulista
        $spot2 = Location::create([
            'name' => 'Paulista Hub Coworking',
            'address' => 'Av. Paulista, 1374 - Bela Vista, São Paulo - SP',
            'latitude' => -23.5615,
            'longitude' => -46.6559,
            'category' => 'coworking',
            'wifi_speed_mbps' => 500,
            'noise_level' => 'moderate',
            'outlet_density' => 'abundant',
            'description' => 'Espaço corporativo com vista incrível da Paulista. Salas de reunião privativas, chopp liberado pós-18h e fibra óptica de 1Gbps redundante.',
            'image_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
            'weather_summary' => 'Parcialmente Nublado',
            'weather_icon' => '02d',
            'weather_temp' => 22.0,
            'created_by_user_id' => $demoUser->id,
        ]);

        // 3. Spot Real 3: Beco do Batat
        $spot3 = Location::create([
            'name' => 'Urban Roasters & Lounge',
            'address' => 'R. Aspicuelta, 288 - Vila Madalena, São Paulo - SP',
            'latitude' => -23.5539,
            'longitude' => -46.6881,
            'category' => 'cafe',
            'wifi_speed_mbps' => 120,
            'noise_level' => 'lively',
            'outlet_density' => 'moderate',
            'description' => 'Vibe moderna, música indie suave, brunch excelente e ambiente aconchegante para quem curte codar ouvindo som ambiente.',
            'image_url' => 'https://images.unsplash.com/photo-1521017432531-fbd92d768814?auto=format&fit=crop&w=1200&q=80',
            'weather_summary' => 'Céu Limpo',
            'weather_icon' => '01d',
            'weather_temp' => 26.2,
            'created_by_user_id' => $demoUser->id,
        ]);

        // Criar mais locais fictícios
        $moreLocations = Location::factory(6)->create();

        // Adicionar avaliações
        $allLocations = Location::all();
        foreach ($allLocations as $loc) {
            Review::create([
                'user_id' => $demoUser->id,
                'location_id' => $loc->id,
                'rating' => 5,
                'comment' => 'Sensacional para trabalhar a tarde toda! Conexão voando e café perfeito.',
                'wifi_rating' => 5,
                'comfort_rating' => 5,
            ]);

            foreach ($users->random(2) as $u) {
                Review::create([
                    'user_id' => $u->id,
                    'location_id' => $loc->id,
                    'rating' => rand(4, 5),
                    'comment' => 'Ótima infraestrutura para chamadas de vídeo e foco contínuo.',
                    'wifi_rating' => rand(4, 5),
                    'comfort_rating' => rand(4, 5),
                ]);
            }
        }

        // Marcar favoritos para o usuário Demo
        Favorite::create([
            'user_id' => $demoUser->id,
            'location_id' => $spot1->id,
        ]);
        Favorite::create([
            'user_id' => $demoUser->id,
            'location_id' => $spot2->id,
        ]);
    }
}
