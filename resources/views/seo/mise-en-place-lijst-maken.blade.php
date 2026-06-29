<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle       = 'Mise en Place Lijst Maken voor Horeca | Gratis Digitale Checklist | TaskCheck';
        $seoDescription = 'Direct een mise en place lijst maken voor jouw restaurant, lunchroom, hotel of bakkerij? Digitaliseer je keukenvoorbereiding met TaskCheck. Inclusief gratis 14 dagen proefperiode, HACCP, NVWA en meer.';
        $seoUrl = route('seo.mise-en-place-lijst-maken');
        $seoImage = asset('images/taskcheck-horeca-seo-hero.webp');
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

<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="seo-mep-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-mep-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">Mise en place &middot; keukenvoorbereiding en checklists</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Mise en place lijst maken voor
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">de horeca</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-mep-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-mep-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Een goede mise en place zorgt ervoor dat je restaurant, lunchroom of caf&eacute; soepel draait tijdens drukke momenten.
                </p>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500 sm:text-lg">
                    Met een duidelijke mise en place lijst weet iedere medewerker precies wat voorbereid moet worden voordat gasten arriveren.
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:flex-wrap">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Naar dashboard
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Start 14 dagen gratis
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endauth
                    <a href="{{ route('pricing') }}" class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 sm:w-auto sm:min-h-0 touch-manipulation">
                        Bekijk prijzen
                    </a>
                </div>

                <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 sm:mt-6 sm:gap-x-6">
                    @foreach([

                ['Digitale HACCP Checklist', route('seo.haccp-checklist')],
                ['Temperatuurregistratie', route('seo.temperatuurregistratie')],
                ['NVWA Controle', route('seo.nvwa-controle')],
                ['Digitale Checklists', route('seo.digitale-checklists')],'Voorbeelden inbegrepen', 'Digitaal afvinken', 'HACCP gecombineerd', 'Geen creditcard proefperiode'] as $b)
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
                        <img src="{{ asset('images/taskcheck-horeca-seo-hero.webp') }}"
                             alt="Mise en place lijst maken voor horeca &ndash; TaskCheck keukenvoorbereiding"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="800"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Alles op zijn plaats v&oacute;&oacute;r de service &mdash; minder stress in de keuken.</p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-200 bg-slate-50/80">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364 1.636-1.591 1.591M21 12h-2.25m-3.636 0-1.591 1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773-1.591-1.591M12 7.5V9m0 5.25V18.75"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Voor opening</p>
                    <p class="mt-0.5 text-sm text-slate-500">keuken klaar voor service</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Per dienst</p>
                    <p class="mt-0.5 text-sm text-slate-500">lunch &middot; diner &middot; service</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">HACCP</p>
                    <p class="mt-0.5 text-sm text-slate-500">voedselveiligheid meegenomen</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Realtime</p>
                    <p class="mt-0.5 text-sm text-slate-500">overzicht voor managers</p>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <section class="mt-16 sm:mt-20">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Wat is mise en place</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Alles op zijn plaats v&oacute;&oacute;r de service</h2>
                <p class="mt-3 text-slate-500">Mise en place betekent letterlijk: alles op zijn plaats. In de horeca betekent dit dat alle voorbereidingen zijn afgerond voordat de service begint. Zo werk je sneller, maak je minder fouten en verloopt de service soepeler.</p>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php
                $hoe = [
                    ['t' => 'Waarom een lijst?', 'd' => 'Voorkom vergeten taken zoals sauzen voorbereiden, voorraad aanvullen, groenten snijden, koelingen controleren en werkstations klaarzetten.', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z']],
                    ['t' => 'Digitale lijst', 'd' => 'Papieren lijsten raken kwijt. Met TaskCheck vink je taken af, voeg je foto&rsquo;s toe, plaats je opmerkingen en meld je problemen.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z']],
                    ['t' => 'Meer controle keuken', 'd' => 'Zie welke taken klaar zijn, welke openstaan, wie verantwoordelijk is en welke controles ontbreken. Voorkom verrassingen tijdens drukke momenten.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z', 'M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.546 5.974 5.974 0 0 1-1.313-.352 5.99 5.99 0 0 0 2.743-2.334Z']],
                    ['t' => 'HACCP combineren', 'd' => 'Combineer mise en place met temperatuurregistratie, HACCP controles, schoonmaakcontroles en leverancierscontrole in &eacute;&eacute;n systeem.', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z']],
                    ['t' => 'Snellere voorbereiding', 'd' => 'Minder vergeten taken, meer overzicht en betere voedselveiligheid. Iedereen werkt volgens dezelfde standaard.', 'c' => 'text-blue-700', 'bg' => 'bg-blue-50', 'paths' => ['M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z']],
                    ['t' => 'Meerdere locaties', 'd' => 'Beheer mise en place checklists voor meerdere restaurants, lunchrooms of caf&eacute;s vanuit &eacute;&eacute;n centraal dashboard.', 'c' => 'text-slate-700', 'bg' => 'bg-slate-100', 'paths' => ['M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008H17.25v-.008Zm0 3h.008v.008H17.25v-.008Zm0 3h.008v.008H17.25v-.008Z']],
                ];
                @endphp
                @foreach($hoe as $h)
                <div class="rounded-2xl border border-slate-100 bg-white p-5 text-center shadow-sm transition hover:border-blue-200 hover:shadow-md">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl {{ $h['bg'] }}">
                        <svg class="h-5 w-5 {{ $h['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            @foreach($h['paths'] as $d)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-bold text-slate-900">{{ $h['t'] }}</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">{!! $h['d'] !!}</p>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto mb-10 max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Voorbeelden</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Mise en place lijst keuken</h2>
                <p class="mt-3 text-slate-500">Gebruik deze voorbeelden als basis en pas ze aan op jouw keuken. Met TaskCheck digitaliseer je ze direct voor je team.</p>
            </div>
            <div class="grid gap-5 lg:grid-cols-3">
                @php
                $voorbeelden = [
                    ['t' => 'Voor opening', 'items' => ['Koelingen controleren', 'Temperatuur registreren', 'Werkbanken reinigen', 'Groenten snijden', 'Sauzen voorbereiden', 'Vlees portioneren', 'Voorraad controleren', 'Apparatuur controleren']],
                    ['t' => 'Voor lunchservice', 'items' => ['Brood afbakken', 'Dranken aanvullen', 'Werkstation controleren', 'Bestek aanvullen', 'HACCP controles uitvoeren']],
                    ['t' => 'Voor diner', 'items' => ['Ingredi&euml;nten aanvullen', 'Werkplekken organiseren', 'Specials voorbereiden', 'Temperatuurcontrole uitvoeren']],
                ];
                @endphp
                @foreach($voorbeelden as $vb)
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900">{{ $vb['t'] }}</h3>
                    <ul class="mt-4 space-y-2.5">
                        @foreach($vb['items'] as $item)
                        <li class="flex items-start gap-2.5 text-sm text-slate-700">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span>{!! $item !!}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 rounded-3xl bg-gradient-to-br from-slate-50 to-blue-50/60 p-8 sm:p-12 sm:mt-24">
            <div class="lg:grid lg:grid-cols-2 lg:items-center lg:gap-12">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Waarom TaskCheck</span>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Alles voor keukenvoorbereiding in &eacute;&eacute;n platform</h2>
                    <p class="mt-4 leading-relaxed text-slate-600">TaskCheck helpt horecabedrijven met mise en place checklists, opening checklists, sluitingschecklists, HACCP registraties, temperatuurregistratie en werkcontrole. Daardoor werkt iedereen volgens dezelfde standaard.</p>
                    <ul class="mt-5 space-y-2 text-sm text-slate-700">
                        @foreach(['Mise en place checklists', 'Opening checklists', 'Sluitingschecklists', 'HACCP registraties', 'Temperatuurregistratie', 'Werkcontrole'] as $item)
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-8 grid grid-cols-2 gap-3 lg:mt-0">
                    @php
                    $bedrijven = [
                        ['label' => 'Restaurants', 'c' => 'text-orange-600', 'bg' => 'bg-orange-50', 'paths' => ['M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z']],
                        ['label' => 'Lunchrooms', 'c' => 'text-amber-600', 'bg' => 'bg-amber-50', 'paths' => ['M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M12 8.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z']],
                        ['label' => 'Caf&eacute;s', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104v5.714a2.25 2.25 0 0 0 .659 1.591M5 14.5a2.25 2.25 0 0 0-2.25 2.219V17.5A2.25 2.25 0 0 0 5 19.5h14a2.25 2.25 0 0 0 2.25-2.25v-1.781a2.25 2.25 0 0 0-2.25-2.25M5 14.5l2.25-2.25M19.8 15.3 14.25 12.75 14.25 9.336m1.5-6.231a24.32 24.32 0 0 1 0 5.696m-1.5-6.231V9.75']],
                        ['label' => 'Hotels', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008H17.25v-.008Zm0 3h.008v.008H17.25v-.008Zm0 3h.008v.008H17.25v-.008Z']],
                        ['label' => 'Catering', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5']],
                        ['label' => 'Fastfood', 'c' => 'text-red-600', 'bg' => 'bg-red-50', 'paths' => ['M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z']],
                        ['label' => 'Zorgkeukens', 'c' => 'text-slate-600', 'bg' => 'bg-slate-100', 'paths' => ['M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z']],
                    ];
                    @endphp
                    @foreach($bedrijven as $b)
                    <div class="flex items-center gap-3 rounded-xl border border-blue-100 bg-white px-4 py-3 shadow-sm transition hover:border-blue-300">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $b['bg'] }}">
                            <svg class="h-5 w-5 {{ $b['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                @foreach($b['paths'] as $d)
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                                @endforeach
                            </svg>
                        </span>
                        <span class="text-sm font-semibold text-slate-800">{!! $b['label'] !!}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto mb-10 max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Voordelen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Digitale mise en place in de praktijk</h2>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <svg class="mb-3 h-8 w-8 text-blue-200" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    <p class="italic leading-relaxed text-slate-700">&ldquo;Onze mise en place staat nu digitaal. Geen vergeten sauzen of koelingen meer &mdash; de keuken start elke dienst voorbereid.&rdquo;</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">K</div>
                        <div><p class="text-sm font-semibold text-slate-900">Kevin, chef-kok</p><p class="text-xs text-slate-500">Restaurant</p></div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <svg class="mb-3 h-8 w-8 text-indigo-200" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    <p class="italic leading-relaxed text-slate-700">&ldquo;Minder stress tijdens service, realtime inzicht en HACCP gecombineerd met onze voorbereidingslijsten. Precies wat we nodig hadden.&rdquo;</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">N</div>
                        <div><p class="text-sm font-semibold text-slate-900">Nadia, keukenmanager</p><p class="text-xs text-slate-500">Lunchroom</p></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-20 text-center sm:mt-24">
            <div class="rounded-3xl bg-gradient-to-r from-[#2563eb] to-[#4f46e5] px-6 py-12 text-white shadow-xl shadow-blue-500/20 sm:px-12 sm:py-16">
                <h2 class="text-3xl font-extrabold sm:text-4xl">Start vandaag met TaskCheck</h2>
                <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">Wil je meer structuur in de voorbereiding van je restaurant? Met TaskCheck maak je eenvoudig digitale mise en place lijsten voor jouw team.</p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Naar dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Start 14 dagen gratis</a>
                    @endauth
                    <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-xl border-2 border-white/40 px-8 py-4 text-base font-semibold text-white transition hover:bg-white/10">Bekijk prijzen</a>
                </div>
                <p class="mt-4 text-sm text-white/80">Geen verplichtingen &middot; Geen creditcard &middot; Direct starten</p>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mx-auto mt-10 max-w-3xl space-y-3">
                @php
                $faqs = [
                    ['Wat betekent mise en place?', 'Mise en place betekent dat alle voorbereidingen zijn uitgevoerd voordat de service begint.'],
                    ['Waarom een mise en place lijst gebruiken?', 'Een lijst voorkomt dat belangrijke voorbereidingen worden vergeten.'],
                    ['Kan ik een digitale mise en place lijst maken?', 'Ja. Met TaskCheck kun je taken digitaal beheren en afvinken.'],
                    ['Kan ik foto\'s toevoegen aan taken?', 'Ja. Medewerkers kunnen foto\'s, video\'s en opmerkingen toevoegen.'],
                    ['Is TaskCheck geschikt voor meerdere restaurants?', 'Ja. Je kunt meerdere locaties beheren vanuit één dashboard.'],
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

        <section class="mb-4 mt-16 border-t border-slate-200 pt-12 sm:mt-20">
            <p class="text-center text-sm font-semibold text-slate-900">Gerelateerde pagina&rsquo;s</p>
            <div class="mx-auto mt-5 flex max-w-4xl flex-wrap justify-center gap-2">
                @foreach([
                    ['Horeca app', route('seo.horeca-app')],
                    ['Restaurant checklist app', route('seo.restaurant-checklist-app')],
                    ['Horeca checklist app', route('seo.horeca-checklist-app')],
                    ['HACCP app', route('seo.haccp-app')],
                    ['Temperatuurregistratie app', route('seo.temperatuurregistratie-app')],
                    ['Opening checklist horeca', route('seo.opening-checklist-horeca')],
                    ['Sluitingschecklist horeca', route('seo.sluitings-checklist-horeca')],
                    ['Prijzen', route('pricing')],
                ] as $link)
                <a href="{{ $link[1] }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-blue-700 transition hover:border-blue-200 hover:bg-blue-50">
                    {{ $link[0] }}
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
                @endforeach
            </div>
        </section>
    </div>
<section class="py-12"><div class="max-w-4xl mx-auto px-4"><h2 class="text-2xl font-bold mb-4">Voorbeeld mise en place lijst voor verschillende horecazaken</h2><p class="mb-3">Elke horecazaak heeft zijn eigen voorbereidingen. Bekijk hieronder voorbeelden per type zaak en ontdek hoe je deze eenvoudig digitaliseert via TaskCheck.</p><ul class="list-disc ml-6 mb-3"><li><strong>Restaurant:</strong> Mise en place voor lunch en diner, wijnkoeling, dagverse voorbereidingen.</li><li><strong>Lunchroom:</strong> Broodjes, salades, koffiezetapparatuur, schoonmaakrondes.</li><li><strong>Fastfood:</strong> Voorraden, frituuroliën, temperatuurchecks, schoonmaakstations.</li><li><strong>Hotel:</strong> Ontbijtbuffet, roomservice, HACCP-registratie, voorraadbeheer.</li><li><strong>Bakkerij:</strong> Deeg voorbereiding, ovencontrole, koelcel temperatuur, schoonmaak.</li><li><strong>Slagerij:</strong> Vleesvoorbereiding, hygiënechecks, temperatuur- en allergenenregistratie.</li></ul><p class="mb-3">Met TaskCheck maak je voor ieder type zaak een eigen digitale checklist op maat.</p></div></section>
</main>

@include('components.footer')
</body>
</html>
