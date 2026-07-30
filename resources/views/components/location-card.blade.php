@props(['location'])

<div class="glass-card" style="display: flex; flex-direction: column; height: 100%; background: var(--bg-card); border: 1px solid var(--border-glass); border-radius: var(--radius-lg); overflow: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
    <!-- Imagem e Badges do Card -->
    <div class="card-img-wrapper" style="position: relative; height: 210px; width: 100%; overflow: hidden;">
        <img src="{{ $location->image_url ?? 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80' }}" 
             alt="{{ $location->name }}" class="card-img" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">

        <span class="card-badge" style="position: absolute; top: 1rem; left: 1rem; background: rgba(9, 13, 22, 0.85); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.1); padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.8rem; font-weight: 700; color: var(--accent-teal);">
            @if($location->category === 'cafe') ☕ CAFÉ
            @elseif($location->category === 'coworking') 🏢 COWORKING
            @elseif($location->category === 'library') 📚 BIBLIOTECA
            @else 🏨 LOBBY
            @endif
        </span>

        <div class="weather-badge weather-live-card" 
             data-lat="{{ $location->latitude }}" 
             data-lng="{{ $location->longitude }}"
             style="position: absolute; top: 1rem; right: 1rem; background: rgba(9, 13, 22, 0.85); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.1); padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.8rem; font-weight: 700; color: #fbbf24; display: flex; align-items: center; gap: 0.35rem;"
             title="Clima ao vivo nas coordenadas ({{ round($location->latitude, 3) }}, {{ round($location->longitude, 3) }})">
            <img class="weather-card-icon" src="https://openweathermap.org/img/wn/01d.png" style="width: 20px; height: 20px; margin: -5px -2px;" alt="Clima">
            <span class="weather-card-temp">--°C</span>
        </div>
    </div>

    <!-- Conteúdo do Card -->
    <div class="card-body" style="padding: 1.5rem; display: flex; flex-direction: column; flex: 1;">
        <!-- Header do Card: Título + Rating em altura padronizada -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; min-height: 2.8rem; margin-bottom: 0.5rem;">
            <h3 class="card-title" style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: #fff; line-height: 1.35; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $location->name }}">
                {{ $location->name }}
            </h3>
            <div style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); color: var(--accent-amber); padding: 0.3rem 0.6rem; border-radius: var(--radius-sm); font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; gap: 0.25rem; flex-shrink: 0;">
                ★ {{ $location->average_rating }}
            </div>
        </div>

        <!-- Endereço padronizado em 1 linha -->
        <p class="card-address" style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.35rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            <span style="flex-shrink: 0;">📍</span>
            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($location->address, 45) }}</span>
        </p>

        <!-- Métricas Rápidas de Trabalho Remoto (Alinhamento Horizontal Perfeito) -->
        <div class="card-metrics" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.35rem; background: rgba(9, 13, 22, 0.45); border: 1px solid rgba(255, 255, 255, 0.05); padding: 0.75rem 0.35rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; align-items: stretch;">
            <div class="metric-item" style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; text-align: center; gap: 0.25rem; min-width: 0;">
                <span class="metric-val" style="color: var(--accent-teal); font-weight: 700; font-size: 0.82rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%; display: block; text-align: center;">⚡ {{ $location->wifi_speed_mbps }}M</span>
                <span class="metric-lbl" style="font-size: 0.65rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap; display: block; text-align: center;">Wi-Fi Mbps</span>
            </div>
            <div class="metric-item" style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; text-align: center; gap: 0.25rem; min-width: 0;">
                <span class="metric-val" style="color: var(--text-main); font-weight: 700; font-size: 0.82rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%; display: block; text-align: center;">@if($location->noise_level === 'quiet')🤫 Silencioso @elseif($location->noise_level === 'moderate')🎧 Moderado @else🗣️ Animado @endif</span>
                <span class="metric-lbl" style="font-size: 0.65rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap; display: block; text-align: center;">Ruído</span>
            </div>
            <div class="metric-item" style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; text-align: center; gap: 0.25rem; min-width: 0;">
                <span class="metric-val" style="color: var(--text-main); font-weight: 700; font-size: 0.82rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%; display: block; text-align: center;">@if($location->outlet_density === 'abundant')🔌 Abundante @elseif($location->outlet_density === 'moderate')🔌 Média @else⚠️ Escassa @endif</span>
                <span class="metric-lbl" style="font-size: 0.65rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap; display: block; text-align: center;">Tomadas</span>
            </div>
        </div>

        <!-- Botões de Ação (Maiores, Destacados e Perfeitamente Alinhados na Base) -->
        <div style="display: flex; gap: 0.75rem; align-items: center; margin-top: auto; padding-top: 0.25rem;">
            <a href="{{ route('locations.show', $location) }}" class="btn-primary" 
               style="flex: 1; height: 48px; justify-content: center; align-items: center; display: inline-flex; font-size: 0.95rem; font-weight: 700; border-radius: 12px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35); text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.15); transition: all 0.2s ease;">
                Ver Detalhes & Mapa
            </a>

            @auth
                <form action="{{ route('favorites.toggle', $location) }}" method="POST" style="margin: 0; flex-shrink: 0;">
                    @csrf
                    @php
                        $isFav = auth()->user()->favorites()->where('location_id', $location->id)->exists();
                    @endphp
                    <button type="submit" class="btn-outline" 
                            style="width: 48px; height: 48px; padding: 0; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; border-radius: 12px; border: 1px solid {{ $isFav ? 'rgba(244, 63, 94, 0.4)' : 'rgba(255, 255, 255, 0.15)' }}; background: {{ $isFav ? 'rgba(244, 63, 94, 0.18)' : 'rgba(255, 255, 255, 0.08)' }}; cursor: pointer; transition: all 0.2s ease;"
                            title="{{ $isFav ? 'Remover dos Favoritos' : 'Adicionar aos Favoritos' }}">
                        {{ $isFav ? '❤️' : '🤍' }}
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
