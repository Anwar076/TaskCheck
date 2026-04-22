<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TaskCheck') }}</title>
    
    <!-- Local compiled Tailwind CSS -->
    @vite('resources/css/app.css')
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="Professional task management and team collaboration platform">
    <meta name="theme-color" content="#2563eb">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="TaskCheck">
    <meta name="msapplication-TileColor" content="#2563eb">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <link rel="shortcut icon" href="{{ asset('logos/taskcheck-favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" type="image/svg+xml" href="{{ asset('logos/taskcheck-favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('logos/taskcheck-favicon.png') }}">

    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="{{ asset('logos/taskcheck-favicon.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('logos/taskcheck-favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logos/taskcheck-favicon.png') }}">
    
    <!-- Install App Buttons -->
    <style>
        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(139,92,246,0.35);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    @yield('content')
    
    <!-- PWA Detection Script -->
    <script>
        // Check if app is installed as PWA and redirect to login
        function checkPwaAndRedirect() {
            // Check if running in standalone mode (installed PWA)
            if (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) {
                console.log('PWA detected, redirecting to login');
                window.location.href = '/login?source=pwa';
                return;
            }
            
            // Check for iOS PWA
            if (window.navigator.standalone === true) {
                console.log('iOS PWA detected, redirecting to login');
                window.location.href = '/login?source=pwa';
                return;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkPwaAndRedirect();
        });
    </script>
</body>
</html>



