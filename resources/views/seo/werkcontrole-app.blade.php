<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Werkcontrole app voor teams en locaties | TaskCheck';
        $seoDescription = 'Werkcontrole app voor bedrijven: realtime taken, controle op uitvoering en bewijs per taak. Geschikt voor horeca, schoonmaak en meer.';
        $seoUrl = route('seo.werkcontrole-app');
        $seoImage = asset('images/seo-werkcontrole-hero.png');
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
                <pattern id="seo-werkcontrole-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-werkcontrole-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">Werkcontrole app · teams, locaties, realtime</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Werkcontrole app voor
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">operationele processen</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-werkcontrole-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-werkcontrole-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Geen losse lijsten meer in Excel of op papier. Met TaskCheck weet je wat je team doet, wat af is en waar aandacht nodig is — realtime, per locatie.
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
                    @foreach(['Realtime per locatie','Bewijs per taak','Minder Excel en papier','Geen creditcard proefperiode'] as $b)
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
                        <img src="{{ asset('images/seo-werkcontrole-hero.png') }}"
                             alt="Werkcontrole app – manager met tablet controleert taakvoortgang van team in real-time"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="900"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Één overzicht voor managers — teams werken op mobiel.</p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-200 bg-slate-50/80">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Realtime</p>
                    <p class="mt-0.5 text-sm text-slate-500">voortgang per locatie</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Bewijs</p>
                    <p class="mt-0.5 text-sm text-slate-500">foto · video · tekst</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">14 dagen</p>
                    <p class="mt-0.5 text-sm text-slate-500">gratis proberen</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 18.75a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75ZM6 12.75a.75.75 0 0 0-.75.75v3c0 .414.336.75.75.75h12a.75.75 0 0 0 .75-.75v-3a.75.75 0 0 0-.75-.75H6Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5A2.25 2.25 0 0 1 8.25 2.25h7.5A2.25 2.25 0 0 1 18 4.5v15a2.25 2.25 0 0 1-2.25 2.25h-7.5A2.25 2.25 0 0 1 6 19.5v-15Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Mobiel</p>
                    <p class="mt-0.5 text-sm text-slate-500">werkt op elke telefoon</p>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <section class="mt-16 sm:mt-20 lg:grid lg:grid-cols-2 lg:items-center lg:gap-16">
            <div>
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Uitleg</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Wat is een werkcontrole app?</h2>
                <p class="mt-4 leading-relaxed text-slate-600">Een werkcontrole app is software waarmee je dagelijkse taken plant, uitvoert en controleert. In plaats van losse lijsten in Excel of papier werk je met digitale workflows. Teams zien hun taken realtime, managers zien voortgang en afwijkingen direct.</p>
                <p class="mt-3 leading-relaxed text-slate-600">Voor bedrijven met meerdere medewerkers of locaties is dit essentieel. Zonder centrale werkcontrole ontstaan fouten, kwaliteitsverschillen en extra herstelwerk.</p>
            </div>
            <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:mt-0">
                @php
                $wats = [
                    ['t' => 'Taken plannen', 'd' => 'Maak checklists per team, locatie of shift.', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z']],
                    ['t' => 'Live volgen', 'd' => 'Zie direct wat open staat of aandacht vraagt.', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12Z', 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z']],
                    ['t' => 'Bewijs vragen', 'd' => 'Foto of tekst per taak als objectief bewijs.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z', 'M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z']],
                    ['t' => 'Patronen zien', 'd' => 'Data over uitvoering voor continue verbetering.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z']],
                ];
                @endphp
                @foreach($wats as $w)
                <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm transition hover:border-blue-200 hover:shadow-md sm:p-5">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-lg {{ $w['bg'] }}">
                        <svg class="h-5 w-5 {{ $w['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            @foreach($w['paths'] as $d)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </div>
                    <p class="mt-2 text-sm font-bold text-slate-900">{{ $w['t'] }}</p>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ $w['d'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-50 to-blue-50/50 p-8 sm:p-12 sm:mt-24">
            <div class="mx-auto mb-10 max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Voordelen</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Kernvoordelen voor operationele teams</h2>
            </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @php
                $voordelen = [
                    ['t' => 'Realtime zicht', 'd' => 'Direct zien welke taken openstaan, afgerond zijn of aandacht vragen.', 'c' => 'text-amber-600', 'bg' => 'bg-amber-50', 'paths' => ['M3.75 13.5 10.5 6 13.5 9l-6.75 6.75M20.25 4.5 15 9.75l-3.75 3.75']],
                    ['t' => 'Betrouwbaar bewijs', 'd' => 'Foto, video of tekst per taak voor objectieve controle.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z', 'M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z']],
                    ['t' => 'Minder ruis', 'd' => 'Heldere rollen en taken verminderen mondelinge overdracht.', 'c' => 'text-slate-600', 'bg' => 'bg-slate-100', 'paths' => ['M9 12.75 11.25 15 15 9.75m-3.75-4.5H21M4.5 19.5h15M6.75 7.5h.008v.008H6.75V7.5Zm2.25 0h.008v.008H9V7.5Zm2.25 0h.008v.008h-.008V7.5Zm2.25 0h.008v.008H13.5V7.5Zm0 0h.008v.008H13.5V7.5Zm0 3h.008v.008H13.5v-.008Zm2.25 0h.008v.008h-.008V10.5Zm-6 3h.008v.008H9.75v-.008Zm2.25 0h.008v.008h-.008V13.5Zm-6 3h.008v.008H3.75v-.008Zm2.25 0h.008v.008H6v-.008Zm2.25 0h.008v.008h-.008V16.5Z']],
                    ['t' => 'Schaalbaar', 'd' => 'Templates en herhaalplanning voor meerdere teams en locaties.', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z', 'M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z']],
                    ['t' => 'Sneller bijsturen', 'd' => 'Inzicht zonder constant te hoeven bellen of mailen.', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.636 3.502 1.044 5.354 1.33M15 11.25a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z']],
                    ['t' => 'Continue verbetering', 'd' => 'Data over patronen gebruik je voor gerichte coaching.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z']],
                ];
                @endphp
                @foreach($voordelen as $v)
                <div class="flex gap-3 rounded-2xl border border-white/80 bg-white px-4 py-4 shadow-sm">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $v['bg'] }}">
                        <svg class="h-5 w-5 {{ $v['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            @foreach($v['paths'] as $d)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900">{{ $v['t'] }}</p>
                        <p class="mt-0.5 text-xs leading-relaxed text-slate-500 sm:text-sm">{{ $v['d'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Sectoren</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Voor welke bedrijven werkt het?</h2>
                <p class="mt-3 text-slate-500">Overal waar kwaliteit dagelijks wordt uitgevoerd en gecontroleerd.</p>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php
                $sectoren = [
                    ['t' => 'Horeca', 'd' => 'Opening, mise-en-place, service en sluitroutines.', 'c' => 'text-amber-600', 'bg' => 'bg-amber-50', 'paths' => ['M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z']],
                    ['t' => 'Schoonmaak', 'd' => 'Rondes, oplevering en kwaliteitscontrole per locatie.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0']],
                    ['t' => 'Facilitair', 'd' => 'Inspecties, onderhoud en veiligheidscontroles.', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z']],
                    ['t' => 'Logistiek', 'd' => 'Ontvangst, opslag en uitgifte workflows.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M20.25 7.5l-.625 10.814a.75.75 0 0 1-.75.686H4.375a.75.75 0 0 1-.75-.686L3 7.5m18 0h-2.25M3 7.5h5.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Zm6.75 0v-1.875a.375.375 0 0 1 .375-.375h3c.199 0 .375.176.375.375V7.5m-4.5 0h6', 'M9.75 16.5h4.5']],
                    ['t' => 'Retail', 'd' => 'Opening, schapcontrole en sluitprocedures.', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.118 1.243H4.25c-.668 0-1.188-.578-1.118-1.243l1.263-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z']],
                    ['t' => 'Technisch', 'd' => 'Onderhoudsinspecties en compliance taken.', 'c' => 'text-slate-600', 'bg' => 'bg-slate-100', 'paths' => ['M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17 5.81 9.56a2.652 2.652 0 1 1 3.748-3.748l5.61 5.61M11.42 15.17l-1.592-1.592m0 0-1.04 1.04a2.652 2.652 0 0 1-3.748 0 2.652 2.652 0 0 1 0-3.748l1.04-1.04', 'M18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z']],
                ];
                @endphp
                @foreach($sectoren as $s)
                <div class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $s['bg'] }}">
                        <svg class="h-6 w-6 {{ $s['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            @foreach($s['paths'] as $d)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <h3 class="font-bold text-slate-900">{{ $s['t'] }}</h3>
                        <p class="mt-0.5 text-sm leading-relaxed text-slate-500">{{ $s['d'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 text-center sm:mt-24">
            <div class="rounded-3xl bg-gradient-to-r from-[#2563eb] to-[#4f46e5] px-6 py-12 text-white shadow-xl shadow-blue-500/20 sm:px-12 sm:py-16">
                <h2 class="text-3xl font-extrabold sm:text-4xl">Van taakbeheer naar continue verbetering</h2>
                <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">Door data over uitvoering te verzamelen zie je patronen. Die inzichten gebruik je om processen te verbeteren. Zo groeit TaskCheck mee van operationele basis naar strategisch stuurinstrument.</p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Naar dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Start 14 dagen gratis</a>
                    @endauth
                    <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-xl border-2 border-white/40 px-8 py-4 text-base font-semibold text-white transition hover:bg-white/10">Bekijk prijzen</a>
                </div>
                <p class="mt-4 text-sm text-white/80">Geen verplichtingen · Geen creditcard · Direct aan de slag</p>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mx-auto mt-10 max-w-3xl space-y-3">
                @php $faqs = [['Wat is een werkcontrole app precies?','Een werkcontrole app is software waarmee je dagelijkse taken digitaal plant, uitvoert en controleert. Teams zien hun taken in realtime, managers zien voortgang direct.'],['Voor welke sectoren is dit geschikt?','Horeca, schoonmaak, facilitair, logistiek, retail en technisch onderhoud. Overal waar kwaliteit dagelijks uitgevoerd en gecontroleerd moet worden.'],['Kan ik bewijs opvragen per taak?','Ja. Je kunt per taak foto, video of tekstbewijs verplicht stellen. Dat geeft objectieve controle en voorkomt discussies.'],['Werkt het ook voor meerdere locaties?','Zeker. Elke locatie heeft zijn eigen checklists en voortgang. Je beheert alles vanuit één centraal dashboard.']]; @endphp
                @foreach($faqs as $faq)
                <details class="group cursor-pointer rounded-2xl border border-slate-200 bg-white px-5 py-4 transition hover:border-blue-200 sm:px-6">
                    <summary class="flex list-none items-center justify-between gap-3 font-semibold text-slate-900">
                        <span class="text-left text-sm">{{ $faq[0] }}</span>
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
                    ['Takenlijst personeel', route('seo.takenlijst-personeel')],
                    ['Horeca checklist app', route('seo.horeca-checklist-app')],
                    ['Checklist app schoonmaak', route('seo.checklist-app-schoonmaak')],
                    ['Blog: stoppen met Excel', route('blog.waarom-bedrijven-stoppen-met-excel-checklists')],
                ] as $link)
                <a href="{{ $link[1] }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-blue-700 transition hover:border-blue-200 hover:bg-blue-50">
                    {{ $link[0] }}
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
                @endforeach
            </div>
        </section>

    </div>
</main>

@include('components.footer')
</body>
</html>
