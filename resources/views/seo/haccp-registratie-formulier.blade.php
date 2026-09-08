@php
    $seoTitle       = "HACCP registratie formulier: digitaal & compleet | TaskCheck";
    $seoDescription = "HACCP registratie formulier direct digitaal invullen. Voldoe aan NVWA-eisen en voorkom papierwerk. Probeer TaskCheck gratis en ervaar het gemak.";
    $seoKeywords    = "haccp registratie formulier, digitale haccp registratie, haccp checklist, haccp horeca, temperatuurregistratie horeca, nvwa controle, schoonmaakrooster horeca";
    $seoUrl         = route('seo.haccp-registratie-formulier');
    $seoImage       = asset('images/taskcheck-horeca-seo-hero.webp');
    $faqItems = [
        ['Wat is een digitaal HACCP registratie formulier?', 'Een digitaal HACCP registratie formulier is een online tool waarmee horecabedrijven hun voedselveiligheidscontroles, zoals temperatuurmetingen en schoonmaaktaken, eenvoudig en overzichtelijk vastleggen en bewaren.'],
        ['Hoe werkt TaskCheck voor HACCP-registraties?', 'TaskCheck biedt digitale checklists waarmee je dagelijks controles zoals schoonmaak en temperatuur afvinkt. Je voegt bewijs toe via foto’s, video’s of notities; alles wordt automatisch opgeslagen.'],
        ['Is TaskCheck geschikt voor NVWA-controles?', 'Ja. Met TaskCheck heb je direct inzicht in alle registraties en toon je snel rapportages bij inspecties.'],
        ['Kan ik mijn eigen HACCP formulieren maken?', 'Ja. Je stelt checklists samen en past ze aan, zodat ze aansluiten bij HACCP-voorschriften en de werkwijze van jouw bedrijf.'],
        ['Is TaskCheck geschikt voor meerdere locaties?', 'Ja. Je beheert per locatie checklists, taken en rapportages. Ideaal voor horecazaken met meerdere vestigingen.'],
        ['Moet ik software installeren?', 'Nee. TaskCheck werkt in je browser en als app op smartphone of tablet. Je hoeft niets te installeren.'],
    ];
    $ctaHeading = 'Start met digitale HACCP registratie';
    $ctaLead = 'Wil je direct aan de slag met een digitaal HACCP registratie formulier? Maak gratis een proefaccount aan en ontdek TaskCheck voor jouw horecazaak.';
    $seoTheme = 'haccp';
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
                <pattern id="seo-haccpregistr-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-haccpregistr-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(16,185,129,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0 fade-up">
                <p class="blog-kicker fade-up mb-6 sm:mb-7">Digitaal HACCP formulier</p>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    HACCP registratie formulier
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">digitaal invullen</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-haccpregistr-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-haccpregistr-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Met TaskCheck vul je jouw HACCP registratie formulier digitaal in. Geen papieren rompslomp, maar controles, registraties en bewijs overzichtelijk op één platform.
                </p>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500 sm:text-lg">
                    Of je nu een restaurant, lunchroom, hotel, bakkerij of slagerij runt: zo voldoe je sneller aan HACCP- en NVWA-eisen.
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
                    @foreach(['Geen creditcard nodig', '14 dagen gratis proberen', 'NVWA-proof', 'NL support', 'Beheer per locatie'] as $b)
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
                         alt="Horecamedewerker vult digitaal HACCP registratie formulier in op tablet"
                         class="h-auto w-full object-cover"
                         width="1200"
                         height="800"
                         loading="eager"
                         fetchpriority="high">
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Direct digitaal HACCP-controles registreren — klaar voor NVWA-inspectie</p>
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
                    <p class="mt-0.5 text-sm text-slate-500">registratie &amp; bewijs</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Bewijs</p>
                    <p class="mt-0.5 text-sm text-slate-500">foto &amp; video</p>
                </div>
            </div>
        </div>
    </div>
</section>

<x-seo-product-visuals theme="haccp" placement="showcase" />

<div class="pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <section class="mt-16 sm:mt-20">
            <div class="grid items-start gap-12 lg:grid-cols-2 lg:gap-16">
                <div>
                    <span class="blog-kicker">Waarom digitaal</span>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Waarom een digitaal HACCP registratie formulier?</h2>
                    <p class="mt-4 leading-relaxed text-slate-500">HACCP-registratie is verplicht, maar papieren formulieren raken kwijt, zijn lastig te beheren en kosten tijd bij NVWA-controles. Met TaskCheck digitaliseer je alle formulieren — inclusief foto- en videobewijs — zodat je altijd inzicht hebt.</p>
                    <p class="mt-3 leading-relaxed text-slate-500">Zo voorkom je fouten, voldoe je aan wetgeving en bespaar je administratie-tijd.</p>
                </div>
                <div class="grid gap-3">
                    @foreach([
                        'Papieren HACCP formulieren raken kwijt of zijn onleesbaar',
                        'Tijdrovende administratie en onoverzichtelijke archieven',
                        'Onvoldoende bewijs bij NVWA-controle',
                        'Geen inzicht in wie welke taken heeft uitgevoerd',
                        'Vergeten controles en incomplete lijsten',
                        'Moeilijk registraties delen tussen locaties',
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
                    'Temperatuurregistraties voor koeling, vriezer en warmhoudapparatuur',
                    'Schoonmaakcontroles en schoonmaakroosters',
                    'Openings- en sluitingschecks',
                    'Dagelijkse HACCP- en NVWA-controles',
                    'Digitale inspecties met foto- en videobewijs',
                    'Notities, handtekeningen en rapportages',
                    'Controlelijsten op maat voor jouw zaak',
                    'Takenlijsten voor personeel',
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
            <p class="mt-4 max-w-3xl leading-relaxed text-slate-600">Voer HACCP- en hygiënecontroles digitaal uit via smartphone, tablet of pc. Vink af, voeg foto’s toe of noteer afwijkingen — zo blijft je overzicht compleet en actueel.</p>
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach(['Opening keuken', 'Sluiting restaurant', 'Temperatuur koeling', 'Schoonmaak werkplaatsen', 'Personeelshygiëne', 'NVWA inspectie'] as $chip)
                <span class="blog-tag">{{ $chip }}</span>
                @endforeach
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="blog-kicker">Voordelen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Waarom teams digitaal registreren</h2>
            </div>
            <div class="mx-auto mt-10 grid max-w-4xl gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    'Nooit meer papieren formulieren kwijt',
                    'Direct inzicht bij NVWA-controle',
                    'Automatische rapportages per locatie',
                    'Snelle temperatuurregistratie via je telefoon',
                    'Taken eenvoudig toewijzen aan personeel',
                    'Foto- en videobewijs per controlepunt',
                    'Minder kans op fouten of vergeten taken',
                    'Altijd up-to-date en veilig opgeslagen',
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

        <x-seo-product-visuals theme="haccp" placement="story" />

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
                    ['Digitale HACCP registratie', route('seo.digitale-haccp-registratie')],
                    ['HACCP formulieren', route('seo.haccp-formulieren')],
                    ['Temperatuurregistratie', route('seo.temperatuurregistratie-horeca')],
                    ['Horeca checklist app', route('seo.horeca-checklist-app')],
                    ['Prijzen', route('pricing')],
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
