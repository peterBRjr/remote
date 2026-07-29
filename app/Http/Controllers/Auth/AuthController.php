<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\SupabaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Efetua o login do usuário verificando e descriptografando a senha gravada no banco.
     */
    public function login(LoginRequest $request, SupabaseService $supabaseService): RedirectResponse
    {
        $email = $request->email;
        $plainPassword = $request->password;
        $remember = $request->boolean('remember');

        // 1. Buscar o usuário pelo E-mail no banco de dados local
        $user = User::where('email', $email)->first();

        // 2. Se o usuário não existir localmente, buscar na tabela 'users' do Supabase
        if (!$user && $supabaseService->isConfigured()) {
            $supabaseUsers = $supabaseService->fetchTable('users', ['email' => "eq.{$email}"]);
            if (!empty($supabaseUsers)) {
                $sUser = $supabaseUsers[0];
                $user = User::create([
                    'name' => $sUser['name'] ?? 'Desenvolvedor RemoteWorkplace',
                    'email' => $sUser['email'],
                    'password' => $sUser['password'] ?? Crypt::encryptString($plainPassword),
                    'avatar' => $sUser['avatar'] ?? null,
                ]);
            }
        }

        // 3. Validação e Descriptografia da Senha
        if ($user && $user->password) {
            $passwordMatched = false;

            // Tentativa A: Descriptografar a senha gravada no banco (Crypt::decryptString)
            try {
                $decryptedPassword = Crypt::decryptString($user->password);
                if ($decryptedPassword === $plainPassword) {
                    $passwordMatched = true;
                }
            } catch (\Throwable $e) {
                // Tentativa B: Fallback se a senha estiver em formato Hash (Hash::check)
                if (Hash::check($plainPassword, $user->password)) {
                    $passwordMatched = true;
                    // Atualiza a senha no banco para o formato Criptografado
                    $user->update(['password' => Crypt::encryptString($plainPassword)]);
                } elseif ($user->password === $plainPassword) {
                    // Tentativa C: Fallback para senhas criadas manualmente em texto puro via SQL
                    $passwordMatched = true;
                    $user->update(['password' => Crypt::encryptString($plainPassword)]);
                }
            }

            if ($passwordMatched) {
                Auth::login($user, $remember);
                $request->session()->regenerate();
                return redirect()->intended('/')->with('success', 'Login realizado com sucesso! Senha descriptografada e validada.');
            }
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'As credenciais informadas não correspondem aos nossos registros.']);
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Registra o usuário gravando a senha com Criptografia (Crypt::encryptString) no Banco e Supabase.
     */
    public function register(RegisterRequest $request, SupabaseService $supabaseService): RedirectResponse
    {
        $validated = $request->validated();

        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($validated['name']) . '&background=6366f1&color=fff';

        // Criptografia reversível da senha via AES-256 (Crypt)
        $encryptedPassword = Crypt::encryptString($validated['password']);

        $userPayload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $encryptedPassword,
            'avatar' => $avatarUrl,
        ];

        // 1. Criar Usuário no Banco de Dados Local
        $user = User::create($userPayload);

        // 2. Sincronizar Novo Usuário com a Tabela 'users' do Supabase com a Senha Criptografada
        if ($supabaseService->isConfigured()) {
            $supabaseService->insertTable('users', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $encryptedPassword,
                'avatar' => $user->avatar,
                'created_at' => now()->toIso8601String(),
            ]);
        }

        // 3. Efetuar Login do Novo Usuário
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Conta criada com sucesso! Sua senha foi criptografada e armazenada no Supabase.');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('info', 'Você saiu da sua conta.');
    }
}
