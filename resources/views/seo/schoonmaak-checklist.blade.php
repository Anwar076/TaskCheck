@php
    $seoTitle       = 'Schoonmaak Checklist App | Digitale Schoonmaakcontrole | TaskCheck';
        $seoDescription = 'Werk met digitale schoonmaak checklists en bewijs per taak. Controleer schoonmaakwerkzaamheden eenvoudig met TaskCheck. Start 14 dagen gratis.';
        $seoKeywords    = 'schoonmaak checklist, schoonmaak checklist app, schoonmaak controle app, digitale schoonmaak checklist, schoonmaak inspectie app, schoonmaak takenlijst';
        $seoUrl         = route('seo.schoonmaak-checklist');
        $seoImage       = asset('images/taskcheck-schoonmaak-seo-hero.webp');
        $faqItems = [
            ['Wat is een schoonmaak checklist?', 'Een schoonmaak checklist is een overzicht van schoonmaaktaken die uitgevoerd en gecontroleerd moeten worden.'],
            ['Kan ik foto\'s toevoegen als bewijs?', 'Ja. Medewerkers kunnen foto\'s, video\'s, opmerkingen en handtekeningen toevoegen.'],
            ['Is TaskCheck geschikt voor meerdere locaties?', 'Ja. Je kunt meerdere locaties beheren vanuit één centraal dashboard.'],
            ['Kan ik eigen schoonmaak checklists maken?', 'Ja. Je kunt volledig eigen checklists samenstellen voor jouw organisatie.'],
            ['Is TaskCheck geschikt voor schoonmaakbedrijven?', 'Ja. TaskCheck is speciaal geschikt voor schoonmaakbedrijven die werkzaamheden willen controleren en aantonen.'],
        ];
        $ctaHeading = 'Start vandaag met digitale schoonmaak checklists';
        $ctaLead = 'Wil je meer grip op schoonmaakwerkzaamheden en minder discussie over uitgevoerd werk? Met TaskCheck maak je binnen enkele minuten professionele schoonmaak checklists voor jouw organisatie.';
        $seoTheme = 'schoonmaak';
@endphp

@extends('layouts.seo-page')

@push('head')
<script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            @foreach($faqItems as [$q, $a])
            {
                "@@type": "Question",
                "name": @json($q),
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": @json($a)
                }
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }
    </script>
@endpush

@section('content')
<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="seo-schoonmaak-cl-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-schoonmaak-cl-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(5,150,105,.1)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0 fade-up">
                <p class="blog-kicker fade-up mb-6 sm:mb-7">Schoonmaak checklist · bewijs per taak</p>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Digitale Schoonmaak Checklist voor
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#059669,#0891b2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Bedrijven</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-schoonmaak-cl-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-schoonmaak-cl-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#059669"/>
                                    <stop offset="100%" stop-color="#0891b2"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Met een digitale schoonmaak checklist zorg je ervoor dat schoonmaakwerkzaamheden altijd volgens afspraak worden uitgevoerd.
                </p>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500">
                    TaskCheck helpt schoonmaakbedrijven, horeca, kantoren, zorginstellingen en andere organisaties om schoonmaakcontroles digitaal vast te leggen en aantoonbaar te maken.
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:flex-wrap">
                    @auth
                        <a href="{{ auth()->user()->homeDashboardUrl() }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Naar dashboard
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Start 14 dagen gratis
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endauth
                    <a href="{{ route('contact') }}" class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl border border-[#e6e8ec] bg-white px-6 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 sm:w-auto sm:min-h-0 touch-manipulation">
                        Plan een demo
                    </a>
                </div>

                <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 sm:mt-6 sm:gap-x-6">
                    @foreach(['Geen creditcard nodig','14 dagen gratis proberen','Foto- en videobewijs'] as $b)
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $b }}
                    </span>
                    @endforeach
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-xl lg:mx-0 lg:max-w-none">
                <div class="seo-hero-frame">
                        <img src="{{ asset('images/taskcheck-schoonmaak-seo-hero.webp') }}"
                             alt="Digitale schoonmaak checklist op mobiel"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="800"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Afvinken op locatie — met bewijs waar jij dat verplicht stelt.</p>
            </div>
        </div>
    </div>
</section>

<x-seo-product-visuals theme="schoonmaak" placement="showcase" />

