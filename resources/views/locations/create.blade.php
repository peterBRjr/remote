<x-layout title="Cadastrar Novo Spot — RemoteWorkplace">
    <div style="max-width: 760px; margin: 2rem auto;">
        <div class="glass-card" style="padding: 2.5rem;">
            <h1 style="font-family: var(--font-heading); font-size: 2rem; color: #fff; margin-bottom: 0.5rem;">
                Cadastrar Novo Spot de Trabalho
            </h1>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">
                Compartilhe um café ou coworking excelente com a comunidade de desenvolvedores. O endereço será geocodificado automaticamente.
            </p>

            @if ($errors->any())
                <div class="alert" style="background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(244, 63, 94, 0.3); color: #fb7185; margin-bottom: 1.5rem;">
                    <ul style="margin-left: 1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('locations.store') }}" method="POST">
                @csrf

                <div class="input-group" style="margin-bottom: 1.25rem;">
                    <label class="input-label">Nome do Estabelecimento *</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Coffee Lab, WeWork Paulista..." class="form-control" required>
                </div>

                <div class="input-group" style="margin-bottom: 1.25rem;">
                    <label class="input-label">Endereço Completo *</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Ex: Rua Fradique Coutinho, 1340 - Pinheiros, São Paulo - SP" class="form-control" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div class="input-group">
                        <label class="input-label">Categoria *</label>
                        <select name="category" class="form-control" required>
                            <option value="cafe" {{ old('category') === 'cafe' ? 'selected' : '' }}>☕ Café</option>
                            <option value="coworking" {{ old('category') === 'coworking' ? 'selected' : '' }}>🏢 Coworking</option>
                            <option value="library" {{ old('category') === 'library' ? 'selected' : '' }}>📚 Biblioteca</option>
                            <option value="hotel_lobby" {{ old('category') === 'hotel_lobby' ? 'selected' : '' }}>🏨 Lobby de Hotel</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Velocidade Estimada Wi-Fi (Mbps)</label>
                        <input type="number" name="wifi_speed_mbps" value="{{ old('wifi_speed_mbps', 100) }}" min="1" max="1000" class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div class="input-group">
                        <label class="input-label">Nível de Ruído *</label>
                        <select name="noise_level" class="form-control" required>
                            <option value="quiet" {{ old('noise_level') === 'quiet' ? 'selected' : '' }}>🤫 Silencioso (Foco Total)</option>
                            <option value="moderate" {{ old('noise_level') === 'moderate' ? 'selected' : '' }}>🎧 Moderado (Música Ambiente)</option>
                            <option value="lively" {{ old('noise_level') === 'lively' ? 'selected' : '' }}>🗣️ Animado (Networking)</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Densidade de Tomadas *</label>
                        <select name="outlet_density" class="form-control" required>
                            <option value="abundant" {{ old('outlet_density') === 'abundant' ? 'selected' : '' }}>🔌 Abundante (Em cada mesa)</option>
                            <option value="moderate" {{ old('outlet_density') === 'moderate' ? 'selected' : '' }}>🔌 Moderada (Em algumas mesas)</option>
                            <option value="scarce" {{ old('outlet_density') === 'scarce' ? 'selected' : '' }}>⚠️ Escassa (Poucas tomadas)</option>
                        </select>
                    </div>
                </div>

                <div class="input-group" style="margin-bottom: 1.25rem;">
                    <label class="input-label">URL da Imagem Principal (Opcional)</label>
                    <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." class="form-control">
                    <span style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.25rem;">Se não informar, nossa fila em background buscará uma foto automaticamente via Foursquare API.</span>
                </div>

                <div class="input-group" style="margin-bottom: 2rem;">
                    <label class="input-label">Descrição e Dicas do Local</label>
                    <textarea name="description" rows="4" class="form-control" placeholder="Conte detalhes sobre o espaço, qualidade do café, iluminação natural, etc...">{{ old('description') }}</textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('locations.index') }}" class="btn-outline">Cancelar</a>
                    <button type="submit" class="btn-primary">Cadastrar Spot</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
