<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.native-shell')
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
        $allowedSuperAdminTabs = ['overview', 'communications', 'companies', 'users', 'usage', 'monitoring', 'invoices', 'templates'];
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
                        <x-super-admin-icon name="dashboard" class="shrink-0" />
                        Dashboard
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'communications']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && request()->has('tab') && $superAdminDashboardTab === 'communications' ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <x-super-admin-icon name="communication" class="shrink-0" />
                        Communicatie
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.companies.*') || (request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'companies') ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <x-super-admin-icon name="companies" class="shrink-0" />
                        Bedrijven
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'users']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'users' ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <x-super-admin-icon name="profile" class="shrink-0" />
                        Gebruikers
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'usage']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'usage' ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <x-super-admin-icon name="usage" class="shrink-0" />
                        Gebruik
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'monitoring']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'monitoring' ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <x-super-admin-icon name="monitoring" class="shrink-0" />
                        Monitoring
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'templates']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.templates.*') || (request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'templates') ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <x-super-admin-icon name="templates" class="shrink-0" />
                        Templates
                    </a>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'invoices']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') && $superAdminDashboardTab === 'invoices' ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <x-super-admin-icon name="invoices" class="shrink-0" />
                        Facturen
                    </a>
                    <a href="{{ route('super-admin.subscriptions.index') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.subscriptions.*') ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <x-super-admin-icon name="invoices" class="shrink-0" />
                        Abonnementen
                    </a>
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors {{ request()->routeIs('profile.*') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <x-super-admin-icon name="profile" class="shrink-0" />
                        Profiel
                    </a>
                </nav>
                <div class="hidden">
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
            <header class="app-safe-header sticky top-0 z-40 shrink-0 border-b border-slate-200 bg-white pb-3 shadow-sm">
                <div class="mx-auto flex w-full max-w-7xl items-center justify-between gap-2 px-3 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 flex-1 items-center gap-2">
                        <button type="button" class="relative z-10 md:hidden inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100" id="sa-mobile-open" aria-label="Menu">
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
                    <div class="flex min-w-0 shrink-0 items-center gap-2">
                        <div class="hidden sm:block">@include('partials.google-translate', ['variant' => 'topbar'])</div>
                        <a href="{{ route('welcome') }}" class="hidden items-center gap-1.5 rounded-lg px-2 py-1.5 text-sm font-medium text-blue-700 hover:bg-blue-50 hover:text-blue-900 lg:inline-flex"><x-super-admin-icon name="website" class="h-4 w-4" />Website</a>
                        <div class="relative hidden sm:block" data-sa-profile-root>
                            <button type="button" data-sa-profile-toggle aria-expanded="false" class="inline-flex max-w-full items-center gap-2 rounded-xl border border-slate-200 bg-white px-2 py-1.5 text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                                <span class="hidden max-w-36 truncate text-left lg:block"><span class="block truncate text-sm font-semibold leading-4">{{ Auth::user()->name }}</span><span class="block text-[11px] text-slate-500">Superadmin</span></span>
                                <x-super-admin-icon name="chevron" class="h-4 w-4 text-slate-400" />
                            </button>
                            <div data-sa-profile-dropdown class="absolute right-0 z-50 mt-2 hidden w-56 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                                <div class="border-b border-slate-100 px-4 py-3 sm:hidden"><p class="truncate text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p><p class="truncate text-xs text-slate-500">{{ Auth::user()->email }}</p></div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50"><x-super-admin-icon name="profile" class="h-4 w-4 text-slate-500" />Profiel beheren</a>
                                <a href="{{ route('welcome') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 lg:hidden"><x-super-admin-icon name="website" class="h-4 w-4 text-slate-500" />Website openen</a>
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">@csrf<button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm font-medium text-red-600 hover:bg-red-50"><x-super-admin-icon name="logout" class="h-4 w-4" />Uitloggen</button></form>
                            </div>
                        </div>
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

    <div class="app-safe-fab-top fixed right-3 z-50 sm:hidden" data-sa-mobile-profile-root>
        <button type="button" data-sa-mobile-profile-toggle aria-expanded="false" aria-label="Profielmenu openen" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-xs font-bold text-white shadow-sm ring-2 ring-white">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</button>
        <div data-sa-mobile-profile-dropdown class="absolute right-0 mt-2 hidden w-56 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl"><div class="border-b border-slate-100 px-4 py-3"><p class="truncate text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p><p class="text-xs text-slate-500">Superadmin</p></div><a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50"><x-super-admin-icon name="profile" class="h-4 w-4" />Profiel beheren</a><form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">@csrf<button class="flex w-full items-center gap-3 px-4 py-3 text-sm font-medium text-red-600"><x-super-admin-icon name="logout" class="h-4 w-4" />Uitloggen</button></form></div>
    </div>

    <div class="fixed inset-0 z-50 hidden md:hidden" id="sa-mobile-overlay" aria-hidden="true">
        <div class="absolute inset-0 bg-slate-900/60" id="sa-mobile-backdrop"></div>
        <div class="absolute inset-y-0 left-0 flex w-[min(20rem,90vw)] flex-col bg-white text-slate-900 shadow-2xl">
            <div class="app-safe-drawer-header flex items-center justify-between border-b border-slate-200 px-4 pb-4">
                <span class="flex items-center gap-2 font-semibold"><img src="{{ asset('logos/taskcheck-favicon.png') }}" alt="" class="h-8 w-8 rounded-lg">TaskCheck</span>
                <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-xl hover:bg-slate-100" id="sa-mobile-close" aria-label="Sluiten">✕</button>
            </div>
            <nav class="flex-1 space-y-1 p-4">
                @foreach([
                    ['dashboard', 'Dashboard', route('super-admin.dashboard')],
                    ['communication', 'Communicatie', route('super-admin.dashboard', ['tab' => 'communications'])],
                    ['companies', 'Bedrijven', route('super-admin.dashboard', ['tab' => 'companies'])],
                    ['profile', 'Gebruikers', route('super-admin.dashboard', ['tab' => 'users'])],
                    ['usage', 'Gebruik', route('super-admin.dashboard', ['tab' => 'usage'])],
                    ['monitoring', 'Monitoring', route('super-admin.dashboard', ['tab' => 'monitoring'])],
                    ['templates', 'Templates', route('super-admin.dashboard', ['tab' => 'templates'])],
                    ['invoices', 'Facturen', route('super-admin.dashboard', ['tab' => 'invoices'])],
                    ['invoices', 'Abonnementen', route('super-admin.subscriptions.index')],
                    ['profile', 'Profiel', route('profile.edit')],
                ] as [$icon, $label, $url])
                    <a href="{{ $url }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"><x-super-admin-icon :name="$icon" class="h-5 w-5 text-slate-500" />{{ $label }}</a>
                @endforeach
            </nav>
        </div>
    </div>

    <script>
        (function () {
            var openBtn = document.getElementById('sa-mobile-open');
            var overlay = document.getElementById('sa-mobile-overlay');
            var closeBtn = document.getElementById('sa-mobile-close');
            var backdrop = document.getElementById('sa-mobile-backdrop');
            var profileRoot = document.querySelector('[data-sa-profile-root]');
            var profileToggle = document.querySelector('[data-sa-profile-toggle]');
            var profileDropdown = document.querySelector('[data-sa-profile-dropdown]');
            var mobileProfileRoot = document.querySelector('[data-sa-mobile-profile-root]');
            var mobileProfileToggle = document.querySelector('[data-sa-mobile-profile-toggle]');
            var mobileProfileDropdown = document.querySelector('[data-sa-mobile-profile-dropdown]');
            function open() { if (overlay) { overlay.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); } }
            function close() { if (overlay) { overlay.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); } }
            if (openBtn) openBtn.addEventListener('click', open);
            if (closeBtn) closeBtn.addEventListener('click', close);
            if (backdrop) backdrop.addEventListener('click', close);
            if (profileToggle && profileDropdown) {
                profileToggle.addEventListener('click', function (event) {
                    event.stopPropagation();
                    var willOpen = profileDropdown.classList.contains('hidden');
                    profileDropdown.classList.toggle('hidden', !willOpen);
                    profileToggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                });
                document.addEventListener('click', function (event) {
                    if (profileRoot && !profileRoot.contains(event.target)) {
                        profileDropdown.classList.add('hidden');
                        profileToggle.setAttribute('aria-expanded', 'false');
                    }
                });
                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        profileDropdown.classList.add('hidden');
                        profileToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
            if (mobileProfileToggle && mobileProfileDropdown) {
                mobileProfileToggle.addEventListener('click', function (event) { event.stopPropagation(); mobileProfileDropdown.classList.toggle('hidden'); });
                document.addEventListener('click', function (event) { if (mobileProfileRoot && !mobileProfileRoot.contains(event.target)) mobileProfileDropdown.classList.add('hidden'); });
            }
        })();
    </script>
    @stack('scripts')
    @include('partials.page-transitions')
</body>
</html>
