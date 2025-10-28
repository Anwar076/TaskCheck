<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Systeemstatus - {{ config('app.name', 'TaskCheck') }}</title>
    @include('components.head')
</head>
<body class="bg-gray-50 min-h-screen font-sans">

    @include('components.header')
                    colors: {
                        primary: {
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes pulse-green { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        @keyframes pulse-yellow { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        @keyframes pulse-red { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
        
        .status-online { animation: pulse-green 2s infinite; }
        .status-warning { animation: pulse-yellow 2s infinite; }
        .status-offline { animation: pulse-red 2s infinite; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold">TaskCheck Status</span>
            </div>
            <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600">← Terug naar TaskCheck</a>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-6 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Systeemstatus</h1>
            <p class="text-lg text-gray-600">Real-time status van alle TaskCheck services en infrastructuur</p>
            <div class="mt-4 text-sm text-gray-500">
                Last updated: <span id="last-updated">{{ now()->format('M j, Y \a\t g:i A T') }}</span>
            </div>
        </div>

        <!-- Overall Status -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">All Systems Operational</h2>
                    <p class="text-gray-600 mt-1">All services are running normally</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full status-online"></div>
                    <span class="text-green-600 font-medium">Operational</span>
                </div>
            </div>
        </div>

        <!-- Services Status -->
        <div class="grid gap-6 mb-8">
            <!-- API Services -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">API Services</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">REST API</div>
                            <div class="text-sm text-gray-600">api.taskcheck.com</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full status-online"></div>
                            <span class="text-green-600 text-sm">Operational</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">WebSocket API</div>
                            <div class="text-sm text-gray-600">ws.taskcheck.com</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full status-online"></div>
                            <span class="text-green-600 text-sm">Operational</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">Authentication Service</div>
                            <div class="text-sm text-gray-600">auth.taskcheck.com</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full status-online"></div>
                            <span class="text-green-600 text-sm">Operational</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Core Services -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Core Services</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">Task Management</div>
                            <div class="text-sm text-gray-600">Core task operations</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full status-online"></div>
                            <span class="text-green-600 text-sm">Operational</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">User Management</div>
                            <div class="text-sm text-gray-600">User accounts and permissions</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full status-online"></div>
                            <span class="text-green-600 text-sm">Operational</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">File Storage</div>
                            <div class="text-sm text-gray-600">Document and media storage</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full status-online"></div>
                            <span class="text-green-600 text-sm">Operational</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">Email Service</div>
                            <div class="text-sm text-gray-600">Notifications and alerts</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full status-online"></div>
                            <span class="text-green-600 text-sm">Operational</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Services -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Database Services</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">Primary Database</div>
                            <div class="text-sm text-gray-600">PostgreSQL 14.5</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full status-online"></div>
                            <span class="text-green-600 text-sm">Operational</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">Cache Layer</div>
                            <div class="text-sm text-gray-600">Redis 6.2</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full status-online"></div>
                            <span class="text-green-600 text-sm">Operational</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">Search Engine</div>
                            <div class="text-sm text-gray-600">Elasticsearch 7.15</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full status-online"></div>
                            <span class="text-green-600 text-sm">Operational</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scheduled Maintenance -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Scheduled Maintenance</h3>
            <div class="space-y-4">
                <div class="border-l-4 border-blue-500 pl-4">
                    <div class="font-medium text-gray-900">Database Optimization</div>
                    <div class="text-sm text-gray-600">Scheduled for: March 15, 2024 at 2:00 AM UTC</div>
                    <div class="text-sm text-gray-600">Expected duration: 30 minutes</div>
                    <div class="text-sm text-gray-600">Impact: Minor performance improvements</div>
                </div>
                <div class="border-l-4 border-yellow-500 pl-4">
                    <div class="font-medium text-gray-900">Security Updates</div>
                    <div class="text-sm text-gray-600">Scheduled for: March 20, 2024 at 1:00 AM UTC</div>
                    <div class="text-sm text-gray-600">Expected duration: 15 minutes</div>
                    <div class="text-sm text-gray-600">Impact: No service interruption</div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Metrics</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">99.9%</div>
                    <div class="text-sm text-gray-600">Uptime (30 days)</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">45ms</div>
                    <div class="text-sm text-gray-600">Average Response Time</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">0</div>
                    <div class="text-sm text-gray-600">Active Incidents</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 text-gray-600">
            <p>For real-time updates, follow <a href="#" class="text-blue-600 hover:underline">@TaskCheckStatus</a> on Twitter</p>
            <p class="mt-2">Need help? Contact our <a href="{{ route('contact') }}" class="text-blue-600 hover:underline">support team</a></p>
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds
        setInterval(() => {
            document.getElementById('last-updated').textContent = new Date().toLocaleString();
        }, 30000);
    </script>
</body>
</html>
