<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Integraties - {{ config('app.name', 'TaskCheck') }}</title>
    @include('components.head')
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans text-gray-900">

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
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold">TaskCheck Integrations</span>
            </div>
            <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600">← Back to TaskCheck</a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-6 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Integrations</h1>
            <p class="text-lg text-gray-600">Connect TaskCheck with your favorite tools and services</p>
        </div>

        <!-- Coming Soon Banner -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-8 text-center text-white mb-12">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold mb-2">Integrations Coming Soon!</h2>
            <p class="text-blue-100 mb-6">We're working hard to bring you powerful integrations with the tools you love.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                    Get Notified
                </button>
                <button class="border border-white/30 text-white px-6 py-3 rounded-lg font-semibold hover:bg-white/10 transition-colors">
                    Request Integration
                </button>
            </div>
        </div>

        <!-- Planned Integrations -->
        <div class="space-y-8">
            <!-- Communication Tools -->
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Communication Tools</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Discord</h3>
                        <p class="text-sm text-gray-600 mb-4">Get task notifications in your Discord channels</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.954 4.569a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.691 8.094 4.066 6.13 1.64 3.161a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.061a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Slack</h3>
                        <p class="text-sm text-gray-600 mb-4">Sync tasks and updates with your Slack workspace</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.568 8.16c-.169 1.858-.896 3.461-2.189 4.808-.98 1.016-2.16 1.7-3.379 2.052-1.219.352-2.4.352-3.619 0-1.219-.352-2.4-1.036-3.379-2.052-1.293-1.347-2.02-2.95-2.189-4.808-.034-.374-.034-.748 0-1.122.169-1.858.896-3.461 2.189-4.808.98-1.016 2.16-1.7 3.379-2.052 1.219-.352 2.4-.352 3.619 0 1.219.352 2.4 1.036 3.379 2.052 1.293 1.347 2.02 2.95 2.189 4.808.034.374.034.748 0 1.122z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Microsoft Teams</h3>
                        <p class="text-sm text-gray-600 mb-4">Integrate task management with Teams</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                </div>
            </div>

            <!-- Project Management -->
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Project Management</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-17v9l7-4.5-7-4.5z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Jira</h3>
                        <p class="text-sm text-gray-600 mb-4">Sync issues and tasks between Jira and TaskCheck</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-17v9l7-4.5-7-4.5z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Asana</h3>
                        <p class="text-sm text-gray-600 mb-4">Two-way sync with Asana projects and tasks</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-17v9l7-4.5-7-4.5z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Trello</h3>
                        <p class="text-sm text-gray-600 mb-4">Import boards and cards from Trello</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                </div>
            </div>

            <!-- Development Tools -->
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Development Tools</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-17v9l7-4.5-7-4.5z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">GitHub</h3>
                        <p class="text-sm text-gray-600 mb-4">Link tasks to GitHub issues and pull requests</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-17v9l7-4.5-7-4.5z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">GitLab</h3>
                        <p class="text-sm text-gray-600 mb-4">Connect with GitLab issues and merge requests</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-17v9l7-4.5-7-4.5z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Bitbucket</h3>
                        <p class="text-sm text-gray-600 mb-4">Sync with Bitbucket repositories and issues</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                </div>
            </div>

            <!-- Productivity Tools -->
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Productivity Tools</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-17v9l7-4.5-7-4.5z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Google Workspace</h3>
                        <p class="text-sm text-gray-600 mb-4">Sync with Google Calendar and Drive</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-17v9l7-4.5-7-4.5z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Microsoft 365</h3>
                        <p class="text-sm text-gray-600 mb-4">Integrate with Outlook and SharePoint</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                    <div class="border rounded-lg p-6 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-17v9l7-4.5-7-4.5z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Notion</h3>
                        <p class="text-sm text-gray-600 mb-4">Sync tasks with Notion databases</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Coming Soon</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Integration -->
        <div class="bg-white rounded-lg shadow-sm border p-8 mt-12">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Don't See Your Tool?</h2>
                <p class="text-gray-600 mb-6">We're constantly adding new integrations. Request your favorite tool and we'll prioritize it for development.</p>
                <div class="max-w-md mx-auto">
                    <div class="flex gap-4">
                        <input type="email" placeholder="Your email" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <input type="text" placeholder="Tool name" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button class="w-full mt-4 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        Request Integration
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 text-gray-600">
            <p>Want to build your own integration? Check out our <a href="{{ route('api') }}" class="text-blue-600 hover:underline">API documentation</a></p>
            <p class="mt-2">Questions about integrations? <a href="{{ route('contact') }}" class="text-blue-600 hover:underline">Contact our team</a></p>
        </div>
    </div>
</body>
</html>
