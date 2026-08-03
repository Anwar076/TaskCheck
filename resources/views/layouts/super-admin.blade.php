<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Super Admin') — {{ config('app.name', 'TaskCheck') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="theme-color" content="#2563eb">
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">
    @php
        $superAdminDashboardTab = request()->string('tab')->toString();
        $allowedSuperAdminTabs = ['overview', 'communications', 'companies', 'usage', 'monitoring', 'invoices', 'templates'];
        if (!in_array($superAdminDashboardTab, $allowedSuperAdminTabs, true)) {
            $superAdminDashboardTab = 'overview';
        }
    @endphp
    <div class="min-h-screen h-screen flex overflow-hidden">
        <aside class="hidden md:flex md:w-64 md:shrink-0 md:flex-col border-r border-slate-200 bg-white shadow-sm">
            <div class="flex h-screen flex-col pt-6 overflow-y-auto">
                <div class="px-6 mb-8">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('logos/taskcheck-favicon.png') }}" alt="TaskCheck logo" class="h-10 w-10 rounded-xl">
                        <div>
                            <h1 class="text-xl font-bold leading-tight text-slate-900">TaskCheck</h1>
                            <p class="text-xs font-medium text-slate-500">Platformbeheer</p>
                        </div>
                    </div>
                </div>
                <nav class="flex-1 px-4 space-y-1">
                    <a href="{{ route('super-admin.dashboard') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && !request()->has('tab') ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="h-5 w-5 shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'communications']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && request()->has('tab') && $superAdminDashboardTab === 'communications' ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="h-2 w-2 rounded-full bg-blue-300/80"></span>
                        Communicatie
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.companies.*') || (request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'companies') ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="h-2 w-2 rounded-full bg-blue-300/80"></span>
                        Bedrijven
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'usage']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'usage' ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="h-2 w-2 rounded-full bg-emerald-300/80"></span>
                        Gebruik
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'monitoring']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'monitoring' ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="h-2 w-2 rounded-full bg-blue-300/80"></span>
                        Monitoring
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'templates']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.templates.*') || (request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'templates') ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="h-2 w-2 rounded-full bg-blue-300/80"></span>
                        Templates
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'invoices']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'invoices' ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="h-2 w-2 rounded-full bg-blue-300/80"></span>
                        Facturen
                    </a>
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors {{ request()->routeIs('profile.*') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <svg class="h-5 w-5 shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        Profiel
                    </a>
                </nav>
                <div class="mt-auto border-t border-slate-200 p-4">
                    <div class="rounded-xl bg-slate-50 p-3 ring-1 ring-slate-200">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-sm font-semibold text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500">Super admin</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                                Uitloggen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 shrink-0 border-b border-slate-200 bg-white px-4 py-4 shadow-sm sm:px-6">
                <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-3 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-2 min-w-0">
                        <button type="button" class="md:hidden rounded-lg p-2 text-slate-600 hover:bg-slate-100" id="sa-mobile-open" aria-label="Menu">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                        </button>
                        <div class="hidden items-center gap-2 text-sm md:flex min-w-0">
                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg>
                            @hasSection('breadcrumbs')
                                @yield('breadcrumbs')
                            @else
                                <span class="text-slate-400">/</span>
                                <span class="truncate font-semibold text-slate-900">@yield('page-title', 'Super Admin')</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 md:hidden">
                            <img src="{{ asset('logos/taskcheck-favicon.png') }}" alt="TaskCheck" class="h-8 w-8 rounded-lg">
                            <h2 class="text-base font-semibold text-slate-900">TaskCheck</h2>
                        </div>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        @include('partials.google-translate', ['variant' => 'topbar'])
                        <a href="{{ route('welcome') }}" class="hidden text-sm font-medium text-blue-700 hover:text-blue-900 sm:inline">Website</a>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto" data-page-transition-root>
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-900">{{ session('error') }}</div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <div class="fixed inset-0 z-50 hidden md:hidden" id="sa-mobile-overlay" aria-hidden="true">
        <div class="absolute inset-0 bg-slate-900/60" id="sa-mobile-backdrop"></div>
        <div class="absolute inset-y-0 left-0 flex w-[min(20rem,90vw)] flex-col bg-white text-slate-900 shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 p-4">
                <span class="flex items-center gap-2 font-semibold"><img src="{{ asset('logos/taskcheck-favicon.png') }}" alt="" class="h-8 w-8 rounded-lg">TaskCheck</span>
                <button type="button" class="rounded-lg p-2 hover:bg-slate-100" id="sa-mobile-close" aria-label="Sluiten">✕</button>
            </div>
            <nav class="flex-1 space-y-1 p-4">
                <a href="{{ route('super-admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100">Dashboard</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'communications']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100">Communicatie</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100">Bedrijven</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'usage']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100">Gebruik</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'monitoring']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100">Monitoring</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'templates']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100">Templates</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'invoices']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100">Facturen</a>
                <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100">Profiel</a>
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-200 p-4">
                @csrf
                <button type="submit" class="w-full rounded-lg border border-slate-200 py-2 text-sm font-medium hover:bg-slate-50">Uitloggen</button>
            </form>
        </div>
    </div>

    <script>
        (function () {
            var openBtn = document.getElementById('sa-mobile-open');
            var overlay = document.getElementById('sa-mobile-overlay');
            var closeBtn = document.getElementById('sa-mobile-close');
            var backdrop = document.getElementById('sa-mobile-backdrop');
            function open() { if (overlay) { overlay.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); } }
            function close() { if (overlay) { overlay.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); } }
            if (openBtn) openBtn.addEventListener('click', open);
            if (closeBtn) closeBtn.addEventListener('click', close);
            if (backdrop) backdrop.addEventListener('click', close);
        })();
    </script>
    @stack('scripts')
    @include('partials.page-transitions')
</body>
</html>
