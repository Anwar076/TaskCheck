@extends('layouts.admin')

@section('content')
    <!-- Clean Header Section -->
    <div class="bg-white border-b border-gray-200 p-6 lg:p-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
                <p class="text-gray-600">Uitgebreid overzicht van uw taakbeheersysteem</p>
            </div>
            <div class="hidden lg:block">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-4 lg:p-8">

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <!-- Total Employees Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Totaal aantal werknemers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_employees'] }}</p>
                    </div>
                </div>
                <div class="flex items-center text-sm text-blue-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Actieve werknemers</span>
                </div>
            </div>

            <!-- Active Lists Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Actieve lijsten</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_lists'] }}</p>
                    </div>
                </div>
                <div class="flex items-center text-sm text-green-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Bezig</span>
                </div>
            </div>

            <!-- Pending Review Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">In afwachting van beoordeling</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['pending_submissions'] }}</p>
                    </div>
                </div>
                <div class="flex items-center text-sm text-amber-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">In afwachting van goedkeuring</span>
                </div>
            </div>

            <!-- Completed Today Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Vandaag voltooid</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['completed_today'] }}</p>
                    </div>
                </div>
                <div class="flex items-center text-sm text-purple-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Taken voltooid</span>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Recent Activity and Live Monitoring Feed -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-4 lg:p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Team Overzicht</h3>
                            <p class="text-gray-600 mt-1">Activiteit en real-time monitoring van uw team</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex">
                        <button id="recent-tab" class="tab-button active px-6 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600 hover:text-blue-700 focus:outline-none">
                            Recente Activiteit
                        </button>
                        <button id="live-tab" class="tab-button px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                            Live Monitoring
                            <span id="live-indicator" class="inline-flex items-center ml-2 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="animate-pulse w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                Live
                            </span>
                        </button>
                    </nav>
                </div>
                
                <!-- Tab Content -->
                <div class="p-4 lg:p-6">
                    <!-- Recent Activity Tab Content -->
                    <div id="recent-content" class="tab-content">
                        <div class="space-y-4">
                            @forelse($recentSubmissions as $submission)
                                <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="relative">
                                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                                            <span class="text-white font-bold text-lg">{{ substr($submission->user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ $submission->taskList->title }}</p>
                                        <p class="text-sm text-gray-500 mt-1 flex items-center space-x-3">
                                            <span class="font-medium">{{ $submission->user->name }}</span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span>{{ $submission->created_at->diffForHumans() }}</span>
                                        </p>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border
                                            @if($submission->status === 'completed') bg-amber-100 text-amber-800 border-amber-200
                                            @elseif($submission->status === 'reviewed') bg-green-100 text-green-800 border-green-200
                                            @else bg-gray-100 text-gray-800 border-gray-200 @endif">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                        <a href="{{ route('admin.submissions.show', $submission) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            Beoordeling
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-semibold text-lg">Geen recente activiteit</p>
                                    <p class="text-gray-400 text-sm mt-1">Activiteit verschijnt hier zodra gebruikers taken indienen</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Live Monitoring Tab Content -->
                    <div id="live-content" class="tab-content hidden">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="animate-pulse w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-600">Laatste update: <span id="last-update">Nu</span></span>
                            </div>
                            <button id="refresh-live" class="inline-flex items-center px-3 py-1 rounded-lg text-sm bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Ververs
                            </button>
                        </div>
                        <div id="live-monitoring-data">
                            <div class="text-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                                <p class="text-gray-500 mt-2">Laden van live data...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Panel -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-4 lg:p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Snelle acties</h3>
                            <p class="text-gray-600 text-sm">Beheer uw werkruimte efficiënt</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 lg:p-6">
                    <div class="space-y-4">
                        <a href="{{ route('admin.lists.create') }}" class="block p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors border border-blue-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Nieuwe lijst aanmaken</h4>
                                    <p class="text-sm text-gray-600">Start een nieuwe takenlijst voor uw team</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.submissions.index') }}" class="block p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-colors border border-green-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Inzendingen beoordelen</h4>
                                    <p class="text-sm text-gray-600">Controleer en keur teaminzendingen goed</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.users.create') }}" class="block p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors border border-purple-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Voeg gebruiker toe</h4>
                                    <p class="text-sm text-gray-600">Nodig nieuwe teamleden uit</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Performance Section -->
        <div class="mt-6 lg:mt-8 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-4 lg:p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Team Performance</h3>
                        <p class="text-gray-600 mt-1">30-dagen voltooiingsanalyses en inzichten</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="p-4 lg:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                    @forelse($employeeStats as $employee)
                        <div class="bg-gray-50 rounded-xl p-4 lg:p-6 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center justify-between mb-4">
                                <div class="relative">
                                    <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">{{ substr($employee->name, 0, 1) }}</span>
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                        <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-gray-900">{{ $employee->completion_rate }}%</p>
                                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Voltooiingspercentage</p>
                                </div>
                            </div>
                            <div class="mb-4">
                                <p class="font-semibold text-gray-900 truncate">{{ $employee->name }}</p>
                                <p class="text-sm text-gray-500 mt-1 flex items-center space-x-2">
                                    <span class="font-medium">{{ $employee->department }}</span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span>{{ $employee->completed_submissions }}/{{ $employee->total_submissions }} taken</span>
                                </p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-green-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">Prestatie</span>
                                </div>
                                <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-green-500 rounded-full transition-all duration-1000" style="width: {{ $employee->completion_rate }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-semibold text-lg">Geen werknemersgegevens</p>
                            <p class="text-gray-400 text-sm mt-1">Prestatiegegevens van werknemers zullen hier verschijnen</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .tab-button.active {
            color: #2563eb;
            border-color: #2563eb;
        }
        .tab-content.hidden {
            display: none;
        }
    </style>

    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const recentTab = document.getElementById('recent-tab');
            const liveTab = document.getElementById('live-tab');
            const recentContent = document.getElementById('recent-content');
            const liveContent = document.getElementById('live-content');
            const refreshButton = document.getElementById('refresh-live');
            const lastUpdateSpan = document.getElementById('last-update');

            let refreshInterval;

            // Live monitoring functions
            function loadLiveData() {
                console.log('Loading live data...'); // Debug log
                const liveDataContainer = document.getElementById('live-monitoring-data');
                
                // Show loading state
                liveDataContainer.innerHTML = `
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="text-gray-500 mt-2">Laden van live data...</p>
                    </div>
                `;
                
                fetch('/admin/live-monitoring', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status); // Debug log
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Live data loaded:', data); // Debug log
                        liveDataContainer.innerHTML = renderLiveData(data);
                        updateLastUpdateTime();
                    })
                    .catch(error => {
                        console.error('Error loading live data:', error);
                        liveDataContainer.innerHTML = `
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5C2.312 18.333 3.274 20 4.814 20z"></path>
                                    </svg>
                                </div>
                                <p class="text-red-500 font-semibold text-lg">Fout bij laden van data</p>
                                <p class="text-gray-400 text-sm mt-1">Fout: ${error.message}</p>
                                <button onclick="window.loadLiveDataGlobal()" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Opnieuw proberen
                                </button>
                            </div>
                        `;
                    });
            }

            // Tab switching
            function switchToTab(tab) {
                console.log('Switching to tab:', tab); // Debug log
                if (tab === 'recent') {
                    recentTab.classList.add('active', 'text-blue-600', 'border-blue-600');
                    recentTab.classList.remove('text-gray-500', 'border-transparent');
                    liveTab.classList.remove('active', 'text-blue-600', 'border-blue-600');
                    liveTab.classList.add('text-gray-500', 'border-transparent');
                    
                    recentContent.classList.remove('hidden');
                    liveContent.classList.add('hidden');
                    
                    clearInterval(refreshInterval);
                    console.log('Auto-refresh stopped'); // Debug log
                } else if (tab === 'live') {
                    liveTab.classList.add('active', 'text-blue-600', 'border-blue-600');
                    liveTab.classList.remove('text-gray-500', 'border-transparent');
                    recentTab.classList.remove('active', 'text-blue-600', 'border-blue-600');
                    recentTab.classList.add('text-gray-500', 'border-transparent');
                    
                    liveContent.classList.remove('hidden');
                    recentContent.classList.add('hidden');
                    
                    loadLiveData();
                    startAutoRefresh();
                }
            }

            // Tab event listeners
            recentTab.addEventListener('click', function() {
                switchToTab('recent');
            });

            liveTab.addEventListener('click', function() {
                switchToTab('live');
            });

            function renderLiveData(data) {
                if (!data.activeSessions || data.activeSessions.length === 0) {
                    return `
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4.5"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-semibold text-lg">Geen actieve sessies</p>
                            <p class="text-gray-400 text-sm mt-1">Gebruikers zijn momenteel niet actief bezig met taken</p>
                        </div>
                    `;
                }

                let html = '<div class="space-y-4">';
                
                // Summary card first
                html += `
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Live Overzicht</h4>
                                    <p class="text-sm text-gray-600">${data.summary.active_users} actieve gebruikers</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">${data.summary.avg_progress}%</p>
                                <p class="text-sm text-gray-600">Gem. voortgang</p>
                            </div>
                        </div>
                    </div>
                `;

                // Active sessions
                data.activeSessions.forEach(session => {
                    const statusColor = getStatusColor(session.status);
                    const progressWidth = session.progress_percentage || 0;
                    
                    html += `
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 p-4 bg-gray-50 rounded-xl border-l-4 ${statusColor.border} cursor-pointer hover:bg-gray-100 transition-colors" onclick="window.location.href='/admin/submissions/${session.submission_id}'">
                            <div class="relative">
                                <div class="w-12 h-12 ${statusColor.bg} rounded-xl flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">${session.user_name.charAt(0)}</span>
                                </div>
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white animate-pulse"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">${session.task_list_title}</p>
                                <div class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-3 text-sm text-gray-500 mt-1">
                                    <span class="font-medium">${session.user_name}</span>
                                    <span class="hidden sm:inline w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span>Actief: ${session.time_active}</span>
                                    <span class="hidden sm:inline w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span class="text-xs">${session.current_task}</span>
                                </div>
                                <div class="flex items-center space-x-2 mt-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="${statusColor.progress} h-2 rounded-full transition-all duration-500" style="width: ${progressWidth}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-600">${progressWidth}%</span>
                                    <span class="text-xs text-gray-500">(${session.completed_tasks}/${session.total_tasks})</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statusColor.badge}">
                                    <span class="animate-pulse w-2 h-2 ${statusColor.dot} rounded-full mr-2"></span>
                                    ${session.status}
                                </span>
                                <span class="text-xs text-gray-500">Update: ${session.last_activity}</span>
                                ${session.recent_task_activity ? `<span class="text-xs text-green-600">📝 ${session.recent_task_activity} recente taken</span>` : ''}
                                <div class="flex items-center text-xs text-blue-600 hover:text-blue-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Bekijk & Controleer
                                </div>
                            </div>
                        </div>
                    `;
                });

                // Show recent completions if available
                if (data.recentCompletions && data.recentCompletions.length > 0) {
                    html += `
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Recent voltooid (laatste 2 uur)</h4>
                            <div class="space-y-2">
                    `;
                    data.recentCompletions.forEach(completion => {
                        html += `
                            <div class="flex items-center justify-between p-2 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">${completion.user_name}</span>
                                        <span class="text-xs text-gray-500 ml-2">${completion.task_list_title}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-600">${completion.completion_time} min</span>
                                    <div class="text-xs text-gray-500">${completion.completed_at}</div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div></div>';
                }

                // Show stale users warning if any
                if (data.staleUsers && data.staleUsers.length > 0) {
                    html += `
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h4 class="text-sm font-semibold text-yellow-800 mb-2">⚠️ Inactieve gebruikers</h4>
                            <div class="text-xs text-yellow-700">
                                ${data.staleUsers.map(user => `${user.user_name} (${user.inactive_duration})`).join(', ')}
                            </div>
                        </div>
                    `;
                }

                html += '</div>';
                return html;
            }

            function getStatusColor(status) {
                switch(status) {
                    case 'Working':
                        return {
                            bg: 'bg-blue-600',
                            border: 'border-blue-500',
                            badge: 'bg-blue-100 text-blue-800',
                            dot: 'bg-blue-500',
                            progress: 'bg-blue-500'
                        };
                    case 'Active':
                        return {
                            bg: 'bg-green-600',
                            border: 'border-green-500',
                            badge: 'bg-green-100 text-green-800',
                            dot: 'bg-green-500',
                            progress: 'bg-green-500'
                        };
                    case 'Idle':
                        return {
                            bg: 'bg-yellow-600',
                            border: 'border-yellow-500',
                            badge: 'bg-yellow-100 text-yellow-800',
                            dot: 'bg-yellow-500',
                            progress: 'bg-yellow-500'
                        };
                    case 'Paused':
                        return {
                            bg: 'bg-orange-600',
                            border: 'border-orange-500',
                            badge: 'bg-orange-100 text-orange-800',
                            dot: 'bg-orange-500',
                            progress: 'bg-orange-500'
                        };
                    case 'In Progress': // Fallback for backward compatibility
                        return {
                            bg: 'bg-blue-600',
                            border: 'border-blue-500',
                            badge: 'bg-blue-100 text-blue-800',
                            dot: 'bg-blue-500',
                            progress: 'bg-blue-500'
                        };
                    default:
                        return {
                            bg: 'bg-gray-600',
                            border: 'border-gray-500',
                            badge: 'bg-gray-100 text-gray-800',
                            dot: 'bg-gray-500',
                            progress: 'bg-gray-500'
                        };
                }
            }

            function updateLastUpdateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('nl-NL', { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    second: '2-digit' 
                });
                lastUpdateSpan.textContent = timeString;
            }

            function startAutoRefresh() {
                clearInterval(refreshInterval); // Clear any existing interval
                refreshInterval = setInterval(() => {
                    console.log('Auto-refreshing live data...'); // Debug log
                    loadLiveData();
                }, 8000); // Refresh elke 8 seconden
            }

            // Manual refresh button
            refreshButton.addEventListener('click', function() {
                console.log('Manual refresh clicked'); // Debug log
                
                // Add visual feedback
                const originalText = refreshButton.innerHTML;
                refreshButton.innerHTML = `
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-1"></div>
                    Laden...
                `;
                refreshButton.disabled = true;
                
                // Load data and restore button
                loadLiveData();
                
                setTimeout(() => {
                    refreshButton.innerHTML = originalText;
                    refreshButton.disabled = false;
                }, 1500);
            });

            function startAutoRefresh() {
                clearInterval(refreshInterval); // Clear any existing interval
                refreshInterval = setInterval(() => {
                    console.log('Auto-refreshing live data...'); // Debug log
                    loadLiveData();
                }, 8000); // Refresh elke 8 seconden
                console.log('Auto-refresh started'); // Debug log
            }

            function updateLastUpdateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('nl-NL', { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    second: '2-digit' 
                });
                if (lastUpdateSpan) {
                    lastUpdateSpan.textContent = timeString;
                }
            }

            // Manual refresh button
            refreshButton.addEventListener('click', function() {
                console.log('Manual refresh clicked'); // Debug log
                
                // Add visual feedback
                const originalText = refreshButton.innerHTML;
                refreshButton.innerHTML = `
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-1"></div>
                    Laden...
                `;
                refreshButton.disabled = true;
                
                // Load data and restore button
                loadLiveData();
                
                setTimeout(() => {
                    refreshButton.innerHTML = originalText;
                    refreshButton.disabled = false;
                }, 1500);
            });

            // Make functions globally accessible
            window.loadLiveDataGlobal = loadLiveData;
        });
    </script>
@endsection