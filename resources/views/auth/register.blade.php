<x-layout>
    <div style="max-width: 520px; margin: 3rem auto;">
        <div class="glass-card" style="padding: 2.5rem;">
            <!-- Header do Card -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <div class="logo-icon" style="margin: 0 auto 1rem; width: 48px; height: 48px; font-size: 1.5rem;">🚀</div>
                <h2 style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 800; color: #fff;">Criar Conta no RemoteWorkplace</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.5rem;">
                    Junte-se a milhares de desenvolvedores e trabalhe dos melhores coworkings e cafés.
                </p>
            </div>

            <!-- Botão Cadastro Social via Google -->
            <div style="margin-bottom: 1.5rem;">
                <a href="{{ route('login.google') }}" class="btn-primary btn-google-login" 
                   style="width: 100%; justify-content: center; padding: 0.85rem; background: linear-gradient(135deg, #4285F4, #34A853); font-size: 1rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.761H12.545z"/>
                    </svg>
                    <span class="btn-text">Cadastrar com Google</span>
                </a>
            </div>

            <!-- Divisor Visual -->
            <div style="display: flex; align-items: center; margin: 1.5rem 0; color: var(--text-dim); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                <div style="flex: 1; height: 1px; background: var(--border-glass);"></div>
                <span style="padding: 0 0.75rem;">ou preencha seus dados</span>
                <div style="flex: 1; height: 1px; background: var(--border-glass);"></div>
            </div>

            <!-- Formulário de Cadastro Nativo -->
            <form action="{{ route('register.perform') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                @csrf

                @if ($errors->any())
                    <div style="background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(244, 63, 94, 0.3); color: #fb7185; padding: 0.75rem 1rem; border-radius: var(--radius-md); font-size: 0.875rem;">
                        <ul style="margin: 0; padding-left: 1.25rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="input-group">
                    <label class="input-label" for="name">Nome Completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Ex: Pedro Henrique" class="form-control">
                </div>

                <div class="input-group">
                    <label class="input-label" for="email">E-mail Profissional</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           placeholder="seu.email@exemplo.com" class="form-control">
                </div>

                <div class="input-group">
                    <label class="input-label" for="password">Senha</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Mínimo 8 caracteres" class="form-control">
                </div>

                <div class="input-group">
                    <label class="input-label" for="password_confirmation">Confirmar Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required 
                           placeholder="Repita a sua senha" class="form-control">
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 0.85rem; font-size: 1rem; margin-top: 0.5rem;">
                    <span class="btn-text">Criar Minha Conta</span>
                </button>
            </form>

            <!-- Link de Login -->
            <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-glass); color: var(--text-muted); font-size: 0.9rem;">
                Já possui uma conta? 
                <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 700; text-decoration: none;">
                    Fazer Login
                </a>
            </div>
        </div>
    </div>
</x-layout>
