@php
    $seoTitle       = "Alles-in-één horeca platform voor digitale checklists | TaskCheck";
    $seoDescription = "TaskCheck: hét alles-in-één horeca platform voor HACCP, NVWA en digitale controles. Probeer 14 dagen gratis en ervaar het gemak zelf.";
    $seoKeywords    = "alles-in-één horeca platform, horeca checklist app, digitale checklists horeca, HACCP platform, NVWA controle app, schoonmaakrooster horeca, temperatuurregistratie horeca";
    $seoUrl         = route('seo.alles-in-één-horeca-platform');
    $seoImage       = asset('images/taskcheck-horeca-seo-hero.webp');
    $faqItems = [
        ['Wat is een alles-in-één horeca platform?', 'Een alles-in-één horeca platform combineert alle dagelijkse controles, registraties en taken in één digitale omgeving. Zo beheer je HACCP, NVWA, schoonmaak, temperatuur en meer zonder losse tools of papieren.'],
        ['Kan ik mijn eigen checklists en taken aanmaken?', 'Ja, in TaskCheck maak je eenvoudig zelf checklists en taken aan, volledig afgestemd op de processen van jouw horecazaak. Zo sluit alles perfect aan op je werkwijze.'],
        ['Is TaskCheck geschikt voor meerdere locaties?', 'Zeker! Je beheert eenvoudig meerdere vestigingen, locaties of teams vanuit één dashboard. Rapportages en controles zijn per locatie inzichtelijk.'],
        ['Hoe helpt TaskCheck bij een NVWA-inspectie?', 'Met TaskCheck heb je direct alle controlelijsten en bewijzen digitaal beschikbaar. Zo toon je eenvoudig aan dat je voldoet aan de eisen van de NVWA en HACCP-wetgeving.'],
        ['Kan ik temperatuurregistraties automatiseren?', 'Ja, je voert handmatig of via integraties temperatuurmetingen in. Alles wordt direct digitaal opgeslagen en is inzichtelijk in het dashboard.'],
        ['Is het gebruik van TaskCheck veilig en AVG-proof?', 'Ja, TaskCheck voldoet aan alle Nederlandse privacyregels en AVG-wetgeving. Jouw gegevens zijn veilig opgeslagen binnen Nederland.'],
    ];
    $ctaHeading = 'Start met alles-in-één horeca platform';
    $ctaLead = 'Wil je minder papierwerk en altijd klaar zijn voor elke controle? Probeer TaskCheck 14 dagen gratis en ervaar het gemak van digitale horeca checklists.';
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
                <pattern id="seo-allesineen-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-allesineen-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0 fade-up">
                <p class="blog-kicker fade-up mb-6 sm:mb-7">Alles-in-één horeca platform</p>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Het alles-in-één platform voor
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">horeca</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-allesineen-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-allesineen-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    TaskCheck is hét Nederlandse alles-in-één horeca platform waarmee je al je dagelijkse controles, registraties en rapportages digitaal afhandelt. Geen losse papieren meer, maar één centrale plek voor al je HACCP, NVWA en operationele taken.
                </p>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500 sm:text-lg">
                    Of je nu een restaurant, lunchroom, fastfoodzaak, hotel, bakkerij of slagerij runt: TaskCheck helpt je om overzicht te houden, tijd te besparen en altijd klaar te zijn voor inspectie. Start vandaag nog met digitale checklists en krijg grip op je processen.
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
                    @foreach(['Geen creditcard nodig', '14 dagen gratis proberen', 'NL support', 'Direct starten', 'AVG-proof', 'Voor kleine én grote teams'] as $b)
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
                         alt="Dashboard van TaskCheck met digitale horeca checklists"
                         class="h-auto w-full object-cover"
                         width="1200"
                         height="800"
                         loading="eager"
                         fetchpriority="high">
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Alles-in-één platform voor digitale horeca controles, HACCP en registraties</p>
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
                    <p class="mt-0.5 text-sm text-slate-500">geautomatiseerd</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Rapportages</p>
                    <p class="mt-0.5 text-sm text-slate-500">direct inzicht</p>
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
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Waarom kiezen voor een alles-in-één horeca platform?</h2>
                    <p class="mt-4 leading-relaxed text-slate-500">Een alles-in-één horeca platform zoals TaskCheck vereenvoudigt jouw dagelijkse werk. Je registreert alle controles digitaal, verzamelt automatisch bewijs en voldoet eenvoudig aan HACCP- en NVWA-eisen. Geen losse papieren, maar altijd overzicht, inzicht en zekerheid.</p>
                    <p class="mt-3 leading-relaxed text-slate-500">Dankzij automatische herinneringen, rapportages en takenlijsten mis je nooit meer een controle. Zo werk je veiliger, efficiënter én professioneler – en ben je altijd klaar voor iedere inspectie.</p>
                </div>
                <div class="grid gap-3">
                    @foreach([
                        'Papieren checklists raken kwijt of zijn niet compleet',
                        'Onduidelijke takenverdeling binnen het team',
                        'Last-minute stress bij NVWA-inspectie',
                        'Tijdrovende handmatige temperatuurregistraties',
                        'Geen bewijs van uitgevoerde controles',
                        'Moeilijk overzicht houden bij meerdere locaties',
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
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Wat kun je registreren met TaskCheck?</h2>
            </div>
            <ul class="mx-auto mt-10 grid max-w-4xl gap-3 sm:grid-cols-2">
                @foreach([
                    'HACCP-controles volledig digitaal',
                    'NVWA-controles en rapportages',
                    'Openings- en sluitingsrondes',
                    'Temperatuurregistraties van koel- en vrieskasten',
                    'Schoonmaaktaken & hygiënecontroles',
                    'Foto- en videobewijs toevoegen aan elke controle',
                    'Digitale inspecties voor eigen protocollen',
                    'Automatische herinneringen bij openstaande taken',
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
            <p class="mt-4 max-w-3xl leading-relaxed text-slate-600">Met TaskCheck voer je alle dagelijkse, wekelijkse en maandelijkse checklist-rondes eenvoudig digitaal uit. Iedere medewerker ziet precies wat er moet gebeuren, kan taken afvinken en bewijs toevoegen. Zo weet je altijd wie wat heeft gedaan en ben je direct audit-proof.</p>
            <p class="mt-3 max-w-3xl leading-relaxed text-slate-600">Van het openen van de keuken tot de sluitingsronde en van schoonmaak tot temperatuurmetingen: alle stappen zijn duidelijk, gestructureerd en centraal opgeslagen.</p>
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach(['Opening keuken', 'Sluiting restaurant', 'Temperatuurcontrole', 'Hygiënecontrole', 'Schoonmaakronde', 'NVWA voorbereiding'] as $chip)
                <span class="blog-tag">{{ $chip }}</span>
                @endforeach
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="blog-kicker">Voordelen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Voordelen van TaskCheck</h2>
            </div>
            <div class="mx-auto mt-10 grid max-w-4xl gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    'Altijd up-to-date met HACCP- en NVWA-eisen',
                    'Nooit meer papieren kwijt of onvolledig',
                    'Sneller en makkelijker werken voor het hele team',
                    'Direct foto- en videobewijs bij elke controle',
                    'Automatische rapportages voor audits en inspecties',
                    'Makkelijk opschalen naar meerdere locaties',
                    'Overzichtelijke dashboards voor managers',
                    'Voldoet aan AVG en privacywetgeving',
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
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Voor wie is TaskCheck geschikt?</h2>
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
                    ['Schoonmaak checklist', route('seo.schoonmaak-checklist')],
                    ['Opening checklist horeca', route('seo.opening-checklist-horeca')],
                    ['Gratis starten', route('register')],
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
