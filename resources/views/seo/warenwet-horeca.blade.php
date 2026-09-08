@php
    $seoTitle       = "Warenwet horeca: digitale checklist & controle | TaskCheck";
    $seoDescription = "Voldoe eenvoudig aan de warenwet horeca. Start direct met digitale checklists, HACCP- en NVWA-controles. 14 dagen gratis proberen.";
    $seoKeywords    = "warenwet horeca, digitale checklist horeca, HACCP horeca, NVWA, temperatuurregistratie, schoonmaakrooster, voedselveiligheid";
    $seoUrl         = route('seo.warenwet-horeca');
    $seoImage       = asset('images/taskcheck-horeca-seo-hero.webp');
    $faqItems = [
        ['Wat is de Warenwet voor horeca?', 'De Warenwet stelt eisen aan voedselveiligheid, hygiëne en registratie voor alle horecabedrijven. Je moet kunnen aantonen dat je dagelijks controleert op bijvoorbeeld temperatuur, schoonmaak en allergenen.'],
        ['Hoe helpt TaskCheck bij NVWA-controles?', 'Alle controles en registraties worden digitaal vastgelegd met tijd, gebruiker en eventueel foto- of videobewijs. Zo toon je eenvoudig aan dat je voldoet aan de NVWA-eisen.'],
        ['Welke controles moet ik uitvoeren volgens de Warenwet?', 'Je moet o.a. temperaturen registreren, schoonmaak uitvoeren, HACCP-controles doen en alles documenteren. TaskCheck biedt hiervoor standaard checklists.'],
        ['Kan ik rapportages exporteren voor inspecties?', 'Ja, je kunt alle registraties en rapportages exporteren als PDF of Excel, klaar voor een NVWA-inspectie of intern gebruik.'],
        ['Is TaskCheck geschikt voor meerdere locaties?', 'Ja, je kunt voor elke locatie aparte checklists aanmaken, taken toewijzen en rapportages per locatie bekijken.'],
        ['Kan ik foto\'s toevoegen als bewijs?', 'Ja, bij iedere taak kun je foto\'s en video\'s uploaden. Zo heb je altijd visueel bewijs van de uitgevoerde controles.'],
    ];
    $ctaHeading = 'Start met digitale warenwet-controle';
    $ctaLead = 'Probeer TaskCheck 14 dagen gratis en ontdek het gemak van digitale HACCP- en NVWA-checklists voor jouw horecazaak. Geen creditcard nodig.';
    $seoTheme = 'horeca';
@endphp

@extends('layouts.seo-page')

