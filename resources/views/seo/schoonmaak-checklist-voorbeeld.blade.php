<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Schoonmaak checklist voorbeeld – gratis template';
        $seoDescription = 'Gratis schoonmaak checklist voorbeeld voor kantoor, toilet en keuken. Praktische lijsten per ruimte en direct digitaal bijhouden met TaskCheck.';
        $seoUrl = route('seo.schoonmaak-checklist-voorbeeld');
        $seoImage = asset('images/seo-checklist-schoonmaak-hero.png');
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
                <pattern id="seo-schoon-vb-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-schoon-vb-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(16,185,129,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">Gratis template · kantoor, toilet, keuken</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Schoonmaak checklist voorbeeld:
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">gratis template</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-schoon-vb-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-schoon-vb-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Zonder duidelijke schoonmaaklijst worden taken overgeslagen of dubbel gedaan. Hier vind je een praktisch voorbeeld per ruimte — direct te gebruiken of digitaal bij te houden in TaskCheck.
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
                    @foreach(['Per ruimte uit te breiden','Bewijs optioneel per taak','Mobiel eerst','Geen creditcard proefperiode'] as $b)
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
                        <img src="{{ asset('images/seo-checklist-schoonmaak-hero.png') }}"
                             alt="Schoonmaker met digitale checklist op tablet"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="800"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Zelfde template op papier of — beter — als vaste digitale lijst per locatie.</p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-200 bg-slate-50/80">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Dagelijks</p>
                    <p class="mt-0.5 text-sm text-slate-500">dezelfde kwaliteit</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Minder</p>
                    <p class="mt-0.5 text-sm text-slate-500">vergeten taken</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Bewijs</p>
                    <p class="mt-0.5 text-sm text-slate-500">per taak vast te leggen</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 18.75a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75ZM6 12.75a.75.75 0 0 0-.75.75v3c0 .414.336.75.75.75h12a.75.75 0 0 0 .75-.75v-3a.75.75 0 0 0-.75-.75H6Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5A2.25 2.25 0 0 1 8.25 2.25h7.5A2.25 2.25 0 0 1 18 4.5v15a2.25 2.25 0 0 1-2.25 2.25h-7.5A2.25 2.25 0 0 1 6 19.5v-15Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Mobiel</p>
                    <p class="mt-0.5 text-sm text-slate-500">telefoon, geen papier</p>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <section class="mt-12 max-w-3xl sm:mt-14">
            <p class="text-lg leading-relaxed text-slate-600">Schoonmaak gaat mis als er geen duidelijke lijst is. De ene medewerker denkt dat de ander het doet. Taken worden half gedaan of vergeten. Klanten en collega&rsquo;s merken het direct.</p>
            <p class="mt-4 text-lg leading-relaxed text-slate-600">Een goede schoonmaak checklist geeft structuur: elke taak staat erin, per ruimte en per frequentie. Op deze pagina vind je een gratis voorbeeld dat je direct kunt gebruiken.</p>
        </section>

        <section class="mt-16 sm:mt-20 lg:grid lg:grid-cols-2 lg:items-start lg:gap-16">
            <div>
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Het probleem</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Waarom een schoonmaak checklist belangrijk is</h2>
                <p class="mt-4 leading-relaxed text-slate-600">Zonder checklist weet niemand precies wat zijn of haar taak is. De ene keer wordt de vloer gedweild, de andere keer niet. Bij een klacht is er vaak weinig om op terug te vallen als er geen registratie is.</p>
                <p class="mt-3 leading-relaxed text-slate-600">Een checklist geeft grip: taken zijn duidelijk omschreven, medewerkers weten wat er verwacht wordt en je kunt controleren of alles gedaan is — zonder er de hele tijd fysiek bij te staan.</p>
                <p class="mt-3 leading-relaxed text-slate-600">Voor bedrijven met meerdere locaties of wisselend personeel is een digitale checklist extra waardevol: één standaard voor iedereen.</p>
            </div>
            <div class="mt-10 space-y-3 lg:mt-0">
                @php $problemen = [
                    ['Taken worden vergeten of overgeslagen'],
                    ['Kwaliteitsverschil tussen medewerkers onderling'],
                    ['Weinig om op terug te vallen bij klachten van opdrachtgevers'],
                    ['Discussie over wie wat had moeten doen'],
                    ['Papieren lijstjes raken kwijt of worden niet bijgehouden'],
                ]; @endphp
                @foreach($problemen as $p)
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">{{ $p[0] }}</span>
                </div>
                @endforeach
                <div class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                    <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm font-semibold text-emerald-800">Een vaste schoonmaak checklist helpt dit stap voor stap oplossen</span>
                </div>
            </div>
        </section>

        <section class="mt-20 rounded-3xl bg-gradient-to-br from-slate-50 to-blue-50/60 p-8 sm:p-12 sm:mt-24">
            <div class="mx-auto mb-10 max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Gratis voorbeeld</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Schoonmaak checklist voorbeeld</h2>
                <p class="mt-3 text-slate-500">Een algemene dagelijkse lijst als basis. Pas taken aan op jouw pand en afspraken met opdrachtgevers.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $algemeen = [
                        'Vloer vegen',
                        'Vloer dweilen',
                        'Prullenbakken legen en zakken vervangen',
                        'Oppervlakken afstoffen',
                        'Deuren en deurklinken desinfecteren',
                        'Lichtschakelaars schoonmaken',
                        'Ramen en glaswerk reinigen',
                        'Hygiënemiddelen bijvullen',
                    ];
                    $toiletten = [
                        'Toiletpot reinigen en desinfecteren',
                        'Toiletbril schoonmaken',
                        'Wastafel en kraan reinigen',
                        'Spiegel schoonmaken',
                        'Vloer schrobben en desinfecteren',
                        'Prullenbak legen',
                        'Zeep en papier bijvullen',
                        'Geur controleren en luchten',
                    ];
                    $keuken = [
                        'Aanrecht afnemen en desinfecteren',
                        'Spoelbak reinigen',
                        'Magnetron van binnen schoonmaken',
                        'Koelkast buiten afnemen',
                        'Koffiezetapparaat schoonmaken',
                        'Vloer vegen en dweilen',
                        'Vuilnisbak legen',
                        'Doeken en sponzen vervangen',
                    ];
                @endphp

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <p class="mb-3 text-xs font-bold uppercase tracking-wide text-blue-600">Dagelijkse taken (algemeen)</p>
                    <ul class="space-y-1.5">
                        @foreach($algemeen as $item)
                        <li class="flex items-center gap-2.5 text-sm text-slate-700">
                            <span class="flex h-4 w-4 shrink-0 rounded border-2 border-slate-300" aria-hidden="true"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <p class="mb-3 text-xs font-bold uppercase tracking-wide text-blue-600">Sanitair &amp; toiletten</p>
                    <ul class="space-y-1.5">
                        @foreach($toiletten as $item)
                        <li class="flex items-center gap-2.5 text-sm text-slate-700">
                            <span class="flex h-4 w-4 shrink-0 rounded border-2 border-slate-300" aria-hidden="true"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <p class="mb-3 text-xs font-bold uppercase tracking-wide text-blue-600">Keuken &amp; pantry</p>
                    <ul class="space-y-1.5">
                        @foreach($keuken as $item)
                        <li class="flex items-center gap-2.5 text-sm text-slate-700">
                            <span class="flex h-4 w-4 shrink-0 rounded border-2 border-slate-300" aria-hidden="true"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <p class="mt-6 text-center text-xs text-slate-500">Dit is een basisvoorbeeld. In TaskCheck maak je je eigen checklist per ruimte, locatie en frequentie.</p>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Per ruimte</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Checklist per ruimte: toilet, kantoor en keuken</h2>
                <p class="mt-3 text-slate-500">Elke ruimte heeft andere taken en frequentie. Hier de belangrijkste punten per type ruimte.</p>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-3">

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-red-100 bg-red-50 px-5 py-4">
                        <p class="font-bold text-slate-900">Toilet</p>
                        <p class="mt-0.5 text-xs text-slate-500">Minimaal 2x per dag bij gebruik</p>
                    </div>
                    <ul class="space-y-2 p-5">
                        @foreach([
                            'Toiletpot schrobben en desinfecteren',
                            'Bril en deksel reinigen',
                            'Wastafel en kraan schoonmaken',
                            'Spiegel poetsen',
                            'Vloer dweilen met desinfectiemiddel',
                            'Prullenbak legen',
                            'Toiletpapier en zeep bijvullen',
                            'Geur controleren',
                        ] as $item)
                        <li class="flex items-start gap-2 text-sm text-slate-600">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-blue-100 bg-blue-50 px-5 py-4">
                        <p class="font-bold text-slate-900">Kantoor</p>
                        <p class="mt-0.5 text-xs text-slate-500">Dagelijks of 3x per week</p>
                    </div>
                    <ul class="space-y-2 p-5">
                        @foreach([
                            'Bureau afnemen en afstoffen',
                            'Toetsenbord en muis schoonmaken',
                            'Scherm reinigen',
                            'Prullenbak legen',
                            'Vloer stofzuigen of dweilen',
                            'Ramen schoonmaken (wekelijks)',
                            'Keukentje of koffiehoek bijhouden',
                            'Vergadertafel reinigen na gebruik',
                        ] as $item)
                        <li class="flex items-start gap-2 text-sm text-slate-600">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-blue-100 bg-blue-50 px-5 py-4">
                        <p class="font-bold text-slate-900">Keuken</p>
                        <p class="mt-0.5 text-xs text-slate-500">Dagelijks; koel/diepvries wekelijks</p>
                    </div>
                    <ul class="space-y-2 p-5">
                        @foreach([
                            'Aanrecht schoonmaken en desinfecteren',
                            'Spoelbak reinigen',
                            'Magnetron van binnen schoonmaken',
                            'Koelkastbuitenkant afnemen',
                            'Koffiezetapparaat spoelen',
                            'Vloer vegen en dweilen',
                            'Vuilnisbak legen en bak reinigen',
                            'Doeken en sponzen vervangen',
                        ] as $item)
                        <li class="flex items-start gap-2 text-sm text-slate-600">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                <div class="border-b border-slate-200 px-6 py-4">
                    <p class="font-bold text-slate-900">Frequentie per taak</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-slate-200 bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-slate-600">Taak</th>
                                <th class="px-4 py-3 text-center font-semibold text-slate-600">Dagelijks</th>
                                <th class="px-4 py-3 text-center font-semibold text-slate-600">Wekelijks</th>
                                <th class="px-4 py-3 text-center font-semibold text-slate-600">Maandelijks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php $frequenties = [
                                ['Vloer vegen en dweilen', true, false, false],
                                ['Prullenbakken legen', true, false, false],
                                ['Toiletten reinigen en desinfecteren', true, false, false],
                                ['Spiegels en glas reinigen', true, false, false],
                                ['Keukenapparaten schoonmaken', true, false, false],
                                ['Ramen wassen', false, true, false],
                                ['Koelkast van binnen reinigen', false, true, false],
                                ['Radiatoren en ventilatieroosters', false, false, true],
                                ['Diepvries ontdooien en reinigen', false, false, true],
                            ]; @endphp
                            @foreach($frequenties as $row)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3 text-slate-700">{{ $row[0] }}</td>
                                @foreach([$row[1], $row[2], $row[3]] as $val)
                                <td class="px-4 py-3 text-center">
                                    @if($val)
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50">
                                        <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    @else
                                    <span class="text-slate-300">–</span>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Valkuilen</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Veelgemaakte fouten bij schoonmaakchecklists</h2>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2">
                @php $fouten = [
                    ['Taken zijn te vaag omschreven', 'Schrijf niet "toilet schoonmaken" maar concrete stappen: pot schrobben, bril desinfecteren, vloer dweilen. Hoe duidelijker, hoe minder interpretatie.'],
                    ['Geen frequentie bij elke taak', 'Zonder "hoe vaak" doet iedereen het anders. Zet altijd de gewenste frequentie bij de taak.'],
                    ['Checklist nooit bijwerken', 'Een lijst die niet meer matcht met de praktiek werkt tegen je. Plan periodiek een korte review met je team.'],
                    ['Geen registratie bij discussies', 'Zonder afvinken of bewijs is terugkijken lastig. Kies voor digitale afhandeling als je sturing en overzicht wilt.'],
                    ['Alleen papier', 'Papier raakt kwijt en is lastig te delen tussen locaties. Digitaal geeft sneller overzicht.'],
                    ['Team niet betrekken', 'Checklists die alleen "van bovenaf" komen worden minder consequent gevolgd. Vraag naar onduidelijkheden op de vloer.'],
                ]; @endphp
                @foreach($fouten as $f)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-200">
                    <p class="mb-1.5 text-sm font-bold text-slate-900">{{ $f[0] }}</p>
                    <p class="text-sm leading-relaxed text-slate-500">{{ $f[1] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-50 to-blue-50/50 p-8 sm:p-12 sm:mt-24">
            <div class="lg:grid lg:grid-cols-2 lg:items-center lg:gap-12">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">TaskCheck</span>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Hoe TaskCheck helpt</h2>
                    <p class="mt-3 leading-relaxed text-slate-600">TaskCheck is een digitale checklist app voor operationele teams. Je zet je schoonmaaklijst online, wijst medewerkers toe en ziet wat af is — per ruimte en locatie.</p>
                    <p class="mt-3 leading-relaxed text-slate-600">Medewerkers werken op de telefoon. Waar jij bewijs vereist, kunnen ze bijvoorbeeld een foto of notitie toevoegen voordat een taak op “klaar” staat.</p>
                    <a href="{{ route('seo.checklist-app-schoonmaak') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-800">
                        Meer over de checklist app voor schoonmaak
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
                <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:mt-0">
                    @php
                    $features = [
                        ['t' => 'Per ruimte of locatie', 'paths' => ['M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z', 'M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z'], 'c' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                        ['t' => 'Bewijs waar nodig', 'paths' => ['M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z', 'M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z'], 'c' => 'text-sky-600', 'bg' => 'bg-sky-50'],
                        ['t' => 'Live voortgang', 'paths' => ['M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z'], 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                        ['t' => 'Dagelijks herhalen', 'paths' => ['M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99'], 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50'],
                        ['t' => 'Openstaand werk zichtbaar', 'paths' => ['M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.636 3.502 1.044 5.354 1.33M15 11.25a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'], 'c' => 'text-amber-600', 'bg' => 'bg-amber-50'],
                        ['t' => 'Snel nieuwe mensen inwerken', 'paths' => ['M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z'], 'c' => 'text-slate-600', 'bg' => 'bg-slate-100'],
                    ];
                    @endphp
                    @foreach($features as $feat)
                    <div class="flex items-center gap-3 rounded-xl border border-white/80 bg-white px-4 py-3 shadow-sm">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $feat['bg'] }}">
                            <svg class="h-5 w-5 {{ $feat['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                @foreach($feat['paths'] as $d)
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                                @endforeach
                            </svg>
                        </span>
                        <span class="text-sm font-semibold text-slate-900">{{ $feat['t'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Doelgroep</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Voor wie is dit geschikt</h2>
                <p class="mt-3 text-slate-500">Voor iedereen die schoonmaaktaken wil structureren — ongeacht de branche.</p>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php $doelgroepen = [
                    ['Schoonmaakbedrijf', 'Meerdere locaties, wisselend personeel en opdrachtgevers die vastlegging verwachten.'],
                    ['Kantoor en bedrijfspand', 'Eigen schoonmaak of externe partij aansturen met duidelijke taken.'],
                    ['Horeca en restaurant', 'Hygiëne en keuken/sanitair structureel vastleggen.'],
                    ['Zorg en welzijn', 'Striktere hygiënenormen met duidelijke taken en bewijs waar nodig.'],
                    ['Retail en winkels', 'Vloer, etalage en sanitair per dienst afvinkbaar.'],
                    ['Teamleiders en managers', 'Overzicht op meerdere plekken zonder overal fysiek te zijn.'],
                ]; @endphp
                @foreach($doelgroepen as $d)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                    <p class="text-sm font-bold text-slate-900">{{ $d[0] }}</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $d[1] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 text-center sm:mt-24">
            <div class="rounded-3xl bg-gradient-to-r from-[#2563eb] to-[#4f46e5] px-6 py-12 text-white shadow-xl shadow-blue-500/20 sm:px-12 sm:py-16">
                <h2 class="text-3xl font-extrabold sm:text-4xl">Start vandaag</h2>
                <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">Zet je schoonmaak checklist digitaal neer in minuten. Geen creditcard nodig, 14 dagen gratis proberen.</p>
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
                @php $faqs = [
                    ['Wat staat er in een schoonmaak checklist?', 'Taken per ruimte, de gewenste frequentie en wie verantwoordelijk is. Denk aan vloeren, prullenbakken, sanitair, spiegels, oppervlakken en keukenonderhoud.'],
                    ['Hoe maak ik een schoonmaak checklist voor mijn bedrijf?', 'Inventariseer ruimtes, schrijf per ruimte concrete taken op en bepaal hoe vaak iets moet. Zet dat vervolgens vast in een tool — bijvoorbeeld TaskCheck — zodat iedereen dezelfde lijst gebruikt.'],
                    ['Verschil dagelijks en wekelijks?', 'Dagelijks: vegen, legen, sanitair en keukenbasis. Wekelijks of maandelijks: diepgaander werk zoals ramen, koelkast van binnen, roosters.'],
                    ['Hoe snel kan ik starten in TaskCheck?', 'Een eerste checklist zet je in korte tijd neer: taken, volgorde en eventueel bewijs per taak. Daarna kunnen medewerkers direct aan de slag.'],
                    ['Kan ik per locatie een eigen lijst maken?', 'Ja. Je maakt aparte checklists of varianten per locatie of object, zodat elke vestiging zijn eigen standaard heeft.'],
                ]; @endphp
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
                    ['Checklist app schoonmaak', route('seo.checklist-app-schoonmaak')],
                    ['Werkcontrole app', route('seo.werkcontrole-app')],
                    ['Horeca checklist app', route('seo.horeca-checklist-app')],
                    ['Takenlijst personeel', route('seo.takenlijst-personeel')],
                    ['Blog: beste checklist app schoonmaak', route('blog.beste-checklist-app-voor-schoonmaakbedrijven')],
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
