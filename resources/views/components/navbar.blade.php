<header class="navbar">
    <div class="container nav-content">
        <a href="{{ route('home') }}" class="logo">
            <div class="logo-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
            </div>
            <span>Remote<span style="color: var(--accent-teal)">Workplace</span></span>
        </a>

        <nav class="nav-links">
            <a href="{{ route('locations.index') }}" class="nav-link {{ request()->routeIs('locations.index') ? 'active' : '' }}">
                🔍 Descobrir Locais
            </a>

            @auth
                <a href="{{ route('favorites.index') }}" class="nav-link {{ request()->routeIs('favorites.index') ? 'active' : '' }}">
                    ❤️ Favoritos
                </a>
                <a href="{{ route('locations.create') }}" class="btn-primary">
                    <span>+ Cadastrar Spot</span>
                </a>
                <div class="user-profile-bar" style="display: flex; align-items: center; gap: 0.75rem; margin-left: 1rem;">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="avatar">
                    @else
                        <div class="avatar" style="background: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff;">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Sair</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                    Entrar
                </a>
                <a href="{{ route('register') }}" class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}">
                    Cadastrar
                </a>
                <a href="{{ route('login.google') }}" class="btn-primary btn-google-login" style="background: linear-gradient(135deg, #4285F4, #34A853); padding: 0.5rem 1rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.761H12.545z"/>
                    </svg>
                    <span class="btn-text">Google</span>
                </a>
            @endauth
        </nav>
    </div>
</header>
