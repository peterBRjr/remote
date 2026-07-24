<x-layout title="Meus Favoritos — RemoteSpot">
    <div style="margin-bottom: 3rem;">
        <h1 style="font-family: var(--font-heading); font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem;">
            Meus Spots <span style="color: var(--accent-rose)">Favoritos</span> ❤️
        </h1>
        <p style="color: var(--text-muted); font-size: 1.1rem;">
            Sua lista pessoal de cafés e coworkings salvos para trabalhar quando precisar.
        </p>
    </div>

    @if($favorites->count() > 0)
        <div class="location-grid">
            @foreach($favorites as $location)
                <x-location-card :location="$location" />
            @endforeach
        </div>

        <div style="margin-bottom: 4rem;">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="glass-card" style="padding: 4rem; text-align: center; margin-bottom: 4rem;">
            <h3 style="font-size: 1.5rem; color: var(--text-main); margin-bottom: 1rem;">Sua lista de favoritos está vazia</h3>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Navegue pela lista de locais e clique no coração ❤️ para salvar seus lugares preferidos.</p>
            <a href="{{ route('locations.index') }}" class="btn-primary">Descobrir Spots</a>
        </div>
    @endif
</x-layout>
