<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TaskCheck') }}</title>

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

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- PWA Service Worker Registration -->
    <script>
        let deferredPrompt;

        function showInstallInstructions() {
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
            const isAndroid = /Android/.test(navigator.userAgent);
            const isInAppBrowser = /(FBAN|FBAV|Instagram|Line|Twitter|wv)/i.test(navigator.userAgent);

            let instructions = '';

            if (isInAppBrowser) {
                instructions = 'Open TaskCheck eerst in Chrome of Safari. In-app browsers maken vaak alleen een snelkoppeling.';
            } else if (isIOS) {
                instructions = 'Open in Safari → Share (📤) → Zet op beginscherm. Dan krijg je de echte web-app modus.';
            } else if (isAndroid) {
                instructions = 'Open in Chrome → menu (⋮) → App installeren. Dan krijg je de echte web-app in plaats van een losse snelkoppeling.';
            } else {
                instructions = 'Gebruik de install-optie van je browser (adresbalk of menu).';
            }

            alert(`TaskCheck installeren\n\n${instructions}`);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const installButton = document.getElementById('install-button');
            const installButtonMobile = document.getElementById('install-button-mobile');

            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then((registration) => {
                            registration.addEventListener('updatefound', () => {
                                const newWorker = registration.installing;
                                if (!newWorker) {
                                    return;
                                }

                                newWorker.addEventListener('statechange', () => {
                                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                        newWorker.postMessage({ type: 'SKIP_WAITING' });
                                        window.location.reload();
                                    }
                                });
                            });
                        })
                        .catch((registrationError) => {
                            console.log('SW registration failed: ', registrationError);
                        });
                });
            }

            const handleInstall = () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then(() => {
                        deferredPrompt = null;
                        if (installButton) {
                            installButton.style.display = 'none';
                        }
                        if (installButtonMobile) {
                            installButtonMobile.style.display = 'none';
                        }
                    });
                } else {
                    showInstallInstructions();
                }
            };

            window.addEventListener('beforeinstallprompt', (event) => {
                event.preventDefault();
                deferredPrompt = event;

                if (installButton) {
                    installButton.style.display = 'block';
                }
                if (installButtonMobile) {
                    installButtonMobile.style.display = 'block';
                }
            });

            if (installButton) {
                installButton.addEventListener('click', handleInstall);
            }
            if (installButtonMobile) {
                installButtonMobile.addEventListener('click', handleInstall);
            }

            window.addEventListener('appinstalled', () => {
                if (installButton) {
                    installButton.style.display = 'none';
                }
                if (installButtonMobile) {
                    installButtonMobile.style.display = 'none';
                }
            });
        });
    </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
