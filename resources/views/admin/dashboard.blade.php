@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

        {{-- Hero sectie --}}
        <div id="quickstart-admin-hero" class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                                </svg>
                            </div>
                            <div>
                                @php
                                    $nowNl = now('Europe/Amsterdam')->locale('nl');
                                    $hourNl = (int) $nowNl->format('G');
                                    $greeting = $hourNl < 12 ? 'morgen' : ($hourNl < 17 ? 'middag' : 'avond');
                                @endphp
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">
                                    Goede{{ $greeting }},
                                    <span class="text-blue-100">{{ explode(' ', auth()->user()->name ?? 'Beheerder')[0] }}</span>
                                </h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">
                                    {{ $nowNl->translatedFormat('l, j F Y') }}
                                    <span class="ml-2 font-medium" id="dashboard-time">{{ $nowNl->format('H:i') }}</span>
                                </p>
                            </div>
                        </div>
                        @php $company = auth()->user()->company; @endphp
                        @if($company)
                        <div class="flex items-center gap-2 px-5 py-3 bg-white/20 backdrop-blur rounded-xl">
                            @if($company->logo_path)
                                <img src="{{ Storage::url($company->logo_path) }}" alt="{{ $company->name }}" class="h-14 sm:h-16 w-auto max-w-[200px] sm:max-w-[240px] object-contain">
                            @else
                                <span class="text-white font-bold text-base sm:text-lg">{{ $company->name }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6 sm:mb-8">
            <form method="GET" class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex flex-col md:flex-row md:items-end gap-3">
                    <div>
                        <label for="dashboard-location-filter" class="block text-sm font-medium text-slate-700 mb-1.5">Locatie filter</label>
                        <select id="dashboard-location-filter" name="location_id" class="w-full md:min-w-[280px] md:w-[340px] px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                            <option value="">Alle locaties</option>
                            @foreach(collect($locations ?? []) as $location)
                                <option value="{{ $location->id }}" {{ (string) ($selectedLocationId ?? '') === (string) $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors w-full md:w-auto md:self-end">
                        Toepassen
                    </button>
                </div>
            </form>
        </div>

        {{-- KPI-kaarten --}}
        <div id="quickstart-admin-kpis" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
            <a href="{{ route('admin.users.index') }}" class="group bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-5 hover:shadow-lg hover:border-blue-100 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $stats['total_employees'] }}</p>
                <p class="text-sm text-slate-600 mt-1">Medewerkers</p>
            </a>

            <a href="{{ route('admin.lists.index') }}" class="group bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-5 hover:shadow-lg hover:border-green-100 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-50 rounded-xl flex items-center justify-center group-hover:bg-green-100 transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-green-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $stats['total_lists'] }}</p>
                <p class="text-sm text-slate-600 mt-1">Takenlijsten</p>
            </a>

            <a href="{{ route('admin.submissions.index') }}" class="group bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-5 hover:shadow-lg hover:border-amber-100 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 rounded-xl flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                        </svg>
                    </div>
                    @if($stats['pending_submissions'] > 0)
                    <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-1.5 rounded-full text-xs font-bold text-white bg-amber-500">
                        {{ $stats['pending_submissions'] > 99 ? '99+' : $stats['pending_submissions'] }}
                    </span>
                    @else
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-amber-400 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                    @endif
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $stats['pending_submissions'] }}</p>
                <p class="text-sm text-slate-600 mt-1">Wacht op beoordeling</p>
            </a>

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $stats['completed_today'] }}</p>
                <p class="text-sm text-slate-600 mt-1">Vandaag voltooid</p>
            </div>
        </div>

        {{-- Hoofdcontent grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            {{-- Team overzicht + recente activiteit --}}
            <div id="quickstart-admin-team" class="lg:col-span-2 bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Teamoverzicht</h2>
                            <p class="text-slate-600 text-sm mt-0.5">Recente inzendingen en realtime overzicht</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="border-b border-slate-100">
                    <nav class="flex">
                        <button type="button" id="recent-tab" class="tab-btn flex-1 px-4 sm:px-6 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600">
                            Recente activiteit
                        </button>
                        <button type="button" id="live-tab" class="tab-btn flex-1 px-4 sm:px-6 py-3 text-sm font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-700">
                            <span class="hidden sm:inline">Realtime overzicht</span>
                            <span class="sm:ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="animate-pulse w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>Realtime
                            </span>
                        </button>
                    </nav>
                </div>

                <div class="p-4 sm:p-6">
                    <div id="recent-content" class="tab-panel">
                        <div class="space-y-3 sm:space-y-4">
                            @forelse($recentSubmissions as $submission)
                                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100/80 transition-colors">
                                    <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-bold text-sm sm:text-base">{{ substr($submission->user->name ?? '?', 0, 1) }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-900 truncate">{{ $submission->taskList->title ?? 'Lijst' }}</p>
                                            <p class="text-sm text-slate-500 flex items-center gap-2 flex-wrap">
                                                <span>{{ $submission->user->name ?? '-' }}</span>
                                                <span class="text-slate-300">•</span>
                                                <span>{{ $submission->created_at->locale('nl')->diffForHumans() }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-medium
                                            @if($submission->status === 'completed') bg-amber-100 text-amber-800
                                            @elseif($submission->status === 'reviewed') bg-emerald-100 text-emerald-800
                                            @else bg-slate-100 text-slate-700 @endif">
                                            {{ $submission->status === 'completed' ? 'Wacht op beoordeling' : ($submission->status === 'reviewed' ? 'Goedgekeurd' : ucfirst($submission->status)) }}
                                        </span>
                                        <a href="{{ route('admin.submissions.show', $submission) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            Beoordelen
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 px-4">
                                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                                        </svg>
                                    </div>
                                    <p class="text-slate-700 font-semibold">Geen recente activiteit</p>
                                    <p class="text-slate-500 text-sm mt-1">Inzendingen verschijnen hier zodra medewerkers taken indienen</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div id="live-content" class="tab-panel hidden">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-slate-600">Laatst bijgewerkt: <strong id="last-update">Nu</strong></span>
                            <button type="button" id="refresh-live" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                                Ververs
                            </button>
                        </div>
                        <div id="live-monitoring-data">
                            <div class="text-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-2 border-blue-600 border-t-transparent mx-auto"></div>
                                <p class="text-slate-500 mt-2 text-sm">Laden...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Snelle acties --}}
            <div id="quickstart-admin-actions" class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Snelle acties</h2>
                                <p class="text-slate-600 text-sm">Beheer je werkruimte</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-5 space-y-3">
                    <a href="{{ route('admin.lists.create') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition-all group">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Nieuwe lijst</h3>
                            <p class="text-sm text-slate-600">Maak een takenlijst aan</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.submissions.index') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/50 transition-all group">
                        <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Inzendingen beoordelen</h3>
                            <p class="text-sm text-slate-600">Controleer en keur goed</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-violet-200 hover:bg-violet-50/50 transition-all group">
                        <div class="w-10 h-10 bg-violet-600 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Gebruiker toevoegen</h3>
                            <p class="text-sm text-slate-600">Nodig teamleden uit</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Teamprestaties (vandaag, live) --}}
        <div id="quickstart-admin-performance" class="mt-6 sm:mt-8 bg-white rounded-2xl sm:rounded-3xl shadow-[0_1px_2px_rgba(15,23,42,.04),0_8px_24px_rgba(15,23,42,.04)] border border-slate-100/80 overflow-hidden">
            <div class="px-5 sm:px-6 py-5 sm:py-6 border-b border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Live</p>
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-semibold ring-1 ring-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Vandaag
                            </span>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Teamprestaties</h2>
                        <p class="text-slate-500 text-sm mt-1">Voltooiing van toegewezen takenlijsten per medewerker</p>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-500">
                        <span>Bijgewerkt: <strong id="team-performance-updated" class="text-slate-800 font-semibold tabular-nums">{{ now()->format('H:i') }}</strong></span>
                        <button type="button" id="refresh-team-performance" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-semibold bg-slate-50 text-slate-700 border border-slate-200 hover:bg-white hover:border-blue-200 hover:text-blue-700 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                            Ververs
                        </button>
                    </div>
                </div>

                <div id="team-performance-summary" class="mt-5 grid grid-cols-2 lg:grid-cols-4 gap-3">
                    @php $summary = $teamPerformance['summary']; @endphp
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3">
                        <p class="text-xs font-medium text-slate-500">Lijsten vandaag</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900 tabular-nums" data-summary="finished">{{ $summary['finished_lists'] }}<span class="text-base font-semibold text-slate-400">/{{ $summary['total_lists'] }}</span></p>
                    </div>
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 px-4 py-3">
                        <p class="text-xs font-medium text-emerald-700">Team voortgang</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-700 tabular-nums" data-summary="rate">{{ $summary['completion_rate'] }}%</p>
                    </div>
                    <div class="rounded-xl border border-blue-100 bg-blue-50/60 px-4 py-3">
                        <p class="text-xs font-medium text-blue-700">Nu bezig</p>
                        <p class="mt-1 text-2xl font-bold text-blue-700 tabular-nums" data-summary="active">{{ $summary['active_now'] }}</p>
                    </div>
                    <div class="rounded-xl border border-amber-100 bg-amber-50/60 px-4 py-3">
                        <p class="text-xs font-medium text-amber-700">Te beoordelen</p>
                        <p class="mt-1 text-2xl font-bold text-amber-700 tabular-nums" data-summary="pending">{{ $summary['pending_review'] }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-6">
                <div id="team-performance-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @forelse($teamPerformance['employees'] as $employee)
                        @php
                            $rate = $employee['completion_rate'];
                            $avatarBg = $rate >= 80 ? 'from-emerald-500 to-emerald-600' : ($rate >= 50 ? 'from-blue-500 to-blue-600' : ($rate > 0 ? 'from-amber-500 to-amber-600' : 'from-slate-400 to-slate-500'));
                            $barBg = $rate >= 80 ? 'from-emerald-500 to-emerald-400' : ($rate >= 50 ? 'from-blue-600 to-blue-500' : ($rate > 0 ? 'from-amber-500 to-amber-400' : 'from-slate-400 to-slate-300'));
                        @endphp
                        <a href="{{ $employee['profile_url'] }}" class="group block rounded-2xl border border-slate-100 bg-slate-50/50 p-5 hover:bg-white hover:border-blue-100 hover:shadow-[0_4px_20px_rgba(37,99,235,.08)] transition-all duration-300">
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="relative w-12 h-12 bg-gradient-to-br {{ $avatarBg }} rounded-xl flex items-center justify-center shadow-sm shrink-0">
                                        <span class="text-white font-bold text-lg">{{ $employee['initials'] }}</span>
                                        @if($employee['is_active_now'])
                                            <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-400 ring-2 ring-white"></span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900 truncate group-hover:text-blue-700 transition-colors">{{ $employee['name'] }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $employee['department'] ?? 'Geen afdeling' }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-2xl font-bold text-slate-900 tabular-nums">{{ $rate }}%</p>
                                    <p class="text-[11px] uppercase tracking-wide text-slate-400 font-semibold">Voortgang</p>
                                </div>
                            </div>

                            @if($employee['is_active_now'] && $employee['current_list'])
                                <div class="mb-4 rounded-xl border border-blue-100 bg-blue-50/70 px-3 py-2.5">
                                    <div class="flex items-center justify-between gap-2 text-xs">
                                        <span class="font-semibold text-blue-800 truncate">Bezig: {{ $employee['current_list'] }}</span>
                                        <span class="text-blue-600 font-bold tabular-nums">{{ $employee['progress'] ?? 0 }}%</span>
                                    </div>
                                    <div class="mt-2 w-full bg-blue-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-600 to-blue-500 rounded-full" style="width: {{ min($employee['progress'] ?? 0, 100) }}%"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <div class="text-center rounded-lg bg-white border border-slate-100 px-2 py-2">
                                    <div class="text-lg font-bold text-emerald-600 tabular-nums">{{ $employee['finished_lists'] }}</div>
                                    <div class="text-[11px] text-slate-500">Klaar</div>
                                </div>
                                <div class="text-center rounded-lg bg-white border border-slate-100 px-2 py-2">
                                    <div class="text-lg font-bold text-blue-600 tabular-nums">{{ $employee['in_progress_lists'] }}</div>
                                    <div class="text-[11px] text-slate-500">Bezig</div>
                                </div>
                                <div class="text-center rounded-lg bg-white border border-slate-100 px-2 py-2">
                                    <div class="text-lg font-bold text-slate-700 tabular-nums">{{ $employee['open_lists'] }}</div>
                                    <div class="text-[11px] text-slate-500">Open</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-medium text-slate-700 tabular-nums">
                                    <span class="text-emerald-600">{{ $employee['finished_lists'] }}</span>
                                    <span class="text-slate-400">/</span>
                                    <span>{{ $employee['total_lists'] }}</span>
                                    <span class="text-slate-500 font-normal"> lijsten</span>
                                </p>
                                @if($employee['pending_review'] > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">{{ $employee['pending_review'] }} te beoordelen</span>
                                @endif
                            </div>

                            <div class="mt-3 w-full bg-slate-200/80 rounded-full h-2 overflow-hidden">
                                <div class="h-full bg-gradient-to-r {{ $barBg }} rounded-full transition-all duration-500" style="width: {{ min($rate, 100) }}%"></div>
                            </div>
                        </a>
                    @empty
                        <div id="team-performance-empty" class="col-span-full text-center py-12 px-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 ring-1 ring-slate-100">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="text-slate-800 font-semibold">Geen lijsten voor vandaag</p>
                            <p class="text-slate-500 text-sm mt-1 max-w-md mx-auto">Wijs takenlijsten toe aan medewerkers om hier live voortgang te zien.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tab-btn.active { color: #2563eb; border-color: #2563eb; }
.tab-panel.hidden { display: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Klok
    function updateTime() {
        const el = document.getElementById('dashboard-time');
        if (el) {
            const now = new Date();
            el.textContent = now.toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit', timeZone: 'Europe/Amsterdam' });
        }
    }
    updateTime();
    setInterval(updateTime, 60000);

    // Tabs
    const recentTab = document.getElementById('recent-tab');
    const liveTab = document.getElementById('live-tab');
    const recentContent = document.getElementById('recent-content');
    const liveContent = document.getElementById('live-content');
    const refreshBtn = document.getElementById('refresh-live');
    const lastUpdateSpan = document.getElementById('last-update');
    let refreshInterval;

    function setTab(active) {
        [recentTab, liveTab].forEach(t => t.classList.remove('active', 'border-blue-600', 'text-blue-600'));
        [recentTab, liveTab].forEach(t => t.classList.add('border-transparent', 'text-slate-500'));
        recentContent.classList.add('hidden');
        liveContent.classList.add('hidden');
        if (active === 'recent') {
            recentTab.classList.add('active', 'border-blue-600', 'text-blue-600');
            recentTab.classList.remove('text-slate-500');
            recentContent.classList.remove('hidden');
            clearInterval(refreshInterval);
        } else {
            liveTab.classList.add('active', 'border-blue-600', 'text-blue-600');
            liveTab.classList.remove('text-slate-500');
            liveContent.classList.remove('hidden');
            loadLiveData();
            refreshInterval = setInterval(loadLiveData, 8000);
        }
    }

    recentTab.addEventListener('click', () => setTab('recent'));
    liveTab.addEventListener('click', () => setTab('live'));

    function loadLiveData() {
        const container = document.getElementById('live-monitoring-data');
        container.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-2 border-blue-600 border-t-transparent mx-auto"></div><p class="text-slate-500 mt-2 text-sm">Laden...</p></div>';

        const selectedLocationId = document.getElementById('dashboard-location-filter')?.value || '';
        const query = selectedLocationId ? `?location_id=${encodeURIComponent(selectedLocationId)}` : '';

        fetch(`/admin/live-monitoring${query}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.ok ? r.json() : Promise.reject(new Error('Netwerkfout. Controleer je verbinding.')))
            .then(data => {
                container.innerHTML = renderLiveData(data);
                if (lastUpdateSpan) lastUpdateSpan.textContent = new Date().toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            })
            .catch(err => {
                container.innerHTML = `<div class="text-center py-8"><p class="text-red-600 font-medium">Kon gegevens niet laden</p><p class="text-slate-500 text-sm mt-1">${err.message}</p><button onclick="loadLiveData()" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">Opnieuw proberen</button></div>`;
            });
    }

    function renderLiveData(data) {
        if (!data.activeSessions || data.activeSessions.length === 0) {
            return `<div class="text-center py-12"><div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3"><svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4.5"/></svg></div><p class="text-slate-700 font-medium">Geen actieve sessies</p><p class="text-slate-500 text-sm mt-1">Niemand is momenteel bezig met taken</p></div>`;
        }
        const statusStyles = { Working: { border: 'border-blue-500', badge: 'bg-blue-100 text-blue-800', label: 'Bezig' }, Active: { border: 'border-emerald-500', badge: 'bg-emerald-100 text-emerald-800', label: 'Actief' }, Idle: { border: 'border-amber-500', badge: 'bg-amber-100 text-amber-800', label: 'Inactief' }, Paused: { border: 'border-orange-500', badge: 'bg-orange-100 text-orange-800', label: 'Gepauzeerd' } };
        let html = '';
        if (data.summary) {
            html += `<div class="mb-4 p-4 bg-blue-50 rounded-xl border border-blue-100"><div class="flex justify-between items-center"><span class="font-semibold text-slate-900">${data.summary.active_users} actief</span><span class="text-xl font-bold text-blue-600">${data.summary.avg_progress || 0}%</span></div></div>`;
        }
        data.activeSessions.forEach(s => {
            const st = statusStyles[s.status] || { border: 'border-slate-400', badge: 'bg-slate-100 text-slate-800', label: s.status || '-' };
            const statusLabel = st.label || s.status;
            const teamBadge = s.is_team_submission
                ? '<span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-indigo-100 text-indigo-800 mr-2">Team</span>'
                : '';
            html += `<a href="/admin/submissions/${s.submission_id}" class="block p-4 bg-slate-50 rounded-xl hover:bg-slate-100 border-l-4 ${st.border} mb-3 transition-colors"><div class="flex justify-between items-start gap-3"><div><p class="font-semibold text-slate-900">${teamBadge}${s.task_list_title}</p><p class="text-sm text-slate-500">${s.user_name} • ${s.time_active || '-'}</p><div class="mt-2 flex items-center gap-2"><div class="flex-1 bg-slate-200 rounded-full h-2"><div class="bg-blue-500 h-2 rounded-full" style="width:${s.progress_percentage || 0}%"></div></div><span class="text-xs font-medium">${s.progress_percentage || 0}%</span></div></div><span class="text-xs px-2 py-1 rounded-lg font-medium ${st.badge}">${statusLabel}</span></div></a>`;
        });
        return html || '<p class="text-slate-500">Geen gegevens</p>';
    }

    refreshBtn?.addEventListener('click', function() {
        const orig = this.innerHTML;
        this.disabled = true;
        this.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full"></span> Laden...';
        loadLiveData();
        setTimeout(() => {
            this.disabled = false;
            this.innerHTML = orig;
        }, 1200);
    });

    // Teamprestaties (live)
    const teamPerformanceUpdated = document.getElementById('team-performance-updated');
    const teamPerformanceGrid = document.getElementById('team-performance-grid');
    const refreshTeamPerformanceBtn = document.getElementById('refresh-team-performance');
    let teamPerformanceInterval;

    function teamRateStyles(rate) {
        if (rate >= 80) return { avatar: 'from-emerald-500 to-emerald-600', bar: 'from-emerald-500 to-emerald-400' };
        if (rate >= 50) return { avatar: 'from-blue-500 to-blue-600', bar: 'from-blue-600 to-blue-500' };
        if (rate > 0) return { avatar: 'from-amber-500 to-amber-600', bar: 'from-amber-500 to-amber-400' };
        return { avatar: 'from-slate-400 to-slate-500', bar: 'from-slate-400 to-slate-300' };
    }

    function renderTeamPerformanceCard(employee) {
        const styles = teamRateStyles(employee.completion_rate);
        const activeBlock = employee.is_active_now && employee.current_list
            ? `<div class="mb-4 rounded-xl border border-blue-100 bg-blue-50/70 px-3 py-2.5">
                <div class="flex items-center justify-between gap-2 text-xs">
                    <span class="font-semibold text-blue-800 truncate">${employee.is_team_active ? 'Team bezig' : 'Bezig'}: ${employee.current_list}</span>
                    <span class="text-blue-600 font-bold tabular-nums">${employee.progress ?? 0}%</span>
                </div>
                <div class="mt-2 w-full bg-blue-100 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-600 to-blue-500 rounded-full" style="width:${Math.min(employee.progress ?? 0, 100)}%"></div>
                </div>
            </div>` : '';
        const pendingBadge = employee.pending_review > 0
            ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">${employee.pending_review} te beoordelen</span>`
            : '';

        return `<a href="${employee.profile_url}" class="group block rounded-2xl border border-slate-100 bg-slate-50/50 p-5 hover:bg-white hover:border-blue-100 hover:shadow-[0_4px_20px_rgba(37,99,235,.08)] transition-all duration-300">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="relative w-12 h-12 bg-gradient-to-br ${styles.avatar} rounded-xl flex items-center justify-center shadow-sm shrink-0">
                        <span class="text-white font-bold text-lg">${employee.initials}</span>
                        ${employee.is_active_now ? '<span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-400 ring-2 ring-white"></span>' : ''}
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 truncate group-hover:text-blue-700 transition-colors">${employee.name}</p>
                        <p class="text-xs text-slate-500 truncate">${employee.department || 'Geen afdeling'}</p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-2xl font-bold text-slate-900 tabular-nums">${employee.completion_rate}%</p>
                    <p class="text-[11px] uppercase tracking-wide text-slate-400 font-semibold">Voortgang</p>
                </div>
            </div>
            ${activeBlock}
            <div class="grid grid-cols-3 gap-2 mb-4">
                <div class="text-center rounded-lg bg-white border border-slate-100 px-2 py-2"><div class="text-lg font-bold text-emerald-600 tabular-nums">${employee.finished_lists}</div><div class="text-[11px] text-slate-500">Klaar</div></div>
                <div class="text-center rounded-lg bg-white border border-slate-100 px-2 py-2"><div class="text-lg font-bold text-blue-600 tabular-nums">${employee.in_progress_lists}</div><div class="text-[11px] text-slate-500">Bezig</div></div>
                <div class="text-center rounded-lg bg-white border border-slate-100 px-2 py-2"><div class="text-lg font-bold text-slate-700 tabular-nums">${employee.open_lists}</div><div class="text-[11px] text-slate-500">Open</div></div>
            </div>
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-medium text-slate-700 tabular-nums"><span class="text-emerald-600">${employee.finished_lists}</span><span class="text-slate-400">/</span><span>${employee.total_lists}</span><span class="text-slate-500 font-normal"> lijsten</span></p>
                ${pendingBadge}
            </div>
            <div class="mt-3 w-full bg-slate-200/80 rounded-full h-2 overflow-hidden">
                <div class="h-full bg-gradient-to-r ${styles.bar} rounded-full transition-all duration-500" style="width:${Math.min(employee.completion_rate, 100)}%"></div>
            </div>
        </a>`;
    }

    function renderTeamPerformance(data) {
        const summary = data.summary || {};
        const finishedEl = document.querySelector('[data-summary="finished"]');
        if (finishedEl) finishedEl.innerHTML = `${summary.finished_lists || 0}<span class="text-base font-semibold text-slate-400">/${summary.total_lists || 0}</span>`;
        document.querySelector('[data-summary="rate"]')?.replaceChildren(document.createTextNode(`${summary.completion_rate || 0}%`));
        document.querySelector('[data-summary="active"]')?.replaceChildren(document.createTextNode(String(summary.active_now || 0)));
        document.querySelector('[data-summary="pending"]')?.replaceChildren(document.createTextNode(String(summary.pending_review || 0)));

        if (!teamPerformanceGrid) return;

        if (!data.employees || data.employees.length === 0) {
            teamPerformanceGrid.innerHTML = `<div id="team-performance-empty" class="col-span-full text-center py-12 px-4">
                <div class="w-16 h-16 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 ring-1 ring-slate-100">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-slate-800 font-semibold">Geen lijsten voor vandaag</p>
                <p class="text-slate-500 text-sm mt-1 max-w-md mx-auto">Wijs takenlijsten toe aan medewerkers om hier live voortgang te zien.</p>
            </div>`;
            return;
        }

        teamPerformanceGrid.innerHTML = data.employees.map(renderTeamPerformanceCard).join('');
    }

    function loadTeamPerformance() {
        const selectedLocationId = document.getElementById('dashboard-location-filter')?.value || '';
        const query = selectedLocationId ? `?location_id=${encodeURIComponent(selectedLocationId)}` : '';

        return fetch(`/admin/team-performance${query}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.ok ? r.json() : Promise.reject(new Error('Kon teamprestaties niet laden.')))
            .then(data => {
                renderTeamPerformance(data);
                if (teamPerformanceUpdated) {
                    teamPerformanceUpdated.textContent = new Date().toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit' });
                }
            })
            .catch(() => {});
    }

    refreshTeamPerformanceBtn?.addEventListener('click', function() {
        const orig = this.innerHTML;
        this.disabled = true;
        loadTeamPerformance().finally(() => {
            this.disabled = false;
            this.innerHTML = orig;
        });
    });

    loadTeamPerformance();
    teamPerformanceInterval = setInterval(loadTeamPerformance, 30000);

});
</script>
@endsection
