<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Sluitings checklist horeca – sluit je zaak zonder fouten';
        $seoDescription = 'Sluit je horecazaak elke avond zonder fouten. Bekijk een praktische sluitings checklist horeca en start gratis met TaskCheck.';
        $seoUrl = route('seo.sluitings-checklist-horeca');
        $seoImage = asset('images/seo-opening-checklist-horeca-hero.png');
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
                <pattern id="seo-sluit-horeca-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-sluit-horeca-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">Sluitings checklist horeca · elke avond dezelfde afsluiting</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Sluitings checklist horeca:
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">sluit je zaak zonder fouten</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-sluit-horeca-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-sluit-horeca-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Een slechte sluiting kost je de volgende dag direct tijd. Met een vaste sluitings checklist weet elk teamlid precies wat er moet gebeuren — elke avond, bij elke shift.
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
                    @foreach(['Zelfde afsluiting, elk team','Minder vergeten taken','Bewijs per taak mogelijk','Geen creditcard proefperiode'] as $b)
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
                        <img src="{{ asset('images/seo-opening-checklist-horeca-hero.png') }}"
                             alt="Horecamedewerker sluit zaak af met digitale checklist op telefoon"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="900"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Digitale sluitings checklist op mobiel — geen kwijtgeraakte papiertjes na de dienst.</p>
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
                    <p class="text-2xl font-extrabold text-slate-900">Elke avond</p>
                    <p class="mt-0.5 text-sm text-slate-500">dezelfde afsluiting</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Minder</p>
                    <p class="mt-0.5 text-sm text-slate-500">vergeten taken &amp; stress</p>
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

<section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16">
    <div class="max-w-3xl">
        <p class="text-lg leading-relaxed text-slate-600">Aan het einde van een drukke avond is er maar één gedachte: naar huis. Maar als je de sluiting overhaast doet, betaal je daar de volgende dag de prijs voor. Koeling die open blijft, kassa die niet klopt, alarm dat niet aan staat. Kleine fouten met grote gevolgen.</p>
        <p class="mt-4 text-lg leading-relaxed text-slate-600">Een vaste sluitings checklist voorkomt dit. Niet één keer, maar elke avond. Voor elk teamlid, op elke locatie.</p>
    </div>
</section>

