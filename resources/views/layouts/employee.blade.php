<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="taskcheck-auth" content="1">
    @include('partials.native-shell')
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
<body class="employee-portal font-sans antialiased bg-gray-50 min-h-screen">
    @include('partials.impersonation-banner')
    <div class="flex flex-col min-h-screen">
        @php
            $unreadNotifications = auth()->user()->unreadNotifications()->orderBy('created_at', 'desc')->take(5)->get();
            $unreadCount = auth()->user()->unreadNotifications()->count();
        @endphp
        <!-- Desktop top nav (xl+). On mobile/tablet the icon bar at the bottom replaces this. -->
        <nav class="sticky top-0 z-50 @hasSection('nav-extra') bg-white border-b border-gray-200 @else xl:bg-white xl:border-b xl:border-gray-200 @endif">
            <div class="hidden xl:block app-safe-header">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center min-h-14 gap-2 sm:h-16">
                    <div class="flex min-w-0 items-center">
                        <!-- Clean Logo -->
                        <div class="flex min-w-0 items-center">
                            <a href="{{ ($subscriptionLocked ?? false) ? route('employee.settings.edit') : route('employee.dashboard') }}" class="flex min-w-0 items-center gap-2 sm:gap-3">
                                <img src="{{ asset('logos/taskcheck-favicon.png') }}" alt="TaskCheck logo" class="h-8 w-8 shrink-0 rounded-lg sm:h-9 sm:w-9">
                                <div class="min-w-0 leading-tight">
                                    <p class="truncate text-base font-semibold text-gray-900 sm:text-lg">TaskCheck</p>
                                    <p class="hidden truncate text-[11px] text-gray-500 sm:block">Checklist &amp; kwaliteitscontrole</p>
                                </div>
                            </a>
                        </div>

                        <!-- Clean Navigation Links -->
                        @unless($subscriptionLocked ?? false)
                        <div class="hidden xl:ml-8 xl:flex xl:space-x-1">
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
                        </div>
                        @endunless
                    </div>

                    <!-- Clean User Menu -->
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <div class="flex items-center space-x-4">
                        @if(empty($subscriptionLocked) && auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
                            <div class="flex items-center rounded-xl border border-slate-200 bg-slate-50 p-1">
                                <form method="POST" action="{{ route('dashboard.switch') }}">
                                    @csrf
                                    <input type="hidden" name="mode" value="admin">
                                    <button type="submit" class="inline-flex h-8 items-center gap-1.5 rounded-lg px-3 text-xs font-semibold text-slate-600 hover:bg-white hover:text-blue-700 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 8.25V6zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 018.25 20.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                                        </svg>
                                        Adminweergave
                                    </button>
                                </form>
                                <span class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-blue-600 px-3 text-xs font-semibold text-white shadow-sm">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Medewerkersweergave
                                </span>
                            </div>
                        @endif
                        @include('partials.google-translate', ['variant' => 'topbar'])
                        </div>

                        <!-- Notifications Dropdown -->
                        <div class="relative" x-data="{ open: false }" data-employee-notification-root>
                            <button
                                type="button"
                                id="employee-notification-bell"
                                @click="open = !open"
                                class="relative p-2.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors"
                                title="Notificaties"
                                aria-label="Notificaties openen"
                                :aria-expanded="open.toString()"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                </svg>
                                <span data-unread-count-badge class="absolute -top-0.5 -right-0.5 inline-flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold leading-none text-white ring-2 ring-white {{ $unreadCount > 0 ? '' : 'hidden' }}">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            </button>

                            <div
                                x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-[22rem] max-w-[90vw] rounded-2xl border border-slate-200 bg-white shadow-xl z-50 overflow-hidden"
                                style="display: none;"
                            >
                                <div class="border-b border-slate-100 px-4 py-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="text-sm font-semibold text-slate-900">Notificaties</h3>
                                        @if($unreadCount > 0)
                                            <span class="text-[11px] font-medium text-slate-500">{{ $unreadCount }} ongelezen</span>
                                        @endif
                                    </div>
                                    <div class="mt-2 flex items-center gap-2">
                                        <a
                                            href="{{ route('employee.notifications.index') }}"
                                            class="inline-flex items-center rounded-md border border-slate-200 px-2.5 py-1 text-[11px] font-medium text-slate-600 hover:bg-slate-50 transition-colors"
                                        >
                                            Alle notificaties
                                        </a>
                                        <button
                                            type="button"
                                            data-employee-mark-all-read
                                            class="inline-flex items-center rounded-md border border-slate-200 px-2.5 py-1 text-[11px] font-medium text-slate-600 hover:bg-slate-50 transition-colors {{ $unreadCount > 0 ? '' : 'hidden' }}"
                                        >
                                            Markeer alles gelezen
                                        </button>
                                    </div>
                                </div>

                                <div class="max-h-80 overflow-y-auto" data-employee-notification-list>
                                    @if($unreadNotifications->count() > 0)
                                        @foreach($unreadNotifications as $notification)
                                            @php
                                                $notificationData = is_array($notification->data) ? $notification->data : [];
                                                $notificationUrl = $notificationData['url'] ?? null;
                                                if (! $notificationUrl && ! empty($notificationData['submission_id'])) {
                                                    $notificationUrl = url('/employee/submissions/'.$notificationData['submission_id']);
                                                }
                                                $notificationUrl = $notificationUrl ?: route('employee.notifications.index');
                                                if (is_string($notificationUrl) && str_ends_with($notificationUrl, '/edit')) {
                                                    $notificationUrl = substr($notificationUrl, 0, -5);
                                                }
                                            @endphp
                                            <div class="border-b border-slate-100 px-4 py-3 last:border-b-0" data-employee-notification-item="{{ $notification->id }}">
                                                <p class="text-sm font-semibold text-slate-900">{{ $notification->title ?? 'Nieuwe melding' }}</p>
                                                @if(!empty($notification->message))
                                                    <p class="mt-1 text-xs text-slate-600">{{ Str::limit($notification->message, 120) }}</p>
                                                @endif
                                                <p class="mt-1 text-[11px] text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                                                <div class="mt-2 flex items-center gap-2">
                                                    <a
                                                        href="{{ $notificationUrl }}"
                                                        class="inline-flex items-center rounded-md border border-blue-200 px-2.5 py-1 text-[11px] font-medium text-blue-700 hover:bg-blue-50 transition-colors"
                                                    >
                                                        Openen
                                                    </a>
                                                    <button
                                                        type="button"
                                                        data-employee-mark-read-notification
                                                        data-notification-id="{{ $notification->id }}"
                                                        class="inline-flex items-center rounded-md border border-emerald-200 px-2.5 py-1 text-[11px] font-medium text-emerald-700 hover:bg-emerald-50 transition-colors"
                                                    >
                                                        Markeer gelezen
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="px-4 py-8 text-center">
                                            <svg class="mx-auto h-10 w-10 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                            </svg>
                                            <p class="mt-3 text-sm text-slate-500">Nog geen nieuwe meldingen.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative" x-data="{ open: false }">
                            <button
                                type="button"
                                @click="open = !open"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-slate-700 hover:border-slate-300 hover:bg-slate-50 transition-colors"
                                aria-label="Profielmenu openen"
                                :aria-expanded="open.toString()"
                            >
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-xs font-semibold text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </span>
                                <span class="hidden xl:block text-sm font-medium max-w-[10rem] truncate">{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                                </svg>
                            </button>

                            <div
                                x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-52 rounded-xl border border-slate-200 bg-white shadow-xl z-50 overflow-hidden"
                                style="display: none;"
                            >
                                <a href="{{ route('employee.settings.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    Instellingen
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        Uitloggen
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            </div>
                @hasSection('nav-extra')
                    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                        <div class="py-3 flex items-center justify-center xl:border-t xl:border-gray-100">
                            @yield('nav-extra')
                        </div>
                    </div>
                @endif

        </nav>

        @include('partials.mobile-bottom-nav-employee')

        <!-- Page Content -->
        <main class="flex-1" data-page-transition-root>
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

            @include('partials.ios-push-prompt')

            @yield('content')
        </main>

        <!-- Minimal Footer -->
        <footer class="bg-white border-t border-gray-200 py-8 mt-16 pb-[calc(2rem+4.5rem+max(48px,var(--safe-bottom)))] xl:pb-8">
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
        const notificationMarkAllReadUrl = @json(route('employee.notifications.mark-all-read', [], false));
        const csrfTokenValue = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const realtimePageLoadedAt = Date.now();

        function employeeNotificationEmptyStateHtml() {
            return `
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    <p class="mt-3 text-sm text-slate-500">Nog geen nieuwe meldingen.</p>
                </div>
            `;
        }

        function updateUnreadBadges(count) {
            const badges = document.querySelectorAll('[data-unread-count-badge]');
            badges.forEach((badge) => {
                badge.classList.toggle('hidden', !count || count <= 0);
                if (count && count > 0) {
                    badge.textContent = count > 9 ? '9+' : String(count);
                }
            });

            document.querySelector('[data-employee-mark-all-read]')?.classList.toggle('hidden', !count || count <= 0);

            syncAppIconBadge(count || 0);
        }

        async function markEmployeeNotificationAsRead(notificationId) {
            if (!notificationId) {
                return;
            }

            const url = notificationMarkReadUrlTemplate.replace('__ID__', String(notificationId));
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfTokenValue,
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                return;
            }

            document.querySelector(`[data-employee-notification-item="${notificationId}"]`)?.remove();

            const list = document.querySelector('[data-employee-notification-list]');
            if (list && !list.querySelector('[data-employee-notification-item]')) {
                list.innerHTML = employeeNotificationEmptyStateHtml();
            }

            const currentUnread = Number(document.querySelector('[data-unread-count-badge]')?.textContent || 0);
            updateUnreadBadges(Math.max(0, currentUnread - 1));
        }

        async function markAllEmployeeNotificationsAsRead() {
            const response = await fetch(notificationMarkAllReadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfTokenValue,
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                return;
            }

            const list = document.querySelector('[data-employee-notification-list]');
            if (list) {
                list.innerHTML = employeeNotificationEmptyStateHtml();
            }

            updateUnreadBadges(0);
        }

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
            if (document.documentElement.classList.contains('is-native-app')) {
                if (typeof window.TaskCheckNative?.registerPush === 'function') {
                    await window.TaskCheckNative.registerPush();
                }
                return;
            }

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

        function isHistoricalRealtimeNotification(notification) {
            if (!notification?.created_at) {
                return false;
            }
            const createdAt = Date.parse(notification.created_at);
            if (!Number.isFinite(createdAt)) {
                return false;
            }
            return createdAt < realtimePageLoadedAt - 2000;
        }

        async function showRealtimeNotification(notification) {
            if (isHistoricalRealtimeNotification(notification)) {
                return;
            }
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
            if (isHistoricalRealtimeNotification(notification)) {
                return;
            }

            let container = document.querySelector('[data-realtime-toast-container]');
            if (!container) {
                container = document.createElement('div');
                container.setAttribute('data-realtime-toast-container', '1');
                container.className = 'realtime-toast-stack fixed right-3 left-3 sm:left-auto sm:right-4 z-[9999] sm:max-w-sm space-y-3';
                document.body.appendChild(container);
            }

            const existing = container.querySelector(`[data-realtime-toast-id="${notification.id}"]`);
            if (existing) {
                return;
            }

            container.querySelectorAll('[data-realtime-toast]').forEach((el) => el.remove());

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
                    // Eerste poll na login: cursor zetten, geen catch-up van oude
                    // ongelezen meldingen. Die staan in het bel-icoon.
                    if (!hasExistingCursor && typeof payload.latest_user_notification_id === 'number') {
                        lastNotificationId = payload.latest_user_notification_id;
                        localStorage.setItem(realtimeStorageKey, String(lastNotificationId));
                        hasExistingCursor = true;
                        return;
                    }

                    if (notifications.length > 0) {
                        await showRealtimeNotification(notifications[notifications.length - 1]);
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
            const isNativeApp = document.documentElement.classList.contains('is-native-app');

            if (!isNativeApp) {
                registerServiceWorkerIfNeeded();
            }

            if (!isNativeApp && 'Notification' in window && Notification.permission === 'default') {
                document.addEventListener('click', () => {
                    Notification.requestPermission()
                        .then((permission) => {
                            if (permission === 'granted') {
                                subscribeForBackgroundPush();
                            }
                        })
                        .catch(() => {});
                }, { once: true });
            } else if (!isNativeApp && 'Notification' in window && Notification.permission === 'granted') {
                subscribeForBackgroundPush();
            }

            startRealtimeNotificationPolling();

            document.querySelector('[data-employee-notification-root]')?.addEventListener('click', async (event) => {
                const markReadButton = event.target.closest('[data-employee-mark-read-notification]');
                if (markReadButton) {
                    event.preventDefault();
                    await markEmployeeNotificationAsRead(markReadButton.dataset.notificationId);
                    return;
                }

                const markAllButton = event.target.closest('[data-employee-mark-all-read]');
                if (markAllButton) {
                    event.preventDefault();
                    await markAllEmployeeNotificationsAsRead();
                }
            });

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
    @include('partials.page-transitions')
</body>
</html>
