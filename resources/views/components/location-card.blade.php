@props(['location'])

<div class="glass-card">
    <div class="card-img-wrapper">
        <img src="{{ $location->image_url ?? 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80' }}" 
             alt="{{ $location->name }}" class="card-img" loading="lazy">

        <span class="card-badge">
            @if($location->category === 'cafe') ☕ Café
            @elseif($location->category === 'coworking') 🏢 Coworking
            @elseif($location->category === 'library') 📚 Biblioteca
            @else 🏨 Lobby
            @endif
        </span>

        <div class="weather-badge weather-live-card" 
             data-lat="{{ $location->latitude }}" 
             data-lng="{{ $location->longitude }}"
             title="Clima ao vivo nas coordenadas ({{ round($location->latitude, 3) }}, {{ round($location->longitude, 3) }})">
            <img class="weather-card-icon" src="https://openweathermap.org/img/wn/01d.png" style="width: 20px; height: 20px; margin: -5px -2px;" alt="Clima">
            <span class="weather-card-temp">--°C</span>
        </div>
    </div>

    <div class="card-body">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <h3 class="card-title">{{ $location->name }}</h3>
            <div style="background: rgba(245, 158, 11, 0.15); color: var(--accent-amber); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 0.25rem;">
                ★ {{ $location->average_rating }}
            </div>
        </div>

        <p class="card-address">
            📍 {{ Str::limit($location->address, 45) }}
        </p>

        <!-- Métricas Rápidas de Trabalho Remoto -->
        <div class="card-metrics">
            <div class="metric-item">
                <span class="metric-val" style="color: var(--accent-teal)">⚡ {{ $location->wifi_speed_mbps }}M</span>
                <span class="metric-lbl">Wi-Fi Mbps</span>
            </div>
            <div class="metric-item">
                <span class="metric-val">
                    @if($location->noise_level === 'quiet') 🤫 Silencioso
                    @elseif($location->noise_level === 'moderate') 🎧 Moderado
                    @else 🗣️ Animado
                    @endif
                </span>
                <span class="metric-lbl">Ruído</span>
            </div>
            <div class="metric-item">
                <span class="metric-val">
                    @if($location->outlet_density === 'abundant') 🔌 Abundante
                    @elseif($location->outlet_density === 'moderate') 🔌 Média
                    @else ⚠️ Escassa
                    @endif
                </span>
                <span class="metric-lbl">Tomadas</span>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem; align-items: center; margin-top: 1rem;">
            <a href="{{ route('locations.show', $location) }}" class="btn-primary" style="flex: 1; justify-content: center; font-size: 0.9rem;">
                Ver Detalhes & Mapa
            </a>

            @auth
                <form action="{{ route('favorites.toggle', $location) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-outline" style="padding: 0.65rem; border-color: var(--border-glass);">
                        @if(auth()->user()->favorites()->where('location_id', $location->id)->exists())
                            ❤️
                        @else
                            🤍
                        @endif
                    </button>
                </form>
            @endauth
        </div>
    </div>
</div>

<script>
    if (typeof window.initCardWeather === 'undefined') {
        window.initCardWeather = true;
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.weather-live-card').forEach(function(card) {
                const lat = card.getAttribute('data-lat');
                const lng = card.getAttribute('data-lng');
                if (lat && lng) {
                    fetch(`/api/weather?lat=${lat}&lng=${lng}`)
                        .then(res => res.json())
                        .then(data => {
                            const iconImg = card.querySelector('.weather-card-icon');
                            const tempSpan = card.querySelector('.weather-card-temp');
                            if (iconImg && data.icon) {
                                iconImg.src = data.icon.startsWith('http') ? data.icon : `https://openweathermap.org/img/wn/${data.icon}.png`;
                            }
                            if (tempSpan && data.temp !== undefined) {
                                tempSpan.textContent = `${Number(data.temp).toFixed(1)}°C`;
                            }
                        })
                        .catch(() => {
                            const tempSpan = card.querySelector('.weather-card-temp');
                            if (tempSpan) tempSpan.textContent = '24.0°C';
                        });
                }
            });
        });
    }
</script>
