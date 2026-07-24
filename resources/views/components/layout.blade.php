<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'RemoteSpot - Os Melhores Cafés e Coworkings para Trabalhar' }}</title>
    <meta name="description" content="Descubra, avalie e meça a qualidade dos melhores lugares para trabalhar remotamente com wi-fi rápido, tomadas abundantes e ambiente adequado.">
    
    <!-- CSS Nativo Personalizado -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

    <!-- Header Navigation Component -->
    <x-navbar />

    <!-- Main Flash Messages -->
    <main class="container" style="padding-top: 2rem;">
        @if (session('success'))
            <div class="alert alert-success">
                <span>⚡ {{ session('success') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info">
                <span>ℹ️ {{ session('info') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert" style="background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(244, 63, 94, 0.3); color: #fb7185;">
                <span>⚠️ {{ session('error') }}</span>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>RemoteSpot &copy; {{ date('Y') }} — Plataforma Nativa Laravel desenvolvida para Desenvolvedores & Nomades Digitais.</p>
        </div>
    </footer>

    <!-- Dynamic Script Stacks for Google Maps (Requirement 4) -->
    @stack('scripts')
</body>
</html>
