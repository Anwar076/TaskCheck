<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
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
                        <img src="{{ asset('icons/taskcheck-favicon.png') }}" alt="TaskCheck logo" onerror="this.onerror=null;this.src='{{ asset('icons/icon-192x192.png') }}';" class="w-10 h-10 rounded-xl">
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
                    <a href="{{ route('admin.weekly-overview') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.weekly-overview') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.weekly-overview') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                        Weekoverzicht
                    </a>
                    
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
                        <img src="{{ asset('icons/taskcheck-favicon.png') }}" alt="TaskCheck logo" onerror="this.onerror=null;this.src='{{ asset('icons/icon-192x192.png') }}';" class="w-10 h-10 rounded-xl">
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
                    <a href="{{ route('admin.weekly-overview') }}" 
                       class="flex items-center px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.weekly-overview') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.weekly-overview') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                        Weekoverzicht
                    </a>
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
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
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