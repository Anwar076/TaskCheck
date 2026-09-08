@php
    $seoTitle       = "Welke software voor schoonmaakbedrijven werkt goed op mobiel?";
        $seoDescription = "Ontdek TaskCheck: dé mobiele software voor digitale schoonmaaklijsten en controles. Start nu gratis en werk direct efficiënter.";
        $seoKeywords    = "schoonmaaksoftware mobiel, digitale schoonmaaklijst, HACCP app, NVWA checklist, horeca schoonmaak app, schoonmaakbedrijf software";
        $seoUrl         = route('seo.welke-software-voor-schoonmaakbedrijven-werkt-goed-op-mobiel');
        $seoImage       = asset('images/taskcheck-horeca-seo-hero.webp');
        $faqItems = [
            [
                        "Welke software werkt goed op mobiel voor schoonmaakbedrijven?",
                        "TaskCheck is volledig mobiel te gebruiken. Je voert schoonmaak- en andere controles direct uit op je smartphone of tablet, waar je ook bent."
            ],
            [
                        "Kan ik met TaskCheck voldoen aan HACCP- en NVWA-eisen?",
                        "Ja, TaskCheck is ontwikkeld voor horeca en voldoet aan de eisen voor HACCP en NVWA. Je registreert alle controles digitaal en hebt altijd een compleet rapport."
            ],
            [
                        "Kan ik foto's of video's toevoegen als bewijs?",
                        "Ja, aan elke taak of controle kun je direct foto’s, video’s, notities of handtekeningen toevoegen als bewijslast."
            ],
            [
                        "Is TaskCheck geschikt voor meerdere locaties?",
                        "Jazeker. Je beheert checklists, taken en rapportages eenvoudig per locatie, bijvoorbeeld voor meerdere vestigingen of filialen."
            ],
            [
                        "Heb ik technische kennis nodig om te starten?",
                        "Nee, TaskCheck is eenvoudig in gebruik en binnen enkele minuten klaar voor jouw bedrijf. Je kunt direct starten met de gratis proefperiode."
            ],
            [
                        "Welke schoonmaaktaken kan ik digitaliseren?",
                        "Alle denkbare taken: van dagelijkse schoonmaak, periodieke controles en temperatuurmetingen tot aan voorraadbeheer en sluitrondes."
            ]
];
        $ctaHeading = 'Start met mobiele schoonmaaksoftware';
        $ctaLead = 'Probeer TaskCheck 14 dagen gratis en ontdek zelf het gemak van digitale schoonmaaklijsten en HACCP-controles. Geen creditcard nodig.';
        $seoTheme = 'schoonmaak';
@endphp

@extends('layouts.seo-page')

@push('head')
<script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            {
                "@@type": "Question",
                "name": "Welke software werkt goed op mobiel voor schoonmaakbedrijven?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "TaskCheck is volledig mobiel te gebruiken. Je voert schoonmaak- en andere controles direct uit op je smartphone of tablet, waar je ook bent."
                }
            },
            {
                "@@type": "Question",
                "name": "Kan ik met TaskCheck voldoen aan HACCP- en NVWA-eisen?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Ja, TaskCheck is ontwikkeld voor horeca en voldoet aan de eisen voor HACCP en NVWA. Je registreert alle controles digitaal en hebt altijd een compleet rapport."
                }
            },
            {
                "@@type": "Question",
                "name": "Kan ik foto's of video's toevoegen als bewijs?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Ja, aan elke taak of controle kun je direct foto’s, video’s, notities of handtekeningen toevoegen als bewijslast."
                }
            },
            {
                "@@type": "Question",
                "name": "Is TaskCheck geschikt voor meerdere locaties?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Jazeker. Je beheert checklists, taken en rapportages eenvoudig per locatie, bijvoorbeeld voor meerdere vestigingen of filialen."
                }
            },
            {
                "@@type": "Question",
                "name": "Heb ik technische kennis nodig om te starten?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Nee, TaskCheck is eenvoudig in gebruik en binnen enkele minuten klaar voor jouw bedrijf. Je kunt direct starten met de gratis proefperiode."
                }
            },
            {
                "@@type": "Question",
                "name": "Welke schoonmaaktaken kan ik digitaliseren?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Alle denkbare taken: van dagelijkse schoonmaak, periodieke controles en temperatuurmetingen tot aan voorraadbeheer en sluitrondes."
                }
            }
        ]
    }
    </script>
@endpush

