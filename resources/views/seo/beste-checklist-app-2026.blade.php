<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Beste checklist app 2026 | Vergelijking + top keuze | TaskCheck';
        $seoDescription = 'Wat is de beste checklist app in 2026? Vergelijk de beste tools en ontdek waarom TaskCheck de beste keuze is voor bedrijven.';
        $seoUrl = route('seo.beste-checklist-app-2026');
        $seoImage = asset('images/taskcheck-dashboard-hero.webp');
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <style>
        .cta-btn { background: linear-gradient(135deg, #2563eb, #4f46e5); }
        .cta-btn:hover { background: linear-gradient(135deg, #1d4ed8, #4338ca); }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased overflow-x-hidden">

@include('components.header')

{{-- Hero — zelfde taal als welcome: licht, rustig, focus op merk --}}
<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="seo-beste-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-beste-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">Vergelijking checklist apps · 2026</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    De
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">beste checklist app</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-beste-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-beste-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                    voor bedrijven die grip willen op uitvoering
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Geen tool-shopping zonder criteria: bewijs per taak, realtime overzicht en meerdere locaties. Zo zie je snel welke oplossing past bij operationeel werk — en waarom teams TaskCheck boven alternatieven plaatsen.
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:flex-wrap">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Naar dashboard
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Start 14 dagen gratis
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endauth
                    <a href="{{ route('pricing') }}" class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 sm:w-auto sm:min-h-0 touch-manipulation">
                        Bekijk prijzen
                    </a>
                </div>

                <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 sm:mt-6 sm:gap-x-6">
                    @foreach(['Bewijs per taak', 'Realtime dashboard', 'Meerdere locaties', 'Geen creditcard proefperiode'] as $b)
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $b }}
                    </span>
                    @endforeach
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-xl lg:mx-0 lg:max-w-none">
                <div class="rounded-2xl border border-slate-200/90 bg-white p-2 shadow-[0_24px_56px_-24px_rgba(37,99,235,.2),0_0_0_1px_rgba(241,245,249,.9)_inset] sm:p-3">
                    <div class="overflow-hidden rounded-xl ring-1 ring-slate-100">
                        <img src="{{ asset('images/taskcheck-dashboard-hero.webp') }}" alt="TaskCheck dashboard – overzicht van checklist app voor bedrijven" class="h-auto w-full object-cover" width="1200" height="800" loading="eager" fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Dashboard en mobiele uitvoering: één plek voor voortgang en bewijs.</p>
            </div>
        </div>
    </div>
</section>

{{-- KPI-rij --}}
<section class="border-b border-slate-200 bg-slate-50/80">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">#1 keus</p>
                    <p class="mt-0.5 text-sm text-slate-500">voor teams met bewijs &amp; locaties</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Bewijs</p>
                    <p class="mt-0.5 text-sm text-slate-500">foto · video · handtekening</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Realtime</p>
                    <p class="mt-0.5 text-sm text-slate-500">overzicht per team &amp; locatie</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">14 dagen</p>
                    <p class="mt-0.5 text-sm text-slate-500">volledige proefperiode</p>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Criteria --}}
        <section class="mt-16 sm:mt-20">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Criteria</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Waar een serieuze checklist app aan moet voldoen</h2>
                <p class="mt-3 text-slate-500">Dit zijn de punten waar je op wilt vergelijken vóór je een abonnement afsluit.</p>
            </div>

            <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php
                $criteria = [
                    ['t'=>'Mobiel werken','d'=>'Medewerkers vullen taken af op de telefoon — zonder laptop of kantoortijd.','c'=>'text-blue-600','bg'=>'bg-blue-50','paths'=>['M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3']],
                    ['t'=>'Realtime inzicht','d'=>'Leidinggevenden zien wat open staat, wat klaar is en waar je moet bijsturen.','c'=>'text-indigo-600','bg'=>'bg-indigo-50','paths'=>['M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z']],
                    ['t'=>'Bewijs per taak','d'=>'Foto, korte video, tekst of handtekening als objectieve afronding.','c'=>'text-sky-600','bg'=>'bg-sky-50','paths'=>['M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z','M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z']],
                    ['t'=>'Herhalende taken','d'=>'Dag-, week- of maandritmes zonder handmatig opnieuw plannen.','c'=>'text-slate-700','bg'=>'bg-slate-100','paths'=>['M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99']],
                    ['t'=>'Meerdere locaties','d'=>'Templates en voortgang per object, centraal beheerd.','c'=>'text-emerald-600','bg'=>'bg-emerald-50','paths'=>['M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z','M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z']],
                    ['t'=>'Snel live','d'=>'Geen maanden implementatie: je start met echte taken in dezelfde week.','c'=>'text-blue-700','bg'=>'bg-blue-50','paths'=>['M3.75 13.5 14.25 2.25 12 10.5h8.25L9.75 21.75 12 13.5H3.75Z']],
                ];
                @endphp
                @foreach($criteria as $c)
                <div class="group flex gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $c['bg'] }}">
                        <svg class="h-6 w-6 {{ $c['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            @foreach($c['paths'] as $d)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-slate-900">{{ $c['t'] }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $c['d'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- Vergelijking — geen emoji, wel duidelijke hiërarchie --}}
        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Vergelijking</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Drie benaderingen die je tegenkomt in 2026</h2>
                <p class="mt-3 text-slate-500">TaskCheck richt zich op <strong class="font-semibold text-slate-700">dagelijks werk op de vloer</strong> met sturing en bewijs. Andere tools zijn sterker in templates of formulieren — kies wat bij jouw proces past.</p>
            </div>

            <div class="mt-12 space-y-5">
                <article class="relative overflow-hidden rounded-2xl border-2 border-blue-500 bg-white p-6 shadow-md sm:p-8">
                    <div class="absolute right-4 top-4 flex items-center gap-1.5 rounded-full bg-blue-600 px-3 py-1 text-xs font-bold text-white shadow-sm">
                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Aanbevolen voor operationele teams
                    </div>
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-xl font-extrabold text-white shadow-lg shadow-blue-500/30">1</div>
                        <div class="min-w-0 flex-1 pr-2 pt-1 sm:pr-28">
                            <h3 class="text-xl font-bold text-slate-900">TaskCheck</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">Checklist app voor bedrijven: bewijs per taak, realtime dashboard, herhalingen en meerdere locaties. Gebouwd voor horeca, schoonmaak, facilitair en logistiek — waar mensen mobiel werken en managers bij willen sturen zonder e-mailchaos.</p>
                            <ul class="mt-4 flex flex-wrap gap-2">
                                @foreach(['Bewijs per taak','Realtime overzicht','Meerdere locaties','Mobiel + web','14 dagen gratis proberen'] as $tag)
                                <li class="rounded-full bg-blue-50 px-3 py-0.5 text-xs font-semibold text-blue-800">{{ $tag }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-xl font-extrabold text-slate-600">2</div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-xl font-bold text-slate-900">iAuditor (SafetyCulture)</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">Zeer breed aanbod aan inspectietemplates en audits. Krachtig voor veiligheid en compliance, minder snel “klaar voor kleine teams zonder consultant” en vaak een hoger prijspunt.</p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="rounded-full bg-emerald-50 px-3 py-0.5 text-xs font-medium text-emerald-800">Plus · diepte in inspecties</span>
                                <span class="rounded-full bg-red-50 px-3 py-0.5 text-xs font-medium text-red-800">Let op · complexiteit en kosten</span>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-xl font-extrabold text-slate-600">3</div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-xl font-bold text-slate-900">Jotform Checklist</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">Flexibel formulierenbouwer met koppelingen. Geschikt voor eenvoudige lijsten en registratie, minder gericht op doorlopende teamuitvoering, shiftsturing en vaste operationele cadans.</p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="rounded-full bg-emerald-50 px-3 py-0.5 text-xs font-medium text-emerald-800">Plus · integraties</span>
                                <span class="rounded-full bg-red-50 px-3 py-0.5 text-xs font-medium text-red-800">Let op · minder team-workflow</span>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        {{-- Doelgroep --}}
        <section class="mt-20 sm:mt-24">
            <div class="overflow-hidden rounded-3xl bg-slate-900 px-6 py-10 text-white sm:px-10 sm:py-14">
                <div class="grid gap-10 lg:grid-cols-2 lg:items-center lg:gap-14">
                    <div>
                        <span class="text-sm font-semibold uppercase tracking-wide text-blue-400">Doelgroep</span>
                        <h2 class="mt-2 text-3xl font-bold sm:text-4xl">Voor wie is TaskCheck de logische keuze?</h2>
                        <p class="mt-4 leading-relaxed text-slate-300">Voor organisaties waar <strong class="font-semibold text-white">kwaliteit en aantoonbaarheid</strong> samenkomen: je wilt geen discussie over “of iets gebeurd is”, je wilt data per taak en locatie.</p>
                        <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-5">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Prijzen</p>
                            <p class="mt-2 text-slate-200">Vanaf <span class="text-xl font-bold text-white">€39</span> per maand · 14 dagen gratis proberen · geen creditcard nodig om te starten.</p>
                        </div>
                    </div>
                    <ul class="grid gap-3 sm:grid-cols-2">
                        @foreach(['Schoonmaakbedrijven','Horeca & restaurants','Logistieke teams','Facilitair beheer','Retail met meerdere plekken','Bouw & technische diensten'] as $t)
                        <li class="flex items-center gap-3 rounded-xl bg-white/10 px-4 py-3">
                            <svg class="h-5 w-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm font-medium">{{ $t }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-20 text-center sm:mt-24">
            <div class="rounded-3xl bg-gradient-to-r from-[#2563eb] to-[#4f46e5] px-6 py-12 text-white shadow-xl shadow-blue-500/20 sm:px-12 sm:py-16">
                <h2 class="text-3xl font-extrabold sm:text-4xl">Zelf ervaren waarom teams TaskCheck kiezen</h2>
                <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">Volledige proefperiode, alle kernfuncties. Geen creditcard nodig om te beginnen.</p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Naar dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Start 14 dagen gratis</a>
                    @endauth
                    <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-xl border-2 border-white/40 px-8 py-4 text-base font-semibold text-white transition hover:bg-white/10">Bekijk prijzen</a>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mx-auto mt-10 max-w-3xl space-y-3">
                @php
                $faqs = [
                    ['Wat is de beste checklist app in 2026?','Dat hangt van je proces af. Zoek je realtime sturing, bewijs per taak en meerdere locaties in één dashboard, dan sluit TaskCheck daar het nauwkeurigst op aan.'],
                    ['Zijn er gratis checklist apps?','Er bestaan gratis basisversies, die zijn vaak beperkt voor teams op locatie. TaskCheck biedt een volledige proefperiode van 14 dagen.'],
                    ['Welke app is het beste voor kleine bedrijven?','Kleine teams profiteren juist van eenvoud: duidelijke lijsten, mobiele uitvoering en geen ingewikkelde installatie. TaskCheck schaalt mee van enkele medewerkers tot grotere organisaties.'],
                    ['Kan ik meerdere locaties koppelen?','Ja. Je beheert locaties en checklists centraal; elke plek houdt eigen voortgang en bewijs.'],
                ];
                @endphp
                @foreach($faqs as $faq)
                <details class="group cursor-pointer rounded-2xl border border-slate-200 bg-white px-5 py-4 transition hover:border-blue-200 sm:px-6">
                    <summary class="flex list-none items-center justify-between gap-3 font-semibold text-slate-900">
                        <span class="text-left">{{ $faq[0] }}</span>
                        <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-45" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $faq[1] }}</p>
                </details>
                @endforeach
            </div>
        </section>

        {{-- Interne links --}}
        <section class="mb-4 mt-16 border-t border-slate-200 pt-12 sm:mt-20">
            <p class="text-center text-sm font-semibold text-slate-900">Verder lezen</p>
            <div class="mx-auto mt-5 flex max-w-3xl flex-wrap justify-center gap-2">
                <a href="{{ route('seo.checklist-app-voor-bedrijven') }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-blue-700 transition hover:border-blue-200 hover:bg-blue-50">
                    Checklist app voor bedrijven
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
                <a href="{{ route('seo.checklist-app-schoonmaak') }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-blue-700 transition hover:border-blue-200 hover:bg-blue-50">
                    Checklist app schoonmaak
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
                <a href="{{ route('seo.horeca-checklist-app') }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-blue-700 transition hover:border-blue-200 hover:bg-blue-50">
                    Horeca checklist app
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
                <a href="{{ route('pricing') }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-blue-700 transition hover:border-blue-200 hover:bg-blue-50">
                    Prijzen
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </section>
    </div>
</main>

@include('components.footer')
</body>
</html>
