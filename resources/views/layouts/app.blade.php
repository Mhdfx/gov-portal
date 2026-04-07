<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', 'I.M System - Plateforme d\'Entrepreneuriat au Maroc')</title>
    <meta name="description" content="@yield('meta_description', 'Plateforme numérique pour la mise en relation des porteurs de projets, auto-entrepreneurs, investisseurs, entreprises et institutions publiques au Maroc')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    {{-- PWA Manifest --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="I.M System">

    {{-- Vite will automatically use built assets in production or dev server in development --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white">
    <main id="main-content">
        @yield('content')
    </main>

    @stack('scripts')
    
    {{-- Dark Mode Script --}}
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    
    {{-- Accessibility Script --}}
    <script src="{{ asset('js/accessibility.js') }}"></script>
    
    {{-- Real-time Script --}}
    <script src="{{ asset('js/realtime.js') }}"></script>
    
    {{-- Service Worker Registration --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('Service Worker registered:', registration);
                    })
                    .catch((error) => {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }
    </script>
</body>
</html>