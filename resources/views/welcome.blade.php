<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Checklist app voor bedrijven, horeca en schoonmaak | TaskCheck';
        $seoDescription = 'TaskCheck is de checklist app voor bedrijven: takenlijst personeel beheren, werkcontrole uitvoeren en bewijs verzamelen met foto en video. Start 30 dagen gratis.';
        $seoUrl = route('welcome');
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
    <meta name="twitter:image:alt" content="TaskCheck checklist app voor bedrijven">

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "SoftwareApplication",
            "name": "TaskCheck",
            "applicationCategory": "BusinessApplication",
            "operatingSystem": "Web",
            "url": "{{ $seoUrl }}",
            "description": "{{ $seoDescription }}",
            "offers": {
                "@@type": "Offer",
                "price": "29",
                "priceCurrency": "EUR"
            }
        }
    </script>
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "Organization",
            "name": "TaskCheck",
            "url": "{{ $seoUrl }}",
            "logo": "{{ asset('icons/icon-192x192.png') }}",
            "sameAs": []
        }
    </script>
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "FAQPage",
            "mainEntity": [
                {
                    "@@type": "Question",
                    "name": "Voor welke bedrijven is TaskCheck geschikt?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "TaskCheck is geschikt voor horeca, schoonmaakbedrijven en andere operationele teams die met checklists, takenlijsten en werkcontrole werken."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "Kan ik bewijs per taak vastleggen?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Ja, per taak kun je bewijs verzamelen met foto, video, tekst of handtekening. Zo kun je uitvoering en kwaliteit aantoonbaar maken."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "Hoe start ik met TaskCheck?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Je kunt starten met een proefperiode van 30 dagen. Daarna kies je een abonnement dat past bij je team en bedrijfsgrootte."
                    }
                }
            ]
        }
    </script>
    <style>
        @keyframes floatY {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-14px); }
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: .55; }
            50% { opacity: .9; }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .float-soft {
            animation: floatY 7s ease-in-out infinite;
        }

        .float-soft-delayed {
            animation: floatY 8.5s ease-in-out infinite;
            animation-delay: 1.8s;
        }

        .pulse-glow {
            animation: pulseGlow 6s ease-in-out infinite;
        }

        .play-card {
            transition: transform .25s ease, box-shadow .25s ease;
            will-change: transform;
        }

        .play-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 20px 35px -24px rgba(37, 99, 235, .45);
        }

        .cta-btn {
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
        }

        .cta-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 24px -18px rgba(37, 99, 235, .55);
            filter: saturate(1.05);
        }

        .reveal {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity .55s ease, transform .55s ease;
        }

        .reveal.in-view {
            opacity: 1;
            transform: translateY(0);
        }

        .feature-pill {
            position: relative;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, background-color .2s ease;
        }

        .feature-pill::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(110deg, rgba(255,255,255,0) 20%, rgba(255,255,255,.5) 45%, rgba(255,255,255,0) 70%);
            transform: translateX(-130%);
            transition: transform .55s ease;
            pointer-events: none;
        }

        .feature-pill:hover::after {
            transform: translateX(130%);
        }

        .feature-pill.active {
            transform: translateY(-2px);
            box-shadow: 0 16px 30px -22px rgba(37, 99, 235, .5);
        }

        .feature-pill.active .feature-pill-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .feature-panel {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            border: 1px solid rgba(147, 197, 253, .45);
            background: linear-gradient(120deg, rgba(255,255,255,.92), rgba(239,246,255,.85), rgba(238,242,255,.88));
            box-shadow: 0 20px 40px -30px rgba(37, 99, 235, .35);
        }

        .feature-panel::before {
            content: "";
            position: absolute;
            width: 12rem;
            height: 12rem;
            border-radius: 9999px;
            background: radial-gradient(circle, rgba(125,211,252,.34) 0%, rgba(125,211,252,0) 70%);
            top: -4rem;
            right: -3rem;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-sky-50 via-indigo-50 to-fuchsia-50 text-slate-900 min-h-screen font-sans">

    @include('components.header')

    <section class="relative overflow-hidden pt-28 pb-20">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 h-96 w-96 rounded-full bg-cyan-300/30 blur-3xl float-soft pulse-glow"></div>
        <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-pink-300/30 blur-3xl float-soft-delayed pulse-glow"></div>
        <div class="relative max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="reveal">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-blue-200 bg-white/80 text-xs text-slate-700 mb-5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Slimme checklists voor operationele teams
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-emerald-200 bg-emerald-50 text-xs text-emerald-700 mb-5 ml-2">
                        <span class="text-emerald-600">🎉</span>
                        Probeer gratis voor 1 maand
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.14] sm:leading-[1.1] lg:leading-[1.08] tracking-tight text-slate-900">
                        Checklist app voor bedrijven
                        <span class="block mt-1 sm:mt-2 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-pink-600">
                            met taken, controle en bewijs
                        </span>
                    </h1>
                    <p class="mt-6 text-slate-700 text-lg max-w-xl">
                        Beheer taken, stuur personeel aan en verzamel bewijs per taak met foto of video. TaskCheck maakt werkcontrole eenvoudig voor horeca, schoonmaak en andere operationele teams.
                    </p>
                    <div class="mt-6 grid sm:grid-cols-2 gap-3 max-w-2xl text-sm text-slate-700">
                        <div class="group inline-flex items-center gap-3 rounded-xl border border-blue-100/90 bg-white/90 px-4 py-3 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                            <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M4 7a2 2 0 0 1 2-2h2l1-1h6l1 1h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="12" r="3.2" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                            </span>
                            <span><strong class="font-semibold text-slate-900">Bewijs per taak</strong><span class="block text-slate-600">Foto, video, tekst of handtekening</span></span>
                        </div>
                        <div class="group inline-flex items-center gap-3 rounded-xl border border-emerald-100/90 bg-white/90 px-4 py-3 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
                            <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M13 2 5 14h6l-1 8 9-13h-6l1-7Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <span><strong class="font-semibold text-slate-900">Realtime overzicht</strong><span class="block text-slate-600">Live voortgang voor managers en teams</span></span>
                        </div>
                    </div>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="cta-btn inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold transition">
                                Naar dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="cta-btn inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold transition">
                                Probeer 1 maand gratis
                            </a>
                        @endauth
                        <a href="{{ route('pricing') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 text-slate-700 font-semibold transition">
                            Bekijk prijzen
                        </a>
                    </div>
                    <div class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl">
                        <div class="play-card rounded-xl border border-blue-100 bg-white/90 p-4 shadow-sm">
                            <p class="text-2xl font-bold text-blue-600" id="live-users">1,247</p>
                            <p class="text-xs text-slate-500 mt-1">Actieve gebruikers</p>
                        </div>
                        <div class="play-card rounded-xl border border-emerald-100 bg-white/90 p-4 shadow-sm">
                            <p class="text-2xl font-bold text-emerald-600" id="live-tasks">15,892</p>
                            <p class="text-xs text-slate-500 mt-1">Taken afgerond</p>
                        </div>
                        <div class="play-card rounded-xl border border-indigo-100 bg-white/90 p-4 shadow-sm">
                            <p class="text-2xl font-bold text-indigo-600" id="live-teams">342</p>
                            <p class="text-xs text-slate-500 mt-1">Teams actief</p>
                        </div>
                        <div class="play-card rounded-xl border border-amber-100 bg-white/90 p-4 shadow-sm">
                            <p class="text-2xl font-bold text-amber-600" id="live-hours">2,847</p>
                            <p class="text-xs text-slate-500 mt-1">Uren bespaard</p>
                        </div>
                    </div>
                </div>

                <div class="reveal rounded-2xl border border-blue-100 bg-white/90 p-6 shadow-xl">
                    <h2 class="text-xl font-semibold text-slate-900">Live operationeel overzicht</h2>
                    <p class="text-sm text-slate-600 mt-1">Eenzelfde platform voor manager en werkvloer.</p>
                    <div class="mt-5 space-y-3 text-sm">
                        <div class="play-card rounded-xl bg-blue-50 border border-blue-100 p-4">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-slate-900">Vandaag open taken</p>
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">24</span>
                            </div>
                            <p class="text-slate-600 mt-1">Automatisch gegroepeerd per team en locatie.</p>
                        </div>
                        <div class="play-card rounded-xl bg-indigo-50 border border-indigo-100 p-4">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-slate-900">Bewijs ontbreekt</p>
                                <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-700">5</span>
                            </div>
                            <p class="text-slate-600 mt-1">Direct zichtbaar welke taken nagekeken moeten worden.</p>
                        </div>
                        <div class="play-card rounded-xl bg-fuchsia-50 border border-fuchsia-100 p-4">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-slate-900">AI import actief</p>
                                <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Aan</span>
                            </div>
                            <p class="text-slate-600 mt-1">Upload PDF/Excel/foto en genereer lijsten in seconden.</p>
                        </div>
                    </div>
                    <div class="mt-6 rounded-xl border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-4">
                        <p class="text-sm text-blue-800 font-medium">Installeer als app op mobiel of desktop</p>
                        <p class="text-xs text-blue-700 mt-1">Gebruik TaskCheck zonder browserbalk voor sneller werken op de werkvloer.</p>
                        <button id="install-hero-button" class="mt-3 w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-semibold transition">
                            App installeren
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-8 reveal">
        <div class="grid md:grid-cols-3 gap-4">
            <div class="play-card rounded-2xl border border-blue-100 bg-white/85 p-5">
                <p class="text-xs font-semibold text-blue-700">Stap 1</p>
                <h3 class="font-semibold text-slate-900 mt-1">Bouw of importeer je lijst</h3>
                <p class="text-sm text-slate-600 mt-1.5">Start handmatig of gebruik AI-import vanuit document/foto.</p>
            </div>
            <div class="play-card rounded-2xl border border-indigo-100 bg-white/85 p-5">
                <p class="text-xs font-semibold text-indigo-700">Stap 2</p>
                <h3 class="font-semibold text-slate-900 mt-1">Wijs toe aan teams</h3>
                <p class="text-sm text-slate-600 mt-1.5">Geef per taak bewijsregels, verplichte checks en planning mee.</p>
            </div>
            <div class="play-card rounded-2xl border border-fuchsia-100 bg-white/85 p-5">
                <p class="text-xs font-semibold text-fuchsia-700">Stap 3</p>
                <h3 class="font-semibold text-slate-900 mt-1">Monitor en verbeter</h3>
                <p class="text-sm text-slate-600 mt-1.5">Volg voortgang live en stuur bij op kwaliteit en snelheid.</p>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-4 reveal">
        <div class="grid lg:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-indigo-100 bg-white/85 p-5">
                <p class="text-xs font-semibold text-indigo-700">Voor operationeel management</p>
                <p class="text-sm text-slate-600 mt-1">Krijg direct zicht op open taken, ontbrekend bewijs en kwaliteitsafwijkingen per locatie.</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white/85 p-5">
                <p class="text-xs font-semibold text-emerald-700">Voor teams op de werkvloer</p>
                <p class="text-sm text-slate-600 mt-1">Werk met duidelijke instructies en lever snel bewijs aan via mobiel of desktop.</p>
            </div>
            <div class="rounded-2xl border border-fuchsia-100 bg-white/85 p-5">
                <p class="text-xs font-semibold text-fuchsia-700">Voor groei en standaardisatie</p>
                <p class="text-sm text-slate-600 mt-1">Gebruik AI-import en templates om processen sneller op te zetten en gelijk te houden.</p>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-10 reveal">
        <div class="grid md:grid-cols-3 gap-4">
            <a href="{{ route('seo.horeca-checklist-app') }}" class="rounded-2xl border border-blue-100 bg-white/85 p-5 hover:bg-white transition">
                <p class="text-xs font-semibold text-blue-700">Horeca</p>
                <h3 class="mt-1 font-semibold text-slate-900">Restaurant checklist en keukencontrole</h3>
                <p class="text-sm text-slate-600 mt-1">Standaardiseer opening, service en sluiting met duidelijk bewijs per taak.</p>
            </a>
            <a href="{{ route('seo.schoonmaak-checklist-app') }}" class="rounded-2xl border border-emerald-100 bg-white/85 p-5 hover:bg-white transition">
                <p class="text-xs font-semibold text-emerald-700">Schoonmaak</p>
                <h3 class="mt-1 font-semibold text-slate-900">Controle per locatie en ronde</h3>
                <p class="text-sm text-slate-600 mt-1">Werk met vaste lijsten, kwaliteitscontrole en rapportage richting opdrachtgevers.</p>
            </a>
            <a href="{{ route('seo.werkcontrole-app') }}" class="rounded-2xl border border-fuchsia-100 bg-white/85 p-5 hover:bg-white transition">
                <p class="text-xs font-semibold text-fuchsia-700">Alle bedrijven</p>
                <h3 class="mt-1 font-semibold text-slate-900">Werkcontrole app voor operationele teams</h3>
                <p class="text-sm text-slate-600 mt-1">Krijg realtime zicht op uitvoering, afwijkingen en teamproductiviteit.</p>
            </a>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 pb-12 reveal">
        <div class="rounded-2xl border border-blue-100 bg-white/90 p-6 sm:p-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Checklist app voor bedrijven die operationeel willen groeien</h2>
            <p class="mt-3 text-slate-600">TaskCheck helpt organisaties om takenlijsten voor personeel centraal te beheren en werkcontrole aantoonbaar te maken. Voor horeca teams betekent dit grip op opening, keukencontrole en sluitrondes. Voor schoonmaakbedrijven betekent dit consistente kwaliteitscontrole per locatie met bewijs per taak. Ook andere bedrijven met terugkerende operationele processen gebruiken TaskCheck om fouten te verminderen en productiviteit te verhogen.</p>
            <p class="mt-3 text-slate-600">Met realtime dashboards zie je direct welke taken zijn afgerond, welke controles ontbreken en waar je moet bijsturen. Daardoor werk je niet langer op onderbuikgevoel, maar op actuele data. Bekijk ook de pagina’s <a href="{{ route('seo.horeca-checklist-app') }}" class="text-blue-700 font-semibold hover:text-blue-800">horeca checklist app</a>, <a href="{{ route('seo.schoonmaak-checklist-app') }}" class="text-blue-700 font-semibold hover:text-blue-800">schoonmaak checklist app</a>, <a href="{{ route('seo.werkcontrole-app') }}" class="text-blue-700 font-semibold hover:text-blue-800">werkcontrole app</a> en <a href="{{ route('seo.takenlijst-personeel') }}" class="text-blue-700 font-semibold hover:text-blue-800">takenlijst personeel</a> voor sectorgerichte informatie.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-16 reveal">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">Waarom TaskCheck werkt in de praktijk</h2>
            <p class="mt-3 text-slate-600 max-w-2xl mx-auto">Ontworpen voor echte operationele flows: eenvoudig voor medewerkers, krachtig voor management.</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="play-card rounded-2xl border border-blue-100 bg-white/90 p-6 shadow-sm">
                <h3 class="font-semibold text-slate-900">Slim lijstbeheer</h3>
                <p class="text-sm text-slate-600 mt-2">Bouw checklists per team, locatie of dag met duidelijke prioriteiten en planning.</p>
            </div>
            <div class="play-card rounded-2xl border border-emerald-100 bg-white/90 p-6 shadow-sm">
                <h3 class="font-semibold text-slate-900">Bewijs & validatie</h3>
                <p class="text-sm text-slate-600 mt-2">Stel per taak bewijs in: foto, video, tekst, bestand of handtekening.</p>
            </div>
            <div class="play-card rounded-2xl border border-fuchsia-100 bg-white/90 p-6 shadow-sm">
                <h3 class="font-semibold text-slate-900">AI lijst import</h3>
                <p class="text-sm text-slate-600 mt-2">Upload PDF, Excel, Word of foto en laat AI direct werkbare lijsten voorstellen.</p>
            </div>
            <div class="play-card rounded-2xl border border-indigo-100 bg-white/90 p-6 shadow-sm">
                <h3 class="font-semibold text-slate-900">Realtime monitoring</h3>
                <p class="text-sm text-slate-600 mt-2">Zie direct wat klaar is, wat achterloopt en waar actie nodig is.</p>
            </div>
            <div class="play-card rounded-2xl border border-amber-100 bg-white/90 p-6 shadow-sm">
                <h3 class="font-semibold text-slate-900">Teamgericht werken</h3>
                <p class="text-sm text-slate-600 mt-2">Wijs taken toe per medewerker of afdeling met duidelijke opvolging.</p>
            </div>
            <div class="play-card rounded-2xl border border-cyan-100 bg-white/90 p-6 shadow-sm">
                <h3 class="font-semibold text-slate-900">Betrouwbaar en schaalbaar</h3>
                <p class="text-sm text-slate-600 mt-2">Gebouwd voor dagelijks gebruik in professionele operationele omgevingen.</p>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 pb-12 reveal">
        <div class="rounded-2xl border border-indigo-100 bg-white/90 p-6 sm:p-8">
            <h2 class="text-2xl font-bold text-slate-900">Lees meer over checklist apps en werkcontrole</h2>
            <p class="text-slate-600 mt-2">Praktische artikelen voor horeca, schoonmaak en teams die stoppen met Excel.</p>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('blog') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Naar blog</a>
                <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-blue-200 text-slate-700 hover:bg-blue-50 transition">Schoonmaak artikel</a>
                <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-blue-200 text-slate-700 hover:bg-blue-50 transition">Stoppen met Excel</a>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-10 reveal">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">Alle functies op een plek</h2>
            <p class="mt-3 text-slate-600 max-w-2xl mx-auto">Van taakbeheer tot automatisering: kies een categorie en bekijk wat TaskCheck direct voor je team oplost.</p>
        </div>

        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-white/85 px-4 py-2 text-sm text-indigo-700 font-medium">
            <span>👆</span>
            Klik op een categorie om de details te zien
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <button class="home-feature-category feature-pill bg-white/90 border border-blue-100 rounded-xl p-4 text-left" data-category="management">
                <div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-700 mb-2">
                    <span>✅</span>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <h3 class="font-semibold text-slate-900">Taakbeheer</h3>
                    <span class="feature-pill-arrow text-blue-500 text-xs opacity-40 -translate-x-1 transition">Bekijk</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Lijsten, bewijs, prioriteiten</p>
            </button>
            <button class="home-feature-category feature-pill bg-white/90 border border-emerald-100 rounded-xl p-4 text-left" data-category="collaboration">
                <div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 mb-2">
                    <span>🤝</span>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <h3 class="font-semibold text-slate-900">Samenwerking</h3>
                    <span class="feature-pill-arrow text-emerald-500 text-xs opacity-40 -translate-x-1 transition">Bekijk</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Teams, rollen, feedback</p>
            </button>
            <button class="home-feature-category feature-pill bg-white/90 border border-fuchsia-100 rounded-xl p-4 text-left" data-category="analytics">
                <div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-fuchsia-100 text-fuchsia-700 mb-2">
                    <span>📊</span>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <h3 class="font-semibold text-slate-900">Analytics</h3>
                    <span class="feature-pill-arrow text-fuchsia-500 text-xs opacity-40 -translate-x-1 transition">Bekijk</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Inzicht, trends, rapportages</p>
            </button>
            <button class="home-feature-category feature-pill bg-white/90 border border-amber-100 rounded-xl p-4 text-left" data-category="automation">
                <div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 text-amber-700 mb-2">
                    <span>⚡</span>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <h3 class="font-semibold text-slate-900">Automatisering</h3>
                    <span class="feature-pill-arrow text-amber-500 text-xs opacity-40 -translate-x-1 transition">Bekijk</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Workflows en meldingen</p>
            </button>
        </div>

        <div id="home-features-container" class="feature-panel p-5 sm:p-6">
            <div class="mb-4 inline-flex items-center gap-2 rounded-lg border border-blue-100 bg-white/80 px-3 py-2 text-sm text-slate-700">
                <span class="text-slate-500">Geselecteerd:</span>
                <span id="home-feature-active-label" class="font-semibold text-blue-700">Taakbeheer</span>
            </div>
            <div class="home-feature-section" data-category="management">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="play-card rounded-2xl border border-blue-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Slimme taakaanmaak</h3>
                        <p class="text-sm text-slate-600 mt-2">Maak taken met duidelijke omschrijving, planning, bewijs-type, checklist-items en validatieregels.</p>
                    </div>
                    <div class="play-card rounded-2xl border border-indigo-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Lijsten en templates</h3>
                        <p class="text-sm text-slate-600 mt-2">Herbruikbare structuren voor dagelijkse, wekelijkse en locatiegebonden workflows.</p>
                    </div>
                    <div class="play-card rounded-2xl border border-cyan-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Duidelijke prioriteit</h3>
                        <p class="text-sm text-slate-600 mt-2">Sorteer op urgentie en volgorde zodat teams altijd weten wat eerst moet gebeuren.</p>
                    </div>
                </div>
            </div>

            <div class="home-feature-section hidden" data-category="collaboration">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="play-card rounded-2xl border border-emerald-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Team workspaces</h3>
                        <p class="text-sm text-slate-600 mt-2">Houd teams, locaties en verantwoordelijkheden overzichtelijk gescheiden.</p>
                    </div>
                    <div class="play-card rounded-2xl border border-blue-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Review en feedback</h3>
                        <p class="text-sm text-slate-600 mt-2">Geef snel terugkoppeling op bewijs, keur goed of vraag gericht heruitvoering aan.</p>
                    </div>
                    <div class="play-card rounded-2xl border border-fuchsia-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Rollen en rechten</h3>
                        <p class="text-sm text-slate-600 mt-2">Admin en medewerkers werken elk in een duidelijke en veilige omgeving.</p>
                    </div>
                </div>
            </div>

            <div class="home-feature-section hidden" data-category="analytics">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="play-card rounded-2xl border border-fuchsia-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Realtime dashboards</h3>
                        <p class="text-sm text-slate-600 mt-2">Volg live wat afgerond is, achterloopt of extra aandacht nodig heeft.</p>
                    </div>
                    <div class="play-card rounded-2xl border border-indigo-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Rapportages per team</h3>
                        <p class="text-sm text-slate-600 mt-2">Krijg inzicht in kwaliteit, doorlooptijd en productiviteit per medewerker en afdeling.</p>
                    </div>
                    <div class="play-card rounded-2xl border border-amber-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Trendanalyse</h3>
                        <p class="text-sm text-slate-600 mt-2">Gebruik historische data om processen slimmer te plannen en te verbeteren.</p>
                    </div>
                </div>
            </div>

            <div class="home-feature-section hidden" data-category="automation">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="play-card rounded-2xl border border-amber-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Slimme meldingen</h3>
                        <p class="text-sm text-slate-600 mt-2">Ontvang automatische alerts bij deadlines, ontbrekend bewijs, afkeur of afwijkingen.</p>
                    </div>
                    <div class="play-card rounded-2xl border border-blue-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">Workflow triggers</h3>
                        <p class="text-sm text-slate-600 mt-2">Activeer vervolgacties op basis van status, planning of teamregels.</p>
                    </div>
                    <div class="play-card rounded-2xl border border-emerald-100 bg-white/95 p-5 shadow-sm">
                        <h3 class="font-semibold text-slate-900">AI import flows</h3>
                        <p class="text-sm text-slate-600 mt-2">Upload PDF, Excel, Word of foto en zet bronmateriaal direct om naar werkbare lijsten.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-blue-200 bg-white hover:bg-blue-50 text-slate-700 font-semibold transition">
                Probeer 30 dagen gratis
            </a>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-16 reveal">
        <div class="rounded-3xl border border-blue-100 bg-gradient-to-r from-blue-50 via-indigo-50 to-fuchsia-50 p-8 sm:p-10 shadow-sm">
            <div class="grid lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">Klaar om je operationele kwaliteit te verhogen?</h2>
                    <p class="text-slate-700 mt-3">Werk met duidelijke taken, meetbare voortgang en minder discussie over uitvoering. Start direct met Home, Prijzen en Contact.</p>
                </div>
                <div class="flex flex-wrap gap-3 lg:justify-end">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="cta-btn inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold transition">Open dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="cta-btn inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold transition">Probeer 1 maand gratis</a>
                    @endauth
                    <a href="mailto:admin@taskcheck.com" class="inline-flex items-center px-6 py-3 rounded-xl border border-indigo-200 bg-white text-slate-700 font-semibold hover:bg-indigo-50 transition">Contact sales</a>
                </div>
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

            // Scroll reveal
            const revealEls = document.querySelectorAll('.reveal');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.16 });
            revealEls.forEach(el => observer.observe(el));

            const homeFeatureButtons = document.querySelectorAll('.home-feature-category');
            const homeFeatureSections = document.querySelectorAll('.home-feature-section');
            const firstFeatureButton = homeFeatureButtons[0];
            const featureActiveLabel = document.getElementById('home-feature-active-label');

            if (firstFeatureButton) {
                firstFeatureButton.classList.add('active', 'ring-2', 'ring-blue-500', 'bg-blue-50');
            }

            homeFeatureButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    const category = this.dataset.category;
                    const label = this.querySelector('h3');

                    homeFeatureButtons.forEach((btn) => {
                        btn.classList.remove('active', 'ring-2', 'ring-blue-500', 'bg-blue-50');
                    });

                    this.classList.add('active', 'ring-2', 'ring-blue-500', 'bg-blue-50');

                    homeFeatureSections.forEach((section) => {
                        section.classList.add('hidden');
                    });

                    const target = document.querySelector(`#home-features-container .home-feature-section[data-category="${category}"]`);
                    if (target) {
                        target.classList.remove('hidden');
                    }

                    if (featureActiveLabel && label) {
                        featureActiveLabel.textContent = label.textContent.trim();
                    }
                });
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