<main class="pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <section class="mt-16 sm:mt-20 lg:grid lg:grid-cols-2 lg:items-start lg:gap-16">
            <div>
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Het probleem</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Waarom een sluitings checklist belangrijk is</h2>
                <p class="mt-4 leading-relaxed text-slate-600">Een sluiting die niet goed gaat, merk je de volgende ochtend direct. Producten die slecht zijn geworden, een vieze keuken, een kassa die niet klopt. Je verliest tijd, geld en soms ook klanten.</p>
                <p class="mt-3 leading-relaxed text-slate-600">Veel horecazaken laten de sluiting over aan wie toevallig als laatste weg gaat. Zonder vaste lijst vergeet iemand de koeling te controleren of het alarm in te stellen. Niet omdat die persoon slordig is, maar omdat het druk was en er geen structuur was.</p>
                <p class="mt-3 leading-relaxed text-slate-600">Met een vaste digitale sluitings checklist heeft elk teamlid een duidelijke takenlijst. Afgevinkt is afgevinkt. En als iets niet gedaan is, weet de manager dat direct.</p>
            </div>
            <div class="mt-10 space-y-3 lg:mt-0">
                @php $problemen = [
                    ['Koeling blijft open of is te warm door vergeten controle'],
                    ['Kassa klopt niet door overhaast afsluiten'],
                    ['Alarm staat niet aan bij vertrek'],
                    ['Schoonmaak wordt half gedaan omdat niemand weet wat zijn taak is'],
                    ['Manager weet niet of de sluiting goed is verlopen'],
                ]; @endphp
                @foreach($problemen as $p)
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">{{ $p[0] }}</span>
                </div>
                @endforeach
                <div class="mt-2 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                    <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm font-semibold text-emerald-800">Een vaste sluitings checklist lost dit direct op</span>
                </div>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Inhoud</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Wat moet er in een sluitings checklist horeca</h2>
                <p class="mt-3 text-slate-500">Een goede sluitings checklist dekt vier gebieden: voedselveiligheid, schoonmaak, kassa en beveiliging.</p>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @php
                $gebieden = [
                    ['t' => 'Voedselveiligheid', 'd' => 'Koeltemperaturen controleren, restjes goed opbergen, houdbaarheidsdatums checken.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104v5.714a2.25 2.25 0 0 0 .659 1.591M5 14.5a2.25 2.25 0 0 0-2.25 2.219V17.5A2.25 2.25 0 0 0 5 19.5h14a2.25 2.25 0 0 0 2.25-2.25v-1.781a2.25 2.25 0 0 0-2.25-2.25M5 14.5l2.25-2.25M19.8 15.3 14.25 12.75 14.25 9.336m1.5-6.231a24.32 24.32 0 0 1 0 5.696m-1.5-6.231V9.75']],
                    ['t' => 'Schoonmaak', 'd' => 'Werkstations reinigen, vloer dweilen, vuilnis buiten zetten, toiletten controleren.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0']],
                    ['t' => 'Kassa &amp; systemen', 'd' => 'Dagomzet opmaken, kassa afsluiten, PIN-apparaat uitzetten, reserveringen doornemen.', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z']],
                    ['t' => 'Beveiliging', 'd' => 'Alle ramen en deuren dicht, terras opgeruimd, alarm inschakelen, verlichting uit.', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z']],
                ];
                @endphp
                @foreach($gebieden as $g)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $g['bg'] }}">
                        <svg class="h-5 w-5 {{ $g['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            @foreach($g['paths'] as $d)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </div>
                    <p class="mt-3 font-bold text-slate-900">{!! $g['t'] !!}</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $g['d'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 rounded-3xl bg-gradient-to-br from-slate-50 to-blue-50/60 p-8 sm:p-12 sm:mt-24">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Voorbeeld</span>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Praktisch voorbeeld: sluitings checklist restaurant</h2>
                    <p class="mt-3 leading-relaxed text-slate-600">Dit is een voorbeeld van een sluitings checklist voor een restaurant. Pas het aan op jouw situatie.</p>
                    <div class="mt-4 space-y-1.5">
                        @php $keuken = [
                            'Koeling 1 controleren en temperatuur noteren',
                            'Koeling 2 controleren en temperatuur noteren',
                            'Resterende producten goed afdekken en opbergen',
                            'Houdbaarheidsdatums gecontroleerd',
                            'Friteuses uitgeschakeld en afgedekt',
                            'Werkstations gereinigd en gedesinfecteerd',
                            'Vloer geveegd en gedweild',
                            'Vuilniszakken gesloten en buiten gezet',
                        ]; @endphp
                        <p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-500">Keuken</p>
                        @foreach($keuken as $item)
                        <div class="flex items-center gap-2.5 rounded-lg border border-blue-100 bg-white px-3.5 py-2.5 text-sm text-slate-700">
                            <span class="flex h-4 w-4 shrink-0 rounded border-2 border-slate-300" aria-hidden="true"></span>
                            {{ $item }}
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-8 space-y-1.5 lg:mt-0">
                    @php $zaal = [
                        'Tafels afruimen en reinigen',
                        'Stoelen op tafel zetten',
                        'Terras opgeruimd en eventueel vastgezet',
                        'Bar schoongemaakt',
                        'Toiletten gecontroleerd en bijgevuld',
                        'Muziek en verlichting uitgeschakeld',
                        'Alle ramen en deuren gecontroleerd en gesloten',
                    ]; @endphp
                    <p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-500">Zaal &amp; bediening</p>
                    @foreach($zaal as $item)
                    <div class="flex items-center gap-2.5 rounded-lg border border-blue-100 bg-white px-3.5 py-2.5 text-sm text-slate-700">
                        <span class="flex h-4 w-4 shrink-0 rounded border-2 border-slate-300" aria-hidden="true"></span>
                        {{ $item }}
                    </div>
                    @endforeach
                    @php $kassa = [
                        'Kassa opgemaakt en dagomzet genoteerd',
                        'Kassalade veilig opgeborgen',
                        'PIN-apparaat afgesloten',
                        'Reserveringen voor morgen doorgelezen',
                        'Alarm ingeschakeld bij vertrek',
                    ]; @endphp
                    <p class="mb-2 mt-4 text-xs font-bold uppercase tracking-wide text-slate-500">Kassa &amp; beveiliging</p>
                    @foreach($kassa as $item)
                    <div class="flex items-center gap-2.5 rounded-lg border border-blue-100 bg-white px-3.5 py-2.5 text-sm text-slate-700">
                        <span class="flex h-4 w-4 shrink-0 rounded border-2 border-slate-300" aria-hidden="true"></span>
                        {{ $item }}
                    </div>
                    @endforeach
                </div>
            </div>
            <p class="mt-6 text-center text-xs text-slate-500">Dit is een basisvoorbeeld. In TaskCheck maak je je eigen sluitings checklist per locatie en rol.</p>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Valkuilen</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Veelgemaakte fouten bij sluiten</h2>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2">
                @php $fouten = [
                    ['Koeling niet controleren bij sluiting', 'Dit is een van de duurste fouten. Als de koeling te warm staat of niet goed dicht is, zijn producten de volgende ochtend slecht. Voeg een verplichte temperatuurcheck toe met foto als bewijs.'],
                    ['Geen vaste volgorde in de checklist', 'Zonder volgorde slaat iemand stappen over. Zet de keuken altijd voor de zaal, en beveiliging altijd als laatste. Zo kun je niets missen.'],
                    ['Kassa overhaast afsluiten', 'Aan het einde van een lange dienst wil iedereen snel weg. Maar een kassa die niet klopt, kost de volgende dag meer tijd. Maak het een verplichte stap.'],
                    ['Geen bewijs vragen bij kritieke taken', 'Bij koeltemperaturen en schoonmaak is bewijs belangrijk voor HACCP en bij discussies achteraf. Laat medewerkers een foto of notitie toevoegen.'],
                    ['Sluiting overlaten aan de laatste persoon', 'Iedereen denkt dat de ander het doet. Met een vaste checklist is er altijd één persoon verantwoordelijk per taak.'],
                    ['Checklist nooit bijwerken', 'Een checklist uit 2022 sluit niet meer aan op je huidige situatie. Plan elk kwartaal een korte review met je team.'],
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
                    <p class="mt-3 leading-relaxed text-slate-600">TaskCheck is een digitale checklist app voor operationele teams. Je bouwt je <strong class="font-semibold text-slate-800">sluitings checklist per shift of locatie</strong>, wijst hem toe aan de juiste medewerkers en ziet of alles afgevinkt is.</p>
                    <p class="mt-3 leading-relaxed text-slate-600">Medewerkers werken op hun telefoon. Geen papier, geen verwarring. Bij kritieke taken zoals koeltemperaturen kunnen ze pas verder als ze een foto hebben toegevoegd. Zo weet jij als manager wat er is gedaan — ook als je er zelf niet bij was.</p>
                </div>
                <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:mt-0">
                    @php
                    $features = [
                        ['t' => 'Per shift of locatie', 'paths' => ['M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z', 'M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z'], 'c' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                        ['t' => 'Verplicht bewijs per taak', 'paths' => ['M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z', 'M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z'], 'c' => 'text-sky-600', 'bg' => 'bg-sky-50'],
                        ['t' => 'Live voortgang', 'paths' => ['M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z'], 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                        ['t' => 'Dagelijks herhalen', 'paths' => ['M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99'], 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50'],
                        ['t' => 'Open taken inzichtelijk', 'paths' => ['M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.636 3.502 1.044 5.354 1.33M15 11.25a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'], 'c' => 'text-amber-600', 'bg' => 'bg-amber-50'],
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
                <p class="mt-3 text-slate-500">Voor iedereen die dagelijks een ploeg moet afsluiten met de juiste kwaliteit en veiligheid.</p>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php $doelgroepen = [
                    ['Restaurant en bistro', 'Van kleine eetcafés tot grotere restaurants met meerdere sluitingsrondes per week.'],
                    ['Hotel en B&B', 'Avondshift voor receptie, keuken en gemeenschappelijke ruimtes netjes afsluiten.'],
                    ['Lunchroom en café', 'Dagelijkse sluiting met vaste schoonmaaktaken en voorraadbeheer.'],
                    ['Cateringbedrijf', 'Afsluiting op locatie met wisselend personeel en vaste kwaliteitsstandaard.'],
                    ['Keten en franchise', 'Meerdere vestigingen, één standaard voor elke sluiting. Bewijs per locatie.'],
                    ['Teamleiders en managers', 'Houd overzicht op meerdere teams zonder elke avond aanwezig te zijn.'],
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
                <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">Maak je eigen sluitings checklist in minuten. Geen creditcard nodig, 14 dagen gratis proberen.</p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Naar dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Start 14 dagen gratis</a>
                    @endauth
                    <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-xl border-2 border-white/40 px-8 py-4 text-base font-semibold text-white transition hover:bg-white/10">Bekijk prijzen</a>
                </div>
                <p class="mt-4 text-sm text-white/80">Geen verplichtingen · Geen creditcard · Vandaag live</p>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mx-auto mt-10 max-w-3xl space-y-3">
                @php $faqs = [
                    ['Wat is een sluitings checklist horeca precies?', 'Een sluitings checklist horeca is een vaste lijst met taken die elke avond afgewerkt moeten worden voordat de zaak dicht gaat. Denk aan koeltemperaturen controleren, schoonmaken, kassa opmaken en het alarm instellen.'],
                    ['Hoe lang duurt het om een sluitings checklist te maken in TaskCheck?', 'Je maakt een basischecklist in 5 tot 10 minuten. Je voegt taken toe, stelt een volgorde in en bepaalt welke taken bewijs vereisen. Daarna staat de checklist klaar voor je team.'],
                    ['Kan ik de checklist aanpassen per locatie of dag?', 'Ja. In TaskCheck maak je aparte checklists per locatie, shift of dag. De vrijdagavond heeft misschien andere taken dan een rustige woensdagavond.'],
                    ['Wat als een medewerker een taak vergeet bij sluiting?', 'Openstaande taken blijven zichtbaar in het overzicht. Afhankelijk van je instellingen kun je daar ook op geattendeerd worden, zodat je snel kunt bijsturen.'],
                    ['Werkt het ook voor een kleine horecazaak met 2 of 3 medewerkers?', 'Ja. Ook met een klein team helpt een vaste sluitings checklist om kwaliteit te bewaken en fouten te voorkomen. TaskCheck is betaalbaar en direct te gebruiken.'],
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
                    ['Opening checklist horeca', route('seo.opening-checklist-horeca')],
                    ['Horeca checklist app', route('seo.horeca-checklist-app')],
                    ['Horeca app personeel', route('seo.horeca-app-personeel')],
                    ['Takenlijst personeel', route('seo.takenlijst-personeel')],
                    ['Werkcontrole app', route('seo.werkcontrole-app')],
                    ['Blog: personeel controleren', route('blog.horeca-personeel-controleren-checklist-app')],
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
