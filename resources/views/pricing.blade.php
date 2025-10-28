<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Prijzen - {{ config('app.name', 'TaskCheck') }}</title>
    @include('components.head')
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans text-gray-900">

    @include('components.header')

    <style>
        @keyframes fadeUp { from { opacity:0; transform:translateY(30px);} to {opacity:1; transform:translateY(0);} }
        @keyframes fadeIn { from { opacity:0;} to {opacity:1;} }
        @keyframes float { 0%,100% {transform:translateY(0);} 50% {transform:translateY(-8px);} }

        .fade-up { animation: fadeUp 0.9s ease-out forwards; }
        .fade-in { animation: fadeIn 1s ease-out forwards; }
        .float { animation: float 6s ease-in-out infinite; }

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
                <a href="{{ url('/pricing') }}" class="text-primary-600 font-medium">Pricing</a>
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
        <div class="absolute top-10 -left-20 w-64 h-64 bg-blue-400/20 rounded-full blur-3xl float"></div>
        <div class="absolute bottom-10 -right-20 w-72 h-72 bg-purple-400/20 rounded-full blur-3xl float" style="animation-delay:3s;"></div>

        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl sm:text-6xl font-extrabold leading-tight fade-up">
                Simple, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Transparent</span> Pricing
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto fade-up" style="animation-delay:0.3s;">
                Choose the perfect plan for your team. No hidden fees, no surprises. Start your free trial today.
            </p>
            
            <!-- Billing Toggle -->
            <div class="mt-8 flex items-center justify-center space-x-4 fade-up" style="animation-delay:0.6s;">
                <span class="text-gray-600">Monthly</span>
                <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" id="billing-toggle">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-1" id="toggle-button"></span>
                </button>
                <span class="text-gray-600">Annual <span class="text-green-600 font-semibold">(Save 20%)</span></span>
            </div>
        </div>
    </section>

    <!-- Pricing Plans -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Starter Plan -->
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 relative fade-up" style="animation-delay:0.2s;">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Starter</h3>
                    <div class="text-4xl font-bold text-blue-600 mb-4">
                        <span class="monthly-price">$29</span>
                        <span class="annual-price hidden">$23</span>
                        <span class="text-lg text-gray-500">/month</span>
                    </div>
                    <p class="text-gray-600 mb-6">Perfect for small teams getting started</p>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        1 Admin Account
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        5 Employee Accounts
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Basic Task Management
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Email Support
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        5GB Storage
                    </li>
                </ul>
                <button onclick="selectPlan('starter')" class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                    Start Free Trial
                </button>
            </div>
            
            <!-- Professional Plan -->
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 relative border-2 border-blue-500 fade-up" style="animation-delay:0.4s;">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-blue-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                </div>
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Professional</h3>
                    <div class="text-4xl font-bold text-blue-600 mb-4">
                        <span class="monthly-price">$79</span>
                        <span class="annual-price hidden">$63</span>
                        <span class="text-lg text-gray-500">/month</span>
                    </div>
                    <p class="text-gray-600 mb-6">Ideal for growing teams</p>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        2 Admin Accounts
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        10 Employee Accounts
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Advanced Analytics
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Priority Support
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        50GB Storage
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        API Access
                    </li>
                </ul>
                <button onclick="selectPlan('professional')" class="w-full btn-gradient text-white py-3 rounded-lg font-semibold">
                    Start Free Trial
                </button>
            </div>
            
            <!-- Enterprise Plan -->
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 relative fade-up" style="animation-delay:0.6s;">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Enterprise</h3>
                    <div class="text-4xl font-bold text-blue-600 mb-4">
                        <span class="monthly-price">$149</span>
                        <span class="annual-price hidden">$119</span>
                        <span class="text-lg text-gray-500">/month</span>
                    </div>
                    <p class="text-gray-600 mb-6">For large organizations</p>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        5 Admin Accounts
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        20 Employee Accounts
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Custom Workflows
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        24/7 Phone Support
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        200GB Storage
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        SSO Integration
                    </li>
                </ul>
                <button class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                    Start Free Trial
                </button>
            </div>
            
            <!-- Custom Plan -->
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 relative fade-up" style="animation-delay:0.8s;">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Custom</h3>
                    <div class="text-4xl font-bold text-blue-600 mb-4">Custom<span class="text-lg text-gray-500">/month</span></div>
                    <p class="text-gray-600 mb-6">Tailored to your needs</p>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Unlimited Admins
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Unlimited Employees
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        White-label Solution
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Dedicated Manager
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Unlimited Storage
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Custom Integrations
                    </li>
                </ul>
                <button onclick="selectPlan('enterprise')" class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                    Contact Sales
                </button>
            </div>
        </div>
    </section>

    <!-- Payment Methods -->
    <section class="max-w-4xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Secure Payment Options</h2>
            <p class="text-lg text-gray-600">We accept all major payment methods for your convenience</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8">
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.2s;">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Credit & Debit Cards</h3>
                <div class="flex space-x-4 mb-6">
                    <div class="w-16 h-10 bg-blue-600 rounded flex items-center justify-center">
                        <span class="text-white font-bold text-xs">VISA</span>
                    </div>
                    <div class="w-16 h-10 bg-red-600 rounded flex items-center justify-center">
                        <span class="text-white font-bold text-xs">MC</span>
                    </div>
                    <div class="w-16 h-10 bg-blue-800 rounded flex items-center justify-center">
                        <span class="text-white font-bold text-xs">AMEX</span>
                    </div>
                    <div class="w-16 h-10 bg-orange-600 rounded flex items-center justify-center">
                        <span class="text-white font-bold text-xs">DISC</span>
                    </div>
                </div>
                <p class="text-gray-600">All major credit and debit cards accepted with secure processing.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.4s;">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Digital Wallets</h3>
                <div class="flex space-x-4 mb-6">
                    <div class="w-16 h-10 bg-yellow-400 rounded flex items-center justify-center">
                        <span class="text-black font-bold text-xs">PP</span>
                    </div>
                    <div class="w-16 h-10 bg-green-600 rounded flex items-center justify-center">
                        <span class="text-white font-bold text-xs">AP</span>
                    </div>
                    <div class="w-16 h-10 bg-blue-500 rounded flex items-center justify-center">
                        <span class="text-white font-bold text-xs">GP</span>
                    </div>
                </div>
                <p class="text-gray-600">Quick and secure payments through popular digital wallets.</p>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="max-w-4xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Pricing FAQ</h2>
            <p class="text-lg text-gray-600">Common questions about our pricing and billing</p>
        </div>
        
        <div class="space-y-6">
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.2s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial?</h3>
                <p class="text-gray-600">Yes! All plans come with a 14-day free trial. No credit card required to get started.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.4s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change plans anytime?</h3>
                <p class="text-gray-600">Absolutely! You can upgrade or downgrade your plan at any time. Changes take effect immediately.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.6s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">What happens if I exceed my user limit?</h3>
                <p class="text-gray-600">We'll notify you when you're approaching your limit. You can upgrade your plan or purchase additional users.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.8s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Do you offer refunds?</h3>
                <p class="text-gray-600">We offer a 30-day money-back guarantee. If you're not satisfied, we'll refund your payment.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:1.0s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Are there any setup fees?</h3>
                <p class="text-gray-600">No setup fees for any plan. You only pay the monthly or annual subscription fee.</p>
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

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">Complete Your Subscription</h3>
                    <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="planDetails" class="mb-6">
                    <!-- Plan details will be populated here -->
                </div>

                <form id="paymentForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="fullName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                        <input type="text" id="companyName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="selectPaymentMethod('card')" class="payment-method p-3 border border-gray-300 rounded-lg hover:border-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm0 2v2h16V6H4zm0 4v6h16v-6H4z"/>
                                    </svg>
                                    <span class="text-sm font-medium">Card</span>
                                </div>
                            </button>
                            <button type="button" onclick="selectPaymentMethod('paypal')" class="payment-method p-3 border border-gray-300 rounded-lg hover:border-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.432-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.543-.68c-.013-.76-.4-1.38-1.057-1.75-.84-.48-2.01-.6-3.13-.6H8.287c-.524 0-.968.382-1.05.9L5.135 19.482h4.944c.524 0 .968-.382 1.05-.9l1.12-7.106h2.19c4.298 0 7.664-1.747 8.647-6.797.03-.144.054-.289.077-.432.292-1.867-.002-3.137-1.012-4.287z"/>
                                    </svg>
                                    <span class="text-sm font-medium">PayPal</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div id="cardDetails" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                            <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                <input type="text" id="expiryDate" placeholder="MM/YY" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CVC</label>
                                <input type="text" id="cvc" placeholder="123" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        Complete Subscription
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-500">
                        By subscribing, you agree to our <a href="{{ route('terms') }}" class="text-blue-600 hover:underline">Terms of Service</a> 
                        and <a href="{{ route('privacy') }}" class="text-blue-600 hover:underline">Privacy Policy</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Billing Toggle and Payment -->
    <script>
        let selectedPlan = null;
        let selectedPaymentMethod = null;

        function selectPlan(plan) {
            selectedPlan = plan;
            const plans = {
                starter: {
                    name: 'Starter',
                    price: '$29',
                    period: 'month',
                    features: ['1 Admin Account', '5 Employee Accounts', 'Basic Task Management', 'Email Support', '5GB Storage']
                },
                professional: {
                    name: 'Professional',
                    price: '$79',
                    period: 'month',
                    features: ['2 Admin Accounts', '10 Employee Accounts', 'Advanced Analytics', 'Priority Support', '50GB Storage', 'API Access']
                },
                enterprise: {
                    name: 'Enterprise',
                    price: '$149',
                    period: 'month',
                    features: ['5 Admin Accounts', '20 Employee Accounts', 'Custom Workflows', '24/7 Phone Support', 'Unlimited Storage', 'Custom Integrations']
                }
            };

            const planData = plans[plan];
            document.getElementById('planDetails').innerHTML = `
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">${planData.name} Plan</h4>
                    <div class="text-2xl font-bold text-blue-600 mb-2">${planData.price}<span class="text-sm text-gray-500">/${planData.period}</span></div>
                    <ul class="text-sm text-gray-600 space-y-1">
                        ${planData.features.map(feature => `<li>• ${feature}</li>`).join('')}
                    </ul>
                </div>
            `;

            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            selectedPlan = null;
            selectedPaymentMethod = null;
            document.getElementById('paymentForm').reset();
            document.getElementById('cardDetails').classList.add('hidden');
        }

        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;
            
            // Remove active class from all payment methods
            document.querySelectorAll('.payment-method').forEach(btn => {
                btn.classList.remove('border-blue-500', 'bg-blue-50');
                btn.classList.add('border-gray-300');
            });

            // Add active class to selected method
            event.target.closest('.payment-method').classList.add('border-blue-500', 'bg-blue-50');
            event.target.closest('.payment-method').classList.remove('border-gray-300');

            // Show/hide card details
            if (method === 'card') {
                document.getElementById('cardDetails').classList.remove('hidden');
            } else {
                document.getElementById('cardDetails').classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Billing toggle functionality
            const toggle = document.getElementById('billing-toggle');
            const toggleButton = document.getElementById('toggle-button');
            const monthlyPrices = document.querySelectorAll('.monthly-price');
            const annualPrices = document.querySelectorAll('.annual-price');
            let isAnnual = false;

            toggle.addEventListener('click', function() {
                isAnnual = !isAnnual;
                
                if (isAnnual) {
                    toggleButton.style.transform = 'translateX(1.25rem)';
                    monthlyPrices.forEach(price => price.classList.add('hidden'));
                    annualPrices.forEach(price => price.classList.remove('hidden'));
                } else {
                    toggleButton.style.transform = 'translateX(0.25rem)';
                    monthlyPrices.forEach(price => price.classList.remove('hidden'));
                    annualPrices.forEach(price => price.classList.add('hidden'));
                }
            });

            // Payment form submission
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!selectedPaymentMethod) {
                    alert('Please select a payment method');
                    return;
                }

                if (selectedPaymentMethod === 'card') {
                    const cardNumber = document.getElementById('cardNumber').value;
                    const expiryDate = document.getElementById('expiryDate').value;
                    const cvc = document.getElementById('cvc').value;

                    if (!cardNumber || !expiryDate || !cvc) {
                        alert('Please fill in all card details');
                        return;
                    }
                }

                // Simulate payment processing
                const submitBtn = e.target.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Processing...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    alert('Payment successful! Welcome to TaskCheck. You will receive a confirmation email shortly.');
                    closePaymentModal();
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }, 2000);
            });

            // Close modal when clicking outside
            document.getElementById('paymentModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closePaymentModal();
                }
            });
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
