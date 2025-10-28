<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'TaskCheck') }}</title>
    @include('components.head')
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans text-gray-900">

    @include('components.header')

    <!-- Hero -->
    <section class="pt-32 pb-20 text-center relative overflow-hidden">
        <!-- Floating shapes -->
        <div class="absolute top-10 -left-20 w-64 h-64 bg-blue-400/20 rounded-full blur-3xl float"></div>
        <div class="absolute bottom-10 -right-20 w-72 h-72 bg-purple-400/20 rounded-full blur-3xl float" style="animation-delay:3s;"></div>

        <div class="max-w-5xl mx-auto px-4">
            <h1 class="text-4xl sm:text-6xl font-extrabold leading-tight fade-up">
                Welkom bij <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">TaskCheck</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto fade-up" style="animation-delay:0.3s;">
                Het ultieme taakbeheer platform voor moderne teams. Stroomijn workflows, verhoog productiviteit, en bereik je doelen met krachtige samenwerkingstools.
            </p>
            
            <!-- Live Stats -->
            <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto fade-up" style="animation-delay:0.6s;">
                <div class="bg-white/70 backdrop-blur-md rounded-xl p-4 shadow-lg">
                    <div class="text-2xl font-bold text-blue-600" id="live-users">1,247</div>
                    <div class="text-sm text-gray-600">Actieve Gebruikers</div>
                </div>
                <div class="bg-white/70 backdrop-blur-md rounded-xl p-4 shadow-lg">
                    <div class="text-2xl font-bold text-green-600" id="live-tasks">15,892</div>
                    <div class="text-sm text-gray-600">Afgeronde Taken</div>
                </div>
                <div class="bg-white/70 backdrop-blur-md rounded-xl p-4 shadow-lg">
                    <div class="text-2xl font-bold text-purple-600" id="live-teams">342</div>
                    <div class="text-sm text-gray-600">Teams in Gebruik</div>
                </div>
                <div class="bg-white/70 backdrop-blur-md rounded-xl p-4 shadow-lg">
                    <div class="text-2xl font-bold text-orange-600" id="live-hours">2,847</div>
                    <div class="text-sm text-gray-600">Uren Bespaard</div>
                </div>
            </div>
            
            <!-- PWA Install Section -->
            <div class="mt-12 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 fade-up" style="animation-delay:0.9s;">
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">📱 Installeer TaskCheck App</h3>
                    <p class="text-gray-600 mb-4">Krijg snelle toegang met onze mobiele app. Werkt op alle apparaten!</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <button id="install-hero-button" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all hover:scale-105">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            App Installeren
                        </button>
                        <div class="text-sm text-gray-500 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Werkt op iPhone, Android, Desktop & iPad
                        </div>
                    </div>
                    
                    <!-- Mobile-specific instructions -->
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="text-sm text-yellow-800">
                            <strong>📱 Op Mobiel:</strong> Dit maakt een <strong>echte app</strong> zonder de browser adresbalk - net als een native app!
                        </div>
                        <div class="mt-2 text-xs text-yellow-700">
                            <strong>⚠️ Belangrijk:</strong> Gebruik Safari op iPhone of Chrome op Android. Als je "Maak een snelle link" ziet, gebruik je de verkeerde browser!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Krachtige Functies voor Moderne Teams</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Alles wat je nodig hebt om taken te beheren, effectief samen te werken, en je doelen te bereiken.</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.2s;">
                <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Slim Taakbeheer</h3>
                <p class="text-gray-600">Maak, wijs toe, en volg taken met intelligente prioritering en deadlinebeheer.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.4s;">
                <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Teamsamenwerking</h3>
                <p class="text-gray-600">Werk naadloos samen met real-time updates, opmerkingen, en bestandsdeling.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.6s;">
                <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Geavanceerde Analytics</h3>
                <p class="text-gray-600">Krijg inzicht in teamprestaties met gedetailleerde rapporten en productiviteitsstatistieken.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.8s;">
                <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-xl shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Tijdregistratie</h3>
                <p class="text-gray-600">Bewaak bestede tijd op taken en projecten met ingebouwde tijdregistratietools.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:1.0s;">
                <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-indigo-500 to-blue-600 text-white rounded-xl shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 13h6V7H4v6zM4 5h6V1H4v4zM10 3h4v4h-4V3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Aangepaste Workflows</h3>
                <p class="text-gray-600">Creëer aangepaste workflows en automatiseringsregels om je processen te stroomlijnen.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:1.2s;">
                <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-xl shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Veilig & Betrouwbaar</h3>
                <p class="text-gray-600">Enterprise-niveau beveiliging met 99.9% uptime garantie en dataversleuteling.</p>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Kies Jouw Perfecte Plan</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Flexibele prijsopties passend bij teams van alle groottes. Start gratis en schaal mee terwijl je groeit.</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Starter Plan -->
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 relative fade-up" style="animation-delay:0.2s;">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Starter</h3>
                    <div class="text-4xl font-bold text-blue-600 mb-4">€29<span class="text-lg text-gray-500">/maand</span></div>
                    <p class="text-gray-600 mb-6">Perfect voor kleine teams die beginnen</p>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        1 Beheerder Account
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        5 Medewerker Accounts
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Basis Taakbeheer
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Email Ondersteuning
                    </li>
                </ul>
                <button class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                    Begin Nu
                </button>
            </div>
            
            <!-- Professional Plan -->
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 relative border-2 border-blue-500 fade-up" style="animation-delay:0.4s;">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-blue-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Meest Populair</span>
                </div>
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Professioneel</h3>
                    <div class="text-4xl font-bold text-blue-600 mb-4">€79<span class="text-lg text-gray-500">/maand</span></div>
                    <p class="text-gray-600 mb-6">Ideaal voor groeiende teams</p>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        2 Beheerder Accounts
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        10 Medewerker Accounts
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Geavanceerde Analytics
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Prioriteitsondersteuning
                    </li>
                </ul>
                <button class="w-full btn-gradient text-white py-3 rounded-lg font-semibold">
                    Begin Nu
                </button>
            </div>
            
            <!-- Enterprise Plan -->
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 relative fade-up" style="animation-delay:0.6s;">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Enterprise</h3>
                    <div class="text-4xl font-bold text-blue-600 mb-4">€149<span class="text-lg text-gray-500">/maand</span></div>
                    <p class="text-gray-600 mb-6">Voor grote organisaties</p>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        5 Beheerder Accounts
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        20 Medewerker Accounts
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Aangepaste Workflows
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        24/7 Telefoonondersteuning
                    </li>
                </ul>
                <button class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                    Begin Nu
                </button>
            </div>
            
            <!-- Custom Plan -->
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 relative fade-up" style="animation-delay:0.8s;">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Aangepast</h3>
                    <div class="text-4xl font-bold text-blue-600 mb-4">Aangepast<span class="text-lg text-gray-500">/maand</span></div>
                    <p class="text-gray-600 mb-6">Op maat gemaakt voor jouw behoeften</p>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Onbeperkt Beheerders
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Onbeperkt Medewerkers
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        White-label Oplossing
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Toegewijde Manager
                    </li>
                </ul>
                <button class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                    Contact Verkoop
                </button>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="text-center py-20 fade-up" style="animation-delay:1s;">
        @auth
            <a href="{{ url('/dashboard') }}" class="btn-gradient text-white px-10 py-4 rounded-full font-semibold text-lg inline-flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
                Ga naar Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-gradient text-white px-10 py-4 rounded-full font-semibold text-lg inline-flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"></path>
                </svg>
                Begin Nu
            </a>
        @endauth
        <p class="mt-6 text-gray-600">Een account nodig? Neem contact op: <a href="mailto:admin@taskcheck.com" class="text-primary-600 font-medium hover:underline">admin@taskcheck.com</a></p>
    </section>

    <!-- Testimonials -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Wat Onze Klanten Zeggen</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Sluit je aan bij duizenden teams die TaskCheck vertrouwen voor hun workflow management.</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.2s;">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                        SM
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Sarah Mitchell</h4>
                        <p class="text-sm text-gray-600">CEO, TechStart Inc.</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">"TaskCheck heeft een revolutie teweeggebracht in hoe ons team projecten beheert. De interface is intuïtief en de analytics helpen ons op koers te blijven."</p>
                <div class="flex text-yellow-400 mt-4">
                    ★★★★★
                </div>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.4s;">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold">
                        MJ
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Michael Johnson</h4>
                        <p class="text-sm text-gray-600">Projectmanager, DesignCo</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">"De real-time samenwerkingsfuncties zijn baanbrekend. Onze teamproductiviteit is met 40% gestegen sinds we TaskCheck gebruiken."</p>
                <div class="flex text-yellow-400 mt-4">
                    ★★★★★
                </div>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 fade-up" style="animation-delay:0.6s;">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold">
                        AL
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Anna Lee</h4>
                        <p class="text-sm text-gray-600">Operations Directeur, GrowthCorp</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">"De aangepaste workflows en automatisering hebben ons talloze uren bespaard. TaskCheck schaalt perfect mee met ons groeiende team."</p>
                <div class="flex text-yellow-400 mt-4">
                    ★★★★★
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="max-w-4xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Veelgestelde Vragen</h2>
            <p class="text-lg text-gray-600">Alles wat je moet weten over TaskCheck</p>
        </div>
        
        <div class="space-y-6">
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.2s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Hoe werkt de prijsstelling?</h3>
                <p class="text-gray-600">Onze prijzen zijn gebaseerd op het aantal beheerder- en medewerker accounts. Je kunt je plan op elk moment upgraden of downgraden.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.4s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Is er een gratis proefperiode?</h3>
                <p class="text-gray-600">Ja! We bieden een 14-daagse gratis proefperiode voor alle plannen. Geen creditcard nodig om te beginnen.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.6s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Kan ik mijn abonnement aanpassen?</h3>
                <p class="text-gray-600">Absoluut! Ons Aangepaste plan stelt je in staat om een abonnement te maken dat is afgestemd op jouw specifieke behoeften met onbeperkte gebruikers en aangepaste functies.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-6 fade-up" style="animation-delay:0.8s;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Welke ondersteuning bieden jullie?</h3>
                <p class="text-gray-600">We bieden emailondersteuning voor alle plannen, prioriteitsondersteuning voor Professioneel, en 24/7 telefoonondersteuning voor Enterprise en Aangepaste plannen.</p>
            </div>
        </div>
    </section>

    @include('components.footer')

    <!-- JavaScript for Live Numbers -->
    <script>
        // Animate live numbers
        function animateNumber(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const current = Math.floor(progress * (end - start) + start);
                element.textContent = current.toLocaleString();
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Check if app is installed as PWA and redirect to login
        function checkPwaAndRedirect() {
            // Check if running in standalone mode (installed PWA)
            if (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) {
                console.log('PWA detected, redirecting to login');
                window.location.href = '/login?source=pwa';
                return;
            }
            
            // Check for iOS PWA
            if (window.navigator.standalone === true) {
                console.log('iOS PWA detected, redirecting to login');
                window.location.href = '/login?source=pwa';
                return;
            }
        }

        // Start animations when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Check PWA first
            checkPwaAndRedirect();
            setTimeout(() => {
                animateNumber(document.getElementById('live-users'), 0, 1247, 2000);
                animateNumber(document.getElementById('live-tasks'), 0, 15892, 2500);
                animateNumber(document.getElementById('live-teams'), 0, 342, 1800);
                animateNumber(document.getElementById('live-hours'), 0, 2847, 2200);
            }, 1000);

            // Mobile menu functionality
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');

            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });
        });

        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('SW registered: ', registration);
                        
                        // Check for updates
                        registration.addEventListener('updatefound', () => {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    // New content is available, force update
                                    newWorker.postMessage({ type: 'SKIP_WAITING' });
                                    window.location.reload();
                                }
                            });
                        });
                    })
                    .catch((registrationError) => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }

        // PWA Install Prompt
        let deferredPrompt;
        const installButton = document.getElementById('install-button');
        const installButtonMobile = document.getElementById('install-button-mobile');
        const installButtonAlways = document.getElementById('install-button-always');
        const installButtonMobileAlways = document.getElementById('install-button-mobile-always');
        const installHeroButton = document.getElementById('install-hero-button');

        // Check if app is already installed
        function isAppInstalled() {
            return window.matchMedia('(display-mode: standalone)').matches || 
                   window.navigator.standalone === true;
        }

        // Show install prompt if not installed
        if (!isAppInstalled()) {
            console.log('App not installed, showing install options');
        } else {
            console.log('App is already installed');
            // Hide install buttons if already installed
            [installButton, installButtonMobile, installButtonAlways, installButtonMobileAlways, installHeroButton].forEach(btn => {
                if (btn) btn.style.display = 'none';
            });
        }

        // Function to show install instructions
        function showInstallInstructions() {
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
            const isAndroid = /Android/.test(navigator.userAgent);
            const isDesktop = !isIOS && !isAndroid;

            let instructions = '';
            
            if (isIOS) {
                instructions = 'To install as a REAL APP (not just a link):\n\n1. Make sure you\'re in Safari browser\n2. Tap the Share button (📤) at the bottom\n3. Scroll down and tap "Add to Home Screen"\n4. Tap "Add" to confirm\n\n✅ This creates a real app without browser bars!\n❌ If you see "Make a fast link" - you\'re not in Safari!';
            } else if (isAndroid) {
                instructions = 'To install as a REAL APP (not just a link):\n\n1. Make sure you\'re in Chrome browser\n2. Tap the menu (⋮) in the top right\n3. Look for "Install App" or "Add to Home Screen"\n4. Tap "Install" to confirm\n\n✅ This creates a real app without browser bars!\n❌ If you see "Make a fast link" - try Chrome browser!';
            } else {
                instructions = 'To install: Click the install button in your browser\'s address bar, or use the browser menu';
            }

            alert(`📱 Install TaskCheck App\n\n${instructions}\n\nOr look for the install option in your browser menu.`);
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent the mini-infobar from appearing on mobile
            e.preventDefault();
            // Stash the event so it can be triggered later
            deferredPrompt = e;
            // Show the install buttons
            if (installButton) {
                installButton.style.display = 'block';
            }
            if (installButtonMobile) {
                installButtonMobile.style.display = 'block';
            }
            if (installHeroButton) {
                installHeroButton.textContent = 'Install App';
            }
        });

        function handleInstall() {
            if (deferredPrompt) {
                // Show the install prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                deferredPrompt.userChoice.then((choiceResult) => {
                    console.log(`User response to the install prompt: ${choiceResult.outcome}`);
                    // Clear the deferredPrompt variable
                    deferredPrompt = null;
                    // Hide the install buttons
                    if (installButton) {
                        installButton.style.display = 'none';
                    }
                    if (installButtonMobile) {
                        installButtonMobile.style.display = 'none';
                    }
                });
            } else {
                // Show instructions if no prompt available
                showInstallInstructions();
            }
        }

        // Add event listeners
        if (installButton) {
            installButton.addEventListener('click', handleInstall);
        }
        if (installButtonMobile) {
            installButtonMobile.addEventListener('click', handleInstall);
        }
        if (installButtonAlways) {
            installButtonAlways.addEventListener('click', showInstallInstructions);
        }
        if (installButtonMobileAlways) {
            installButtonMobileAlways.addEventListener('click', showInstallInstructions);
        }
        if (installHeroButton) {
            installHeroButton.addEventListener('click', handleInstall);
        }

        // Track successful installation
        window.addEventListener('appinstalled', () => {
            console.log('PWA was installed');
            if (installButton) {
                installButton.style.display = 'none';
            }
            if (installButtonMobile) {
                installButtonMobile.style.display = 'none';
            }
            if (installHeroButton) {
                installHeroButton.textContent = 'App Installed!';
                installHeroButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                installHeroButton.classList.add('bg-green-600');
            }
        });
    </script>
</body>
</html>
