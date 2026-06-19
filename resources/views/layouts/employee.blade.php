<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $versionFile = public_path('build/manifest.json');
        $fallbackFile = public_path('sw.js');
        $webAppVersion = 'onbekend';
        $versionTimestamp = null;

        if (file_exists($versionFile)) {
            $versionTimestamp = filemtime($versionFile);
        } elseif (file_exists($fallbackFile)) {
            $versionTimestamp = filemtime($fallbackFile);
        }

        if ($versionTimestamp) {
            $webAppVersion = 'v' . date('y.m.d-Hi', $versionTimestamp) . ' (' . date('d-m-Y H:i', $versionTimestamp) . ')';
        }
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>{{ config('app.name', 'Laravel') }} - Employee Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Meta Tags -->
    <meta name="theme-color" content="#3b82f6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="TaskCheck">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="{{ asset('logos/taskcheck-favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('logos/taskcheck-favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logos/taskcheck-favicon.png') }}">
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div class="flex flex-col min-h-screen">
        <!-- Clean Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Clean Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('employee.dashboard') }}" class="flex items-center space-x-3">
                                <img src="{{ asset('logos/taskcheck-favicon.png') }}" alt="TaskCheck logo" class="h-9 w-9 rounded-lg">
                                <div class="leading-tight">
                                    <p class="text-lg font-semibold text-gray-900">TaskCheck</p>
                                    <p class="text-[11px] text-gray-500">Checklist &amp; kwaliteitscontrole</p>
                                </div>
                            </a>
                        </div>

                        <!-- Clean Navigation Links -->
                        <div class="hidden lg:ml-8 lg:flex lg:space-x-1">
                            <a href="{{ route('employee.dashboard') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('employee.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('employee.lists.index') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('employee.lists.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Mijn Taken
                            </a>
                            <a href="{{ route('employee.settings.edit') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('employee.settings.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Instellingen
                            </a>
                        </div>
                    </div>

                    <!-- Clean User Menu -->
                    <div class="hidden lg:flex lg:items-center lg:space-x-4">
                        @if(auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
                            <form method="POST" action="{{ route('dashboard.switch') }}">
                                @csrf
                                <input type="hidden" name="mode" value="admin">
                                <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 border border-blue-200 hover:bg-blue-50 rounded-lg transition-colors">
                                    Admin dashboard
                                </button>
                            </form>
                        @endif
                        @php
                            $unreadNotifications = auth()->user()->unreadNotifications()->orderBy('created_at', 'desc')->take(5)->get();
                            $unreadCount = auth()->user()->unreadNotifications()->count();
                        @endphp
                        
                        <!-- Notifications Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                @if($unreadCount > 0)
                                    <span data-unread-count-badge class="absolute -top-1 -right-1 inline-flex items-center justify-center h-4 w-4 text-xs font-medium text-white bg-red-500 rounded-full">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </button>
                            
                            <!-- Dropdown Panel -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-96 overflow-hidden flex flex-col"
                                 style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-900">Notificaties</h3>
                                    @if($unreadCount > 0)
                                        <span class="text-xs text-gray-500">{{ $unreadCount }} ongelezen</span>
                                    @endif
                                </div>
                                <div class="overflow-y-auto flex-1">
                                    @if($unreadNotifications->count() > 0)
                                        @foreach($unreadNotifications as $notification)
                                            <a href="{{ route('employee.notifications.index') }}" 
                                               class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition-colors">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 mt-1">
                                                        <div class="h-2 w-2 bg-blue-500 rounded-full"></div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $notification->title ?? 'Nieuwe notificatie' }}
                                                        </p>
                                                        <p class="text-xs text-gray-600 truncate mt-1">
                                                            {{ Str::limit($notification->message ?? '', 60) }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    @else
                                        <div class="px-4 py-8 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500">Geen nieuwe notificaties</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                    <a href="{{ route('employee.notifications.index') }}" 
                                       class="block w-full text-center text-sm font-medium text-blue-600 hover:text-blue-700">
                                        Alle notificaties bekijken
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3 text-sm">
                            <div class="h-8 w-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-medium">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">Werknemer</div>
                            </div>
                        </div>
                        <div class="h-6 w-px bg-gray-300"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Uitloggen
                            </button>
                        </form>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="lg:hidden flex items-center">
                        <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-colors">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu hidden lg:hidden bg-white border-t border-gray-200">
                <div class="px-4 py-3 space-y-1">
                    <a href="{{ route('employee.dashboard') }}" 
                       class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('employee.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('employee.lists.index') }}" 
                       class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('employee.lists.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Mijn Taken
                    </a>
                    <a href="{{ route('employee.settings.edit') }}" 
                       class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('employee.settings.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Instellingen
                    </a>
                </div>
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="h-8 w-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-medium">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        @if(auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
                            <form method="POST" action="{{ route('dashboard.switch') }}" class="mb-3">
                                @csrf
                                <input type="hidden" name="mode" value="admin">
                                <button type="submit" class="w-full flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50 rounded-lg transition-colors border border-blue-200">
                                    Naar admin dashboard
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-red-200">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Uitloggen
                            </button>
                        </form>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Clean Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flash-message">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flash-message">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>

        <!-- Minimal Footer -->
        <footer class="bg-white border-t border-gray-200 py-8 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <div class="flex items-center justify-center mb-2">
                        <img src="{{ asset('logos/taskcheck-favicon.png') }}" alt="TaskCheck logo" class="h-9 w-9 rounded-lg mr-3">
                        <div class="text-left leading-tight">
                            <p class="text-lg font-semibold text-gray-900">TaskCheck</p>
                            <p class="text-[11px] text-gray-500">Checklist &amp; kwaliteitscontrole</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">&copy; {{ date('Y') }} TaskCheck. All rights reserved.</p>
                    <p class="mt-1 text-xs text-gray-400">Web app versie: {{ $webAppVersion }}</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Clean JavaScript -->
    <script>
        const realtimeFeedUrl = @json(route('employee.notifications.realtime-feed', [], false));
        const realtimeStorageKey = `taskcheck:last_notification_id:user:${@json((string) auth()->id())}`;
        const realtimeShownNotificationsKey = `taskcheck:shown_notifications:user:${@json((string) auth()->id())}`;
        const vapidKeyUrl = @json(route('push.vapid-public-key', [], false));
        const pushSubscribeUrl = @json(route('push.subscribe', [], false));
        const notificationMarkReadUrlTemplate = @json(route('employee.notifications.mark-read', ['notification' => '__ID__'], false));
        const csrfTokenValue = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }

            return outputArray;
        }

        function updateUnreadBadges(count) {
            const badges = document.querySelectorAll('[data-unread-count-badge]');
            badges.forEach((badge) => {
                if (!count || count <= 0) {
                    badge.style.display = 'none';
                    return;
                }

                badge.style.display = 'inline-flex';
                badge.textContent = count > 9 ? '9+' : String(count);
            });

            syncAppIconBadge(count || 0);
        }

        async function syncAppIconBadge(count) {
            try {
                if (typeof navigator.setAppBadge !== 'function' || typeof navigator.clearAppBadge !== 'function') {
                    return;
                }

                if (count > 0) {
                    await navigator.setAppBadge(count);
                } else {
                    await navigator.clearAppBadge();
                }
            } catch (error) {
                // Not all browsers/OS combinations support app icon badges.
            }
        }

        function normalizeEmployeeNotificationUrl(url) {
            if (!url || typeof url !== 'string') {
                return '/employee/notifications';
            }

            return url.endsWith('/edit') ? url.slice(0, -5) : url;
        }

        async function registerServiceWorkerIfNeeded() {
            if (!('serviceWorker' in navigator)) {
                return null;
            }

            try {
                const registration = await navigator.serviceWorker.register('/sw.js');
                return registration;
            } catch (error) {
                console.warn('Service worker registration failed', error);
                return null;
            }
        }

        async function subscribeForBackgroundPush() {
            try {
                if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                    return;
                }

                const registration = await navigator.serviceWorker.ready;
                const keyResponse = await fetch(`${vapidKeyUrl}?_ts=${Date.now()}`, {
                    cache: 'no-store',
                    credentials: 'include',
                    headers: { 'Accept': 'application/json' },
                });

                if (!keyResponse.ok) {
                    return;
                }

                const keyPayload = await keyResponse.json();
                const vapidPublicKey = keyPayload?.publicKey;
                if (!vapidPublicKey) {
                    return;
                }

                let subscription = await registration.pushManager.getSubscription();
                if (!subscription) {
                    subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
                    });
                }

                await fetch(pushSubscribeUrl, {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                    body: JSON.stringify({
                        ...subscription.toJSON(),
                        contentEncoding: (window.PushManager && Array.isArray(window.PushManager.supportedContentEncodings) && window.PushManager.supportedContentEncodings[0])
                            ? window.PushManager.supportedContentEncodings[0]
                            : 'aes128gcm',
                    }),
                });
            } catch (error) {
                console.warn('Push subscription failed', error);
            }
        }

        async function showRealtimeNotification(notification) {
            if (wasNotificationRecentlyShown(notification.id)) {
                return;
            }
            rememberShownNotification(notification.id);
            showInAppRealtimeToast(notification);
            playRealtimeNotificationSound();

            if (!('Notification' in window)) {
                return;
            }

            if (Notification.permission !== 'granted') {
                return;
            }

            // Voorkom dubbele meldingen op iOS/mobiel: als app open en zichtbaar is, alleen in-app toast.
            if (document.visibilityState === 'visible' && document.hasFocus()) {
                return;
            }

            const registration = await navigator.serviceWorker.ready;
            await registration.showNotification(notification.title || 'Nieuwe melding', {
                body: notification.message || 'Je hebt een nieuwe melding in TaskCheck.',
                icon: '/logos/taskcheck-favicon.png',
                badge: '/logos/taskcheck-favicon.png',
                vibrate: [100, 40, 140],
                tag: `taskcheck-notification-${notification.id}`,
                data: {
                    url: normalizeEmployeeNotificationUrl(notification.url),
                },
            });
        }

        function getShownNotificationIds() {
            try {
                const raw = sessionStorage.getItem(realtimeShownNotificationsKey);
                const parsed = raw ? JSON.parse(raw) : [];
                return Array.isArray(parsed) ? parsed : [];
            } catch (error) {
                return [];
            }
        }

        function rememberShownNotification(id) {
            if (!id) return;
            const current = getShownNotificationIds();
            if (!current.includes(id)) {
                current.push(id);
            }
            const trimmed = current.slice(-100);
            sessionStorage.setItem(realtimeShownNotificationsKey, JSON.stringify(trimmed));
        }

        function wasNotificationRecentlyShown(id) {
            if (!id) return false;
            return getShownNotificationIds().includes(id);
        }

        function playRealtimeNotificationSound() {
            try {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                if (!AudioContextClass) {
                    return;
                }

                const context = new AudioContextClass();
                const oscillator = context.createOscillator();
                const gainNode = context.createGain();

                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, context.currentTime);
                oscillator.frequency.exponentialRampToValueAtTime(660, context.currentTime + 0.14);

                gainNode.gain.setValueAtTime(0.0001, context.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.08, context.currentTime + 0.02);
                gainNode.gain.exponentialRampToValueAtTime(0.0001, context.currentTime + 0.2);

                oscillator.connect(gainNode);
                gainNode.connect(context.destination);
                oscillator.start(context.currentTime);
                oscillator.stop(context.currentTime + 0.22);

                oscillator.onended = () => {
                    context.close().catch(() => {});
                };
            } catch (error) {
                // Browsers kunnen geluid blokkeren zonder user interaction.
            }
        }

        function showInAppRealtimeToast(notification) {
            if (!notification?.id) return;

            let container = document.querySelector('[data-realtime-toast-container]');
            if (!container) {
                container = document.createElement('div');
                container.setAttribute('data-realtime-toast-container', '1');
                container.className = 'fixed bottom-[calc(1rem+env(safe-area-inset-bottom))] right-3 left-3 sm:left-auto sm:right-4 z-[9999] sm:max-w-sm space-y-3';
                document.body.appendChild(container);
            }

            const existing = container.querySelector(`[data-realtime-toast-id="${notification.id}"]`);
            if (existing) {
                return;
            }

            const toast = document.createElement('div');
            toast.setAttribute('data-realtime-toast', '1');
            toast.setAttribute('data-realtime-toast-id', String(notification.id));
            toast.className = 'rounded-2xl border border-blue-200 bg-white px-4 py-3 shadow-2xl ring-1 ring-blue-100';
            const targetUrl = normalizeEmployeeNotificationUrl(notification.url);
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-blue-600 text-sm">🔔</span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-slate-900">${notification.title || 'Nieuwe melding'}</p>
                        <p class="mt-1 text-xs text-slate-600">${notification.message || 'Je hebt een nieuwe melding in TaskCheck.'}</p>
                        <div class="mt-2 flex items-center gap-2">
                            <a href="${targetUrl}" class="inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-900">Open melding</a>
                            <button type="button" data-notification-read="${notification.id}" class="inline-flex items-center rounded-lg border border-slate-200 px-2 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">Afgevinkt</button>
                        </div>
                    </div>
                </div>
            `;

            const markReadButton = toast.querySelector(`[data-notification-read="${notification.id}"]`);
            markReadButton?.addEventListener('click', async () => {
                markReadButton.setAttribute('disabled', 'disabled');
                markReadButton.textContent = 'Bezig...';

                const url = notificationMarkReadUrlTemplate.replace('__ID__', String(notification.id));

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfTokenValue,
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Kon melding niet afvinken.');
                    }

                    toast.remove();
                    const currentUnread = Number(document.querySelector('[data-unread-count-badge]')?.textContent || 0);
                    const nextUnread = Number.isFinite(currentUnread) ? Math.max(currentUnread - 1, 0) : 0;
                    updateUnreadBadges(nextUnread);
                } catch (error) {
                    markReadButton.removeAttribute('disabled');
                    markReadButton.textContent = 'Afgevinkt';
                }
            });

            container.appendChild(toast);
        }

        async function startRealtimeNotificationPolling() {
            let lastNotificationId = Number(localStorage.getItem(realtimeStorageKey) || 0);
            let hasExistingCursor = Number.isFinite(lastNotificationId) && lastNotificationId > 0;

            const poll = async () => {
                try {
                    const response = await fetch(`${realtimeFeedUrl}?after_id=${lastNotificationId}&_ts=${Date.now()}`, {
                        cache: 'no-store',
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        return;
                    }

                    const payload = await response.json();
                    if (!payload || payload.success !== true) {
                        return;
                    }

                    if (typeof payload.latest_user_notification_id === 'number' && payload.latest_user_notification_id < lastNotificationId) {
                        lastNotificationId = payload.latest_user_notification_id;
                        localStorage.setItem(realtimeStorageKey, String(lastNotificationId));
                        hasExistingCursor = lastNotificationId > 0;
                    }

                    updateUnreadBadges(payload.unread_count || 0);

                    const notifications = Array.isArray(payload.notifications) ? payload.notifications : [];
                    const unreadNotifications = Array.isArray(payload.unread_notifications) ? payload.unread_notifications : [];
                    if (!hasExistingCursor && typeof payload.latest_user_notification_id === 'number') {
                        for (const unreadNotification of unreadNotifications) {
                            showInAppRealtimeToast(unreadNotification);
                        }
                        lastNotificationId = payload.latest_user_notification_id;
                        localStorage.setItem(realtimeStorageKey, String(lastNotificationId));
                        hasExistingCursor = true;
                        return;
                    }

                    for (const notification of notifications) {
                        await showRealtimeNotification(notification);
                    }

                    if (typeof payload.after_id === 'number' && payload.after_id > lastNotificationId) {
                        lastNotificationId = payload.after_id;
                        localStorage.setItem(realtimeStorageKey, String(lastNotificationId));
                        hasExistingCursor = true;
                    }
                } catch (error) {
                    console.warn('Realtime notification polling failed', error);
                }
            };

            await poll();
            setInterval(poll, 8000);
        }

        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            registerServiceWorkerIfNeeded();

            if ('Notification' in window && Notification.permission === 'default') {
                document.addEventListener('click', () => {
                    Notification.requestPermission()
                        .then((permission) => {
                            if (permission === 'granted') {
                                subscribeForBackgroundPush();
                            }
                        })
                        .catch(() => {});
                }, { once: true });
            } else if ('Notification' in window && Notification.permission === 'granted') {
                subscribeForBackgroundPush();
            }

            startRealtimeNotificationPolling();

            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                });
            }

            // Auto-hide only real flash messages (not inline rejection blocks).
            setTimeout(function() {
                const flashMessages = document.querySelectorAll('.flash-message');
                if (flashMessages.length > 0) {
                    flashMessages.forEach(function(message) {
                        if (message && message.style) {
                            message.style.transition = 'opacity 0.3s ease-out';
                            message.style.opacity = '0';
                            setTimeout(function() {
                                if (message && message.parentNode) {
                                    message.remove();
                                }
                            }, 300);
                        }
                    });
                }
            }, 5000);
        });
    </script>
    @stack('scripts')
    @include('partials.google-translate')
</body>
</html>