@section('content')
<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="seo-welkesoftwar-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-welkesoftwar-dots)"/>
        </svg>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0 fade-up">
                <p class="blog-kicker fade-up mb-6 sm:mb-7">Mobiele schoonmaaksoftware</p>
                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Welke software voor schoonmaakbedrijven werkt
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">goed op mobiel?</span>
                    </span>
                </h1>
                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Zoek je een gebruiksvriendelijke, mobiele oplossing voor schoonmaakbedrijven, horeca of bakkerijen? Met TaskCheck werk je altijd en overal digitaal, direct vanaf je smartphone of tablet.
                </p>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500">
                    TaskCheck is speciaal ontwikkeld voor restaurants, hotels, bakkerijen en andere horecabedrijven die hun schoonmaak, HACCP- en NVWA-controles professioneel en efficiënt willen vastleggen. Geen papieren lijsten meer, maar één centrale app voor al je controles, registraties en bewijslast.
                </p>
                <div class="mt-7 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:flex-wrap">
                    @auth
                        <a href="{{ auth()->user()->homeDashboardUrl() }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">Naar dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">Start 14 dagen gratis</a>
                    @endauth
                    <a href="{{ route('contact') }}" class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl border border-[#e6e8ec] bg-white px-6 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 sm:w-auto sm:min-h-0 touch-manipulation">Plan een demo</a>
                </div>
                <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 sm:mt-6 sm:gap-x-6">
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Geen creditcard nodig
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        14 dagen gratis proberen
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Direct mobiel inzetbaar
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        AVG-proof
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Nederlandse support
                    </span>
                </div>
            </div>
            <div class="relative mx-auto w-full max-w-xl lg:mx-0 lg:max-w-none">
                <div class="seo-hero-frame">
                        <img src="{{ asset('images/taskcheck-horeca-seo-hero.webp') }}"
                             alt="Mobiele schoonmaaksoftware in gebruik in horeca"
                             class="h-auto w-full object-cover" width="1200" height="800" loading="eager" fetchpriority="high">
                    </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Direct digitaal registreren op mobiel of tablet</p>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-[#e6e8ec] bg-[#f7f8fa]">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Temperatuur</p>
                    <p class="mt-0.5 text-sm text-slate-500">koeling &amp; vriezer</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Schoonmaak</p>
                    <p class="mt-0.5 text-sm text-slate-500">roosters &amp; taken</p>
                </div>
            </div>
        </div>
    </div>
</section>

<x-seo-product-visuals theme="schoonmaak" placement="showcase" />

<div>
<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-start gap-16 lg:grid-cols-2">
            <div>
                <p class="blog-kicker mb-3">Waarom digitaal</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Waarom mobiele schoonmaaksoftware?</h2>
                <div class="mt-4 text-lg leading-relaxed text-slate-500"><p>Met mobiele schoonmaaksoftware zoals TaskCheck heb je altijd overzicht op je schoonmaakroosters, taken en registraties. Papieren lijsten raken kwijt en zijn niet realtime inzichtelijk. Met TaskCheck voldoe je eenvoudig aan HACCP- en NVWA-eisen, voorkom je fouten en bespaar je tijd voor je team.</p><p>Alle controles, van temperatuur tot schoonmaak, zijn direct te registreren met foto- of videobewijs. Zo werk je slimmer, veiliger en overzichtelijker – altijd en overal.</p></div>
            </div>
            <div class="space-y-3">
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">Papieren schoonmaaklijsten raken kwijt of zijn onvolledig</span>
                </div>
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">Geen realtime inzicht voor managers of NVWA-inspectie</span>
                </div>
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">Fouten bij handmatige temperatuurregistratie</span>
                </div>
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">Tijdrovende administratie en onduidelijke rapportages</span>
                </div>
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">Geen direct bewijs bij controles</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="blog-kicker mb-3">Functies</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Wat kun je registreren</h2>
        </div>
        <ul class="mx-auto mt-10 grid max-w-4xl gap-3 sm:grid-cols-2">
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Schoonmaakcontroles (dagelijks, wekelijks, maandelijks)
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                HACCP-registraties voor voedselveiligheid
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Temperatuurmetingen (koelingen, vriezers, werkbanken)
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Openings- en sluitrondes
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Foto- en videobewijs toevoegen aan elke taak
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Digitale handtekeningen en notities
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Automatische rapportages per locatie
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Taken en checklists op maat maken
            </li>
        </ul>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <p class="blog-kicker mb-3">Checklists</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Controles digitaal uitvoeren</h2>
                <div class="mt-4 text-lg leading-relaxed text-slate-500"><p>Met TaskCheck voer je alle controles en checklists eenvoudig digitaal uit. Medewerkers werken direct via hun mobiel of tablet, zonder losse papieren. Taken worden afgevinkt, bewijs wordt toegevoegd en alles is direct inzichtelijk voor leidinggevenden.</p><p>Of het nu gaat om een openingsronde, HACCP-controle of sluitingscheck: je kunt alles naar eigen wens instellen en per locatie beheren.</p></div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">Opening keuken</span>
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">Sluiting restaurant</span>
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">Dagelijkse schoonmaak</span>
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">Temperatuurcontrole</span>
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">NVWA-inspectie</span>
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">Voorraadcontrole</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="blog-kicker mb-3">Voordelen</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Voordelen</h2>
        </div>
        <div class="mx-auto mt-10 grid max-w-4xl gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Alles digitaal: geen papieren lijsten meer</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Realtime inzicht in alle registraties</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Eenvoudig bewijs toevoegen (foto/video/handtekening)</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Direct klaar voor NVWA- en HACCP-inspectie</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Bespaar tijd en voorkom fouten</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Automatische rapportages per locatie</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Veilig en AVG-proof opgeslagen</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Geschikt voor elk type (horeca)schoonmaakbedrijf</span>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="blog-kicker mb-3">Doelgroep</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Voor wie geschikt</h2>
        </div>
        <div class="mx-auto mt-10 flex max-w-3xl flex-wrap justify-center gap-3">
            <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">Restaurants</span>
            <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">Lunchrooms</span>
            <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">Fastfoodzaken</span>
            <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">Hotels</span>
            <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">Bakkerijen</span>
            <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">Slagerijen</span>
        </div>
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
            <details class="group cursor-pointer rounded-2xl border border-[#e6e8ec] bg-white px-5 py-4 transition hover:border-[#d7e2f7] sm:px-6">
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
            <a href="{{ route('seo.haccp-app') }}" class="inline-flex items-center gap-1.5 rounded-full border border-[#e6e8ec] bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-[#d7e2f7] hover:text-blue-700">HACCP app</a>
            <a href="{{ route('seo.temperatuurregistratie-horeca') }}" class="inline-flex items-center gap-1.5 rounded-full border border-[#e6e8ec] bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-[#d7e2f7] hover:text-blue-700">Temperatuurregistratie</a>
        </div>
    </div>
</section>
</div>
@endsection
