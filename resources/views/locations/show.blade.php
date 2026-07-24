<x-layout title="{{ $location->name }} — RemoteSpot">
    <div style="margin-top: 1rem; margin-bottom: 2rem;">
        <a href="{{ route('locations.index') }}" class="btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
            ← Voltar para a lista
        </a>
    </div>

    <!-- Header & Visual Hero do Local -->
    <div class="glass-card" style="margin-bottom: 3rem;">
        <div style="position: relative; height: 360px;">
            <img src="{{ $location->image_url ?? 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80' }}" 
                 alt="{{ $location->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(9,13,22,0.95) 0%, transparent 60%);"></div>

            <div style="position: absolute; bottom: 2rem; left: 2rem; right: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <span class="card-badge" style="position: static; margin-bottom: 0.75rem; display: inline-block;">
                        {{ strtoupper($location->category) }}
                    </span>
                    <h1 style="font-family: var(--font-heading); font-size: 2.75rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem;">
                        {{ $location->name }}
                    </h1>
                    <p style="color: var(--text-muted); font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
                        📍 {{ $location->address }}
                    </p>
                </div>

                @auth
                    <form action="{{ route('favorites.toggle', $location) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary" style="background: {{ $isFavorited ? 'var(--accent-rose)' : 'var(--primary)' }};">
                            {{ $isFavorited ? '❤️ Favoritado' : '🤍 Adicionar aos Favoritos' }}
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        <div style="padding: 2rem; display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div>
                <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: #fff; margin-bottom: 1rem;">Sobre este Espaço</h3>
                <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.7; margin-bottom: 2rem;">
                    {{ $location->description ?? 'Este local ainda não possui uma descrição completa, mas já conta com métricas verificadas pela comunidade de trabalhadores remotos.' }}
                </p>

                <!-- Grid de Métricas Expandidas -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; background: rgba(9, 13, 22, 0.6); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-glass);">
                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Velocidade Wi-Fi</span>
                        <div style="font-size: 1.25rem; font-weight: 800; color: var(--accent-teal); margin-top: 0.25rem;">
                            ⚡ {{ $location->wifi_speed_mbps }} Mbps
                        </div>
                    </div>

                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Ruído Ambiente</span>
                        <div style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-top: 0.25rem;">
                            {{ ucfirst($location->noise_level) }}
                        </div>
                    </div>

                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Tomadas Elétricas</span>
                        <div style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-top: 0.25rem;">
                            {{ ucfirst($location->outlet_density) }}
                        </div>
                    </div>

                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Clima Atual</span>
                        <div style="font-size: 1.1rem; font-weight: 700; color: #fbbf24; margin-top: 0.25rem;">
                            🌤️ {{ $location->weather_summary ?? '24°C' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Nota Média -->
            <div style="background: rgba(9, 13, 22, 0.6); border-radius: var(--radius-md); padding: 1.5rem; border: 1px solid var(--border-glass); text-align: center; display: flex; flex-direction: column; justify-content: center;">
                <span style="font-size: 0.85rem; color: var(--text-dim); text-transform: uppercase; font-weight: 700;">Avaliação da Comunidade</span>
                <div style="font-size: 3.5rem; font-weight: 800; color: var(--accent-amber); margin: 0.5rem 0;">
                    ★ {{ $location->average_rating }}
                </div>
                <span style="color: var(--text-muted); font-size: 0.9rem;">
                    Baseado em {{ $location->reviews->count() }} avaliações de desenvolvedores
                </span>
            </div>
        </div>
    </div>

    <!-- Seção de Geolocalização (Google Maps Container) -->
    <div class="glass-card" style="padding: 2rem; margin-bottom: 3rem;">
        <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: #fff; margin-bottom: 1rem;">📍 Localização no Mapa</h3>
        
        <!-- Renderização do Container do Mapa -->
        <div id="map" style="width: 100%; height: 380px; border-radius: var(--radius-md); border: 1px solid var(--border-glass); z-index: 1;"></div>

        <!-- Script de Inicialização do Mapa (Google Maps / Leaflet) -->
        @if(config('services.google.maps_api_key') && config('services.google.maps_api_key') !== 'mock_google_maps_key')
            <script>
                function initGoogleMap() {
                    const lat = {{ $location->latitude }};
                    const lng = {{ $location->longitude }};
                    const spotLocation = { lat: lat, lng: lng };

                    const map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 15,
                        center: spotLocation,
                    });

                    new google.maps.Marker({
                        position: spotLocation,
                        map: map,
                        title: "{{ addslashes($location->name) }}",
                    });
                }
            </script>
            <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initGoogleMap" async defer></script>
        @else
            <!-- Leaflet CSS & JS para Mapa Interativo Real em Ambiente Local/Dev -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
                setTimeout(function() {
                    const lat = {{ $location->latitude }};
                    const lng = {{ $location->longitude }};
                    
                    const map = L.map('map', {
                        center: [lat, lng],
                        zoom: 15,
                        zoomControl: true
                    });

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
                        maxZoom: 19
                    }).addTo(map);

                    const customMarker = L.marker([lat, lng]).addTo(map);
                    customMarker.bindPopup(`
                        <div style="font-family: sans-serif; padding: 0.25rem;">
                            <strong style="font-size: 1rem; color: #1e293b;">{{ addslashes($location->name) }}</strong><br>
                            <span style="font-size: 0.85rem; color: #64748b;">📍 {{ addslashes($location->address) }}</span><br>
                            <span style="font-size: 0.85rem; color: #059669; font-weight: bold;">⚡ {{ $location->wifi_speed_mbps }} Mbps</span>
                        </div>
                    `).openPopup();
                }, 100);
            </script>
        @endif
    </div>

    <!-- Seção de Avaliações -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 5rem;">
        <!-- Lista de Comentários -->
        <div>
            <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: #fff; margin-bottom: 1.5rem;">
                Avaliações Recentes ({{ $location->reviews->count() }})
            </h3>

            @forelse($location->reviews as $review)
                <div class="glass-card" style="padding: 1.25rem; margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <img src="{{ $review->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) }}" class="avatar">
                            <div>
                                <h4 style="color: #fff; font-size: 0.95rem; font-weight: 700;">{{ $review->user->name }}</h4>
                                <span style="font-size: 0.75rem; color: var(--text-dim);">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div style="color: var(--accent-amber); font-weight: 700;">
                            ★ {{ $review->rating }}/5
                        </div>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.5;">
                        "{{ $review->comment }}"
                    </p>
                </div>
            @empty
                <p style="color: var(--text-dim);">Nenhuma avaliação ainda. Seja o primeiro a avaliar!</p>
            @endforelse
        </div>

        <!-- Formulário para Enviar Avaliação -->
        <div>
            <div class="glass-card" style="padding: 2rem;">
                <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: #fff; margin-bottom: 1.5rem;">Deixe sua Avaliação</h3>

                @auth
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="location_id" value="{{ $location->id }}">

                        <div class="input-group" style="margin-bottom: 1rem;">
                            <label class="input-label">Nota Geral (1 a 5 Estrelas)</label>
                            <select name="rating" class="form-control" required>
                                <option value="5">★★★★★ (5 - Excelente)</option>
                                <option value="4">★★★★☆ (4 - Muito Bom)</option>
                                <option value="3">★★★☆☆ (3 - Razoável)</option>
                                <option value="2">★★☆☆☆ (2 - Ruim)</option>
                                <option value="1">★☆☆☆☆ (1 - Péssimo)</option>
                            </select>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div class="input-group">
                                <label class="input-label">Nota do Wi-Fi</label>
                                <select name="wifi_rating" class="form-control">
                                    <option value="5">5 - Ultra Rápido</option>
                                    <option value="4">4 - Estável</option>
                                    <option value="3">3 - Ok</option>
                                    <option value="2">2 - Lento</option>
                                    <option value="1">1 - Caótico</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label class="input-label">Conforto das Mesas</label>
                                <select name="comfort_rating" class="form-control">
                                    <option value="5">5 - Ergonômico</option>
                                    <option value="4">4 - Confortável</option>
                                    <option value="3">3 - Razoável</option>
                                    <option value="2">2 - Desconfortável</option>
                                </select>
                            </div>
                        </div>

                        <div class="input-group" style="margin-bottom: 1.5rem;">
                            <label class="input-label">Seu Comentário</label>
                            <textarea name="comment" rows="4" class="form-control" placeholder="Conte como foi trabalhar aqui, tomadas, iluminação e café..." required></textarea>
                        </div>

                        <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">Publicar Avaliação</button>
                    </form>
                @else
                    <div style="text-align: center; padding: 2rem 0;">
                        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Você precisa estar autenticado para enviar uma avaliação.</p>
                        <a href="{{ route('login.google') }}" class="btn-primary" style="background: linear-gradient(135deg, #4285F4, #34A853);">
                            Entrar com Google para Avaliar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</x-layout>