<div>
<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-start gap-16 lg:grid-cols-2">
            <div>
                <p class="blog-kicker mb-3">Waarom digitaal</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Waarom een schoonmaak checklist gebruiken?</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Veel bedrijven werken nog met papieren lijsten of losse Excel-bestanden. Met TaskCheck werk je met vaste digitale schoonmaak checklists die medewerkers eenvoudig kunnen afvinken via mobiel, tablet of computer.
                </p>
            </div>
            <div class="space-y-3">
                @foreach([
                    'Taken worden vergeten',
                    'Geen bewijs van uitgevoerde werkzaamheden',
                    'Klachten van klanten',
                    'Geen overzicht bij meerdere locaties',
                    'Onduidelijkheid tussen medewerkers',
                ] as $problem)
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">{{ $problem }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="blog-kicker mb-3">Basis</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Wat is een schoonmaak checklist?</h2>
            <p class="mt-4 text-lg text-slate-500">
                Een schoonmaak checklist is een overzicht van taken die uitgevoerd moeten worden om een ruimte schoon en hygiënisch te houden. Met TaskCheck leg je deze werkzaamheden digitaal vast.
            </p>
        </div>
        <ul class="mx-auto mt-10 grid max-w-4xl gap-3 sm:grid-cols-2">
            @foreach([
                'Vloeren reinigen',
                'Werkbladen schoonmaken',
                'Toiletten reinigen',
                'Prullenbakken legen',
                'Ramen controleren',
                'Apparatuur reinigen',
                'Desinfecteren van contactpunten',
            ] as $item)
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                {{ $item }}
            </li>
            @endforeach
        </ul>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <p class="blog-kicker mb-3">Bewijs</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Schoonmaakwerkzaamheden aantoonbaar maken</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Een veelvoorkomend probleem is dat werk wel uitgevoerd wordt, maar niet aantoonbaar is. Met TaskCheck kan een medewerker per taak:
                </p>
                <ul class="mt-6 space-y-3">
                    @foreach(['Een foto toevoegen', 'Een video uploaden', 'Een notitie plaatsen', 'Een handtekening toevoegen'] as $proof)
                    <li class="flex items-center gap-3 text-sm text-slate-700">
                        <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $proof }}
                    </li>
                    @endforeach
                </ul>
                <p class="mt-4 text-sm text-slate-600">Zo beschik je altijd over bewijs van uitgevoerde werkzaamheden.</p>
                <a href="{{ route('seo.checklist-app-met-foto-bewijs') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-blue-700 hover:underline">
                    Checklist app met foto bewijs
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
            <figure class="seo-hero-frame"><img src="{{ asset('images/seo-checklist-schoonmaak-workflow.png') }}"
                     alt="Schoonmaak checklist met fotobewijs"
                     loading="lazy" decoding="async" width="800" height="600"
                     class="w-full object-cover"></figure>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div class="order-2 lg:order-1 grid gap-3 sm:grid-cols-2">
                @foreach([
                    ['Uitgevoerd', 'Welke taken zijn uitgevoerd', 'text-emerald-600', 'bg-emerald-50'],
                    ['Open', 'Welke taken nog openstaan', 'text-amber-600', 'bg-amber-50'],
                    ['Achterstand', 'Welke locaties achterlopen', 'text-red-600', 'bg-red-50'],
                    ['Aandacht', 'Waar extra aandacht nodig is', 'text-blue-600', 'bg-blue-50'],
                ] as [$tag, $desc, $color, $bg])
                <div class="rounded-xl border border-slate-200 {{ $bg }} p-4">
                    <p class="text-sm font-bold {{ $color }}">{{ $tag }}</p>
                    <p class="mt-1 text-xs text-slate-600">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
            <div class="order-1 lg:order-2">
                <p class="blog-kicker mb-3">Locaties</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Schoonmaak controle per locatie</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Werk je op meerdere locaties? TaskCheck geeft realtime inzicht per locatie, afdeling of team — zonder achter papieren lijsten aan te gaan.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="feature-card rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <p class="text-sm font-bold uppercase tracking-wider text-emerald-600">Schoonmaakbedrijven</p>
                <h2 class="mt-2 text-2xl font-extrabold text-slate-900 sm:text-3xl">Voor schoonmaakbedrijven</h2>
                <p class="mt-3 text-slate-500">Voor schoonmaakbedrijven biedt TaskCheck extra voordelen:</p>
                <ul class="mt-6 space-y-4">
                    @foreach([
                        ['Meer controle', 'Controleer eenvoudig of werkzaamheden daadwerkelijk zijn uitgevoerd.'],
                        ['Minder klachten', 'Door bewijs per taak voorkom je discussies met opdrachtgevers.'],
                        ['Betere kwaliteit', 'Medewerkers werken volgens vaste procedures.'],
                        ['Snellere rapportages', 'Genereer eenvoudig overzichten voor klanten en opdrachtgevers.'],
                    ] as [$title, $desc])
                    <li>
                        <p class="text-sm font-semibold text-slate-900">{{ $title }}</p>
                        <p class="mt-0.5 text-sm text-slate-500">{{ $desc }}</p>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('seo.schoonmaak-controle-app') }}" class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-blue-700 hover:underline">
                    Schoonmaak controle app
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

            <div class="feature-card rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">Horeca &amp; overig</p>
                <h2 class="mt-2 text-2xl font-extrabold text-slate-900 sm:text-3xl">Voor horeca en andere bedrijven</h2>
                <p class="mt-3 text-slate-500">Ook horeca, kantoren, zorginstellingen en winkels gebruiken schoonmaak checklists. Veelgebruikte checklists zijn:</p>
                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach(['Dagelijkse schoonmaak checklist','Wekelijkse schoonmaak checklist','Keuken schoonmaak checklist','Toilet controle checklist','Sluitingschecklist','Hygiënecontrole'] as $chip)
                    <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-700">{{ $chip }}</span>
                    @endforeach
                </div>
                <a href="{{ route('seo.haccp-app') }}" class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-blue-700 hover:underline">
                    HACCP app voor horeca
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <p class="blog-kicker mb-3">Dashboard</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Realtime dashboard voor managers</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">Met TaskCheck zie je direct voltooide taken, openstaande taken, afgekeurde controles, foto bewijs en rapportages per locatie.</p>
                <p class="mt-3 text-slate-600">Daardoor heb je altijd overzicht over de kwaliteit van het schoonmaakwerk.</p>
                <a href="{{ route('seo.werkcontrole-app') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-blue-700 hover:underline">
                    Werkcontrole app
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-emerald-50/60 p-8">
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-600">Voordelen</p>
                <h3 class="mt-2 text-xl font-bold text-slate-900">Voordelen van een digitale schoonmaak checklist</h3>
                <div class="mt-5 grid gap-2 sm:grid-cols-2">
                    @foreach(['Minder papierwerk','Meer controle','Foto- en videobewijs','Realtime inzicht','Controle per locatie','Betere kwaliteit','Minder klachten','Professionele rapportages'] as $benefit)
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $benefit }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="blog-kicker mb-3">Doelgroep</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Voor welke bedrijven is TaskCheck geschikt?</h2>
            <p class="mt-4 text-lg text-slate-500">TaskCheck wordt gebruikt door:</p>
        </div>
        <div class="mx-auto mt-10 flex max-w-3xl flex-wrap justify-center gap-3">
            @foreach(['Schoonmaakbedrijven','Restaurants','Cafés','Hotels','Kantoorgebouwen','Winkels','Zorginstellingen','Scholen','Logistieke bedrijven'] as $target)
            <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">{{ $target }}</span>
            @endforeach
        </div>
        <p class="mx-auto mt-8 max-w-2xl text-center text-sm text-slate-500">
            Ontdek ook onze <a href="{{ route('seo.digitale-checklist-app') }}" class="font-medium text-blue-700 hover:underline">digitale checklist app</a> voor andere operationele processen.
        </p>
    </div>
