@extends('layouts.admin')

@section('content')
    <!-- Clean Header Section -->
    <div class="bg-white border-b border-gray-200 p-6 lg:p-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
                <p class="text-gray-600">Comprehensive overview of your task management system</p>
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
        <!-- Loading State -->
        <div id="dashboard-loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-sm text-gray-600">Loading dashboard data...</p>
        </div>

        <!-- Statistics Cards -->
        <div id="dashboard-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8" style="display: none;">
            <!-- Total Users Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div id="total-users" class="text-2xl lg:text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-sm text-gray-600">Total Users</div>
            </div>

            <!-- Active Users Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div id="active-users" class="text-2xl lg:text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-sm text-gray-600">Active Users</div>
            </div>

            <!-- Total Lists Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
                <div id="total-lists" class="text-2xl lg:text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-sm text-gray-600">Total Lists</div>
            </div>

            <!-- Active Lists Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div id="active-lists" class="text-2xl lg:text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-sm text-gray-600">Active Lists</div>
            </div>
        </div>

        <!-- Additional Stats Row -->
        <div id="additional-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8" style="display: none;">
            <!-- Total Submissions Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div id="total-submissions" class="text-2xl lg:text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-sm text-gray-600">Total Submissions</div>
            </div>

            <!-- Pending Submissions Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div id="pending-submissions" class="text-2xl lg:text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-sm text-gray-600">Pending Submissions</div>
            </div>

            <!-- Total Templates Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div id="total-templates" class="text-2xl lg:text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-sm text-gray-600">Total Templates</div>
            </div>

            <!-- Active Templates Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div id="active-templates" class="text-2xl lg:text-3xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-sm text-gray-600">Active Templates</div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div id="recent-activity" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 lg:p-6 mb-6 lg:mb-8" style="display: none;">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
            <div id="activity-list" class="space-y-3">
                <!-- Activity items will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // API-powered dashboard
        document.addEventListener('DOMContentLoaded', async function() {
            await loadDashboardData();
        });

        async function loadDashboardData() {
            const loadingDiv = document.getElementById('dashboard-loading');
            const statsDiv = document.getElementById('dashboard-stats');
            const additionalStatsDiv = document.getElementById('additional-stats');
            const activityDiv = document.getElementById('recent-activity');

            try {
                // Load admin stats
                const stats = await DashboardAPI.getAdminStats();
                
                // Update main stats
                document.getElementById('total-users').textContent = stats.total_users || 0;
                document.getElementById('active-users').textContent = stats.active_users || 0;
                document.getElementById('total-lists').textContent = stats.total_lists || 0;
                document.getElementById('active-lists').textContent = stats.active_lists || 0;

                // Update additional stats
                document.getElementById('total-submissions').textContent = stats.total_submissions || 0;
                document.getElementById('pending-submissions').textContent = stats.pending_submissions || 0;
                document.getElementById('total-templates').textContent = stats.total_templates || 0;
                document.getElementById('active-templates').textContent = stats.active_templates || 0;

                // Show stats
                loadingDiv.style.display = 'none';
                statsDiv.style.display = 'grid';
                additionalStatsDiv.style.display = 'grid';

                // Load recent activity
                const activities = await DashboardAPI.getRecentActivity();
                renderRecentActivity(activities);
                activityDiv.style.display = 'block';

            } catch (error) {
                console.error('Failed to load dashboard data:', error);
                loadingDiv.innerHTML = `
                    <div class="text-red-600">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-2 text-sm">Failed to load dashboard data</p>
                        <button onclick="loadDashboardData()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Retry
                        </button>
                    </div>
                `;
            }
        }

        function renderRecentActivity(activities) {
            const activityList = document.getElementById('activity-list');
            
            if (activities.length === 0) {
                activityList.innerHTML = '<p class="text-gray-500 text-center py-4">No recent activity</p>';
                return;
            }

            const activitiesHtml = activities.map(activity => `
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full ${getActivityColor(activity.type)} flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${getActivityIcon(activity.type)}
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">${activity.message}</p>
                        <p class="text-xs text-gray-500">${formatTimestamp(activity.timestamp)}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(activity.status)}">
                            ${activity.status}
                        </span>
                    </div>
                </div>
            `).join('');

            activityList.innerHTML = activitiesHtml;
        }

        function getActivityColor(type) {
            switch (type) {
                case 'submission': return 'bg-blue-500';
                case 'assignment': return 'bg-green-500';
                default: return 'bg-gray-500';
            }
        }

        function getActivityIcon(type) {
            switch (type) {
                case 'submission': return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>';
                case 'assignment': return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>';
                default: return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
            }
        }

        function getStatusColor(status) {
            switch (status) {
                case 'completed': return 'bg-green-100 text-green-800';
                case 'pending': return 'bg-yellow-100 text-yellow-800';
                case 'assigned': return 'bg-blue-100 text-blue-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        function formatTimestamp(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffInMinutes = Math.floor((now - date) / (1000 * 60));
            
            if (diffInMinutes < 1) return 'Just now';
            if (diffInMinutes < 60) return `${diffInMinutes} minutes ago`;
            if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)} hours ago`;
            return `${Math.floor(diffInMinutes / 1440)} days ago`;
        }
    </script>
@endsection
