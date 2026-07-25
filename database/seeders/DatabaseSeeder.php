<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\SupabaseService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $supabaseService = app(SupabaseService::class);

        // 1. Criar o Usuário Tech Lead Demo se não existir
        User::firstOrCreate(
            ['email' => 'techlead@remotespot.com'],
            [
                'name' => 'Tech Lead Demo',
                'password' => Crypt::encryptString('password123'),
                'avatar' => 'https://ui-avatars.com/api/?name=Tech+Lead&background=6366f1&color=fff',
            ]
        );

        // 2. Sincronizar exclusivamente todas as localizações e dados do Supabase
        if ($supabaseService->isConfigured()) {
            try {
                $supabaseService->syncAllFromSupabase();
            } catch (\Throwable $e) {
                Log::warning('Erro ao sincronizar dados do Supabase no Seeder: ' . $e->getMessage());
            }
        }
    }
}
