<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.native-shell')
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes taskcheck-login-grid-drift {
                from { background-position: 0 0; }
                to { background-position: 56px 56px; }
            }

            .taskcheck-login-scene {
                background: #eef3f9;
            }

            .taskcheck-login-grid {
                position: absolute;
                inset: 0;
                pointer-events: none;
                background-image:
                    linear-gradient(to right, rgba(15, 23, 42, 0.055) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(15, 23, 42, 0.055) 1px, transparent 1px);
                background-size: 56px 56px;
                animation: taskcheck-login-grid-drift 4s linear infinite;
            }

            .taskcheck-login-vignette {
                position: absolute;
                inset: 0;
                pointer-events: none;
                background:
                    radial-gradient(circle at 50% 45%, rgba(255, 255, 255, 0.82) 0%, rgba(255, 255, 255, 0.45) 28%, rgba(238, 243, 249, 0) 58%),
                    linear-gradient(180deg, rgba(255, 255, 255, 0.42), rgba(238, 243, 249, 0.2));
            }

            @media (prefers-reduced-motion: reduce) {
                .taskcheck-login-grid {
                    animation: none;
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="taskcheck-login-scene relative min-h-screen overflow-hidden" data-page-transition-root>
            <div class="taskcheck-login-grid" aria-hidden="true"></div>
            <div class="taskcheck-login-vignette" aria-hidden="true"></div>
            <!-- Auth Card -->
            <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-10 pt-[max(2.5rem,calc(env(safe-area-inset-top,0px)+2.5rem))] sm:px-6">
                <div class="w-full max-w-md rounded-2xl border border-slate-200/80 bg-white/95 p-7 shadow-xl backdrop-blur">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @include('partials.page-transitions')
        @include('partials.google-translate')
    </body>
</html>
