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

    <title>@yield('page-title', 'Dashboard') - {{ config('app.name', 'TaskCheck') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- API Client -->
    <script src="{{ asset('js/api-client.js') }}"></script>
    
    <!-- Additional Meta Tags -->
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
</head>
<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen flex">
        <!-- Clean Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div class="flex flex-col flex-grow pt-6 overflow-y-auto bg-white shadow-sm border-r border-slate-200">
                <!-- Clean Logo -->
                <div class="flex items-center flex-shrink-0 px-6 mb-8">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('logos/taskcheck-favicon.png') }}" alt="TaskCheck logo" class="w-10 h-10 rounded-xl">
                        <div>
                            <h1 class="text-xl font-bold text-slate-900">TaskCheck</h1>
                            <p class="text-xs text-slate-500 font-medium">Checklist &amp; kwaliteitscontrole</p>
                        </div>
                    </div>
                </div>

                <!-- Clean Navigation -->
                <nav class="flex-1 px-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.lists.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.lists.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.lists.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Takenlijsten
                    </a>
                    <a href="{{ route('admin.locations.index') }}"
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.locations.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.locations.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.75-5.625-6.75-11.25a6.75 6.75 0 1113.5 0C18.75 15.375 12 21 12 21z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        Locaties
                    </a>
                    <a href="{{ route('admin.templates.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.templates.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.templates.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        Sjablonen
                    </a>
                    <a href="{{ route('admin.submissions.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.submissions.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.submissions.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                        </svg>
                        Inzendingen
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.users.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                        </svg>
                        Gebruikers
                    </a>
                    @if((auth()->user()->company?->subscription_plan ?? 'starter') !== 'starter')
                        <a href="{{ route('admin.weekly-overview') }}" 
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.weekly-overview') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.weekly-overview') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                            Weekoverzicht
                        </a>
                    @endif
                    
                    <a href="{{ route('subscription.show') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('subscription.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('subscription.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                        </svg>
                        Abonnement
                    </a>
                    <a href="{{ route('admin.settings.edit') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.settings.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Instellingen
                    </a>
                </nav>

                <!-- Clean User section -->
                <div class="flex-shrink-0 border-t border-slate-200 p-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="relative h-10 w-10 shrink-0 rounded-xl bg-blue-600 flex items-center justify-center text-white font-semibold shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                <span class="absolute -right-1 -bottom-1 h-3 w-3 rounded-full border-2 border-white bg-emerald-400"></span>
                            </div>
                            <div class="min-w-0">
                                <div class="truncate text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-slate-500 whitespace-nowrap">Beheerder</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600" title="Uitloggen">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.625A2.625 2.625 0 0013.125 3h-6.75A2.625 2.625 0 003.75 5.625v12.75A2.625 2.625 0 006.375 21h6.75a2.625 2.625 0 002.625-2.625V15"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12h12m0 0-3-3m3 3-3 3"/>
                                </svg>
                                Uitloggen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clean Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Clean Top navigation -->
            <header class="bg-white border-b border-slate-200 px-4 sm:px-6 py-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <!-- Mobile menu button -->
                    <button type="button" aria-label="Menu openen" class="md:hidden p-2.5 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors" id="mobile-menu-button">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                    </button>
                    
                    <!-- Breadcrumb -->
                    <div class="hidden md:flex items-center gap-2 text-sm min-w-0">
                        <svg class="h-4 w-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                        <span class="text-slate-500">/</span>
                        <span class="text-slate-900 font-semibold truncate">@yield('page-title', 'Dashboard')</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        @php $adminUnreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                        <div class="relative" data-admin-notification-root>
                            <button
                                type="button"
                                class="relative p-2.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors"
                                title="Notificaties"
                                aria-label="Notificaties openen"
                                aria-expanded="false"
                                data-admin-notification-toggle
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                </svg>
                                <span data-admin-unread-badge class="absolute -top-1 -right-1 inline-flex items-center justify-center h-4 min-w-[1rem] px-1 text-[10px] font-medium text-white bg-red-500 rounded-full {{ $adminUnreadCount > 0 ? '' : 'hidden' }}">
                                    {{ $adminUnreadCount > 9 ? '9+' : $adminUnreadCount }}
                                </span>
                            </button>

                            <div
                                class="hidden absolute right-0 mt-2 w-[22rem] max-w-[90vw] rounded-2xl border border-slate-200 bg-white shadow-xl z-50 overflow-hidden"
                                data-admin-notification-dropdown
                            >
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="text-sm font-semibold text-slate-900">Notificaties</h3>
                                        <span class="text-[11px] font-medium text-slate-500" data-admin-notification-permission-state>
                                            Browser machtiging: onbekend
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-slate-200 px-2.5 py-1 text-[11px] font-medium text-slate-600 hover:bg-slate-50 transition-colors"
                                            data-admin-mark-all-read
                                        >
                                            Markeer alles gelezen
                                        </button>
                                    </div>
                                    <button
                                        type="button"
                                        class="mt-2 hidden w-full rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition-colors"
                                        data-admin-enable-notifications
                                    >
                                        Meldingen toestaan
                                    </button>
                                </div>
                                <div class="max-h-80 overflow-y-auto" data-admin-notification-list>
                                    <div class="px-4 py-3 text-sm text-slate-500">Nog geen nieuwe meldingen.</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.settings.edit') }}" class="p-2.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors" title="Instellingen">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Clean Page content -->
            <main class="flex-1 overflow-y-auto bg-slate-50">
                <div class="p-4 sm:p-6">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flash-message">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flash-message">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                                </svg>
                                <p class="text-red-800 font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('trial_warning'))
                        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4 flash-message">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                    </svg>
                                    <p class="text-yellow-800 font-medium">{{ session('trial_warning')['message'] }}</p>
                                </div>
                                <a href="{{ route('subscription.choose-plan') }}" class="ml-4 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
                                    Plan kiezen
                                </a>
                            </div>
                        </div>
                    @endif

                    @if(auth()->check() && auth()->user()->company)
                        @php
                            $company = auth()->user()->company;
                        @endphp
                        @if($company->isOnTrial() && $company->trialDaysRemaining() <= 3)
                            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                                        </svg>
                                        <p class="text-blue-800 font-medium">
                                            Je proefperiode eindigt over {{ $company->trialDaysRemaining() }} dag(en). 
                                            <a href="{{ route('subscription.choose-plan') }}" class="underline font-bold">Kies een plan</a> om door te gaan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Page header -->
                    @hasSection('header')
                        <div class="mb-8">
                            @yield('header')
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        @yield('content')
                    </div>
                    <div class="mt-4 text-right">
                        <span class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs text-slate-500">
                            Web app versie: {{ $webAppVersion }}
                        </span>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile menu overlay -->
    <div class="md:hidden fixed inset-0 z-50 hidden" id="mobile-menu-overlay">
        <div class="fixed inset-0 bg-slate-900/75" id="mobile-menu-backdrop"></div>
        <div class="fixed inset-y-0 left-0 w-80 max-w-[85vw] bg-white shadow-2xl">
            <!-- Mobile menu content -->
            <div class="flex flex-col h-full">
                <!-- Mobile Logo -->
                <div class="flex items-center justify-between p-6 border-b border-slate-200/50">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('logos/taskcheck-favicon.png') }}" alt="TaskCheck logo" class="w-10 h-10 rounded-xl">
                        <div>
                            <h1 class="text-lg font-bold text-gray-900">TaskCheck</h1>
                            <p class="text-xs text-slate-500 font-medium">Checklist &amp; kwaliteitscontrole</p>
                        </div>
                    </div>
                    <button type="button" aria-label="Menu sluiten" class="p-2.5 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors" id="close-mobile-menu">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.lists.index') }}" 
                       class="flex items-center px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.lists.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.lists.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Takenlijsten
                    </a>
                    <a href="{{ route('admin.locations.index') }}"
                       class="flex items-center px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.locations.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.locations.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.75-5.625-6.75-11.25a6.75 6.75 0 1113.5 0C18.75 15.375 12 21 12 21z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        Locaties
                    </a>
                    <a href="{{ route('admin.templates.index') }}" 
                       class="flex items-center px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.templates.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.templates.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        Sjablonen
                    </a>
                    <a href="{{ route('admin.submissions.index') }}" 
                       class="flex items-center px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.submissions.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.submissions.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Submissions
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.users.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                        </svg>
                        Gebruikers
                    </a>
                    @if((auth()->user()->company?->subscription_plan ?? 'starter') !== 'starter')
                        <a href="{{ route('admin.weekly-overview') }}" 
                           class="flex items-center px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.weekly-overview') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.weekly-overview') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                            Weekoverzicht
                        </a>
                    @endif
                    <a href="{{ route('subscription.show') }}" 
                       class="flex items-center px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('subscription.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('subscription.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                        </svg>
                        Abonnement
                    </a>
                    <a href="{{ route('admin.settings.edit') }}" 
                       class="flex items-center px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.settings.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Instellingen
                    </a>
                </nav>

                <!-- Mobile User section -->
                <div class="border-t border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="h-8 w-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-medium">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500">Beheerder</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-3 text-base font-medium text-red-600 hover:bg-red-50 rounded-xl transition-colors border border-red-200 hover:border-red-300">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v4m2.25-6h8.25m-8.25 0l2.25 2.25m0 0l2.25 2.25M6 20.25h8.25m-8.25 0l-2.25-2.25M6 20.25l-2.25-2.25"/>
                            </svg>
                            Uitloggen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Clean JavaScript -->
    <script>
        const adminRealtimeFeedUrl = @json(route('admin.notifications.realtime-feed', [], false));
        const adminRealtimeStorageKey = `taskcheck:admin:last_notification_id:user:${@json((string) auth()->id())}`;
        const vapidKeyUrl = @json(route('push.vapid-public-key', [], false));
        const pushSubscribeUrl = @json(route('push.subscribe', [], false));
        const adminMarkReadUrlTemplate = @json(route('admin.notifications.mark-read', ['notification' => '__NOTIFICATION_ID__'], false));
        const adminMarkAllReadUrl = @json(route('admin.notifications.mark-all-read', [], false));
        const adminNotificationState = {
            isOpen: false,
            items: [],
            hydratedFromUnread: false,
        };

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

        function updateAdminUnreadBadge(count) {
            const badges = document.querySelectorAll('[data-admin-unread-badge]');
            badges.forEach((badge) => {
                if (!count || count <= 0) {
                    badge.classList.add('hidden');
                    return;
                }

                badge.classList.remove('hidden');
                badge.textContent = count > 9 ? '9+' : String(count);
            });

            syncAdminAppIconBadge(count || 0);
        }

        async function syncAdminAppIconBadge(count) {
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
                // Browser/launcher might not support app icon badges.
            }
        }

        function showAdminToast(notification) {
            const safeTitle = escapeHtml(notification.title || 'Nieuwe melding');
            const safeMessage = escapeHtml(notification.message || '');
            playRealtimeNotificationSound();
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 z-[9999] max-w-sm rounded-2xl border border-blue-200 bg-white px-4 py-3 shadow-2xl ring-1 ring-blue-100';
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-blue-600 text-sm">🔔</span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900">${safeTitle}</p>
                        <p class="mt-1 text-xs text-slate-600">${safeMessage}</p>
                    </div>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
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

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderAdminNotificationList() {
            const list = document.querySelector('[data-admin-notification-list]');
            if (!list) return;

            if (!adminNotificationState.items.length) {
                list.innerHTML = '<div class="px-4 py-3 text-sm text-slate-500">Nog geen nieuwe meldingen.</div>';
                return;
            }

            list.innerHTML = adminNotificationState.items.map((notification) => {
                const title = escapeHtml(notification.title || 'Nieuwe melding');
                const message = escapeHtml(notification.message || '');
                const notificationId = Number(notification.id || 0);
                const targetUrl = resolveAdminNotificationTargetUrl(notification);
                return `
                    <button
                        type="button"
                        class="w-full text-left px-4 py-3 border-b border-slate-100 last:border-b-0 hover:bg-slate-50 transition-colors"
                        data-admin-notification-item
                        data-notification-id="${notificationId}"
                        data-target-url="${escapeHtml(targetUrl)}"
                    >
                        <p class="text-sm font-semibold text-slate-900">${title}</p>
                        <p class="mt-1 text-xs text-slate-600">${message}</p>
                    </button>
                `;
            }).join('');
        }

        function resolveAdminNotificationTargetUrl(notification) {
            const data = notification && typeof notification === 'object' ? notification.data : null;
            if (data && typeof data === 'object' && typeof data.url === 'string' && data.url.trim() !== '') {
                return data.url;
            }

            return '/admin/dashboard';
        }

        function prependAdminNotificationItems(notifications) {
            if (!Array.isArray(notifications) || notifications.length === 0) {
                return;
            }

            const merged = [...notifications, ...adminNotificationState.items];
            const uniqueById = [];
            const seenIds = new Set();

            for (const item of merged) {
                const notificationId = Number(item?.id || 0);
                if (notificationId > 0 && seenIds.has(notificationId)) {
                    continue;
                }
                if (notificationId > 0) {
                    seenIds.add(notificationId);
                }
                uniqueById.push(item);
                if (uniqueById.length >= 15) {
                    break;
                }
            }

            adminNotificationState.items = uniqueById;
            renderAdminNotificationList();
        }

        async function markAdminNotificationAsRead(notificationId) {
            if (!notificationId || notificationId <= 0) {
                return false;
            }

            try {
                const endpoint = adminMarkReadUrlTemplate.replace('__NOTIFICATION_ID__', String(notificationId));
                const response = await fetch(endpoint, {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                });

                if (!response.ok) {
                    return false;
                }

                const payload = await response.json();
                if (payload && payload.success === true) {
                    updateAdminUnreadBadge(Number(payload.unread_count || 0));
                    return true;
                }
            } catch (error) {
                console.warn('Admin mark notification as read failed', error);
            }

            return false;
        }

        async function markAllAdminNotificationsAsRead() {
            try {
                const response = await fetch(adminMarkAllReadUrl, {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                });

                if (!response.ok) {
                    return false;
                }

                const payload = await response.json();
                if (!payload || payload.success !== true) {
                    return false;
                }

                adminNotificationState.items = [];
                renderAdminNotificationList();
                updateAdminUnreadBadge(Number(payload.unread_count || 0));
                return true;
            } catch (error) {
                console.warn('Admin mark all notifications as read failed', error);
                return false;
            }
        }

        function setupAdminNotificationBell() {
            const root = document.querySelector('[data-admin-notification-root]');
            const toggle = document.querySelector('[data-admin-notification-toggle]');
            const dropdown = document.querySelector('[data-admin-notification-dropdown]');
            const markAllReadButton = document.querySelector('[data-admin-mark-all-read]');
            if (!root || !toggle || !dropdown) return;

            const closeDropdown = () => {
                adminNotificationState.isOpen = false;
                dropdown.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
            };

            toggle.addEventListener('click', (event) => {
                event.stopPropagation();
                adminNotificationState.isOpen = !adminNotificationState.isOpen;
                dropdown.classList.toggle('hidden', !adminNotificationState.isOpen);
                toggle.setAttribute('aria-expanded', adminNotificationState.isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', (event) => {
                if (!root.contains(event.target)) {
                    closeDropdown();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeDropdown();
                }
            });

            if (markAllReadButton) {
                markAllReadButton.addEventListener('click', async (event) => {
                    event.preventDefault();
                    event.stopPropagation();

                    markAllReadButton.disabled = true;
                    markAllReadButton.classList.add('opacity-60');
                    await markAllAdminNotificationsAsRead();
                    markAllReadButton.disabled = false;
                    markAllReadButton.classList.remove('opacity-60');
                });
            }

            dropdown.addEventListener('click', async (event) => {
                const item = event.target.closest('[data-admin-notification-item]');
                if (!item) return;

                const notificationId = Number(item.getAttribute('data-notification-id') || 0);
                const targetUrl = item.getAttribute('data-target-url') || '/admin/dashboard';

                item.classList.add('opacity-70');
                item.disabled = true;

                const readAt = await markAdminNotificationAsRead(notificationId);
                if (readAt) {
                    adminNotificationState.items = adminNotificationState.items.filter((notification) => Number(notification?.id || 0) !== notificationId);
                    renderAdminNotificationList();
                } else {
                    item.classList.remove('opacity-70');
                    item.disabled = false;
                }

                window.location.href = targetUrl;
            });
        }

        function updateAdminPermissionUi() {
            const permissionState = document.querySelector('[data-admin-notification-permission-state]');
            const enableButton = document.querySelector('[data-admin-enable-notifications]');
            if (!permissionState || !enableButton || !('Notification' in window)) return;

            if (Notification.permission === 'granted') {
                permissionState.textContent = 'Browser machtiging: toegestaan';
                enableButton.classList.add('hidden');
                return;
            }

            if (Notification.permission === 'denied') {
                permissionState.textContent = 'Browser machtiging: geblokkeerd';
                enableButton.classList.add('hidden');
                return;
            }

            permissionState.textContent = 'Browser machtiging: nog niet gegeven';
            enableButton.classList.remove('hidden');
        }

        async function requestAdminNotificationPermission() {
            if (!('Notification' in window)) return;

            try {
                const permission = await Notification.requestPermission();
                updateAdminPermissionUi();

                if (permission === 'granted') {
                    await subscribeForBackgroundPush();
                }
            } catch (error) {
                console.warn('Admin notification permission request failed', error);
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
                if (!keyResponse.ok) return;

                const keyPayload = await keyResponse.json();
                if (!keyPayload?.publicKey) return;

                let subscription = await registration.pushManager.getSubscription();
                if (!subscription) {
                    subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(keyPayload.publicKey),
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
                console.warn('Admin push subscription failed', error);
            }
        }

        async function startAdminRealtimePolling() {
            let lastNotificationId = Number(localStorage.getItem(adminRealtimeStorageKey) || 0);
            let hasExistingCursor = Number.isFinite(lastNotificationId) && lastNotificationId > 0;

            const poll = async () => {
                try {
                    const response = await fetch(`${adminRealtimeFeedUrl}?after_id=${lastNotificationId}&_ts=${Date.now()}`, {
                        cache: 'no-store',
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) return;
                    const payload = await response.json();
                    if (!payload || payload.success !== true) return;

                    updateAdminUnreadBadge(payload.unread_count || 0);

                    const notifications = Array.isArray(payload.notifications) ? payload.notifications : [];
                    const unreadNotifications = Array.isArray(payload.unread_notifications) ? payload.unread_notifications : [];
                    if (!hasExistingCursor && typeof payload.latest_user_notification_id === 'number') {
                        if (!adminNotificationState.hydratedFromUnread && unreadNotifications.length > 0) {
                            prependAdminNotificationItems(unreadNotifications);
                            adminNotificationState.hydratedFromUnread = true;
                        }

                        lastNotificationId = payload.latest_user_notification_id;
                        localStorage.setItem(adminRealtimeStorageKey, String(lastNotificationId));
                        hasExistingCursor = true;
                        return;
                    }

                    prependAdminNotificationItems(notifications);

                    for (const notification of notifications) {
                        showAdminToast(notification);
                    }

                    if (typeof payload.after_id === 'number' && payload.after_id > lastNotificationId) {
                        lastNotificationId = payload.after_id;
                        localStorage.setItem(adminRealtimeStorageKey, String(lastNotificationId));
                        hasExistingCursor = true;
                    }
                } catch (error) {
                    console.warn('Admin realtime polling failed', error);
                }
            };

            await poll();
            setInterval(poll, 8000);
        }

        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            }

            setupAdminNotificationBell();
            updateAdminPermissionUi();
            const enableNotificationsButton = document.querySelector('[data-admin-enable-notifications]');
            if (enableNotificationsButton) {
                enableNotificationsButton.addEventListener('click', requestAdminNotificationPermission);
            }

            if ('Notification' in window && Notification.permission === 'granted') {
                subscribeForBackgroundPush();
            }

            startAdminRealtimePolling();

            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const closeMobileMenu = document.getElementById('close-mobile-menu');
            
            if (mobileMenuButton && mobileMenuOverlay) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenuOverlay.classList.remove('hidden');
                });

                function closeMenu() {
                    mobileMenuOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }

                mobileMenuButton.addEventListener('click', function() {
                    document.body.style.overflow = 'hidden';
                });

                if (closeMobileMenu) closeMobileMenu.addEventListener('click', closeMenu);

                const backdrop = document.getElementById('mobile-menu-backdrop');
                if (backdrop) backdrop.addEventListener('click', closeMenu);

                const mobileNavLinks = mobileMenuOverlay.querySelectorAll('nav a');
                mobileNavLinks.forEach(link => link.addEventListener('click', closeMenu));
            }

            // Auto-hide flash messages (only those with the flash-message class)
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transition = 'opacity 0.5s ease-out';
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 500);
                }, 5000);
            });

            // Disabled auto-refresh to prevent menu items from disappearing
            // Auto-refresh was causing issues with sidebar navigation
            // const currentPath = window.location.pathname;
            // const isListsIndex = currentPath === '/admin/lists' || currentPath === '/admin/lists/';
            // const isTemplatesIndex = currentPath === '/admin/templates' || currentPath === '/admin/templates/';
            
            // if (isListsIndex || isTemplatesIndex) {
            //     setInterval(function() {
            //         if (!document.hidden && document.hasFocus() && window.location.pathname === currentPath) {
            //             location.reload();
            //         }
            //     }, 60000);
            // }

            // Disabled focus refresh as it was too aggressive
            // let lastFocusTime = Date.now();
            // window.addEventListener('focus', function() {
            //     const now = Date.now();
            //     if (now - lastFocusTime > 5000) {
            //         location.reload();
            //     }
            // });
            // window.addEventListener('blur', function() {
            //     lastFocusTime = Date.now();
            // });

            // Add cache-busting to all forms
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                if (!form.querySelector('input[name="_token"]')) {
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(tokenInput);
                }
            });
        });
    </script>
</body>
</html>