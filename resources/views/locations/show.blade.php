<x-layout title="{{ $location->name }} — RemoteWorkplace">
    <div style="margin-top: 1rem; margin-bottom: 2rem;">
        <a href="{{ route('locations.index') }}" class="btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
            ← Voltar para a lista
        </a>
    </div>

    <!-- Header & Visual Hero do Local -->
    <div class="glass-card" style="margin-bottom: 3rem;">
        <div class="show-hero-container" style="position: relative; height: 360px;">
            <img src="{{ $location->image_url ?? 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80' }}" 
                 alt="{{ $location->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(9,13,22,0.95) 0%, transparent 60%);"></div>

            <div class="show-hero-overlay" style="position: absolute; bottom: 2rem; left: 2rem; right: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <span class="card-badge" style="position: static; margin-bottom: 0.75rem; display: inline-block;">
                        {{ strtoupper($location->category) }}
                    </span>
                    <h1 class="show-hero-title" style="font-family: var(--font-heading); font-size: 2.75rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem;">
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

        <div class="show-main-grid" style="padding: 2rem; display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div>
                <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: #fff; margin-bottom: 1rem;">Sobre este Espaço</h3>
                <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.7; margin-bottom: 2rem;">
                    {{ $location->description ?? 'Este local ainda não possui uma descrição completa, mas já conta com métricas verificadas pela comunidade de trabalhadores remotos.' }}
                </p>

                <!-- Grid de Métricas Expandidas -->
                <div class="metrics-expanded-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; background: rgba(9, 13, 22, 0.6); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-glass);">
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

                    <div style="text-align: center; background: rgba(251, 191, 36, 0.08); padding: 0.6rem; border-radius: var(--radius-sm); border: 1px solid rgba(251, 191, 36, 0.25);"
                         class="weather-live-box"
                         data-lat="{{ $location->latitude }}"
                         data-lng="{{ $location->longitude }}">
                        <span style="font-size: 0.75rem; color: #fbbf24; text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Clima no Local</span>
                        <div class="weather-display" style="margin-top: 0.25rem; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.1rem;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 0.35rem;">
                                <img class="weather-icon-img" src="https://openweathermap.org/img/wn/01d.png" style="width: 26px; height: 26px; margin: -4px 0;" alt="Clima">
                                <span class="weather-desc-text" style="font-size: 1.05rem; font-weight: 800; color: #fff;">Carregando...</span>
                            </div>
                            <span class="weather-temp-text" style="font-size: 1.15rem; font-weight: 800; color: #fff;">--°C</span>
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

    <!-- Seção de Geolocalização (Interactive Premium Dark Map) -->
    <div class="glass-card" style="padding: 2rem; margin-bottom: 3rem; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <div>
                <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: #fff; display: flex; align-items: center; gap: 0.5rem;">
                    <span>📍 Localização no Mapa</span>
                </h3>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.25rem;">
                    Coordenadas geocodificadas com alta precisão ({{ number_format($location->latitude, 6) }}, {{ number_format($location->longitude, 6) }})
                </p>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <a href="https://www.google.com/maps/search/?api=1&query={{ $location->latitude }},{{ $location->longitude }}" 
                   target="_blank" rel="noopener noreferrer" class="btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                    🗺️ Abrir no Google Maps ↗
                </a>
            </div>
        </div>
        
        <!-- Renderização do Container do Mapa -->
        <div id="map" style="width: 100%; height: 420px; border-radius: var(--radius-md); border: 1px solid var(--border-glass); z-index: 1; overflow: hidden; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);"></div>

        <!-- Script de Inicialização do Mapa -->
        @if(config('services.google.maps_api_key') && config('services.google.maps_api_key') !== 'mock_google_maps_key')
            <script>
                function initGoogleMap() {
                    const lat = {{ $location->latitude }};
                    const lng = {{ $location->longitude }};
                    const spotLocation = { lat: lat, lng: lng };

                    const map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 16,
                        center: spotLocation,
                        styles: [
                            { elementType: "geometry", stylers: [{ color: "#1d2c4d" }] },
                            { elementType: "labels.text.fill", stylers: [{ color: "#8ec3b9" }] },
                            { elementType: "labels.text.stroke", stylers: [{ color: "#1a3646" }] },
                            { featureType: "administrative.country", elementType: "geometry.stroke", stylers: [{ color: "#4b687a" }] },
                            { featureType: "water", elementType: "geometry", stylers: [{ color: "#0e1726" }] }
                        ]
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
            <!-- Leaflet CSS & JS com Map Tiles Dark Theme Glassmorphic (CartoDB Dark Matter) -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const lat = {{ $location->latitude }};
                    const lng = {{ $location->longitude }};
                    
                    const map = L.map('map', {
                        center: [lat, lng],
                        zoom: 16,
                        zoomControl: true
                    });

                    // Dark Mode Tiles (CartoDB Dark Matter) para combinar perfeitamente com a interface Glassmorphic
                    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                        subdomains: 'abcd',
                        maxZoom: 19
                    }).addTo(map);

                    // Ícone customizado de alta tecnologia com efeito Neon Pulse
                    const neonIcon = L.divIcon({
                        className: 'custom-neon-marker',
                        html: `<div style="
                            width: 24px; 
                            height: 24px; 
                            background: #6366f1; 
                            border: 3px solid #38bdf8; 
                            border-radius: 50%; 
                            box-shadow: 0 0 20px #6366f1, 0 0 40px #38bdf8;
                            animation: pulse 2s infinite;
                        "></div>`,
                        iconSize: [24, 24],
                        iconAnchor: [12, 12]
                    });

                    const customMarker = L.marker([lat, lng], { icon: neonIcon }).addTo(map);
                    
                    customMarker.bindPopup(`
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; padding: 0.5rem; min-width: 220px; color: #0f172a;">
                            <div style="font-size: 0.75rem; font-weight: 800; color: #6366f1; text-transform: uppercase; margin-bottom: 0.25rem;">
                                {{ strtoupper($location->category) }}
                            </div>
                            <strong style="font-size: 1.1rem; color: #0f172a; display: block; margin-bottom: 0.35rem;">
                                {{ addslashes($location->name) }}
                            </strong>
                            <div style="font-size: 0.85rem; color: #475569; margin-bottom: 0.5rem;">
                                📍 {{ addslashes($location->address) }}
                            </div>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <span style="font-size: 0.8rem; background: #059669; color: #fff; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: bold;">
                                    ⚡ {{ $location->wifi_speed_mbps }} Mbps
                                </span>
                                <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank" 
                                   style="font-size: 0.8rem; color: #2563eb; font-weight: bold; text-decoration: none; margin-left: auto;">
                                    Abrir no Maps ↗
                                </a>
                            </div>
                        </div>
                    `).openPopup();
                });
            </script>
            <style>
                @keyframes pulse {
                    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7); }
                    70% { transform: scale(1.1); box-shadow: 0 0 0 15px rgba(99, 102, 241, 0); }
                    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
                }
            </style>
        @endif
    </div>

    <!-- Seção de Avaliações -->
    <div class="reviews-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 5rem;">
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
                        <a href="{{ route('login.google') }}" class="btn-primary btn-google-login" style="background: linear-gradient(135deg, #4285F4, #34A853);">
                            <span class="btn-text">Entrar com Google para Avaliar</span>
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const weatherBox = document.querySelector('.weather-live-box');
            if (weatherBox) {
                const lat = weatherBox.getAttribute('data-lat');
                const lng = weatherBox.getAttribute('data-lng');
                fetch(`/api/weather?lat=${lat}&lng=${lng}`)
                    .then(res => res.json())
                    .then(data => {
                        const iconImg = weatherBox.querySelector('.weather-icon-img');
                        const descText = weatherBox.querySelector('.weather-desc-text');
                        const tempText = weatherBox.querySelector('.weather-temp-text');
                        
                        if (data.icon && data.icon.startsWith('http')) {
                            iconImg.src = data.icon;
                        } else if (data.icon) {
                            iconImg.src = `https://openweathermap.org/img/wn/${data.icon}.png`;
                        }
                        
                        if (descText) descText.textContent = data.description || 'Tempo Limpo';
                        if (tempText) tempText.textContent = (data.temp !== undefined ? Number(data.temp).toFixed(1) : '24.0') + '°C';
                    })
                    .catch(() => {
                        const descText = weatherBox.querySelector('.weather-desc-text');
                        const tempText = weatherBox.querySelector('.weather-temp-text');
                        if (descText) descText.textContent = 'Ensolarado';
                        if (tempText) tempText.textContent = '24.0°C';
                    });
            }
        });
    </script>
</x-layout>
