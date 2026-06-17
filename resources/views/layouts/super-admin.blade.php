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
    <title>@yield('page-title', 'Super Admin') — {{ config('app.name', 'TaskCheck') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="theme-color" content="#4c1d95">
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-900">
    @php
        $superAdminDashboardTab = request()->string('tab')->toString();
        $allowedSuperAdminTabs = ['communications', 'companies', 'usage', 'monitoring', 'invoices', 'templates'];
        if (!in_array($superAdminDashboardTab, $allowedSuperAdminTabs, true)) {
            $superAdminDashboardTab = 'communications';
        }
    @endphp
    <div class="min-h-screen flex">
        <aside class="hidden md:flex md:w-64 md:flex-col bg-gradient-to-b from-violet-950 to-slate-900 text-white shadow-xl">
            <div class="flex flex-col flex-grow pt-6 overflow-y-auto">
                <div class="px-6 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20">
                            <svg class="h-6 w-6 text-violet-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.623 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-violet-300/90">Platform</p>
                            <h1 class="text-lg font-bold leading-tight">Super Admin</h1>
                        </div>
                    </div>
                </div>
                <nav class="flex-1 px-4 space-y-1">
                    <a href="{{ route('super-admin.dashboard') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') ? 'bg-white/15 text-white' : 'text-violet-100/90 hover:bg-white/10' }}">
                        <svg class="h-5 w-5 shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'communications']) }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'communications' ? 'bg-white/15 text-white' : 'text-violet-100/90 hover:bg-white/10' }}">
                        <span class="h-2 w-2 rounded-full bg-violet-300/80"></span>
                        Communicatie
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'companies' ? 'bg-white/15 text-white' : 'text-violet-100/90 hover:bg-white/10' }}">
                        <span class="h-2 w-2 rounded-full bg-violet-300/80"></span>
                        Bedrijven
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'usage']) }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'usage' ? 'bg-white/15 text-white' : 'text-violet-100/90 hover:bg-white/10' }}">
                        <span class="h-2 w-2 rounded-full bg-emerald-300/80"></span>
                        Gebruik
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'monitoring']) }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'monitoring' ? 'bg-white/15 text-white' : 'text-violet-100/90 hover:bg-white/10' }}">
                        <span class="h-2 w-2 rounded-full bg-violet-300/80"></span>
                        Monitoring
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'templates']) }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'templates' ? 'bg-white/15 text-white' : 'text-violet-100/90 hover:bg-white/10' }}">
                        <span class="h-2 w-2 rounded-full bg-violet-300/80"></span>
                        Templates
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'invoices']) }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'invoices' ? 'bg-white/15 text-white' : 'text-violet-100/90 hover:bg-white/10' }}">
                        <span class="h-2 w-2 rounded-full bg-violet-300/80"></span>
                        Facturen
                    </a>
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-violet-100/90 hover:bg-white/10 transition-colors {{ request()->routeIs('profile.*') ? 'bg-white/15 text-white' : '' }}">
                        <svg class="h-5 w-5 shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        Profiel
                    </a>
                </nav>
                <div class="border-t border-white/10 p-6 mt-auto">
                    <div class="rounded-xl bg-white/5 p-4 ring-1 ring-white/10">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-violet-500/80 text-sm font-semibold text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-violet-200/80">Super admin</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/5 px-3 py-2 text-sm font-medium text-white transition hover:bg-white/10">
                                Uitloggen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="border-b border-slate-200 bg-white px-4 py-3 shadow-sm sm:px-6">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 min-w-0">
                        <button type="button" class="md:hidden rounded-lg p-2 text-slate-600 hover:bg-slate-100" id="sa-mobile-open" aria-label="Menu">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                        </button>
                        <h2 class="truncate text-base font-semibold text-slate-900 sm:text-lg">@yield('page-title', 'Super Admin')</h2>
                    </div>
                    <a href="{{ route('welcome') }}" class="shrink-0 text-sm font-medium text-violet-700 hover:text-violet-900">Website</a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6">
                    @if (session('success'))
                        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-900">{{ session('error') }}</div>
                    @endif
                    @yield('content')
                    <p class="mt-6 text-right text-xs text-slate-400">Web app versie: {{ $webAppVersion }}</p>
                </div>
            </main>
        </div>
    </div>

    <div class="fixed inset-0 z-50 hidden md:hidden" id="sa-mobile-overlay" aria-hidden="true">
        <div class="absolute inset-0 bg-slate-900/60" id="sa-mobile-backdrop"></div>
        <div class="absolute inset-y-0 left-0 flex w-[min(20rem,90vw)] flex-col bg-gradient-to-b from-violet-950 to-slate-900 text-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-white/10 p-4">
                <span class="font-semibold">Super Admin</span>
                <button type="button" class="rounded-lg p-2 hover:bg-white/10" id="sa-mobile-close" aria-label="Sluiten">✕</button>
            </div>
            <nav class="flex-1 space-y-1 p-4">
                <a href="{{ route('super-admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-white/10">Dashboard</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'communications']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-white/10">Communicatie</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-white/10">Bedrijven</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'usage']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-white/10">Gebruik</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'monitoring']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-white/10">Monitoring</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'templates']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-white/10">Templates</a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'invoices']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-white/10">Facturen</a>
                <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-white/10">Profiel</a>
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="border-t border-white/10 p-4">
                @csrf
                <button type="submit" class="w-full rounded-lg border border-white/20 py-2 text-sm font-medium">Uitloggen</button>
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
    @include('partials.google-translate')
</body>
</html>
