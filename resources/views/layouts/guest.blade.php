<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.native-shell')
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#eef3f9">

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
                min-height: 100vh;
                min-height: 100dvh;
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

            .taskcheck-login-shell {
                position: relative;
                z-index: 10;
                display: flex;
                min-height: 100vh;
                min-height: 100dvh;
                align-items: center;
                justify-content: center;
                padding:
                    max(2.5rem, calc(env(safe-area-inset-top, 0px) + 2.5rem))
                    max(1rem, env(safe-area-inset-right, 0px))
                    max(2.5rem, calc(env(safe-area-inset-bottom, 0px) + 2.5rem))
                    max(1rem, env(safe-area-inset-left, 0px));
            }

            .taskcheck-login-card {
                width: 100%;
                max-width: 28rem;
                border-radius: 1rem;
                border: 1px solid rgba(226, 232, 240, 0.8);
                background: rgba(255, 255, 255, 0.95);
                padding: 1.75rem;
                box-shadow: 0 20px 40px -24px rgba(15, 23, 42, 0.35);
                backdrop-filter: blur(8px);
            }

            @media (max-width: 640px) {
                .taskcheck-login-shell {
                    align-items: flex-start;
                    padding-top: max(1.5rem, calc(env(safe-area-inset-top, 0px) + 1.5rem));
                    padding-bottom: max(1.5rem, calc(env(safe-area-inset-bottom, 0px) + 1.5rem));
                }

                .taskcheck-login-card {
                    padding: 1.25rem 1.15rem;
                    border-radius: 1.15rem;
                    box-shadow: 0 12px 28px -20px rgba(15, 23, 42, 0.28);
                }
            }

            html.is-native-app .taskcheck-login-shell {
                align-items: flex-start;
                padding-top: calc(var(--safe-top) + 1.25rem);
                padding-right: max(1.1rem, var(--safe-right));
                padding-bottom: calc(var(--safe-bottom) + 1.25rem);
                padding-left: max(1.1rem, var(--safe-left));
            }

            html.is-native-app .taskcheck-login-card {
                width: 100%;
                max-width: 26rem;
                margin-inline: auto;
                border: 0;
                background: transparent;
                box-shadow: none;
                backdrop-filter: none;
                padding: 0.25rem 0 0;
                border-radius: 0;
            }

            html.is-native-app .taskcheck-login-grid {
                opacity: 0.55;
                animation: none;
            }

            html.is-native-app [data-translate-root] {
                display: none !important;
            }

            .taskcheck-login-logo {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            .taskcheck-login-logo-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                max-width: 100%;
            }

            .taskcheck-login-logo-img {
                display: block;
                /* Logo PNG has extra transparent space on the right; shift so the mark sits optically centered. */
                transform: translateX(11.5%);
            }

            html.is-native-app .taskcheck-login-logo {
                margin-bottom: 2rem;
            }

            html.is-native-app .taskcheck-login-logo-img {
                height: 3.75rem;
                max-width: min(100%, 19rem);
            }

            @media (prefers-reduced-motion: reduce) {
                .taskcheck-login-grid {
                    animation: none;
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="taskcheck-login-scene relative overflow-hidden" data-page-transition-root>
            <div class="taskcheck-login-grid" aria-hidden="true"></div>
            <div class="taskcheck-login-vignette" aria-hidden="true"></div>
            <div class="taskcheck-login-shell">
                <div class="taskcheck-login-card">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @include('partials.page-transitions')
        @include('partials.google-translate')
    </body>
</html>
