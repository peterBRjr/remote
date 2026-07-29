    <x-layout title="RemoteSpot — Descubra os Melhores Cafés e Coworkings">
        <!-- Hero Banner Compact & Dynamic -->
        <section class="hero">
            <div class="spots-counter-badge">
                <span class="live-dot"></span>
                <span><strong class="counter-number">{{ $totalSpots ?? 0 }}</strong> Spots Verificados</span>
            </div>
            <h1 class="hero-title">
                Espaços Perfeitos para o seu <span class="gradient-text">Trabalho Remoto</span>
            </h1>
            <div class="hero-highlights">
                <span class="highlight-pill">⚡ Wi-Fi Rápido</span>
                <span class="highlight-pill">🔌 Tomadas Garantidas</span>
                <span class="highlight-pill">☕ Atmosfera Produtiva</span>
            </div>
        </section>

        <!-- Filter & Search Bar -->
        <form action="{{ route('locations.index') }}" method="GET" class="filter-bar">
            <div class="input-group">
                <label class="input-label">Buscar por Nome ou Endereço</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ex: Vila Madalena, Coffee Lab, Pinheiros..." class="form-control">
            </div>

            <div class="input-group">
                <label class="input-label">Categoria</label>
                <select name="category" class="form-control">
                    <option value="">Todas</option>
                    <option value="cafe" {{ request('category') === 'cafe' ? 'selected' : '' }}>☕ Cafés</option>
                    <option value="coworking" {{ request('category') === 'coworking' ? 'selected' : '' }}>🏢 Coworkings</option>
                    <option value="library" {{ request('category') === 'library' ? 'selected' : '' }}>📚 Bibliotecas</option>
                    <option value="hotel_lobby" {{ request('category') === 'hotel_lobby' ? 'selected' : '' }}>🏨 Lobbies</option>
                </select>
            </div>

            <div class="input-group">
                <label class="input-label">Nível de Ruído</label>
                <select name="noise_level" class="form-control">
                    <option value="">Todos</option>
                    <option value="quiet" {{ request('noise_level') === 'quiet' ? 'selected' : '' }}>🤫 Silencioso</option>
                    <option value="moderate" {{ request('noise_level') === 'moderate' ? 'selected' : '' }}>🎧 Moderado</option>
                    <option value="lively" {{ request('noise_level') === 'lively' ? 'selected' : '' }}>🗣️ Animado</option>
                </select>
            </div>

            <div class="input-group">
                <label class="input-label">Tomadas</label>
                <select name="outlet_density" class="form-control">
                    <option value="">Qualquer</option>
                    <option value="abundant" {{ request('outlet_density') === 'abundant' ? 'selected' : '' }}>🔌 Abundantes</option>
                    <option value="moderate" {{ request('outlet_density') === 'moderate' ? 'selected' : '' }}>🔌 Moderadas</option>
                </select>
            </div>

            <div class="input-group" style="justify-content: flex-end;">
                <label class="input-label">&nbsp;</label>
                <button type="submit" class="btn-primary" style="height: 42px;">Filtrar</button>
            </div>
        </form>

        <!-- Grid de Cards de Locais -->
        @if($locations->count() > 0)
            <div class="location-grid">
                @foreach($locations as $location)
                    <x-location-card :location="$location" />
                @endforeach
            </div>

            <!-- Paginação -->
            <div style="margin-bottom: 4rem;">
                {{ $locations->links() }}
            </div>
        @else
            <div class="glass-card" style="padding: 4rem; text-align: center; margin-bottom: 4rem;">
                <h3 style="font-size: 1.5rem; color: var(--text-main); margin-bottom: 1rem;">Nenhum local encontrado com esses filtros</h3>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Tente ajustar os critérios da busca ou cadastre um novo local para a comunidade.</p>
                <a href="{{ route('locations.index') }}" class="btn-outline">Limpar Filtros</a>
            </div>
        @endif
    </x-layout>
