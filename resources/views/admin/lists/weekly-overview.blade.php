@extends('layouts.admin')

@section('page-title', 'Rapportages')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-10 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

        {{-- Hero --}}
        <div class="relative bg-white rounded-2xl sm:rounded-3xl shadow-[0_1px_2px_rgba(15,23,42,.04),0_12px_40px_rgba(15,23,42,.08)] border border-slate-100/80 overflow-hidden">
            <div class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-7 sm:py-9">
                <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
                    <div class="absolute -top-24 -right-16 w-72 h-72 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute -bottom-32 -left-20 w-80 h-80 rounded-full bg-indigo-400/20 blur-3xl"></div>
                </div>

                <div class="relative flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">
                    <div class="flex items-start gap-4 sm:gap-5">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white/15 backdrop-blur-md rounded-2xl flex items-center justify-center ring-1 ring-white/20 shrink-0">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-blue-200/80 text-xs sm:text-sm font-medium uppercase tracking-[0.14em] mb-1.5">Analytics</p>
                            <h1 class="text-2xl sm:text-3xl lg:text-[2rem] font-bold text-white tracking-tight">Rapportages</h1>
                            <p class="text-blue-100/85 text-sm sm:text-base mt-2">
                                {{ \Carbon\Carbon::parse($startDate)->locale('nl')->translatedFormat('d M Y') }}
                                t/m
                                {{ \Carbon\Carbon::parse($endDate)->locale('nl')->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md rounded-2xl px-5 py-4 ring-1 ring-white/15">
                            <div class="relative w-16 h-16 flex items-center justify-center shrink-0">
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-white/20" stroke="currentColor" stroke-width="2" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="text-white" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="{{ $summary['completion_rate'] }},100" stroke-linecap="round" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-white tabular-nums">{{ $summary['completion_rate'] }}%</span>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Voltooiing</p>
                                <p class="text-blue-100/80 text-sm">{{ $summary['finished'] }} van {{ $summary['total_lists'] }} lijsten afgerond</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.lists.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3.5 bg-white text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors shadow-lg shadow-blue-900/10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Naar lijsten
                        </a>
                    </div>
                </div>

                <div class="relative grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mt-6 sm:mt-8">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                        <p class="text-2xl sm:text-3xl font-bold text-white tabular-nums">{{ number_format($summary['total_lists']) }}</p>
                        <p class="text-blue-100/90 text-sm mt-0.5">Lijsten ingediend</p>
                        <p class="text-xs text-blue-200/90 mt-2">{{ $summary['today_lists'] }} vandaag</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                        <p class="text-2xl sm:text-3xl font-bold text-white tabular-nums">{{ $summary['active_employees'] }}</p>
                        <p class="text-blue-100/90 text-sm mt-0.5">Actieve medewerkers</p>
                        <p class="text-xs text-blue-200/90 mt-2">van {{ $summary['total_employees'] }} totaal</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                        <p class="text-2xl sm:text-3xl font-bold text-white tabular-nums">{{ $summary['avg_lists_per_employee'] }}</p>
                        <p class="text-blue-100/90 text-sm mt-0.5">Lijsten per medewerker</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                        <p class="text-xl sm:text-2xl font-bold text-white">{{ $summary['productivity_score'] }}</p>
                        <p class="text-blue-100/90 text-sm mt-0.5">Teamscore</p>
                        @if($summary['weekly_growth'] != 0)
                            <p class="text-xs mt-2 font-medium {{ $summary['weekly_growth'] > 0 ? 'text-emerald-300' : 'text-red-300' }}">{{ $summary['weekly_growth'] > 0 ? '↑' : '↓' }} {{ abs($summary['weekly_growth']) }}% vs vorige week</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Exports per takenlijst --}}
            <div class="px-4 sm:px-6 lg:px-8 py-5 border-t border-white/10 bg-white">
                <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Rapport maken</p>
                        <h2 class="text-lg font-bold text-slate-900 mt-1">Uitvoering van een specifieke takenlijst</h2>
                        <p class="text-sm text-slate-500 mt-1">Exporteer ruwe taakdata naar Excel of maak een leesbaar PDF-rapport over de gekozen periode.</p>
                    </div>
                    <form id="list-report-form" class="grid grid-cols-1 sm:grid-cols-[minmax(220px,1fr)_auto_auto] gap-2 w-full lg:w-auto">
                        <div>
                            <label for="report-list-id" class="sr-only">Takenlijst</label>
                            <select id="report-list-id" name="list_id" required class="w-full h-11 px-3.5 border border-slate-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                                <option value="">Kies een takenlijst…</option>
                                @foreach($reportLists as $reportList)
                                    <option value="{{ $reportList->id }}">{{ $reportList->title }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="start_date" value="{{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }}">
                            <input type="hidden" name="end_date" value="{{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}">
                        </div>
                        <button type="button" data-export-url="{{ route('admin.reports.export.excel') }}" class="report-export inline-flex h-11 items-center justify-center gap-2 px-4 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                            Excel ruwe data
                        </button>
                        <button type="button" data-export-url="{{ route('admin.reports.export.pdf') }}" class="report-export inline-flex h-11 items-center justify-center gap-2 px-4 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700">
                            PDF-rapport
                        </button>
                    </form>
                </div>
                <p id="report-list-error" class="hidden mt-2 text-sm text-red-600 text-right">Kies eerst een takenlijst.</p>
            </div>

            {{-- Filters --}}
            @php
                $periodPresets = [
                    ['label' => 'Deze week', 'start' => now()->startOfWeek()->format('Y-m-d'), 'end' => now()->endOfWeek()->format('Y-m-d')],
                    ['label' => 'Vorige week', 'start' => now()->subWeek()->startOfWeek()->format('Y-m-d'), 'end' => now()->subWeek()->endOfWeek()->format('Y-m-d')],
                    ['label' => 'Deze maand', 'start' => now()->startOfMonth()->format('Y-m-d'), 'end' => now()->endOfMonth()->format('Y-m-d')],
                ];
                $currentStart = \Carbon\Carbon::parse($startDate)->format('Y-m-d');
                $currentEnd = \Carbon\Carbon::parse($endDate)->format('Y-m-d');
            @endphp
            <form method="GET" class="px-4 sm:px-6 lg:px-8 py-4 sm:py-5 bg-slate-50/80 border-t border-white/10 space-y-4">
                <div class="flex flex-wrap gap-2">
                    @foreach($periodPresets as $preset)
                        @php $isActivePreset = $currentStart === $preset['start'] && $currentEnd === $preset['end']; @endphp
                        <a href="{{ route('admin.weekly-overview', array_filter(['start_date' => $preset['start'], 'end_date' => $preset['end'], 'location_id' => $selectedLocationId])) }}"
                           class="inline-flex items-center px-3.5 py-2 rounded-xl text-sm font-medium transition-colors {{ $isActivePreset ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/20' : 'bg-white text-slate-600 border border-slate-200 hover:border-blue-200 hover:text-blue-700' }}">
                            {{ $preset['label'] }}
                        </a>
                    @endforeach
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[1fr_1fr_1fr_auto] gap-3 items-end">
                    <div>
                        <label for="weekly-location-filter" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Locatie</label>
                        <select id="weekly-location-filter" name="location_id" class="w-full h-11 px-3.5 border border-slate-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                            <option value="">Alle locaties</option>
                            @foreach(collect($locations ?? []) as $location)
                                <option value="{{ $location->id }}" {{ (string) ($selectedLocationId ?? '') === (string) $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="weekly-start-date" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Van</label>
                        <input id="weekly-start-date" type="date" name="start_date" value="{{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }}" class="w-full h-11 px-3.5 border border-slate-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                    </div>
                    <div>
                        <label for="weekly-end-date" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Tot</label>
                        <input id="weekly-end-date" type="date" name="end_date" value="{{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}" class="w-full h-11 px-3.5 border border-slate-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 h-11 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-sm shadow-blue-600/20 transition-all w-full xl:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        Toepassen
                    </button>
                </div>
            </form>
        </div>

        {{-- KPI cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <div class="bg-white rounded-2xl border border-slate-100/80 p-5 shadow-[0_1px_2px_rgba(15,23,42,.04),0_8px_24px_rgba(15,23,42,.04)]">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Ingediend</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 tabular-nums">{{ number_format($summary['total_lists']) }}</p>
                <p class="text-sm text-slate-500 mt-1">Lijsten in periode</p>
            </div>
            <div class="bg-white rounded-2xl border border-emerald-100/80 p-5 shadow-[0_1px_2px_rgba(15,23,42,.04),0_8px_24px_rgba(15,23,42,.04)]">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Afgerond</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700 tabular-nums">{{ $summary['finished'] }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ $summary['reviewed'] }} goedgekeurd · {{ $summary['completed'] }} te beoordelen</p>
            </div>
            <div class="bg-white rounded-2xl border border-amber-100/80 p-5 shadow-[0_1px_2px_rgba(15,23,42,.04),0_8px_24px_rgba(15,23,42,.04)]">
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Bezig</p>
                <p class="mt-2 text-3xl font-bold text-amber-700 tabular-nums">{{ $summary['in_progress'] }}</p>
                <p class="text-sm text-slate-500 mt-1">Lijsten in uitvoering</p>
            </div>
            <div class="bg-white rounded-2xl border border-red-100/80 p-5 shadow-[0_1px_2px_rgba(15,23,42,.04),0_8px_24px_rgba(15,23,42,.04)]">
                <p class="text-xs font-semibold uppercase tracking-wider text-red-600">Afgewezen</p>
                <p class="mt-2 text-3xl font-bold text-red-700 tabular-nums">{{ $summary['rejected'] }}</p>
                <p class="text-sm text-slate-500 mt-1">Herziening nodig</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100/80 p-5 sm:p-6 shadow-[0_1px_2px_rgba(15,23,42,.04),0_8px_24px_rgba(15,23,42,.04)]">
                <div class="mb-4">
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Trend in periode</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Ingediende en afgeronde lijsten per dag</p>
                </div>
                <div class="h-72 sm:h-80">
                    <canvas id="weeklyTrendChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100/80 p-5 sm:p-6 shadow-[0_1px_2px_rgba(15,23,42,.04),0_8px_24px_rgba(15,23,42,.04)]">
                <div class="mb-4">
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Statusverdeling</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Verdeling over geselecteerde periode</p>
                </div>
                @if($summary['total_lists'] > 0)
                    <div class="h-72 sm:h-80">
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                @else
                    <div class="h-72 sm:h-80 flex flex-col items-center justify-center text-center px-6">
                        <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center mb-3 ring-1 ring-slate-100">
                            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-700">Nog geen data in deze periode</p>
                        <p class="text-sm text-slate-500 mt-1">Pas de periode aan of wacht op nieuwe inzendingen.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Employee performance --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100/80 p-5 sm:p-6 shadow-[0_1px_2px_rgba(15,23,42,.04),0_8px_24px_rgba(15,23,42,.04)]">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Team</p>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Prestaties medewerkers</h2>
                    <p class="text-sm text-slate-500 mt-1">Voltooiing per medewerker in geselecteerde periode</p>
                </div>
            </div>
        @if(count($overview) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($overview as $data)
                    @php
                        $empRate = $data['completion_rate'];
                        $avatarBg = $empRate >= 80 ? 'from-emerald-500 to-emerald-600' : ($empRate >= 50 ? 'from-blue-500 to-blue-600' : ($empRate > 0 ? 'from-amber-500 to-amber-600' : 'from-slate-400 to-slate-500'));
                        $barBg = $empRate >= 80 ? 'from-emerald-500 to-emerald-400' : ($empRate >= 50 ? 'from-blue-600 to-blue-500' : ($empRate > 0 ? 'from-amber-500 to-amber-400' : 'from-slate-400 to-slate-300'));
                    @endphp
                    <a href="{{ route('admin.users.show', $data['employee']) }}" class="group block rounded-2xl border border-slate-100 bg-slate-50/50 p-5 hover:bg-white hover:border-blue-100 hover:shadow-[0_4px_20px_rgba(37,99,235,.08)] transition-all duration-300">
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-12 h-12 bg-gradient-to-br {{ $avatarBg }} rounded-xl flex items-center justify-center shrink-0">
                                    <span class="text-white font-bold text-lg">{{ mb_strtoupper(mb_substr($data['employee']->name, 0, 1)) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-900 truncate group-hover:text-blue-700 transition-colors">{{ $data['employee']->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $data['employee']->department ?? 'Geen afdeling' }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-2xl font-bold text-slate-900 tabular-nums">{{ $empRate }}%</p>
                                <p class="text-[11px] uppercase tracking-wide text-slate-400 font-semibold">Voltooid</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-2 mb-4">
                            <div class="text-center rounded-lg bg-white border border-slate-100 px-1.5 py-2">
                                <div class="text-base font-bold text-slate-800 tabular-nums">{{ $data['total_submissions'] }}</div>
                                <div class="text-[10px] text-slate-500">Totaal</div>
                            </div>
                            <div class="text-center rounded-lg bg-white border border-slate-100 px-1.5 py-2">
                                <div class="text-base font-bold text-emerald-600 tabular-nums">{{ $data['finished'] }}</div>
                                <div class="text-[10px] text-slate-500">Klaar</div>
                            </div>
                            <div class="text-center rounded-lg bg-white border border-slate-100 px-1.5 py-2">
                                <div class="text-base font-bold text-amber-600 tabular-nums">{{ $data['in_progress'] }}</div>
                                <div class="text-[10px] text-slate-500">Bezig</div>
                            </div>
                            <div class="text-center rounded-lg bg-white border border-slate-100 px-1.5 py-2">
                                <div class="text-base font-bold text-red-600 tabular-nums">{{ $data['rejected'] }}</div>
                                <div class="text-[10px] text-slate-500">Afgekeurd</div>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/80 rounded-full h-2 overflow-hidden">
                            <div class="h-full bg-gradient-to-r {{ $barBg }} rounded-full transition-all duration-500" style="width: {{ min($empRate, 100) }}%"></div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 px-6 py-10 text-center">
                <p class="text-sm font-medium text-slate-700">Geen medewerkeractiviteit in deze periode</p>
                <p class="text-sm text-slate-500 mt-1 max-w-lg mx-auto">Er zijn nog geen lijsten ingediend door medewerkers. Controleer toewijzingen of kies een andere periode.</p>
            </div>
        @endif
        </div>

        {{-- Lists --}}
        @if($lists->count() > 0)
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100/80 p-5 sm:p-6 shadow-[0_1px_2px_rgba(15,23,42,.04),0_8px_24px_rgba(15,23,42,.04)]">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Lijsten</p>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Meest gebruikte takenlijsten</h2>
                <p class="text-sm text-slate-500 mt-1">Actieve lijsten met activiteit in deze periode</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($lists as $list)
                    @php
                        $priorityBadge = $list->priority === 'high' ? 'bg-red-100 text-red-800 ring-red-100' : ($list->priority === 'medium' ? 'bg-amber-100 text-amber-800 ring-amber-100' : 'bg-emerald-100 text-emerald-800 ring-emerald-100');
                        $priorityLabel = $list->priority === 'high' ? 'Hoog' : ($list->priority === 'medium' ? 'Normaal' : 'Laag');
                    @endphp
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-5 hover:bg-white hover:border-blue-100 hover:shadow-[0_4px_20px_rgba(37,99,235,.08)] transition-all duration-300">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="w-11 h-11 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="inline-flex px-2.5 py-1 {{ $priorityBadge }} text-xs font-semibold rounded-lg ring-1">{{ $priorityLabel }}</span>
                        </div>
                        <h3 class="font-semibold text-slate-900 line-clamp-2 mb-1">{{ $list->title }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ Str::limit($list->description, 90) ?: 'Geen omschrijving' }}</p>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 mb-4">
                            <span>{{ $list->tasks->count() }} taken</span>
                            <span>{{ $list->assignments->count() }} toegewezen</span>
                            <span class="font-semibold text-blue-700">{{ $list->period_submissions_count }} ingediend</span>
                        </div>
                        <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
                            <span class="text-xs text-slate-500 capitalize">{{ str_replace('_', ' ', $list->schedule_type) }}</span>
                            <a href="{{ route('admin.lists.show', $list) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                                Bekijken
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100/80 p-10 sm:p-12 text-center shadow-[0_1px_2px_rgba(15,23,42,.04),0_8px_24px_rgba(15,23,42,.04)]">
            <div class="w-16 h-16 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 ring-1 ring-slate-100">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 mb-2">Geen lijstactiviteit</h3>
            <p class="text-slate-600 mb-6 max-w-md mx-auto">Er zijn geen ingediende lijsten in deze periode. Pas de datums aan of wijs lijsten toe aan medewerkers.</p>
            <a href="{{ route('admin.lists.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                Lijst aanmaken
            </a>
        </div>
        @endif
    </div>
</div>
<script>
document.querySelectorAll('.report-export').forEach(button => {
    button.addEventListener('click', () => {
        const form = document.getElementById('list-report-form');
        const list = document.getElementById('report-list-id');
        const error = document.getElementById('report-list-error');
        if (!list.value) {
            error.classList.remove('hidden');
            list.focus();
            return;
        }
        error.classList.add('hidden');
        window.location.href = button.dataset.exportUrl + '?' + new URLSearchParams(new FormData(form)).toString();
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartLabels = @json(collect($chartData)->pluck('date'));
    const chartSubmissions = @json(collect($chartData)->pluck('submissions'));
    const chartFinished = @json(collect($chartData)->pluck('finished'));

    const weeklyTrendCtx = document.getElementById('weeklyTrendChart');
    if (weeklyTrendCtx) {
        new Chart(weeklyTrendCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Ingediend',
                    data: chartSubmissions,
                    borderColor: 'rgb(37, 99, 235)',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2,
                }, {
                    label: 'Afgerond',
                    data: chartFinished,
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { grid: { display: false } },
                },
            },
        });
    }

    const statusDistributionCtx = document.getElementById('statusDistributionChart');
    @if($summary['total_lists'] > 0)
    if (statusDistributionCtx) {
        new Chart(statusDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Goedgekeurd', 'Te beoordelen', 'Bezig', 'Afgewezen'],
                datasets: [{
                    data: [
                        {{ $summary['reviewed'] }},
                        {{ $summary['completed'] }},
                        {{ $summary['in_progress'] }},
                        {{ $summary['rejected'] }},
                    ],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(59, 130, 246, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(239, 68, 68, 0.85)',
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: { legend: { position: 'bottom' } },
            },
        });
    }
    @endif
});
</script>
@endsection
