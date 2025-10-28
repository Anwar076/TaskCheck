<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Over Ons - {{ config('app.name', 'TaskCheck') }}</title>
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
                Over <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">TaskCheck</span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto fade-up" style="animation-delay:0.3s;">
                Wij hebben de missie om te revolutioneren hoe teams taken beheren en samenwerken. Leer meer over ons verhaal, waarden, en de mensen achter TaskCheck.
            </p>
        </div>
    </section>

    <!-- Our Story -->
    <section class="max-w-6xl mx-auto px-6 py-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="fade-up">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">Ons Verhaal</h2>
                <p class="text-lg text-gray-600 mb-6">
                    TaskCheck ontstond uit een eenvoudige observatie: de meeste taakbeheer tools zijn ofwel te complex of te basic. We zagen teams worstelen met logge interfaces, ontbrekende functies, en slechte samenwerkingstools.
                </p>
                <p class="text-lg text-gray-600 mb-6">
                    Opgericht in 2020 door een team van productiviteitsenthousiastelingen en software engineers, zijn we begonnen met het creëren van een platform dat zowel krachtig als intuïtief zou zijn. Ons doel was om iets te bouwen dat teams daadwerkelijk elke dag zouden willen gebruiken.
                </p>
                <p class="text-lg text-gray-600">
                    Vandaag de dag bedient TaskCheck duizenden teams wereldwijd, van startups tot Fortune 500 bedrijven. We zijn trots om deel uit te maken van hun succesverhalen en blijven innoveren op basis van hun feedback.
                </p>
            </div>
            <div class="fade-up" style="animation-delay:0.2s;">
                <div class="bg-white/70 backdrop-blur-md rounded-2xl p-8">
                    <div class="grid grid-cols-2 gap-6 text-center">
                        <div>
                            <div class="text-3xl font-bold text-blue-600 mb-2">1,247+</div>
                            <div class="text-gray-600">Actieve Gebruikers</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-green-600 mb-2">342</div>
                            <div class="text-gray-600">Teams</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-purple-600 mb-2">15,892</div>
                            <div class="text-gray-600">Afgeronde Taken</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-orange-600 mb-2">99.9%</div>
                            <div class="text-gray-600">Uptime</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Mission -->
    <section class="max-w-6xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Onze Missie & Waarden</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Wat ons elke dag drijft</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.2s;">
                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-4">Eenvoud</h3>
                <p class="text-gray-600">Wij geloven dat krachtige tools eenvoudig te gebruiken moeten zijn. Geen complexe workflows of verwarrende interfaces.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.4s;">
                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-4">Samenwerking</h3>
                <p class="text-gray-600">Geweldig werk gebeurt wanneer teams naadloos samenwerken. Wij bouwen tools die samenwerking verbeteren.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.6s;">
                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-4">Innovatie</h3>
                <p class="text-gray-600">We verleggen constant grenzen en verkennen nieuwe manieren om taakbeheer te verbeteren.</p>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="max-w-6xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Ontmoet Ons Team</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">De gepassioneerde mensen achter TaskCheck</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.2s;">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    SM
                </div>
                <h3 class="text-xl font-semibold mb-2">Sarah Mitchell</h3>
                <p class="text-blue-600 font-medium mb-4">CEO & Medeoprichter</p>
                <p class="text-gray-600">Voormalig productmanager bij Google. Gepassioneerd over het bouwen van tools die werk leuker maken.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.4s;">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    MJ
                </div>
                <h3 class="text-xl font-semibold mb-2">Michael Johnson</h3>
                <p class="text-green-600 font-medium mb-4">CTO & Medeoprichter</p>
                <p class="text-gray-600">Full-stack engineer met 10+ jaar ervaring. Houdt van het oplossen van complexe problemen met elegante oplossingen.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.6s;">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    AL
                </div>
                <h3 class="text-xl font-semibold mb-2">Anna Lee</h3>
                <p class="text-purple-600 font-medium mb-4">Hoofd Design</p>
                <p class="text-gray-600">UX designer gericht op het creëren van intuïtieve en mooie gebruikerservaringen. Voormalig designer bij Apple.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:0.8s;">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    DC
                </div>
                <h3 class="text-xl font-semibold mb-2">David Chen</h3>
                <p class="text-orange-600 font-medium mb-4">Lead Developer</p>
                <p class="text-gray-600">Backend specialist met expertise in schaalbare systemen. Zorgt ervoor dat TaskCheck soepel draait voor alle gebruikers.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:1.0s;">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    ER
                </div>
                <h3 class="text-xl font-semibold mb-2">Emily Rodriguez</h3>
                <p class="text-teal-600 font-medium mb-4">Hoofd Marketing</p>
                <p class="text-gray-600">Growth marketing expert die teams helpt TaskCheck te ontdekken. Voormalig marketing lead bij Slack.</p>
            </div>
            
            <div class="card-hover bg-white/70 backdrop-blur-md rounded-2xl p-8 text-center fade-up" style="animation-delay:1.2s;">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    JT
                </div>
                <h3 class="text-xl font-semibold mb-2">James Thompson</h3>
                <p class="text-indigo-600 font-medium mb-4">Klantsucces</p>
                <p class="text-gray-600">Toegewijd aan ervoor zorgen dat elke klant de meeste waarde uit TaskCheck haalt. Jouw succes is ons succes.</p>
            </div>
        </div>
    </section>

    <!-- Company Culture -->
    <section class="max-w-6xl mx-auto px-6 py-20">
        <div class="text-center mb-16 fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Onze Cultuur</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Hoe het is om bij TaskCheck te werken</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="fade-up">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Remote-First & Flexible</h3>
                <p class="text-lg text-gray-600 mb-6">
                    We believe great work can happen anywhere. Our team is distributed across the globe, and we've built our culture around flexibility and trust.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Flexible working hours
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Work from anywhere
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Annual team retreats
                    </li>
                    <li class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Learning & development budget
                    </li>
                </ul>
            </div>
            <div class="fade-up" style="animation-delay:0.2s;">
                <div class="bg-white/70 backdrop-blur-md rounded-2xl p-8">
                    <h4 class="text-xl font-semibold mb-4">Join Our Team</h4>
                    <p class="text-gray-600 mb-6">We're always looking for talented people to join our mission. Check out our open positions.</p>
                    <a href="#" class="btn-gradient text-white px-6 py-3 rounded-lg font-semibold inline-block">
                        View Open Positions
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="text-center py-20 fade-up">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">Ready to Get Started?</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Join thousands of teams who trust TaskCheck to manage their workflows and boost productivity.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/pricing') }}" class="btn-gradient text-white px-8 py-4 rounded-lg font-semibold text-lg">
                    View Pricing Plans
                </a>
                <a href="{{ url('/contact') }}" class="bg-white text-gray-700 px-8 py-4 rounded-lg font-semibold text-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    Contact Sales
                </a>
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
