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

    <title>@yield('page-title', 'Dashboard') - {{ config('app.name', 'TaskCheck') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    
    <!-- API Client -->
    <script src="{{ asset('js/api-client.js') }}"></script>
    
    <!-- Additional Meta Tags -->
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
</head>
<body class="font-sans antialiased bg-slate-50">
    @include('partials.impersonation-banner')
    <div class="min-h-screen h-screen flex overflow-hidden">
        <!-- Clean Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col md:shrink-0">
            <div class="sticky top-0 h-screen flex flex-col pt-6 bg-white shadow-sm border-r border-slate-200">
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
                    @if(auth()->user()?->isSuperAdmin())
                    <a href="{{ route('super-admin.dashboard') }}"
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('super-admin.*') ? 'bg-violet-100 text-violet-800' : 'text-violet-700 hover:bg-violet-50 border border-violet-200' }}">
                        <svg class="mr-3 h-5 w-5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.623 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                        Super Admin
                    </a>
                    @elseif(empty($subscriptionLocked))
                    <a href="{{ route('admin.dashboard') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                        </svg>
                        Dashboard
                    </a>
                    @endif

                    @unless($subscriptionLocked ?? false)
                    <a href="{{ route('admin.lists.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.lists.*') && !request()->routeIs('admin.lists.calendar') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.lists.*') && !request()->routeIs('admin.lists.calendar') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Takenlijsten
                    </a>
                    <a href="{{ route('admin.lists.calendar') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.lists.calendar') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.lists.calendar') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                        Agenda
                    </a>
                    <a href="{{ route('admin.templates.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.templates.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.templates.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        Templates
                    </a>
                    <a href="{{ route('admin.starter-packs.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.starter-packs.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.starter-packs.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                        </svg>
                        Starterpacks
                    </a>
                    <a href="{{ route('admin.submissions.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.submissions.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.submissions.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                        </svg>
                        Werkcontroles
                    </a>
                    
                    @if(auth()->user()->company?->hasPlanFeature('reports'))
                        <a href="{{ route('admin.weekly-overview') }}" 
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.weekly-overview') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.weekly-overview') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                            Rapportages
                        </a>
                    @endif
                    @endunless
                    
                    <a href="{{ route('admin.settings.edit') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.locations.*') || request()->routeIs('subscription.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.locations.*') || request()->routeIs('subscription.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Instellingen
                    </a>
                </nav>

            </div>
        </div>

        <!-- Clean Main content -->
        <div class="flex-1 flex flex-col overflow-hidden min-h-0">
            <!-- Clean Top navigation -->
            <header class="app-safe-header sticky top-0 z-40 shrink-0 bg-white border-b border-slate-200 px-3 pb-3 shadow-sm sm:px-6 sm:pb-4">
                <div class="mx-auto flex max-w-7xl items-center justify-between gap-2 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 flex-1 items-center">
                        <a href="{{ ($subscriptionLocked ?? false) ? route('admin.settings.edit') : route('admin.dashboard') }}" class="flex min-w-0 items-center gap-2 md:hidden">
                            <img src="{{ asset('logos/taskcheck-favicon.png') }}" alt="TaskCheck logo" class="h-8 w-8 shrink-0 rounded-lg sm:h-9 sm:w-9">
                            <div class="min-w-0 leading-tight">
                                <p class="truncate text-base font-semibold text-slate-900 sm:text-lg">TaskCheck</p>
                                <p class="hidden truncate text-[11px] text-slate-500 sm:block">Checklist &amp; kwaliteitscontrole</p>
                            </div>
                        </a>

                        <!-- Breadcrumb -->
                        <div class="hidden md:flex items-center gap-2 text-sm min-w-0">
                            <svg class="h-4 w-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                            </svg>
                            @hasSection('breadcrumbs')
                                @yield('breadcrumbs')
                            @else
                                <span class="text-slate-500">/</span>
                                <span class="text-slate-900 font-semibold truncate">@yield('page-title', 'Dashboard')</span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="hidden md:flex items-center gap-2">
                        @if(empty($subscriptionLocked) && auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
                            <div class="hidden items-center rounded-xl border border-slate-200 bg-slate-50 p-1 sm:flex">
                                <span class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-blue-600 px-3 text-xs font-semibold text-white shadow-sm">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 8.25V6zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 018.25 20.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                                    </svg>
                                    Adminweergave
                                </span>
                                <form method="POST" action="{{ route('dashboard.switch') }}">
                                    @csrf
                                    <input type="hidden" name="mode" value="employee">
                                    <button type="submit" data-onboarding-employee-switch class="inline-flex h-8 items-center gap-1.5 rounded-lg px-3 text-xs font-semibold text-slate-600 hover:bg-white hover:text-blue-700 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Medewerkersweergave
                                    </button>
                                </form>
                            </div>
                        @endif
                        @include('partials.google-translate', ['variant' => 'topbar'])
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
                                        <div class="flex items-center gap-2">
                                            <a
                                                href="{{ route('admin.notifications.index') }}"
                                                class="inline-flex items-center rounded-md border border-slate-200 px-2.5 py-1 text-[11px] font-medium text-slate-600 hover:bg-slate-50 transition-colors"
                                            >
                                                Alle notificaties
                                            </a>
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-slate-200 px-2.5 py-1 text-[11px] font-medium text-slate-600 hover:bg-slate-50 transition-colors"
                                                data-admin-mark-all-read
                                            >
                                                Markeer alles gelezen
                                            </button>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        class="mt-2 hidden w-full rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition-colors"
                                        data-admin-enable-notifications
                                    >
                                        Meldingen toestaan
                                    </button>
                                    <p class="mt-2 hidden text-[11px] leading-4 text-slate-500" data-admin-notification-permission-help>
                                        Als de browser geen venster toont, staat toegang geblokkeerd in de browserinstellingen voor deze website.
                                    </p>
                                </div>
                                <div class="max-h-80 overflow-y-auto" data-admin-notification-list>
                                    <div class="px-4 py-3 text-sm text-slate-500">Nog geen nieuwe meldingen.</div>
                                </div>
                            </div>
                        </div>
                        <div class="relative" data-admin-profile-root>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-slate-700 hover:border-slate-300 hover:bg-slate-50 transition-colors"
                                aria-label="Profielmenu openen"
                                aria-expanded="false"
                                data-admin-profile-toggle
                            >
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-xs font-semibold text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </span>
                                <span class="hidden sm:block text-sm font-medium max-w-[10rem] truncate">{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                                </svg>
                            </button>

                            <div
                                class="hidden absolute right-0 mt-2 w-52 rounded-xl border border-slate-200 bg-white shadow-xl z-50 overflow-hidden"
                                data-admin-profile-dropdown
                            >
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    Profiel
                                </a>
                                <a href="{{ route('admin.settings.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
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

                    <button type="button" aria-label="Menu openen" class="relative z-10 md:hidden inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-slate-600 hover:text-slate-800 hover:bg-slate-100 transition-colors" id="mobile-menu-button">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Clean Page content -->
            <main class="flex-1 overflow-y-auto bg-slate-50" data-page-transition-root>
                <div class="p-4 sm:p-6">
                    <!-- Flash Messages -->
                    @if (session('success') && empty($onboarding['active']))
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
                                    Abonnement kiezen
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
                                            <a href="{{ route('subscription.choose-plan') }}" class="underline font-bold">Kies een abonnement</a> om door te gaan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    @include('partials.ios-push-prompt')

                    <!-- Page header -->
                    @hasSection('header')
                        <div class="mb-8">
                            @yield('header')
                        </div>
                    @endif

                    <!-- Content -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>


    <!-- Mobile menu overlay -->
    <div class="md:hidden fixed inset-0 z-[60] hidden" id="mobile-menu-overlay" aria-hidden="true">
        <div class="mobile-menu-backdrop absolute inset-0 bg-slate-950/35 backdrop-blur-sm" id="mobile-menu-backdrop"></div>
        <aside id="mobile-menu-panel" class="absolute inset-y-0 right-0 flex w-[min(22rem,88vw)] translate-x-full flex-col bg-white shadow-2xl ring-1 ring-slate-200 transition-transform duration-300 ease-out">
            <div class="app-safe-drawer-header flex items-center justify-between border-b border-slate-100 px-5 pb-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logos/taskcheck-favicon.png') }}" alt="TaskCheck logo" class="h-9 w-9 rounded-lg">
                    <div class="leading-tight">
                        <p class="text-base font-semibold text-slate-900">TaskCheck</p>
                        <p class="text-[11px] text-slate-500">Menu</p>
                    </div>
                </div>
                <button type="button" aria-label="Menu sluiten" class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-700" id="close-mobile-menu">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-5 py-4">
                <nav class="space-y-1">
                    @unless($subscriptionLocked ?? false)
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center rounded-xl px-3 py-3 text-base font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.lists.index') }}" 
                       class="flex items-center rounded-xl px-3 py-3 text-base font-medium transition-colors {{ request()->routeIs('admin.lists.*') && !request()->routeIs('admin.lists.calendar') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Takenlijsten
                    </a>
                    <a href="{{ route('admin.lists.calendar') }}" 
                       class="flex items-center rounded-xl px-3 py-3 text-base font-medium transition-colors {{ request()->routeIs('admin.lists.calendar') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                        Agenda
                    </a>
                    <a href="{{ route('admin.templates.index') }}" 
                       class="flex items-center rounded-xl px-3 py-3 text-base font-medium transition-colors {{ request()->routeIs('admin.templates.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        Templates
                    </a>
                    <a href="{{ route('admin.starter-packs.index') }}" 
                       class="flex items-center px-3 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.starter-packs.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.starter-packs.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                        </svg>
                        Starterpacks
                    </a>
                    <a href="{{ route('admin.submissions.index') }}" 
                       class="flex items-center rounded-xl px-3 py-3 text-base font-medium transition-colors {{ request()->routeIs('admin.submissions.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Inzendingen
                    </a>
                    
                    @if(auth()->user()->company?->hasPlanFeature('reports'))
                        <a href="{{ route('admin.weekly-overview') }}" 
                           class="flex items-center rounded-xl px-3 py-3 text-base font-medium transition-colors {{ request()->routeIs('admin.weekly-overview') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                            Rapportages
                        </a>
                    @endif
                    @endunless
                    <a href="{{ route('admin.settings.edit') }}" 
                       class="flex items-center rounded-xl px-3 py-3 text-base font-medium transition-colors {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.locations.*') || request()->routeIs('subscription.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Instellingen
                    </a>
                </nav>
            </div>
            <div class="app-safe-bottom border-t border-slate-100 bg-slate-50 px-5 pt-4">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 font-medium text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="truncate text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                        <div class="truncate text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                @if(empty($subscriptionLocked) && auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
                    <form method="POST" action="{{ route('dashboard.switch') }}" class="mb-3">
                        @csrf
                        <input type="hidden" name="mode" value="employee">
                        <button type="submit" data-onboarding-employee-switch class="w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-50">
                            Naar medewerkersweergave
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center rounded-lg border border-red-200 bg-white px-3 py-2 text-sm font-medium text-red-600 transition-colors hover:bg-red-50">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                        Uitloggen
                    </button>
                </form>
            </div>
        </aside>
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
                    <div class="px-4 py-3 border-b border-slate-100 last:border-b-0">
                        <p class="text-sm font-semibold text-slate-900">${title}</p>
                        <p class="mt-1 text-xs text-slate-600">${message}</p>
                        <div class="mt-2 flex items-center gap-2">
                            <a
                                href="${escapeHtml(targetUrl)}"
                                class="inline-flex items-center rounded-md border border-blue-200 px-2.5 py-1 text-[11px] font-medium text-blue-700 hover:bg-blue-50 transition-colors"
                            >
                                Openen
                            </a>
                            <button
                                type="button"
                                class="inline-flex items-center rounded-md border border-emerald-200 px-2.5 py-1 text-[11px] font-medium text-emerald-700 hover:bg-emerald-50 transition-colors"
                                data-admin-mark-read-notification
                                data-notification-id="${notificationId}"
                            >
                                Markeer gelezen
                            </button>
                        </div>
                    </div>
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
                const readButton = event.target.closest('[data-admin-mark-read-notification]');
                if (!readButton) return;

                const notificationId = Number(readButton.getAttribute('data-notification-id') || 0);
                readButton.disabled = true;
                readButton.classList.add('opacity-70');

                const readAt = await markAdminNotificationAsRead(notificationId);
                if (readAt) {
                    adminNotificationState.items = adminNotificationState.items.filter((notification) => Number(notification?.id || 0) !== notificationId);
                    renderAdminNotificationList();
                } else {
                    readButton.disabled = false;
                    readButton.classList.remove('opacity-70');
                }
            });
        }

        function setupAdminProfileMenu() {
            const root = document.querySelector('[data-admin-profile-root]');
            const toggle = document.querySelector('[data-admin-profile-toggle]');
            const dropdown = document.querySelector('[data-admin-profile-dropdown]');
            if (!root || !toggle || !dropdown) return;

            const closeDropdown = () => {
                dropdown.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
            };

            const openDropdown = () => {
                dropdown.classList.remove('hidden');
                toggle.setAttribute('aria-expanded', 'true');
            };

            toggle.addEventListener('click', (event) => {
                event.stopPropagation();
                const isOpen = !dropdown.classList.contains('hidden');
                if (isOpen) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
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
        }

        function updateAdminPermissionUi() {
            const permissionState = document.querySelector('[data-admin-notification-permission-state]');
            const enableButton = document.querySelector('[data-admin-enable-notifications]');
            const permissionHelp = document.querySelector('[data-admin-notification-permission-help]');
            if (!permissionState || !enableButton) return;

            if (!('Notification' in window)) {
                permissionState.textContent = 'Browser machtiging: niet ondersteund';
                enableButton.classList.add('hidden');
                permissionHelp?.classList.add('hidden');
                return;
            }

            if (Notification.permission === 'granted') {
                permissionState.textContent = 'Browser machtiging: toegestaan';
                enableButton.classList.add('hidden');
                permissionHelp?.classList.add('hidden');
                return;
            }

            if (Notification.permission === 'denied') {
                permissionState.textContent = 'Browser machtiging: geblokkeerd';
                enableButton.textContent = 'Opnieuw vragen';
                enableButton.classList.remove('hidden');
                permissionHelp?.classList.remove('hidden');
                return;
            }

            permissionState.textContent = 'Browser machtiging: nog niet gegeven';
            enableButton.textContent = 'Meldingen toestaan';
            enableButton.classList.remove('hidden');
            permissionHelp?.classList.add('hidden');
        }

        async function requestAdminNotificationPermission() {
            if (!('Notification' in window)) return;

            try {
                const permission = await Notification.requestPermission();
                updateAdminPermissionUi();

                if (permission === 'granted') {
                    await subscribeForBackgroundPush();
                } else if (permission === 'denied') {
                    const permissionHelp = document.querySelector('[data-admin-notification-permission-help]');
                    permissionHelp?.classList.remove('hidden');
                }
            } catch (error) {
                console.warn('Admin notification permission request failed', error);
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

                    if (typeof payload.latest_user_notification_id === 'number' && payload.latest_user_notification_id < lastNotificationId) {
                        lastNotificationId = payload.latest_user_notification_id;
                        localStorage.setItem(adminRealtimeStorageKey, String(lastNotificationId));
                        hasExistingCursor = lastNotificationId > 0;
                    }

                    updateAdminUnreadBadge(payload.unread_count || 0);

                    const notifications = Array.isArray(payload.notifications) ? payload.notifications : [];
                    const unreadNotifications = Array.isArray(payload.unread_notifications) ? payload.unread_notifications : [];
                    if (!adminNotificationState.hydratedFromUnread && unreadNotifications.length > 0) {
                        prependAdminNotificationItems(unreadNotifications);
                        adminNotificationState.hydratedFromUnread = true;
                    }

                    if (!hasExistingCursor && typeof payload.latest_user_notification_id === 'number') {
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
                if (!document.documentElement.classList.contains('is-native-app')) {
                    navigator.serviceWorker.register('/sw.js').catch(() => {});
                }
            }

            setupAdminNotificationBell();
            setupAdminProfileMenu();
            updateAdminPermissionUi();
            const enableNotificationsButton = document.querySelector('[data-admin-enable-notifications]');
            if (enableNotificationsButton) {
                enableNotificationsButton.addEventListener('click', requestAdminNotificationPermission);
            }

            if ('Notification' in window && Notification.permission === 'granted') {
                if (!document.documentElement.classList.contains('is-native-app')) {
                    subscribeForBackgroundPush();
                }
            }

            startAdminRealtimePolling();

            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const mobileMenuPanel = document.getElementById('mobile-menu-panel');
            const closeMobileMenu = document.getElementById('close-mobile-menu');
            
            if (mobileMenuButton && mobileMenuOverlay && mobileMenuPanel) {
                function openMenu() {
                    mobileMenuOverlay.classList.remove('hidden');
                    mobileMenuOverlay.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                    requestAnimationFrame(() => {
                        mobileMenuPanel.classList.remove('translate-x-full');
                    });
                }

                function closeMenu() {
                    mobileMenuPanel.classList.add('translate-x-full');
                    mobileMenuOverlay.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                    setTimeout(() => {
                        if (mobileMenuOverlay.getAttribute('aria-hidden') === 'true') {
                            mobileMenuOverlay.classList.add('hidden');
                        }
                    }, 300);
                }

                mobileMenuButton.addEventListener('click', openMenu);

                if (closeMobileMenu) closeMobileMenu.addEventListener('click', closeMenu);

                const backdrop = document.getElementById('mobile-menu-backdrop');
                if (backdrop) backdrop.addEventListener('click', closeMenu);

                const mobileNavLinks = mobileMenuOverlay.querySelectorAll('nav a');
                mobileNavLinks.forEach(link => link.addEventListener('click', closeMenu));

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && mobileMenuOverlay.getAttribute('aria-hidden') === 'false') {
                        closeMenu();
                    }
                });
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
    @if(session('onboarding_completed'))
        <x-onboarding-completed-celebration :data="session('onboarding_completed')" />
    @endif
    @if(!empty($adminHelp['enabled']))
        <x-admin-onboarding-tour :admin-help="$adminHelp" />
    @elseif(!empty($onboarding['active']))
        <x-admin-onboarding-tour :onboarding="$onboarding" />
    @endif
    @stack('scripts')
    @include('partials.page-transitions')
</body>
</html>
