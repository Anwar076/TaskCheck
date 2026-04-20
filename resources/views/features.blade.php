<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Functies TaskCheck - Taakbeheer, analytics en automatisering';
        $seoDescription = 'Ontdek alle TaskCheck functies voor operationele teams: taakbeheer, samenwerking, analytics en automatisering.';
        $seoUrl = route('features');
        $seoImage = asset('icons/taskcheck-logo.png');
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="nl_NL">
    <meta property="og:site_name" content="TaskCheck">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:image" content="{{ $seoImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "WebPage",
            "name": "TaskCheck functies",
            "url": "{{ $seoUrl }}",
            "description": "{{ $seoDescription }}"
        }
    </script>
    <style>
        @keyframes floatY {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .float-soft { animation: floatY 8s ease-in-out infinite; }
        .float-soft-delayed { animation: floatY 9s ease-in-out infinite; animation-delay: 1.6s; }
        .reveal { opacity: 0; transform: translateY(14px); transition: opacity .55s ease, transform .55s ease; }
        .reveal.in-view { opacity: 1; transform: translateY(0); }
        .soft-card { transition: transform .22s ease, box-shadow .22s ease; }
        .soft-card:hover { transform: translateY(-3px); box-shadow: 0 20px 32px -24px rgba(37, 99, 235, .45); }
    </style>
</head>
<body class="bg-gradient-to-br from-sky-50 via-indigo-50 to-fuchsia-50 min-h-screen font-sans text-gray-900">

    @include('components.header')



    <!-- Hero -->
    <section class="relative overflow-hidden pt-28 pb-16">
        <div class="absolute -top-36 left-1/2 -translate-x-1/2 h-96 w-96 rounded-full bg-cyan-300/30 blur-3xl float-soft"></div>
        <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-pink-300/30 blur-3xl float-soft-delayed"></div>
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-10 items-center">
                <div class="reveal">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-blue-200 bg-white/80 text-xs text-slate-700 mb-5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Productoverzicht
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight text-slate-900">
                        Alles wat je nodig hebt
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-pink-600">
                            om operationeel te excelleren
                        </span>
                    </h1>
                    <p class="mt-5 text-lg text-slate-600 max-w-xl">
                        TaskCheck combineert taakbeheer, bewijscontrole, samenwerking, analytics en automatisering in een platform dat werkt voor managers en medewerkers.
                    </p>
                </div>
                <div class="reveal rounded-2xl border border-blue-100 bg-white/90 p-6 shadow-xl">
                    <h2 class="text-lg font-semibold text-slate-900">Wat je direct krijgt</h2>
                    <div class="mt-4 grid sm:grid-cols-2 gap-3 text-sm">
                        <div class="soft-card rounded-xl border border-blue-100 bg-blue-50 p-3 text-slate-700">Taaklijsten met bewijsregels</div>
                        <div class="soft-card rounded-xl border border-indigo-100 bg-indigo-50 p-3 text-slate-700">AI import vanuit documenten/foto</div>
                        <div class="soft-card rounded-xl border border-emerald-100 bg-emerald-50 p-3 text-slate-700">Realtime voortgang en review</div>
                        <div class="soft-card rounded-xl border border-fuchsia-100 bg-fuchsia-50 p-3 text-slate-700">Workflow en meldingen</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Korte waardepropositie -->
    <section class="max-w-7xl mx-auto px-6 pb-8">
        <div class="grid md:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-blue-100 bg-white/80 p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-900">Voor managers</p>
                <p class="text-sm text-slate-600 mt-1">Live overzicht van voortgang, ontbrekend bewijs en kwaliteitsissues per team of locatie.</p>
            </div>
            <div class="rounded-2xl border border-indigo-100 bg-white/80 p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-900">Voor medewerkers</p>
                <p class="text-sm text-slate-600 mt-1">Duidelijke dagelijkse taken met eenvoudige bewijsupload op mobiel en desktop.</p>
            </div>
            <div class="rounded-2xl border border-fuchsia-100 bg-white/80 p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-900">Voor groei</p>
                <p class="text-sm text-slate-600 mt-1">AI-import, templates en workflows die je operationele processen schaalbaar maken.</p>
            </div>
        </div>
    </section>

    <!-- Feature Categories -->
    <section class="max-w-7xl mx-auto px-6 py-10">
        <div class="text-center mb-12 reveal">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Functiecategorieen</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Snel overzicht van de mogelijkheden per onderwerp</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <button class="feature-category soft-card bg-white/80 border border-blue-100 rounded-xl p-5 text-center reveal" data-category="management">
                <div class="w-12 h-12 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Taakbeheer</h3>
            </button>
            
            <button class="feature-category soft-card bg-white/80 border border-blue-100 rounded-xl p-5 text-center reveal" data-category="collaboration">
                <div class="w-12 h-12 mx-auto mb-4 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Samenwerking</h3>
            </button>
            
            <button class="feature-category soft-card bg-white/80 border border-blue-100 rounded-xl p-5 text-center reveal" data-category="analytics">
                <div class="w-12 h-12 mx-auto mb-4 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Analytics</h3>
            </button>
            
            <button class="feature-category soft-card bg-white/80 border border-blue-100 rounded-xl p-5 text-center reveal" data-category="automation">
                <div class="w-12 h-12 mx-auto mb-4 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Automatisering</h3>
            </button>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="max-w-7xl mx-auto px-6 py-12">
        <div id="features-container">
            <!-- Task Management Features -->
            <div class="feature-section" data-category="management">
                <div class="text-center mb-14 reveal">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Taakbeheer</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Krachtige tools om taken te organiseren, prioriteren en volgen</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="soft-card bg-white/85 border border-blue-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Slimme taakaanmaak</h3>
                        <p class="text-gray-600">Maak taken met duidelijke omschrijvingen, deadlines, prioriteiten en eigen velden.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Herbruikbare sjablonen voor terugkerende taken</li>
                            <li>Per taak bewijs-type instellen (foto/video/tekst/bestand)</li>
                            <li>Verplichtingen, checklist-items en handtekeningoptie</li>
                        </ul>
                    </div>
                    
                    <div class="soft-card bg-white/85 border border-emerald-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Slim labelen</h3>
                        <p class="text-gray-600">Orden taken met labels en tags. Filter snel op wat voor jouw team nu belangrijk is.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Filter op team, status, bewijs en prioriteit</li>
                            <li>Snelle zoekfunctie in grote lijsten</li>
                            <li>Duidelijke dag-/weekstructuur per taak</li>
                        </ul>
                    </div>
                    
                    <div class="soft-card bg-white/85 border border-fuchsia-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Prioriteitenbeheer</h3>
                        <p class="text-gray-600">Stel prioriteiten en deadlines in en houd focus op urgente en belangrijke taken.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Urgentie-overzicht voor leidinggevenden</li>
                            <li>Snelle opvolging van achterstallige taken</li>
                            <li>Duidelijke statusflow van open tot goedgekeurd</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Collaboration Features -->
            <div class="feature-section hidden" data-category="collaboration">
                <div class="text-center mb-14 reveal">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Teamsamenwerking</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Werk soepel samen met praktische tools voor teamcommunicatie</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="soft-card bg-white/85 border border-blue-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Team workspaces</h3>
                        <p class="text-gray-600">Richt aparte werkruimtes in per team of project met duidelijke toegangsrechten.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Admin en medewerker-rollen gescheiden</li>
                            <li>Toewijzen op team, locatie of afdeling</li>
                            <li>Beter overzicht in grotere organisaties</li>
                        </ul>
                    </div>
                    
                    <div class="soft-card bg-white/85 border border-emerald-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Realtime opmerkingen</h3>
                        <p class="text-gray-600">Werk samen op taken met reacties en snelle meldingen bij updates van teamleden.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Snel feedback geven op taakbewijs</li>
                            <li>Meldingen bij afkeuren of opnieuw doen</li>
                            <li>Minder miscommunicatie in uitvoering</li>
                        </ul>
                    </div>
                    
                    <div class="soft-card bg-white/85 border border-fuchsia-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Bestanden delen</h3>
                        <p class="text-gray-600">Voeg bestanden toe aan taken en deel documenten met je team op een centrale plek.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Foto en document bewijs per taak</li>
                            <li>Altijd traceerbaar wie wat heeft toegevoegd</li>
                            <li>Sneller reviewen door managers</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Analytics Features -->
            <div class="feature-section hidden" data-category="analytics">
                <div class="text-center mb-14 reveal">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Analytics en rapportages</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Krijg inzicht in productiviteit, voortgang en kwaliteit van uitvoering</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="soft-card bg-white/85 border border-blue-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Performance dashboards</h3>
                        <p class="text-gray-600">Visuele dashboards met teamproductiviteit, afrondingspercentages en projectvoortgang.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Inzicht per locatie, team en periode</li>
                            <li>Snelle signalering van risico's</li>
                            <li>Beter plannen op basis van data</li>
                        </ul>
                    </div>
                    
                    <div class="soft-card bg-white/85 border border-emerald-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Tijdregistratie</h3>
                        <p class="text-gray-600">Volg bestede tijd per taak en project en gebruik rapportages voor analyse en planning.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Werkdruk eerlijker verdelen</li>
                            <li>Inzicht in bottlenecks</li>
                            <li>Betere capaciteitsplanning</li>
                        </ul>
                    </div>
                    
                    <div class="soft-card bg-white/85 border border-fuchsia-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Rapporten op maat</h3>
                        <p class="text-gray-600">Maak rapporten met filters, periodes en exports om sneller bij te sturen op resultaten.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Rapportage voor interne audits</li>
                            <li>Export voor managementoverleg</li>
                            <li>Duidelijke trendanalyse over tijd</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Automation Features -->
            <div class="feature-section hidden" data-category="automation">
                <div class="text-center mb-14 reveal">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Automatisering en workflows</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Stroomlijn processen met slimme automatisering en triggers</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="soft-card bg-white/85 border border-blue-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 13h6V7H4v6zM4 5h6V1H4v4zM10 3h4v4h-4V3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Workflows op maat</h3>
                        <p class="text-gray-600">Activeer automatisch acties op basis van taakstatus, deadlines en voorwaarden.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Standaard opvolging zonder handwerk</li>
                            <li>Consistente processen per team</li>
                            <li>Minder operationele fouten</li>
                        </ul>
                    </div>
                    
                    <div class="soft-card bg-white/85 border border-emerald-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 13h6V7H4v6zM4 5h6V1H4v4zM10 3h4v4h-4V3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Slimme meldingen</h3>
                        <p class="text-gray-600">Ontvang relevante meldingen over deadlines, updates en kritieke gebeurtenissen.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Snelle actie op afwijkingen</li>
                            <li>Meldingen voor managers en medewerkers</li>
                            <li>Minder gemiste deadlines</li>
                        </ul>
                    </div>
                    
                    <div class="soft-card bg-white/85 border border-fuchsia-100 rounded-2xl p-8 reveal">
                        <div class="w-16 h-16 mb-6 flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Procesintegratie</h3>
                        <p class="text-gray-600">Koppel interne processen en standaardiseer opvolging tussen teams en taken.</p>
                        <ul class="mt-4 text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Snellere implementatie van standaarden</li>
                            <li>Gelijke werkwijze over meerdere locaties</li>
                            <li>Betrouwbare kwaliteitsborging</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="text-center py-16 reveal">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">Klaar om deze functies in de praktijk te gebruiken?</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Start vandaag en ontdek hoe TaskCheck je team helpt sneller, consistenter en met meer kwaliteit te werken.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/pricing') }}" class="btn-gradient text-white px-8 py-4 rounded-lg font-semibold text-lg">
                    Probeer 1 maand gratis
                </a>
                <a href="{{ url('/contact') }}" class="bg-white text-gray-700 px-8 py-4 rounded-lg font-semibold text-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    Plan een demo
                </a>
            </div>
        </div>
    </section>

    <!-- JavaScript for Feature Categories -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryButtons = document.querySelectorAll('.feature-category');
            const featureSections = document.querySelectorAll('.feature-section');
            const revealEls = document.querySelectorAll('.reveal');

            // Scroll reveal
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.14 });
            revealEls.forEach(el => observer.observe(el));

            // Set first category active by default
            const firstButton = categoryButtons[0];
            if (firstButton) {
                firstButton.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
            }

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
                    const targetSection = document.querySelector(`#features-container .feature-section[data-category="${category}"]`);
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
</body>
</html>
