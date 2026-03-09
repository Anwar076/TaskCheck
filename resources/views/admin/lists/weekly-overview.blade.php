@extends('layouts.admin')

@section('page-title', 'Weekoverzicht')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        @php
            $totalSubmissions = collect($overview ?? [])->sum('total_submissions');
            $totalCompleted = collect($overview ?? [])->sum('completed');
            $totalInProgress = collect($overview ?? [])->sum('in_progress');
            $totalRejected = collect($overview ?? [])->sum('rejected');
            $completionRate = $totalSubmissions > 0 ? round(($totalCompleted / $totalSubmissions) * 100, 1) : 0;
            $avgTasksPerEmployee = count($overview ?? []) > 0 ? round($totalSubmissions / count($overview), 1) : 0;
            $productivityScore = $completionRate > 80 ? 'Uitstekend' : ($completionRate > 60 ? 'Goed' : ($completionRate > 40 ? 'Matig' : 'Verbetering nodig'));
            $totalEmployees = count($overview ?? []);
            $activeEmployees = collect($overview ?? [])->where('total_submissions', '>', 0)->count();
            $todaySubmissions = \App\Models\Submission::whereDate('created_at', now())->count();
            $yesterdaySubmissions = \App\Models\Submission::whereDate('created_at', now()->subDay())->count();
            $growthRate = $yesterdaySubmissions > 0 ? round((($todaySubmissions - $yesterdaySubmissions) / $yesterdaySubmissions) * 100, 1) : 0;
            $weeklyTotal = \App\Models\Submission::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $lastWeekTotal = \App\Models\Submission::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
            $weeklyGrowth = $lastWeekTotal > 0 ? round((($weeklyTotal - $lastWeekTotal) / $lastWeekTotal) * 100, 1) : 0;
            $weeklyData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $daySubmissions = \App\Models\Submission::whereDate('created_at', $date)->count();
                $dayCompleted = \App\Models\Submission::whereDate('created_at', $date)->where('status', 'completed')->count();
                $weeklyData[] = [
                    'date' => $date->translatedFormat('d M'),
                    'submissions' => $daySubmissions,
                    'completed' => $dayCompleted,
                    'rate' => $daySubmissions > 0 ? round(($dayCompleted / $daySubmissions) * 100, 1) : 0
                ];
            }
        @endphp

        {{-- Hero --}}
        <div class="mb-6 sm:mb-8">
            <div class="relative rounded-2xl shadow-xl overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-400/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                <div class="relative px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex items-start gap-4 sm:gap-5">
                            <div class="flex-shrink-0 w-14 h-14 sm:w-16 sm:h-16 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20">
                                <svg class="w-8 h-8 sm:w-9 sm:h-9 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-xs font-medium text-white bg-white/15 border border-white/20">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                        Live
                                    </span>
                                    <span class="text-blue-100/90 text-xs sm:text-sm">{{ now()->translatedFormat('d M Y') }}</span>
                                </div>
                                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white tracking-tight">Weekoverzicht KPI</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-1">Prestatie-inzichten en analytics in realtime</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                            <div class="flex items-center gap-4 bg-white/10 backdrop-blur-sm rounded-2xl px-5 py-4 border border-white/10">
                                <div class="relative w-16 h-16 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                        <path class="text-white/20" stroke="currentColor" stroke-width="2" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                        <path class="text-white" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="{{ $completionRate }},100" stroke-linecap="round" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    </svg>
                                    <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-white leading-none">{{ $completionRate }}%</span>
                                </div>
                                <div>
                                    <p class="text-white font-semibold">Voltooiing</p>
                                    <p class="text-blue-100/80 text-sm">{{ $totalCompleted }} van {{ $totalSubmissions }} afgerond</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.lists.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors shadow-lg shadow-black/10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                                Naar lijsten
                            </a>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mt-6 sm:mt-8">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 sm:p-5 border border-white/10 hover:bg-white/15 transition-colors">
                            <p class="text-2xl sm:text-3xl font-bold text-white">{{ number_format($totalSubmissions) }}</p>
                            <p class="text-blue-100/90 text-sm mt-0.5">Totaal taken</p>
                            @if($growthRate != 0)<p class="text-xs mt-2 font-medium {{ $growthRate > 0 ? 'text-emerald-300' : 'text-red-300' }}">{{ $growthRate > 0 ? '↑' : '↓' }} {{ abs($growthRate) }}% t.o.v. gisteren</p>@endif
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 sm:p-5 border border-white/10 hover:bg-white/15 transition-colors">
                            <p class="text-2xl sm:text-3xl font-bold text-white">{{ $activeEmployees }}</p>
                            <p class="text-blue-100/90 text-sm mt-0.5">Actieve gebruikers</p>
                            <p class="text-xs text-blue-200/90 mt-2">van {{ $totalEmployees }} totaal</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 sm:p-5 border border-white/10 hover:bg-white/15 transition-colors">
                            <p class="text-2xl sm:text-3xl font-bold text-white">{{ $avgTasksPerEmployee }}</p>
                            <p class="text-blue-100/90 text-sm mt-0.5">Taken per gebruiker</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 sm:p-5 border border-white/10 hover:bg-white/15 transition-colors">
                            <p class="text-xl sm:text-2xl font-bold text-white">{{ $productivityScore }}</p>
                            <p class="text-blue-100/90 text-sm mt-0.5">Prestatie</p>
                            @if($weeklyGrowth != 0)<p class="text-xs mt-2 font-medium {{ $weeklyGrowth > 0 ? 'text-emerald-300' : 'text-red-300' }}">{{ abs($weeklyGrowth) }}% vs vorige week</p>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Periode --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-6 mb-6 sm:mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 mb-1">Periode kiezen</h3>
                    <p class="text-slate-600 text-sm">Selecteer de periode voor de analytics</p>
                </div>
                <form method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <input type="date" name="start_date" value="{{ isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('Y-m-d') : now()->startOfWeek()->format('Y-m-d') }}" class="px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <span class="text-slate-500 text-sm">t/m</span>
                        <input type="date" name="end_date" value="{{ isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('Y-m-d') : now()->endOfWeek()->format('Y-m-d') }}" class="px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        Vernieuwen
                    </button>
                </form>
            </div>
        </div>
        
        {{-- KPI kaarten --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($totalSubmissions) }}</p>
                        <p class="text-sm text-slate-600">Totaal taken</p>
                        <p class="text-xs text-slate-500">{{ $todaySubmissions }} vandaag</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $completionRate }}%</p>
                        <p class="text-sm text-slate-600">Voltooiingspercentage</p>
                        <p class="text-xs text-slate-500">{{ $totalCompleted }} afgerond</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-violet-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $activeEmployees }}</p>
                        <p class="text-sm text-slate-600">Actieve gebruikers</p>
                        <p class="text-xs text-slate-500">van {{ $totalEmployees }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $productivityScore }}</p>
                        <p class="text-sm text-slate-600">Prestatie</p>
                        <p class="text-xs text-slate-500">{{ count($lists ?? []) }} actieve lijsten</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafieken --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Weektrend</h3>
                        <p class="text-slate-600 text-sm">Laatste 7 dagen</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    </div>
                </div>
                <div class="h-72 sm:h-80">
                    <canvas id="weeklyTrendChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Statusverdeling</h3>
                        <p class="text-slate-600 text-sm">Verdeling per status</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                    </div>
                </div>
                <div class="h-72 sm:h-80">
                    <canvas id="statusDistributionChart"></canvas>
                </div>
            </div>
        </div>

        @if(isset($overview) && count($overview) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-6 mb-6 sm:mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Prestaties medewerkers</h3>
                    <p class="text-slate-600 text-sm">Individuele prestaties en voltooiingspercentages</p>
                </div>
                <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($overview as $data)
                    @php
                        $empRate = $data['completion_rate'];
                        $avatarBg = $empRate >= 80 ? 'from-emerald-500 to-emerald-600' : ($empRate >= 60 ? 'from-blue-500 to-blue-600' : ($empRate >= 40 ? 'from-amber-500 to-amber-600' : 'from-red-500 to-red-600'));
                        $barBg = $empRate >= 80 ? 'bg-emerald-600' : ($empRate >= 60 ? 'bg-blue-600' : ($empRate >= 40 ? 'bg-amber-500' : 'bg-red-500'));
                    @endphp
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br {{ $avatarBg }} rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ substr($data['employee']->name, 0, 1) }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-slate-900">{{ $empRate }}%</p>
                                <p class="text-xs text-slate-500">Voltooid</p>
                            </div>
                        </div>
                        <p class="text-base font-semibold text-slate-900 truncate mb-1">{{ $data['employee']->name }}</p>
                        <p class="text-sm text-slate-500 mb-4">{{ $data['employee']->department ?? 'Geen afdeling' }}</p>
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div class="text-center p-2 bg-blue-50 rounded-lg">
                                <div class="text-lg font-bold text-blue-600">{{ $data['total_submissions'] }}</div>
                                <div class="text-xs text-slate-600">Totaal</div>
                            </div>
                            <div class="text-center p-2 bg-emerald-50 rounded-lg">
                                <div class="text-lg font-bold text-emerald-600">{{ $data['completed'] }}</div>
                                <div class="text-xs text-slate-600">Afgerond</div>
                            </div>
                            <div class="text-center p-2 bg-amber-50 rounded-lg">
                                <div class="text-lg font-bold text-amber-600">{{ $data['in_progress'] }}</div>
                                <div class="text-xs text-slate-600">Bezig</div>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full {{ $barBg }} rounded-full transition-all duration-500" style="width: {{ $empRate }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($lists) && count($lists) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Wekelijkse takenlijsten</h3>
                    <p class="text-slate-600 text-sm">Actieve wekelijkse takenlijsten</p>
                </div>
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($lists as $list)
                    @php
                        $priorityBadge = $list->priority === 'high' ? 'bg-red-100 text-red-800' : ($list->priority === 'medium' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800');
                        $priorityLabel = $list->priority === 'high' ? 'Hoog' : ($list->priority === 'medium' ? 'Normaal' : 'Laag');
                    @endphp
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="px-2.5 py-1 {{ $priorityBadge }} text-xs font-semibold rounded-lg">{{ $priorityLabel }}</span>
                        </div>
                        <h4 class="text-base font-semibold text-slate-900 mb-2 line-clamp-2">{{ $list->title }}</h4>
                        <p class="text-sm text-slate-600 mb-4 line-clamp-2">{{ Str::limit($list->description, 80) }}</p>
                        <div class="flex items-center gap-4 text-sm text-slate-500 mb-4">
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-blue-500 rounded-full"></span>{{ $list->tasks->count() }} taken</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span>{{ $list->assignments->count() }} toegewezen</span>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                            <span class="text-xs text-slate-500">{{ $list->created_at->diffForHumans() }}</span>
                            <a href="{{ route('admin.lists.show', $list) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Bekijken
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8 sm:p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 mb-2">Geen wekelijkse lijsten</h3>
            <p class="text-slate-600 mb-6 max-w-sm mx-auto">Maak wekelijkse takenlijsten aan om hier analytics te zien</p>
            <a href="{{ route('admin.lists.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Lijst aanmaken
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Chart.js Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Weekly Trend Chart
    const weeklyTrendCtx = document.getElementById('weeklyTrendChart');
    if (weeklyTrendCtx) {
        new Chart(weeklyTrendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(collect($weeklyData)->pluck('date')) !!},
                datasets: [{
                    label: 'Inzendingen',
                    data: {!! json_encode(collect($weeklyData)->pluck('submissions')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Afgerond',
                    data: {!! json_encode(collect($weeklyData)->pluck('completed')) !!},
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                }
            }
        });
    }

    // Status Distribution Chart
    const statusDistributionCtx = document.getElementById('statusDistributionChart');
    if (statusDistributionCtx) {
        new Chart(statusDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Afgerond', 'Bezig', 'Afgewezen'],
                datasets: [{
                    data: [{{ $totalCompleted }}, {{ $totalInProgress }}, {{ $totalRejected }}],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: false
                    }
                }
            }
        });
    }
});
</script>
@endsection