</section>

<x-seo-product-visuals theme="schoonmaak" placement="story" />

<section class="bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <p class="blog-kicker mb-3">FAQ</p>
            <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Veelgestelde vragen</h2>
        </div>
        <div class="space-y-3">
            @foreach($faqItems as [$q, $a])
            <details class="group cursor-pointer rounded-2xl border border-slate-200 bg-white px-5 py-4 transition hover:border-emerald-200 sm:px-6">
                <summary class="flex list-none items-center justify-between gap-3 font-semibold text-slate-900">
                    <span class="text-left text-sm">{{ $q }}</span>
                    <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-45" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </summary>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $a }}</p>
            </details>
            @endforeach
        </div>
    </div>
</section>

<section class="border-t border-slate-200 bg-white py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="blog-kicker mx-auto">Gerelateerde pagina&rsquo;s</p>
        <div class="mx-auto mt-5 flex max-w-4xl flex-wrap justify-center gap-2">
            @foreach([
                ['Schoonmaak controle app', route('seo.schoonmaak-controle-app')],
                ['Werkcontrole app', route('seo.werkcontrole-app')],
                ['Checklist app met foto bewijs', route('seo.checklist-app-met-foto-bewijs')],
                ['Digitale checklist app', route('seo.digitale-checklist-app')],
                ['HACCP app', route('seo.haccp-app')],
                ['Prijzen', route('pricing')],
            ] as $link)
            <a href="{{ $link[1] }}" class="inline-flex items-center gap-1.5 rounded-full border border-[#e6e8ec] bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-[#d7e2f7] hover:text-blue-700">
                {{ $link[0] }}
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
            @endforeach
        </div>
    </div>
</section>
</div>
@endsection