@push('head')
<script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            @foreach($faqItems as $i => [$q, $a])
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
                <pattern id="seo-warenwet-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-warenwet-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0 fade-up">
                <p class="blog-kicker fade-up mb-6 sm:mb-7">Warenwet &amp; HACCP digitaal</p>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Warenwet checklist voor
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">horeca</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-warenwet-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-warenwet-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Voldoe moeiteloos aan de warenwet horeca met TaskCheck. Digitaliseer al je controles, van temperatuurregistratie tot schoonmaakrondes. Geen papieren rompslomp meer, maar één overzichtelijk platform voor alle HACCP- en NVWA-eisen.
                </p>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500 sm:text-lg">
                    TaskCheck helpt restaurants, hotels, lunchrooms en andere horecazaken bij het uitvoeren en registreren van alle verplichte controles. Zo ben je altijd voorbereid op een NVWA-inspectie en voorkom je boetes of sluiting.
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
                    @foreach(['Geen creditcard nodig', '14 dagen gratis proberen', 'Direct digitaal registreren', 'AVG-proof', 'Geschikt voor alle horecazaken'] as $b)
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $b }}
                    </span>
                    @endforeach
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-xl lg:mx-0 lg:max-w-none">
                <div class="seo-hero-frame">
                    <img src="{{ asset('images/taskcheck-horeca-seo-hero.webp') }}"
                         alt="Digitale checklist en temperatuurregistratie voor horeca op tablet"
                         class="h-auto w-full object-cover"
                         width="1200"
                         height="800"
                         loading="eager"
                         fetchpriority="high">
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Digitale controles voor warenwet &amp; HACCP in de horeca</p>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-[#e6e8ec] bg-[#f7f8fa]">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Temperatuur</p>
                    <p class="mt-0.5 text-sm text-slate-500">koeling &amp; vriezer</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Schoonmaak</p>
                    <p class="mt-0.5 text-sm text-slate-500">roosters &amp; taken</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">HACCP</p>
                    <p class="mt-0.5 text-sm text-slate-500">Warenwet-proof</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Rapportages</p>
                    <p class="mt-0.5 text-sm text-slate-500">klaar voor NVWA</p>
                </div>
            </div>
        </div>
    </div>
</section>

<x-seo-product-visuals theme="horeca" placement="showcase" />

<div class="pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <section class="mt-16 sm:mt-20">
            <div class="grid items-start gap-12 lg:grid-cols-2 lg:gap-16">
                <div>
                    <span class="blog-kicker">Waarom digitaal</span>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Waarom digitale Warenwet controlelijsten?</h2>
                    <p class="mt-4 leading-relaxed text-slate-500">De Warenwet verplicht horecabedrijven om voedselveiligheid structureel te borgen. Met papieren lijsten verlies je snel het overzicht, mis je registraties of bewijs, en loop je risico bij een NVWA-controle. TaskCheck digitaliseert al je checklisten, taken en registraties — overzichtelijk, snel en altijd compleet.</p>
                    <p class="mt-3 leading-relaxed text-slate-500">Met digitale controlelijsten voldoe je eenvoudig aan de Warenwet, HACCP en NVWA-eisen. Je bespaart tijd, voorkomt fouten en hebt altijd bewijs bij de hand.</p>
                </div>
                <div class="grid gap-3">
                    @foreach([
                        'Papieren checklists raken kwijt of zijn onvolledig',
                        'Gebrek aan bewijs bij NVWA-controle',
                        'Tijdrovende administratie',
                        'Geen centraal overzicht van alle controles',
                        'Verhoogde kans op boetes of sluiting',
                    ] as $problem)
                    <div class="flex items-start gap-3 rounded-2xl border border-[#e6e8ec] bg-white px-4 py-3.5 shadow-sm">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-500">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </span>
                        <span class="text-sm text-slate-700">{{ $problem }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="blog-kicker">Functies</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Wat kun je registreren</h2>
            </div>
            <ul class="mx-auto mt-10 grid max-w-4xl gap-3 sm:grid-cols-2">
                @foreach([
                    'Temperatuurregistratie (koeling, vriezer, werkbank)',
                    'Schoonmaakrondes en hygiënecontroles',
                    'Opening- en sluitrondes',
                    'HACCP- en NVWA-controles',
                    'Foto- en videobewijs toevoegen',
                    'Rapportages per dag, week of maand',
                    'Notities en handtekeningen vastleggen',
                    'Meldingen bij vergeten taken',
                ] as $feature)
                <li class="flex items-start gap-3 rounded-2xl border border-[#e6e8ec] bg-white px-4 py-3.5 text-sm text-slate-700 shadow-sm">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
        </section>

        <section class="mt-20 rounded-[18px] border border-[#e6e8ec] bg-[#f7f8fa] p-8 sm:p-12 sm:mt-24">
            <span class="blog-kicker">Checklists</span>
            <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Controles digitaal uitvoeren</h2>
            <p class="mt-4 max-w-3xl leading-relaxed text-slate-600">Met TaskCheck maak je eenvoudig digitale checklists voor alle dagelijkse, wekelijkse en maandelijkse controles die vereist zijn door de Warenwet en de NVWA. Denk aan temperatuurmetingen, schoonmaaklijsten, open- en sluitrondes én incidentregistratie. Alles wordt centraal opgeslagen, inclusief foto’s, handtekeningen en tijdstempels.</p>
            <p class="mt-3 max-w-3xl leading-relaxed text-slate-600">Stel zelf taken in, ontvang herinneringen en exporteer rapportages met één klik. Zo weet je precies wie, wat, wanneer heeft uitgevoerd en ben je altijd klaar voor een inspectie.</p>
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach(['Opening keuken', 'Sluiting restaurant', 'Temperatuur vriezer', 'Schoonmaak bakkerij', 'HACCP dagcontrole', 'NVWA inspectie'] as $chip)
                <span class="blog-tag">{{ $chip }}</span>
                @endforeach
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="blog-kicker">Voordelen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Voordelen</h2>
            </div>
            <div class="mx-auto mt-10 grid max-w-4xl gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    'Nooit meer incomplete lijsten of ontbrekende registraties',
                    'Direct bewijs voor NVWA-inspectie, inclusief foto’s & tijdstempels',
                    'Bespaar tijd op administratie en papierwerk',
                    'Altijd inzicht in openstaande en afgeronde taken',
                    'Makkelijk rapportages aanmaken voor management of inspectie',
                    'Taken en registraties per medewerker en locatie bijhouden',
                    'Sneller inwerken van nieuw personeel dankzij duidelijke processen',
                    'AVG-proof en veilig opgeslagen in de cloud',
                ] as $benefit)
                <div class="flex items-center gap-3 rounded-2xl border border-[#e6e8ec] bg-white px-4 py-3.5 shadow-sm">
                    <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span class="text-sm font-medium text-slate-800">{{ $benefit }}</span>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="blog-kicker">Doelgroep</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Voor wie geschikt</h2>
            </div>
            <div class="mx-auto mt-10 flex max-w-3xl flex-wrap justify-center gap-3">
                @foreach(['Restaurants', 'Lunchrooms', 'Fastfoodzaken', 'Hotels', 'Bakkerijen', 'Slagerijen'] as $target)
                <span class="rounded-full border border-[#e6e8ec] bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">{{ $target }}</span>
                @endforeach
            </div>
        </section>

        <x-seo-product-visuals theme="horeca" placement="story" />

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="blog-kicker">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mx-auto mt-10 max-w-3xl space-y-3">
                @foreach($faqItems as [$q, $a])
                <details class="group cursor-pointer rounded-2xl border border-[#e6e8ec] bg-white px-5 py-4 transition hover:border-[#d7e2f7] sm:px-6">
                    <summary class="flex list-none items-center justify-between gap-3 font-semibold text-slate-900">
                        <span class="text-left">{{ $q }}</span>
                        <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-45" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $a }}</p>
                </details>
                @endforeach
            </div>
        </section>

        <section class="mb-4 mt-16 border-t border-slate-200 pt-12 sm:mt-20">
            <p class="blog-kicker mx-auto">Gerelateerde pagina&rsquo;s</p>
            <div class="mx-auto mt-5 flex max-w-4xl flex-wrap justify-center gap-2">
                @foreach([
                    ['HACCP app', route('seo.haccp-app')],
                    ['Temperatuurregistratie', route('seo.temperatuurregistratie-horeca')],
                    ['Digitale checklist app', route('seo.digitale-checklist-app')],
                    ['Schoonmaak checklist', route('seo.schoonmaak-checklist')],
                    ['Opening checklist horeca', route('seo.opening-checklist-horeca')],
                ] as $link)
                <a href="{{ $link[1] }}" class="inline-flex items-center gap-1.5 rounded-full border border-[#e6e8ec] bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-[#d7e2f7] hover:text-blue-700">
                    {{ $link[0] }}
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection
