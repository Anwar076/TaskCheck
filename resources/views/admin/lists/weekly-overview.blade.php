@extends('layouts.admin')

@section('content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced KPI Dashboard -->
        @php
            $totalSubmissions = collect($overview ?? [])->sum('total_submissions');
            $totalCompleted = collect($overview ?? [])->sum('completed');
            $totalInProgress = collect($overview ?? [])->sum('in_progress');
            $totalRejected = collect($overview ?? [])->sum('rejected');
            $completionRate = $totalSubmissions > 0 ? round(($totalCompleted / $totalSubmissions) * 100, 1) : 0;
            $avgTasksPerEmployee = count($overview ?? []) > 0 ? round($totalSubmissions / count($overview), 1) : 0;
            $productivityScore = $completionRate > 80 ? 'Excellent' : ($completionRate > 60 ? 'Good' : ($completionRate > 40 ? 'Fair' : 'Needs Improvement'));
            
            // Calculate additional KPIs
            $totalEmployees = count($overview ?? []);
            $activeEmployees = collect($overview ?? [])->where('total_submissions', '>', 0)->count();
            
            // Enhanced KPI calculations
            $todaySubmissions = \App\Models\Submission::whereDate('created_at', now())->count();
            $yesterdaySubmissions = \App\Models\Submission::whereDate('created_at', now()->subDay())->count();
            $growthRate = $yesterdaySubmissions > 0 ? round((($todaySubmissions - $yesterdaySubmissions) / $yesterdaySubmissions) * 100, 1) : 0;
            
            $weeklyTotal = \App\Models\Submission::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $lastWeekTotal = \App\Models\Submission::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
            $weeklyGrowth = $lastWeekTotal > 0 ? round((($weeklyTotal - $lastWeekTotal) / $lastWeekTotal) * 100, 1) : 0;
            
            // Weekly trend data
            $weeklyData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $daySubmissions = \App\Models\Submission::whereDate('created_at', $date)->count();
                $dayCompleted = \App\Models\Submission::whereDate('created_at', $date)->where('status', 'completed')->count();
                $weeklyData[] = [
                    'date' => $date->format('M j'),
                    'submissions' => $daySubmissions,
                    'completed' => $dayCompleted,
                    'rate' => $daySubmissions > 0 ? round(($dayCompleted / $daySubmissions) * 100, 1) : 0
                ];
            }
        @endphp

        <!-- Modern KPI Dashboard Header -->
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-xl p-8 mb-8 text-white relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-12 -translate-x-12"></div>
            
            <div class="relative">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">📊 Weekly KPI Dashboard</h2>
                        <p class="text-blue-100 text-lg">Real-time performance insights & analytics</p>
                        <div class="flex items-center space-x-4 mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-white/20 backdrop-blur-sm">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                Live Data
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-white/20 backdrop-blur-sm">
                                📅 {{ now()->format('M j, Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-6 lg:mt-0">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/30">
                            <div class="text-center">
                                <p class="text-3xl font-bold">{{ $completionRate }}%</p>
                                <p class="text-blue-100 text-sm mt-1">Overall Completion</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick KPI Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/30">
                        <div class="text-center">
                            <p class="text-2xl font-bold">{{ $totalSubmissions }}</p>
                            <p class="text-blue-100 text-sm">Total Tasks</p>
                            @if($growthRate != 0)
                                <p class="text-xs mt-1 {{ $growthRate > 0 ? 'text-green-300' : 'text-red-300' }}">
                                    {{ $growthRate > 0 ? '↗️' : '↘️' }} {{ abs($growthRate) }}%
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/30">
                        <div class="text-center">
                            <p class="text-2xl font-bold">{{ $activeEmployees }}</p>
                            <p class="text-blue-100 text-sm">Active Users</p>
                            <p class="text-xs text-blue-200 mt-1">of {{ $totalEmployees }} total</p>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/30">
                        <div class="text-center">
                            <p class="text-2xl font-bold">{{ $avgTasksPerEmployee }}</p>
                            <p class="text-blue-100 text-sm">Tasks/User</p>
                            <p class="text-xs text-blue-200 mt-1">Average</p>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/30">
                        <div class="text-center">
                            <p class="text-2xl font-bold">{{ $productivityScore }}</p>
                            <p class="text-blue-100 text-sm">Performance</p>
                            @if($weeklyGrowth != 0)
                                <p class="text-xs mt-1 {{ $weeklyGrowth > 0 ? 'text-green-300' : 'text-red-300' }}">
                                    {{ $weeklyGrowth > 0 ? '📈' : '📉' }} {{ abs($weeklyGrowth) }}% vs last week
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Selector -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">📅 Date Range Selection</h3>
                    <p class="text-gray-600">Select the period for analytics and insights</p>
                </div>
                <form method="GET" class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="date" name="start_date" value="{{ isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('Y-m-d') : now()->startOfWeek()->format('Y-m-d') }}" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        <span class="text-gray-500 text-sm">to</span>
                        <div class="relative">
                            <input type="date" name="end_date" value="{{ isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('Y-m-d') : now()->endOfWeek()->format('Y-m-d') }}" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Update Analytics
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Enhanced KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Tasks KPI -->
            <div class="group">
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 group-hover:border-blue-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            @if($growthRate != 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $growthRate > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $growthRate > 0 ? '↗️' : '↘️' }} {{ abs($growthRate) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalSubmissions) }}</p>
                        <p class="text-sm font-medium text-gray-600 mb-2">Total Tasks</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>📋 All submissions</span>
                            <span>{{ $todaySubmissions }} today</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completion Rate KPI -->
            <div class="group">
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 group-hover:border-green-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="w-12 h-12 relative">
                            <svg class="w-12 h-12 transform -rotate-90" viewBox="0 0 36 36">
                                <path class="text-gray-200" stroke="currentColor" stroke-width="3" fill="none" d="m18,2.0845 a 15.9155,15.9155 0 0,1 0,31.831 a 15.9155,15.9155 0 0,1 0,-31.831"/>
                                <path class="text-green-600" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="{{ $completionRate }},100" d="m18,2.0845 a 15.9155,15.9155 0 0,1 0,31.831 a 15.9155,15.9155 0 0,1 0,-31.831"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-xs font-bold text-green-600">{{ $completionRate }}%</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $completionRate }}%</p>
                        <p class="text-sm font-medium text-gray-600 mb-2">Completion Rate</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>✅ {{ $totalCompleted }} completed</span>
                            <span>⏳ {{ $totalInProgress }} pending</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Users KPI -->
            <div class="group">
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 group-hover:border-purple-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $totalEmployees > 0 ? round(($activeEmployees / $totalEmployees) * 100) : 0 }}% active
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $activeEmployees }}</p>
                        <p class="text-sm font-medium text-gray-600 mb-2">Active Users</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>👥 {{ $totalEmployees }} total</span>
                            <span>📊 {{ $avgTasksPerEmployee }} avg tasks</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Score KPI -->
            <div class="group">
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 group-hover:border-indigo-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            @if($weeklyGrowth != 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $weeklyGrowth > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $weeklyGrowth > 0 ? '📈' : '📉' }} {{ abs($weeklyGrowth) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $productivityScore }}</p>
                        <p class="text-sm font-medium text-gray-600 mb-2">Performance</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>📅 This week: {{ $weeklyTotal }}</span>
                            <span>⚡ {{ count($lists) }} active lists</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-6 lg:mb-8">
            <!-- Weekly Trend Chart -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">📈 Weekly Performance Trend</h3>
                        <p class="text-gray-600">Last 7 days completion rates</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="weeklyTrendChart"></canvas>
                </div>
            </div>

            <!-- Task Status Distribution -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">🎯 Task Status Distribution</h3>
                        <p class="text-gray-600">Current task status breakdown</p>
                    </div>
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="statusDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Employee Performance Analytics -->
        @if(isset($overview) && count($overview) > 0)
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 p-6 mb-6 lg:mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">👥 Employee Performance</h3>
                    <p class="text-gray-600">Individual performance metrics and completion rates</p>
                </div>
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
                
            <!-- Mobile Card View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($overview as $data)
                    @php
                        $completionRate = $data['completion_rate'];
                        $performanceColor = $completionRate >= 80 ? 'green' : ($completionRate >= 60 ? 'blue' : ($completionRate >= 40 ? 'yellow' : 'red'));
                    @endphp
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-{{ $performanceColor }}-500 to-{{ $performanceColor }}-600 rounded-xl flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-lg">{{ substr($data['employee']->name, 0, 1) }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-900">{{ $completionRate }}%</p>
                                <p class="text-xs text-gray-500">Completion Rate</p>
                            </div>
                        </div>
                        <div class="mb-4">
                            <p class="text-lg font-semibold text-gray-900 truncate">{{ $data['employee']->name }}</p>
                            <p class="text-sm text-gray-500">{{ $data['employee']->department ?? 'No Department' }}</p>
                        </div>
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-xl font-bold text-blue-600">{{ $data['total_submissions'] }}</div>
                                <div class="text-xs text-gray-600">Total</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-xl font-bold text-green-600">{{ $data['completed'] }}</div>
                                <div class="text-xs text-gray-600">Completed</div>
                            </div>
                            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                <div class="text-xl font-bold text-yellow-600">{{ $data['in_progress'] }}</div>
                                <div class="text-xs text-gray-600">Progress</div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="h-full bg-{{ $performanceColor }}-600 rounded-full transition-all duration-1000" style="width: {{ $completionRate }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Weekly Lists Overview -->
        @if(count($lists) > 0)
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">📋 Weekly Task Lists</h3>
                    <p class="text-gray-600">Currently active weekly scheduled lists</p>
                </div>
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>
                
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($lists as $list)
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 4h6m-6 4h6m-7-7V4a1 1 0 011-1h8a1 1 0 011 1v3M5 7a1 1 0 011-1h8a1 1 0 011 1v8a1 1 0 01-1 1H6a1 1 0 01-1-1V7z"></path>
                                </svg>
                            </div>
                            <span class="px-2 py-1 bg-{{ $list->priority == 'high' ? 'red' : ($list->priority == 'medium' ? 'yellow' : 'green') }}-100 text-{{ $list->priority == 'high' ? 'red' : ($list->priority == 'medium' ? 'yellow' : 'green') }}-800 text-xs font-semibold rounded-full">
                                {{ ucfirst($list->priority) }}
                            </span>
                        </div>
                        <div class="mb-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $list->title }}</h4>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($list->description, 80) }}</p>
                            
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                    <span class="text-gray-600">{{ $list->tasks->count() }} tasks</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    <span class="text-gray-600">{{ $list->assignments->count() }} assigned</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Created {{ $list->created_at->diffForHumans() }}</span>
                            <a href="{{ route('admin.lists.show', $list) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                View Details
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Weekly Lists Found</h3>
            <p class="text-gray-600 mb-4">Create weekly scheduled task lists to see analytics here</p>
            <a href="{{ route('admin.lists.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Weekly List
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
                    label: 'Submissions',
                    data: {!! json_encode(collect($weeklyData)->pluck('submissions')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Completed',
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
                labels: ['Completed', 'In Progress', 'Rejected'],
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