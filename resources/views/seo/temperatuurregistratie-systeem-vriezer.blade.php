@php
    $seoTitle       = "Temperatuurregistratie systeem vriezer | Horeca digitaal | TaskCheck";
        $seoDescription = "Temperatuurregistratie systeem voor vriezer en koeling. Digitale checklists, HACCP en NVWA-bewijs. Probeer TaskCheck 14 dagen gratis!";
        $seoKeywords    = "temperatuurregistratie systeem vriezer, digitale temperatuurregistratie, HACCP vriezer, NVWA controle, temperatuur koeling horeca, digitale checklist horeca, temperatuurregistratie horeca";
        $seoUrl         = route('seo.temperatuurregistratie-systeem-vriezer');
        $seoImage       = asset('images/taskcheck-horeca-seo-hero.webp');
        $faqItems = [
            [
                        "Wat is een digitaal temperatuurregistratie systeem voor vriezers?",
                        "Een digitaal temperatuurregistratie systeem registreert automatisch of handmatig de temperaturen van je vriezers en koelingen. Met TaskCheck doe je dit volledig digitaal, inclusief bewijs en meldingen bij afwijkingen."
            ],
            [
                        "Is TaskCheck geschikt voor HACCP en NVWA-controles?",
                        "Ja, TaskCheck voldoet aan de eisen voor HACCP en NVWA. Je kunt alle controles, temperaturen en schoonmaakacties digitaal vastleggen en rapporteren."
            ],
            [
                        "Kan ik foto’s of video toevoegen als bewijs?",
                        "Ja, per temperatuurmeting of taak kun je eenvoudig foto’s, video’s en opmerkingen toevoegen als bewijs voor de NVWA of interne audits."
            ],
            [
                        "Hoe werkt temperatuurregistratie in meerdere filialen?",
                        "Je beheert onbeperkt locaties in één dashboard. Alle temperaturen en controles zijn per locatie inzichtelijk en te rapporteren."
            ],
            [
                        "Heb ik technische kennis nodig om te starten?",
                        "Nee, TaskCheck is eenvoudig in gebruik. Binnen 10 minuten kun je starten met digitale temperatuurregistratie. Support is beschikbaar bij vragen."
            ],
            [
                        "Kan ik mijn bestaande checklists digitaliseren?",
                        "Ja, je kunt eigen checklists en controlepunten toevoegen en aanpassen. Alles is flexibel in te richten voor jouw processen."
            ]
];
        $ctaHeading = 'Start met digitale temperatuurregistratie';
        $ctaLead = 'Probeer TaskCheck 14 dagen gratis en ontdek het gemak van digitale temperatuurregistratie. Geen creditcard nodig.';
        $seoTheme = 'haccp';
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
                "name": "Wat is een digitaal temperatuurregistratie systeem voor vriezers?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Een digitaal temperatuurregistratie systeem registreert automatisch of handmatig de temperaturen van je vriezers en koelingen. Met TaskCheck doe je dit volledig digitaal, inclusief bewijs en meldingen bij afwijkingen."
                }
            },
            {
                "@@type": "Question",
                "name": "Is TaskCheck geschikt voor HACCP en NVWA-controles?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Ja, TaskCheck voldoet aan de eisen voor HACCP en NVWA. Je kunt alle controles, temperaturen en schoonmaakacties digitaal vastleggen en rapporteren."
                }
            },
            {
                "@@type": "Question",
                "name": "Kan ik foto’s of video toevoegen als bewijs?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Ja, per temperatuurmeting of taak kun je eenvoudig foto’s, video’s en opmerkingen toevoegen als bewijs voor de NVWA of interne audits."
                }
            },
            {
                "@@type": "Question",
                "name": "Hoe werkt temperatuurregistratie in meerdere filialen?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Je beheert onbeperkt locaties in één dashboard. Alle temperaturen en controles zijn per locatie inzichtelijk en te rapporteren."
                }
            },
            {
                "@@type": "Question",
                "name": "Heb ik technische kennis nodig om te starten?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Nee, TaskCheck is eenvoudig in gebruik. Binnen 10 minuten kun je starten met digitale temperatuurregistratie. Support is beschikbaar bij vragen."
                }
            },
            {
                "@@type": "Question",
                "name": "Kan ik mijn bestaande checklists digitaliseren?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Ja, je kunt eigen checklists en controlepunten toevoegen en aanpassen. Alles is flexibel in te richten voor jouw processen."
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
                <pattern id="seo-temperatuurr-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-temperatuurr-dots)"/>
        </svg>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0 fade-up">
                <p class="blog-kicker fade-up mb-6 sm:mb-7">Temperatuurregistratie · vriezer &amp; koeling</p>
                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Temperatuurregistratie systeem voor
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">vriezer en koeling</span>
                    </span>
                </h1>
                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Met TaskCheck registreer je eenvoudig de temperaturen van vriezers en koelingen. Geen papieren lijsten meer, maar direct digitaal vastleggen volgens HACCP en NVWA-eisen.
                </p>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500">
                    Voorkom boetes en voedselverspilling. Met automatische herinneringen, foto- en videobewijs en rapportages heb je altijd inzicht en bewijs van correcte temperatuurregistratie.
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
                        Direct klaar voor NVWA
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Onbeperkt locaties
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
                             alt="Dashboard temperatuurregistratie systeem voor horeca vriezer"
                             class="h-auto w-full object-cover" width="1200" height="800" loading="eager" fetchpriority="high">
                    </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Temperaturen van alle vriezers en koelingen altijd inzichtelijk en NVWA-proof</p>
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

<x-seo-product-visuals theme="haccp" placement="showcase" />

<div>
<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-start gap-16 lg:grid-cols-2">
            <div>
                <p class="blog-kicker mb-3">Waarom digitaal</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Waarom een digitaal temperatuurregistratie systeem voor je vriezer?</h2>
                <div class="mt-4 text-lg leading-relaxed text-slate-500"><p>Handmatige temperatuurregistratie op papier is foutgevoelig en kost tijd. Met TaskCheck leg je snel en foutloos alle temperatuurmetingen vast, inclusief bewijs. Zo voldoe je eenvoudig aan de HACCP- en NVWA-voorschriften en heb je altijd een actueel overzicht van alle registraties.</p><p>Digitale registratie voorkomt discussies met de NVWA, vermindert het risico op boetes en zorgt voor direct inzicht bij afwijkingen.</p></div>
            </div>
            <div class="space-y-3">
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">Papieren lijsten raken kwijt of worden vergeten</span>
                </div>
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">Onvolledige of onleesbare registraties</span>
                </div>
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">Onvoldoende bewijs voor NVWA-controle</span>
                </div>
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">Geen overzicht van afwijkingen</span>
                </div>
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">Handmatige rapportage kost veel tijd</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="blog-kicker mb-3">Functies</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Wat kun je registreren met TaskCheck?</h2>
        </div>
        <ul class="mx-auto mt-10 grid max-w-4xl gap-3 sm:grid-cols-2">
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Automatische temperatuurregistratie van vriezers en koelingen
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Handmatige invoer met foto- en videobewijs
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Automatische herinneringen voor metingen
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Direct statusoverzicht per locatie
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Rapportage voor NVWA en management
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Opmerkingen en afwijkingen vastleggen
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Opening- en sluitrondes digitaliseren
            </li>
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Schoonmaak- en HACCP-checklists
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
                <div class="mt-4 text-lg leading-relaxed text-slate-500"><p>Met TaskCheck digitaliseer je alle dagelijkse controles, zoals temperatuurmetingen, schoonmaakrondes en sluitchecks. Via de app of het dashboard wijs je taken toe aan medewerkers, voeg je eenvoudig bewijs toe en deel je rapportages met collega’s en de NVWA.</p><p>Zo werk je gestructureerd, snel en papierloos. Alle data staat veilig opgeslagen en is direct terug te vinden bij een controle.</p></div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">Opening keuken</span>
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">Sluiting restaurant</span>
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">Temperatuur vriezer</span>
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">Temperatuur koeling</span>
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">Schoonmaak werkbank</span>
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">NVWA inspectie</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="blog-kicker mb-3">Voordelen</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Voordelen van TaskCheck</h2>
        </div>
        <div class="mx-auto mt-10 grid max-w-4xl gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Minder kans op fouten door automatische herinneringen</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Direct bewijs met foto’s en video’s</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Snelle en foutloze NVWA-inspectie</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Altijd inzicht in actuele temperaturen</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Alle registraties veilig digitaal bewaard</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Tijdwinst door automatische rapportages</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Eenvoudig in gebruik voor het hele team</span>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">Geschikt voor meerdere locaties en filialen</span>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="blog-kicker mb-3">Doelgroep</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Voor wie is TaskCheck geschikt?</h2>
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

<x-seo-product-visuals theme="haccp" placement="story" />

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
