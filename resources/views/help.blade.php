<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Helpcentrum - {{ config('app.name', 'TaskCheck') }}</title>
    @include('components.head')
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans text-gray-900">

    @include('components.header')

    <style>
        @keyframes fadeUp { from { opacity:0; transform:translateY(30px);} to {opacity:1; transform:translateY(0);} }
        @keyframes fadeIn { from { opacity:0;} to {opacity:1;} }

        .fade-up { animation: fadeUp 0.9s ease-out forwards; }
        .fade-in { animation: fadeIn 1s ease-out forwards; }

        .card-hover { transition: all 0.35s ease; }
        .card-hover:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(139,92,246,0.35);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans text-gray-900">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-md border-b border-gray-200/40">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <a href="{{ url('/') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold">TaskCheck</span>
                </a>
            </div>
            <div class="flex items-center space-x-6">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary-600 font-medium">Home</a>
                <a href="{{ url('/features') }}" class="text-gray-700 hover:text-primary-600 font-medium">Features</a>
                <a href="{{ url('/pricing') }}" class="text-gray-700 hover:text-primary-600 font-medium">Pricing</a>
                <a href="{{ url('/about') }}" class="text-gray-700 hover:text-primary-600 font-medium">About</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-primary-600 font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-gradient text-white px-5 py-2 rounded-lg font-medium">Login</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="pt-32 pb-20 text-center relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl sm:text-6xl font-extrabold leading-tight fade-up">
                Help <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Center</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto fade-up" style="animation-delay:0.3s;">
                Find answers to your questions and learn how to get the most out of TaskCheck.
            </p>
            
            <!-- Search Bar -->
            <div class="mt-8 max-w-2xl mx-auto fade-up" style="animation-delay:0.6s;">
                <div class="relative">
                    <input type="text" id="helpSearch" placeholder="Search for help articles..." class="w-full px-6 py-4 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <!-- Search Results -->
                <div id="searchResults" class="mt-4 bg-white rounded-lg shadow-lg border hidden max-h-96 overflow-y-auto">
                    <div class="p-4">
                        <div class="text-sm text-gray-500 mb-2">Search results:</div>
                        <div id="searchResultsList"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Help Categories -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Quick Help</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Get started quickly with these popular topics</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="#getting-started" class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.2s;">
                <div class="w-16 h-16 mx-auto mb-6 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Getting Started</h3>
                <p class="text-gray-600">Learn the basics of TaskCheck</p>
            </a>
            
            <a href="#tasks" class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.4s;">
                <div class="w-16 h-16 mx-auto mb-6 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Managing Tasks</h3>
                <p class="text-gray-600">Create, organize, and track tasks</p>
            </a>
            
            <a href="#teams" class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.6s;">
                <div class="w-16 h-16 mx-auto mb-6 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Team Collaboration</h3>
                <p class="text-gray-600">Work together with your team</p>
            </a>
            
            <a href="#billing" class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.8s;">
                <div class="w-16 h-16 mx-auto mb-6 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Billing & Plans</h3>
                <p class="text-gray-600">Manage your subscription</p>
            </a>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="max-w-4xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-lg text-gray-600">Quick answers to common questions</p>
        </div>
        
        <div class="space-y-6">
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.2s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">How do I create my first task?</h3>
                <p class="text-gray-600">Click the "New Task" button in your dashboard, fill in the task details, set a due date and priority, then click "Create Task".</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.4s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">How can I invite team members?</h3>
                <p class="text-gray-600">Go to Settings > Team Management, click "Invite Members", enter their email addresses, and choose their role (Admin or Employee).</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.6s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change my subscription plan?</h3>
                <p class="text-gray-600">Yes! You can upgrade or downgrade your plan at any time from your account settings. Changes take effect immediately.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.8s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">How do I export my data?</h3>
                <p class="text-gray-600">Go to Settings > Data & Privacy, then click "Export Data". You'll receive an email with a download link for your data.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:1.0s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a mobile app?</h3>
                <p class="text-gray-600">Yes! TaskCheck has mobile apps for iOS and Android. Download them from the App Store or Google Play Store.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:1.2s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">How do I set up notifications?</h3>
                <p class="text-gray-600">Go to Settings > Notifications to customize your notification preferences for email, push, and in-app notifications.</p>
            </div>
        </div>
    </section>

    <!-- Documentation Sections -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Documentation</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Comprehensive guides and tutorials</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.2s;">
                <div class="w-12 h-12 mb-6 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">User Guide</h3>
                <p class="text-gray-600 mb-4">Complete guide to using TaskCheck features and functionality.</p>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Read Guide →</a>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.4s;">
                <div class="w-12 h-12 mb-6 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">API Documentation</h3>
                <p class="text-gray-600 mb-4">Integrate TaskCheck with your applications using our REST API.</p>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">View API Docs →</a>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.6s;">
                <div class="w-12 h-12 mb-6 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Video Tutorials</h3>
                <p class="text-gray-600 mb-4">Watch step-by-step video tutorials to master TaskCheck.</p>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Watch Videos →</a>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.8s;">
                <div class="w-12 h-12 mb-6 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Best Practices</h3>
                <p class="text-gray-600 mb-4">Learn how to use TaskCheck effectively for maximum productivity.</p>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Read Tips →</a>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:1.0s;">
                <div class="w-12 h-12 mb-6 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Integrations</h3>
                <p class="text-gray-600 mb-4">Connect TaskCheck with your favorite tools and services.</p>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">View Integrations →</a>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:1.2s;">
                <div class="w-12 h-12 mb-6 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Troubleshooting</h3>
                <p class="text-gray-600 mb-4">Common issues and their solutions to help you get back on track.</p>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Find Solutions →</a>
            </div>
        </div>
    </section>

    <!-- Contact Support -->
    <section class="max-w-4xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Still Need Help?</h2>
            <p class="text-lg text-gray-600">Our support team is here to help you succeed</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8">
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.2s;">
                <div class="w-16 h-16 mx-auto mb-6 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Email Support</h3>
                <p class="text-gray-600 mb-6">Get help via email. We typically respond within 24 hours.</p>
                <a href="mailto:support@taskcheck.com" class="btn-gradient text-white px-6 py-3 rounded-lg font-semibold inline-block">
                    Send Email
                </a>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.4s;">
                <div class="w-16 h-16 mx-auto mb-6 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Live Chat</h3>
                <p class="text-gray-600 mb-6">Chat with our support team in real-time during business hours.</p>
                <button class="btn-gradient text-white px-6 py-3 rounded-lg font-semibold">
                    Start Chat
                </button>
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

    <!-- Search Functionality -->
    <script>
        // Help articles data
        const helpArticles = [
            {
                title: "How do I create my first task?",
                content: "Click the 'New Task' button in your dashboard, fill in the task details, set a due date and priority, then click 'Create Task'.",
                category: "Getting Started"
            },
            {
                title: "How can I invite team members?",
                content: "Go to Settings > Team Management, click 'Invite Members', enter their email addresses, and choose their role (Admin or Employee).",
                category: "Team Management"
            },
            {
                title: "Can I change my subscription plan?",
                content: "Yes! You can upgrade or downgrade your plan at any time from your account settings. Changes take effect immediately.",
                category: "Billing"
            },
            {
                title: "How do I export my data?",
                content: "Go to Settings > Data & Privacy, then click 'Export Data'. You'll receive an email with a download link for your data.",
                category: "Data Management"
            },
            {
                title: "Is there a mobile app?",
                content: "Yes! TaskCheck has mobile apps for iOS and Android. Download them from the App Store or Google Play Store.",
                category: "Mobile"
            },
            {
                title: "How do I set up notifications?",
                content: "Go to Settings > Notifications to customize your notification preferences for email, push, and in-app notifications.",
                category: "Settings"
            },
            {
                title: "Managing Tasks",
                content: "Create, organize, and track tasks with our intuitive task management system. Use filters, labels, and due dates to stay organized.",
                category: "Tasks"
            },
            {
                title: "Team Collaboration",
                content: "Work together with your team using comments, file sharing, and real-time updates. Assign tasks and track progress collaboratively.",
                category: "Collaboration"
            },
            {
                title: "API Integration",
                content: "Integrate TaskCheck with your applications using our REST API. Access tasks, users, and teams programmatically.",
                category: "API"
            },
            {
                title: "Security Best Practices",
                content: "Learn about our security features including two-factor authentication, data encryption, and access controls.",
                category: "Security"
            }
        ];

        // Search functionality
        const searchInput = document.getElementById('helpSearch');
        const searchResults = document.getElementById('searchResults');
        const searchResultsList = document.getElementById('searchResultsList');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            
            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }

            const results = helpArticles.filter(article => 
                article.title.toLowerCase().includes(query) || 
                article.content.toLowerCase().includes(query) ||
                article.category.toLowerCase().includes(query)
            );

            if (results.length === 0) {
                searchResultsList.innerHTML = '<div class="text-gray-500 text-sm">No results found</div>';
            } else {
                searchResultsList.innerHTML = results.map(article => `
                    <div class="border-b border-gray-100 py-3 last:border-b-0">
                        <div class="font-medium text-gray-900 mb-1">${article.title}</div>
                        <div class="text-sm text-gray-600 mb-2">${article.content.substring(0, 100)}...</div>
                        <div class="text-xs text-blue-600">${article.category}</div>
                    </div>
                `).join('');
            }

            searchResults.classList.remove('hidden');
        });

        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        // Live chat functionality
        const liveChatBtn = document.querySelector('button:contains("Start Chat")');
        if (liveChatBtn) {
            liveChatBtn.addEventListener('click', function() {
                // Simulate opening live chat
                alert('Live chat would open here. This is a demo - in a real implementation, this would connect to a chat service like Intercom, Zendesk, or custom chat widget.');
            });
        }
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
