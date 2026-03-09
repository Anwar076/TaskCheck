@extends('layouts.admin')

@section('content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Clean Page Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
                        Weekly Analytics
                    </h1>
                    <p class="text-gray-600">Comprehensive performance insights and KPIs</p>
                    <div class="flex items-center space-x-4 text-sm text-gray-500 mt-2">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span>Live Data</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Updated: {{ now()->format('M j, Y g:i A') }}</span>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="w-16 h-16 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Selector -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Date Range Selection</h3>
                    <p class="text-gray-600">Select the period for analytics and insights</p>
                </div>
                <form method="GET" class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="date" name="start_date" value="{{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }}" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        <span class="text-gray-500 text-sm">to</span>
                        <div class="relative">
                            <input type="date" name="end_date" value="{{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}" 
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

        <!-- KPI Dashboard -->
        @php
            $totalSubmissions = collect($overview)->sum('total_submissions');
            $totalCompleted = collect($overview)->sum('completed');
            $totalInProgress = collect($overview)->sum('in_progress');
            $totalRejected = collect($overview)->sum('rejected');
            $completionRate = $totalSubmissions > 0 ? round(($totalCompleted / $totalSubmissions) * 100, 1) : 0;
            $avgTasksPerEmployee = count($overview) > 0 ? round($totalSubmissions / count($overview), 1) : 0;
            $productivityScore = $completionRate > 80 ? 'Excellent' : ($completionRate > 60 ? 'Good' : ($completionRate > 40 ? 'Fair' : 'Needs Improvement'));
            
            // Calculate additional KPIs
            $totalEmployees = count($overview);
            $activeEmployees = collect($overview)->where('total_submissions', '>', 0)->count();
            
            // Fix average completion time calculation
            $avgCompletionTime = 0;
            $completionTimes = [];
            foreach ($overview as $data) {
                if (isset($data['submissions']) && $data['submissions']->count() > 0) {
                    foreach ($data['submissions'] as $submission) {
                        if ($submission->completed_at && $submission->started_at) {
                            $completionTime = $submission->started_at->diffInHours($submission->completed_at);
                            $completionTimes[] = $completionTime;
                        }
                    }
                }
            }
            $avgCompletionTime = count($completionTimes) > 0 ? array_sum($completionTimes) / count($completionTimes) : 0;
            
            $onTimeRate = collect($overview)->where('on_time_rate', '>', 0)->avg('on_time_rate') ?? 0;
            $qualityScore = collect($overview)->where('quality_score', '>', 0)->avg('quality_score') ?? 0;
            
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
        
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6 lg:mb-8">
            <!-- Total Submissions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Tasks</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalSubmissions }}</p>
                    </div>
                </div>
            </div>

            <!-- Completion Rate -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $completionRate }}%</p>
                    </div>
                </div>
            </div>

            <!-- Active Employees -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Employees</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $activeEmployees }}</p>
                    </div>
                </div>
            </div>

            <!-- Average Completion Time -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Avg. Time</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @if($avgCompletionTime > 0)
                                @if($avgCompletionTime < 1)
                                    {{ round($avgCompletionTime * 60, 0) }}m
                                @else
                                    {{ round($avgCompletionTime, 1) }}h
                                @endif
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-6 lg:mb-8">
            <!-- Weekly Trend Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Weekly Performance Trend</h3>
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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Task Status Distribution</h3>
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

        <!-- Performance Metrics -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 lg:mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Performance Metrics</h3>
                    <p class="text-gray-600">Detailed analytics and key performance indicators</p>
                </div>
                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
                
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Completion Rate -->
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-green-600">Completion Rate</p>
                            <p class="text-lg font-bold text-gray-900">{{ $completionRate }}%</p>
                        </div>
                    </div>
                    <div class="w-full bg-green-100 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $completionRate }}%"></div>
                    </div>
                </div>

                <!-- Average Completion Time -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-blue-600">Avg. Time</p>
                            <p class="text-lg font-bold text-gray-900">
                                @if($avgCompletionTime > 0)
                                    @if($avgCompletionTime < 1)
                                        {{ round($avgCompletionTime * 60, 0) }}m
                                    @else
                                        {{ round($avgCompletionTime, 1) }}h
                                    @endif
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="w-full bg-blue-100 rounded-full h-2">
                        @if($avgCompletionTime > 0)
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(($avgCompletionTime / 8) * 100, 100) }}%"></div>
                        @else
                            <div class="bg-gray-300 h-2 rounded-full" style="width: 100%"></div>
                        @endif
                    </div>
                </div>

                <!-- On-Time Rate -->
                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-purple-600">On-Time Rate</p>
                            <p class="text-lg font-bold text-gray-900">{{ round($onTimeRate, 1) }}%</p>
                        </div>
                    </div>
                    <div class="w-full bg-purple-100 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $onTimeRate }}%"></div>
                    </div>
                </div>

                <!-- Quality Score -->
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 bg-yellow-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-yellow-600">Quality Score</p>
                            <p class="text-lg font-bold text-gray-900">{{ round($qualityScore, 1) }}/5</p>
                        </div>
                    </div>
                    <div class="w-full bg-yellow-100 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ ($qualityScore / 5) * 100 }}%"></div>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <!-- Employee Performance Analytics -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 lg:mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Employee Performance</h3>
                    <p class="text-gray-600">Individual performance metrics and completion rates</p>
                </div>
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
                
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tasks</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">In Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rejected</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Rate</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($overview as $data)
                            @php
                                $completionRate = $data['completion_rate'];
                                $performanceColor = $completionRate >= 80 ? 'green' : ($completionRate >= 60 ? 'blue' : ($completionRate >= 40 ? 'yellow' : 'red'));
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ substr($data['employee']->name, 0, 2) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-base font-semibold text-gray-900">{{ $data['employee']->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $data['employee']->department ?? 'No Department' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-base font-semibold text-gray-900">{{ $data['total_submissions'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                        {{ $data['completed'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $data['in_progress'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                        {{ $data['rejected'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="text-right">
                                            <div class="text-sm font-semibold text-gray-900">{{ $completionRate }}%</div>
                                        </div>
                                        <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-{{ $performanceColor }}-600 rounded-full" style="width: {{ $completionRate }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.users.show', $data['employee']) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No employee data available</h3>
                                    <p class="text-gray-600">Employee performance will appear here for the selected period</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($overview as $data)
                    @php
                        $completionRate = $data['completion_rate'];
                        $performanceColor = $completionRate >= 80 ? 'green' : ($completionRate >= 60 ? 'blue' : ($completionRate >= 40 ? 'yellow' : 'red'));
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ substr($data['employee']->name, 0, 2) }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900">{{ $completionRate }}%</p>
                                <p class="text-xs text-gray-500">Completion Rate</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <p class="text-base font-semibold text-gray-900 truncate">{{ $data['employee']->name }}</p>
                            <p class="text-sm text-gray-500">{{ $data['employee']->department ?? 'No Department' }}</p>
                        </div>
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <div class="text-center">
                                <div class="text-lg font-bold text-gray-900">{{ $data['total_submissions'] }}</div>
                                <div class="text-xs text-gray-500">Total</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-green-600">{{ $data['completed'] }}</div>
                                <div class="text-xs text-gray-500">Completed</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-yellow-600">{{ $data['in_progress'] }}</div>
                                <div class="text-xs text-gray-500">In Progress</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-green-600">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Performance</span>
                            </div>
                            <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-{{ $performanceColor }}-600 rounded-full" style="width: {{ $completionRate }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No employee data available</h3>
                        <p class="text-gray-600">Employee performance will appear here for the selected period</p>
                    </div>
                @endforelse
            </div>
            </div>
        </div>

        <!-- Daily Breakdown Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 lg:mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Daily Task Completion</h3>
                    <p class="text-gray-600">Task submission trends over the selected period</p>
                </div>
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
                
                @php
                    $days = [];
                    $currentDate = \Carbon\Carbon::parse($startDate)->copy();
                    while ($currentDate <= \Carbon\Carbon::parse($endDate)) {
                        $daySubmissions = 0;
                        foreach ($overview as $data) {
                            $daySubmissions += $data['submissions']->filter(function($submission) use ($currentDate) {
                                return $submission->created_at->format('Y-m-d') === $currentDate->format('Y-m-d');
                            })->count();
                        }
                        $days[] = [
                            'date' => $currentDate->copy(),
                            'count' => $daySubmissions
                        ];
                        $currentDate->addDay();
                    }
                    $maxCount = collect($days)->max('count') ?: 1;
                @endphp
                
            <!-- Daily Chart Container -->
            <div class="h-80 bg-gray-50 rounded-lg p-6 border border-gray-200">
                <div class="h-full flex items-end justify-between space-x-2">
                    @if(isset($days) && count($days) > 0)
                        @foreach($days as $day)
                            <div class="flex flex-col items-center space-y-3 flex-1">
                                @if($day['count'] > 0)
                                    <div class="w-full bg-blue-600 rounded-t-lg relative" 
                                         style="height: {{ $maxCount > 0 ? ($day['count'] / $maxCount) * 240 : 0 }}px">
                                        <div class="w-full h-full flex items-end justify-center pb-2">
                                            <span class="text-white text-sm font-bold bg-black/20 px-2 py-1 rounded">{{ $day['count'] }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full bg-gray-300 rounded-t-lg relative" style="height: 40px">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="text-gray-600 text-xs font-bold">0</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <div class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($day['date'])->format('M j') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($day['date'])->format('D') }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        @php
                            $sampleDays = [
                                ['date' => now()->subDays(6), 'count' => 0],
                                ['date' => now()->subDays(5), 'count' => 0],
                                ['date' => now()->subDays(4), 'count' => 0],
                                ['date' => now()->subDays(3), 'count' => 0],
                                ['date' => now()->subDays(2), 'count' => 0],
                                ['date' => now()->subDays(1), 'count' => 0],
                                ['date' => now(), 'count' => 0],
                            ];
                        @endphp
                        @foreach($sampleDays as $day)
                            <div class="flex flex-col items-center space-y-3 flex-1">
                                <div class="w-full bg-gray-300 rounded-t-lg relative" style="height: 40px">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-gray-600 text-xs font-bold">0</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm font-semibold text-gray-900">{{ $day['date']->format('M j') }}</div>
                                    <div class="text-xs text-gray-500">{{ $day['date']->format('D') }}</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            
            <!-- Chart Summary -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="text-xl font-bold text-blue-600">{{ collect($days)->sum('count') }}</div>
                    <div class="text-sm text-gray-600">Total Submissions</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <div class="text-xl font-bold text-purple-600">{{ collect($days)->avg('count') > 0 ? round(collect($days)->avg('count'), 1) : 0 }}</div>
                    <div class="text-sm text-gray-600">Daily Average</div>
                </div>
                <div class="text-center p-4 bg-pink-50 rounded-lg border border-pink-200">
                    <div class="text-xl font-bold text-pink-600">{{ collect($days)->max('count') }}</div>
                    <div class="text-sm text-gray-600">Peak Day</div>
                </div>
            </div>
            </div>
        </div>
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