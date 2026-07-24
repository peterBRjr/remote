<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        // Se as chaves forem mock de desenvolvimento, simular login instantâneo para facilitar o teste pelo Tech Lead
        if (config('services.google.client_id') === 'mock_google_client_id') {
            return $this->handleDemoLogin();
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Lógica Profissional de Unificação de Contas por E-mail
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Atualizar/Vincular a conta existente com o google_id e foto do perfil
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar() ?? $user->avatar,
                ]);
            } else {
                // Criar nova conta de usuário
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            Auth::login($user, remember: true);

            return redirect()->intended('/')->with('success', 'Bem-vindo de volta, ' . $user->name . '!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Falha ao autenticar com o Google: ' . $e->getMessage());
        }
    }

    private function handleDemoLogin(): RedirectResponse
    {
        $user = User::firstOrCreate(
            ['email' => 'techlead@remotespot.com'],
            [
                'name' => 'Tech Lead Google User',
                'google_id' => 'google_demo_123456',
                'avatar' => 'https://ui-avatars.com/api/?name=Tech+Lead&background=6366f1&color=fff',
            ]
        );

        Auth::login($user, remember: true);

        return redirect()->intended('/')->with('success', 'Login via Google (Modo Demo/Dev) realizado com sucesso!');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('info', 'Você saiu da sua conta.');
    }
}
