<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SupabaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redireciona o usuário para a tela de autenticação OAuth 2.0 do Google.
     */
    public function redirect(): RedirectResponse
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Throwable $e) {
            Log::error("Erro ao redirecionar para a API do Google: " . $e->getMessage());
            return redirect()->route('login')->with('error', 'Não foi possível iniciar o login com o Google. Verifique se o GOOGLE_CLIENT_ID e GOOGLE_CLIENT_SECRET estão configurados no arquivo .env.');
        }
    }

    /**
     * Processa o retorno (callback) do Google OAuth 2.0, unifica contas e salva no Supabase.
     */
    public function callback(SupabaseService $supabaseService): RedirectResponse
    {
        try {
            // Obter os dados do perfil retornado pela API do Google
            $googleUser = Socialite::driver('google')->user();

            $email = $googleUser->getEmail();
            $name = $googleUser->getName() ?? $googleUser->getNickname() ?? 'Usuário Google';
            $googleId = $googleUser->getId();
            $avatar = $googleUser->getAvatar();

            // 1. Lógica de Unificação de Contas por E-mail (Local e Supabase)
            $user = User::where('email', $email)->first();

            if ($user) {
                // Atualizar e vincular a conta existente com os dados do Google
                $user->update([
                    'google_id' => $googleId,
                    'avatar' => $avatar ?? $user->avatar,
                ]);

                // Atualizar também no Supabase se configurado
                if ($supabaseService->isConfigured()) {
                    $supabaseService->insertTable('users', [
                        'name' => $user->name,
                        'email' => $user->email,
                        'google_id' => $googleId,
                        'avatar' => $user->avatar,
                    ]);
                }
            } else {
                // 2. Criar Novo Usuário na Plataforma (gerando um token criptografado seguro para password)
                $randomPassword = \Illuminate\Support\Facades\Crypt::encryptString(\Illuminate\Support\Str::random(32));
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'avatar' => $avatar,
                    'password' => $randomPassword,
                ]);

                // 3. Cadastrar e Sincronizar o Novo Usuário no Banco de Dados do Supabase
                if ($supabaseService->isConfigured()) {
                    $supabaseService->insertTable('users', [
                        'name' => $name,
                        'email' => $email,
                        'google_id' => $googleId,
                        'avatar' => $avatar,
                        'password' => $randomPassword,
                        'created_at' => now()->toIso8601String(),
                    ]);
                }
            }

            // 4. Autenticar o Usuário na Sessão do Laravel
            Auth::login($user, remember: true);

            return redirect()->intended('/')->with('success', "Bem-vindo ao RemoteSpot, {$user->name}!");

        } catch (\Throwable $e) {
            Log::error("Erro no callback do Google OAuth: " . $e->getMessage());
            return redirect()->route('login')->with('error', 'Falha na autenticação via Google: ' . $e->getMessage());
        }
    }

    /**
     * Encerra a sessão do usuário.
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('info', 'Você saiu da sua conta.');
    }
}
