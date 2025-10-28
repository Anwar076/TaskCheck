<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Functies - {{ config('app.name', 'TaskCheck') }}</title>
    @include('components.head')
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans text-gray-900">

    @include('components.header')



    <!-- Hero -->
    <section class="pt-32 pb-20 text-center relative overflow-hidden">
        <div class="absolute top-10 -left-20 w-64 h-64 bg-blue-400/20 rounded-full blur-3xl float"></div>
        <div class="absolute bottom-10 -right-20 w-72 h-72 bg-purple-400/20 rounded-full blur-3xl float" style="animation-delay:3s;"></div>

        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl sm:text-6xl font-extrabold leading-tight fade-up">
                Powerful <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Features</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto fade-up" style="animation-delay:0.3s;">
                Everything you need to manage tasks, collaborate with your team, and boost productivity. Discover the features that make TaskCheck the perfect choice for modern teams.
            </p>
        </div>
    </section>

    <!-- Feature Categories -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Feature Categories</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Organized by functionality to help you find what you need</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <button class="feature-category bg-white/70 backdrop-blur-md rounded-xl p-6 text-center card-hover fade-up" data-category="management">
                <div class="w-12 h-12 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Task Management</h3>
            </button>
            
            <button class="feature-category bg-white/70 backdrop-blur-md rounded-xl p-6 text-center card-hover fade-up" data-category="collaboration">
                <div class="w-12 h-12 mx-auto mb-4 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Collaboration</h3>
            </button>
            
            <button class="feature-category bg-white/70 backdrop-blur-md rounded-xl p-6 text-center card-hover fade-up" data-category="analytics">
                <div class="w-12 h-12 mx-auto mb-4 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Analytics</h3>
            </button>
            
            <button class="feature-category bg-white/70 backdrop-blur-md rounded-xl p-6 text-center card-hover fade-up" data-category="automation">
                <div class="w-12 h-12 mx-auto mb-4 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Automation</h3>
            </button>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div id="features-container">
            <!-- Task Management Features -->
            <div class="feature-section" data-category="management">
                <div class="text-center mb-16 fade-up">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Task Management</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Powerful tools to organize, prioritize, and track your tasks</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.2s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Smart Task Creation</h3>
                        <p class="text-gray-600">Create tasks with rich descriptions, due dates, priorities, and custom fields. Use templates for recurring tasks.</p>
                    </div>
                    
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.4s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Smart Tagging</h3>
                        <p class="text-gray-600">Organize tasks with custom tags and labels. Use smart filters to find exactly what you're looking for.</p>
                    </div>
                    
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.6s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Priority Management</h3>
                        <p class="text-gray-600">Set task priorities and deadlines. Get intelligent suggestions for task ordering based on urgency and importance.</p>
                    </div>
                </div>
            </div>

            <!-- Collaboration Features -->
            <div class="feature-section hidden" data-category="collaboration">
                <div class="text-center mb-16 fade-up">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Team Collaboration</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Work together seamlessly with powerful collaboration tools</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.2s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Team Workspaces</h3>
                        <p class="text-gray-600">Create dedicated workspaces for different teams and projects. Control access and permissions.</p>
                    </div>
                    
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.4s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Real-time Comments</h3>
                        <p class="text-gray-600">Collaborate on tasks with threaded comments. Get instant notifications when team members respond.</p>
                    </div>
                    
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.6s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">File Sharing</h3>
                        <p class="text-gray-600">Attach files to tasks and share documents with your team. Version control and access management included.</p>
                    </div>
                </div>
            </div>

            <!-- Analytics Features -->
            <div class="feature-section hidden" data-category="analytics">
                <div class="text-center mb-16 fade-up">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Analytics & Reports</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Get insights into your team's productivity and performance</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.2s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Performance Dashboards</h3>
                        <p class="text-gray-600">Visual dashboards showing team productivity, task completion rates, and project progress.</p>
                    </div>
                    
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.4s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Time Tracking</h3>
                        <p class="text-gray-600">Track time spent on tasks and projects. Generate detailed reports for billing and analysis.</p>
                    </div>
                    
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.6s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Custom Reports</h3>
                        <p class="text-gray-600">Create custom reports with filters, date ranges, and export options. Schedule automated reports.</p>
                    </div>
                </div>
            </div>

            <!-- Automation Features -->
            <div class="feature-section hidden" data-category="automation">
                <div class="text-center mb-16 fade-up">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Automation & Workflows</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Streamline your processes with powerful automation tools</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.2s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 13h6V7H4v6zM4 5h6V1H4v4zM10 3h4v4h-4V3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Custom Workflows</h3>
                        <p class="text-gray-600">Create automated workflows that trigger actions based on task status changes and conditions.</p>
                    </div>
                    
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.4s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 13h6V7H4v6zM4 5h6V1H4v4zM10 3h4v4h-4V3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Smart Notifications</h3>
                        <p class="text-gray-600">Get intelligent notifications for deadlines, updates, and important events. Customize notification preferences.</p>
                    </div>
                    
                    <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.6s;">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">API Integration</h3>
                        <p class="text-gray-600">Connect with external tools and services. Build custom integrations with our powerful API.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="text-center py-20 fade-up">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">Ready to Experience These Features?</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Start your free trial today and discover how TaskCheck can transform your team's productivity.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/pricing') }}" class="btn-gradient text-white px-8 py-4 rounded-lg font-semibold text-lg">
                    Start Free Trial
                </a>
                <a href="{{ url('/contact') }}" class="bg-white text-gray-700 px-8 py-4 rounded-lg font-semibold text-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    Schedule Demo
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900">TaskCheck</span>
                    </div>
                    <p class="text-gray-600">The ultimate task management platform for modern teams.</p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Product</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="{{ url('/features') }}" class="hover:text-blue-600 transition-colors">Features</a></li>
                        <li><a href="{{ url('/pricing') }}" class="hover:text-blue-600 transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">Integrations</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">API</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="{{ url('/about') }}" class="hover:text-blue-600 transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">Careers</a></li>
                        <li><a href="{{ url('/contact') }}" class="hover:text-blue-600 transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="{{ url('/help') }}" class="hover:text-blue-600 transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">Status</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors">Security</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-8 text-center text-gray-600">
                <p>© {{ date('Y') }} TaskCheck. Built with <span class="text-red-500">♥</span> Laravel & Tailwind CSS.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript for Feature Categories -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryButtons = document.querySelectorAll('.feature-category');
            const featureSections = document.querySelectorAll('.feature-section');

            categoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const category = this.dataset.category;
                    
                    // Remove active class from all buttons
                    categoryButtons.forEach(btn => {
                        btn.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
                    
                    // Hide all feature sections
                    featureSections.forEach(section => {
                        section.classList.add('hidden');
                    });
                    
                    // Show selected feature section
                    const targetSection = document.querySelector(`[data-category="${category}"]`);
                    if (targetSection) {
                        targetSection.classList.remove('hidden');
                    }
                });
            });
        });
        // Check if app is installed as PWA and redirect to login
        function checkPwaAndRedirect() {
            if (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) {
                window.location.href = '/login?source=pwa';
                return;
            }
            if (window.navigator.standalone === true) {
                window.location.href = '/login?source=pwa';
                return;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkPwaAndRedirect();
        });
    </script>

    @include('components.footer')

    <!-- Mobile menu script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
