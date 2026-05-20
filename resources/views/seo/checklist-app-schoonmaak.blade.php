<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Checklist app schoonmaak voor schoonmaakbedrijven';
        $seoDescription = 'Checklist app schoonmaak voor meer controle, minder fouten en duidelijk bewijs per taak. Start 14 dagen gratis met TaskCheck.';
        $seoUrl = route('seo.checklist-app-schoonmaak');
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

{{-- Hero — welcome-stijl (licht, merk-CTA, geen donkere band) --}}
<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="seo-schoon-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-schoon-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(16,185,129,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">Checklist app voor schoonmaakteams</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Checklist app schoonmaak</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-schoon-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-schoon-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                    met bewijs per taak en overzicht per locatie
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Van papier of Excel naar één duidelijke werkwijze: taken op mobiel, live voortgang voor leiding en foto- of videobewijs waar je het nodig hebt. Zo blijft kwaliteit tussen locaties gelijk en praat je met opdrachtgevers op basis van feiten.
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
                    @foreach(['Bewijs per taak', 'Realtime per locatie', 'Mobiel eerst', 'Geen creditcard proefperiode'] as $b)
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
                             alt="Checklist app schoonmaak – teamleider controleert dashboard terwijl medewerkers aan het werk zijn"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="800"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Zelfde werkwijze op alle objecten: minder herstelwerk, meer vertrouwen bij opdrachtgevers.</p>
            </div>
        </div>
    </div>
</section>

{{-- KPI-rij (geen niet-aantoonbare percentages) --}}
<section class="border-b border-slate-200 bg-slate-50/80">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Per locatie</p>
                    <p class="mt-0.5 text-sm text-slate-500">eigen checklists &amp; voortgang</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">14 dagen</p>
                    <p class="mt-0.5 text-sm text-slate-500">gratis uitproberen</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Realtime</p>
                    <p class="mt-0.5 text-sm text-slate-500">sturing zonder e-mailchaos</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Foto &amp; video</p>
                    <p class="mt-0.5 text-sm text-slate-500">bewijs bij de taak</p>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <section class="mt-16 lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center sm:mt-20">
            <div>
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Waarom het belangrijk is</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Waarom schoonmaakcontrole structuur nodig heeft</h2>
                <p class="mt-4 leading-relaxed text-slate-600">Kwaliteit is herhaling: dezelfde ronde, dezelfde norm. Zonder zichtbare taken en bewijs ontstaan gemiste hoeken, klachten en dubbel werk.</p>
                <p class="mt-3 leading-relaxed text-slate-600">Met een checklist app schoonmaak zie je wat af is, wat open staat en waar bewijs ontbreekt — per medewerker en per locatie.</p>
                <ul class="mt-6 space-y-2.5">
                    @foreach(['Minder klachten van opdrachtgevers', 'Minder herstelwerk en extra ritten', 'Bewijs bij discussies en audits', 'Consistente kwaliteit over alle locaties'] as $item)
                    <li class="flex items-start gap-2.5 text-sm text-slate-700">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                            <svg class="h-3 w-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-10 lg:mt-0">
                <img src="{{ asset('images/seo-checklist-schoonmaak-locaties.png') }}"
                     alt="Schoonmaakbedrijf beheert meerdere locaties in één checklist app"
                     class="w-full rounded-2xl border border-slate-100 shadow-xl"
                     loading="lazy"
                     width="800"
                     height="600">
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Werkwijze</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Hoe werkt een checklist app voor schoonmaak</h2>
                <p class="mt-3 text-slate-500">In vier stappen grip op uitvoering — zonder ingewikkelde implementatie.</p>
            </div>
            <div class="mt-10">
                <img src="{{ asset('images/seo-checklist-schoonmaak-workflow.png') }}"
                     alt="Workflow checklist app schoonmaak: checklist maken, toewijzen, foto bewijs, goedkeuren"
                     class="w-full rounded-2xl border border-slate-100 shadow-lg"
                     loading="lazy"
                     width="1200"
                     height="600">
            </div>
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @php
                $steps = [
                    ['nr' => '1', 'title' => 'Checklist aanmaken', 'desc' => 'Per locatie of type dienst, met taken, instructies en bewijsregels.'],
                    ['nr' => '2', 'title' => 'Taken toewijzen', 'desc' => 'Medewerkers zien op mobiel wat ze moeten doen en wanneer.'],
                    ['nr' => '3', 'title' => 'Bewijs uploaden', 'desc' => 'Foto, korte video of tekst als objectieve afronding per taak.'],
                    ['nr' => '4', 'title' => 'Realtime controleren', 'desc' => 'Teamleiders zien live voortgang en kunnen bijsturen.'],
                ];
                @endphp
                @foreach($steps as $step)
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-sm font-bold text-white">{{ $step['nr'] }}</span>
                    <h3 class="mt-3 font-bold text-slate-900">{{ $step['title'] }}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Toepassingen</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Praktische voorbeelden</h2>
                <p class="mt-3 text-slate-500">Dezelfde app, verschillende contexten — altijd met bewijs en overzicht.</p>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php
                $examples = [
                    ['title' => 'Kantoor', 'desc' => 'Werkplekken, pantry, sanitair, entree en vergaderruimtes.', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21']],
                    ['title' => 'Hotel', 'desc' => 'Kamers, gangen, lobby en gemeenschappelijke ruimtes.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25']],
                    ['title' => 'Restaurant', 'desc' => 'Keukenzones, toiletten en hygiënecontroles.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z']],
                    ['title' => 'Sportschool', 'desc' => 'Kleedkamers, vloeroppervlak en douches.', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5']],
                    ['title' => 'Zorg', 'desc' => 'Complexen, gangen en hygiënisch gevoelige zones.', 'c' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'paths' => ['M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z']],
                    ['title' => 'Oplevering', 'desc' => 'Eindcontrole na grote schoonmaak met bewijs per zone.', 'c' => 'text-slate-700', 'bg' => 'bg-slate-100', 'paths' => ['M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z']],
                ];
                @endphp
                @foreach($examples as $ex)
                <div class="group flex gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $ex['bg'] }}">
                        <svg class="h-6 w-6 {{ $ex['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            @foreach($ex['paths'] as $d)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-slate-900">{{ $ex['title'] }}</h3>
                        <p class="mt-0.5 text-sm leading-relaxed text-slate-500">{{ $ex['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 rounded-3xl bg-gradient-to-br from-slate-50 to-blue-50/60 p-8 sm:p-12 sm:mt-24">
            <div class="lg:grid lg:grid-cols-2 lg:items-center lg:gap-12">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Voordelen</span>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Voordelen voor jouw bedrijf</h2>
                    <p class="mt-3 text-slate-600">Rust op de vloer, grip in het dashboard — en onderhandelingen met klanten op basis van bewijs.</p>
                    <div class="mt-8 space-y-3">
                        @php
                        $benefits = [
                            ['t' => 'Minder klachten', 'd' => 'Vaste werkwijze per locatie = minder fouten en herstelwerk.', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z']],
                            ['t' => 'Realtime inzicht', 'd' => 'Zie wat open staat zonder rond te bellen of te mailen.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z']],
                            ['t' => 'Bewijs per taak', 'd' => 'Foto of tekst voorkomt discussies met opdrachtgevers.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z', 'M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z']],
                            ['t' => 'Sneller inwerken', 'd' => 'Nieuwe mensen volgen dezelfde lijsten als het team.', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658.813A48.32 48.32 0 0 0 12 4.905c2.922 0 5.68.61 8.172 1.7a50.676 50.676 0 0 0-2.658-.813m0 0A50.733 50.733 0 0 0 12 2.25c-1.875 0-3.716.2-5.458.6m10.776 0a50.729 50.729 0 0 1 5.458.6', 'M15.75 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z']],
                            ['t' => 'Per locatie', 'd' => 'Beheer vele objecten vanuit één overzicht.', 'c' => 'text-blue-700', 'bg' => 'bg-blue-50', 'paths' => ['M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z', 'M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z']],
                        ];
                        @endphp
                        @foreach($benefits as $b)
                        <div class="flex gap-3 rounded-xl border border-white/80 bg-white p-4 shadow-sm">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $b['bg'] }}">
                                <svg class="h-5 w-5 {{ $b['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                    @foreach($b['paths'] as $d)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                                    @endforeach
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900">{{ $b['t'] }}</p>
                                <p class="mt-0.5 text-xs leading-relaxed text-slate-500">{{ $b['d'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-10 space-y-4 lg:mt-0">
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-md">
                        <p class="italic leading-relaxed text-slate-700">"Sinds we TaskCheck gebruiken, hebben we veel meer overzicht en veel minder herstelwerk. We zien meteen waar iets mis is gegaan."</p>
                        <div class="mt-4 flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">M</div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Mark, teamleider schoonmaak</p>
                                <p class="text-xs text-slate-500">Facilitair bedrijf, 12 locaties</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-md">
                        <p class="italic leading-relaxed text-slate-700">"Onze opdrachtgevers zijn blij met de foto's als bewijs. Het bespaart ons veel discussies."</p>
                        <div class="mt-4 flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">S</div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Sandra, eigenaar schoonmaakbedrijf</p>
                                <p class="text-xs text-slate-500">Schoonmaakbedrijf, 3 medewerkers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">TaskCheck</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Waarom TaskCheck</h2>
                <p class="mt-3 text-slate-500">Gebouwd voor teams die elke dag op locatie staan — niet voor bibliotheken vol handleidingen.</p>
            </div>
            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @php
                $features = [
                    ['t' => 'Werkt op mobiel', 'd' => 'Geen laptop op de vloer: taken en bewijs op de telefoon.', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3']],
                    ['t' => 'Per locatie ingesteld', 'd' => 'Eigen checklists en planning per object, centraal beheerd.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z']],
                    ['t' => 'Meldingen', 'd' => 'Aandacht als een taak blijft hangen of bewijs ontbreekt.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0']],
                    ['t' => 'Rapportages', 'd' => 'Data op één plek — minder Excel en screenshots.', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z']],
                    ['t' => 'Herhalende taken', 'd' => 'Dag-, week- of maandritmes automatisch ingepland.', 'c' => 'text-slate-700', 'bg' => 'bg-slate-100', 'paths' => ['M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99']],
                    ['t' => 'Veilig &amp; beschikbaar', 'd' => 'Je data blijft bereikbaar; het team werkt in één omgeving.', 'c' => 'text-blue-700', 'bg' => 'bg-blue-50', 'paths' => ['M16.5 10.5V6a4.5 4.5 0 1 0-9 0v4.5m9 0H7.5m9 0h-.75m.75 0v6.75a1.125 1.125 0 0 1-1.125 1.125h-9.75A1.125 1.125 0 0 1 5.25 17.25V10.5m10.5 0H21']],
                ];
                @endphp
                @foreach($features as $f)
                <div class="rounded-2xl border border-slate-100 bg-white p-5 text-center shadow-sm transition hover:border-blue-200 hover:shadow-md">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl {{ $f['bg'] }}">
                        <svg class="h-6 w-6 {{ $f['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            @foreach($f['paths'] as $d)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </div>
                    <h3 class="mt-4 font-bold text-slate-900">{{ $f['t'] }}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $f['d'] }}</p>
                </div>
                @endforeach
            </div>
            <p class="mt-8 text-center text-sm text-slate-500">
                Meer over algemene werkcontrole? Bekijk de
                <a class="font-semibold text-blue-700 hover:underline" href="{{ route('seo.werkcontrole-app') }}">werkcontrole app</a>.
            </p>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="overflow-hidden rounded-3xl bg-slate-900 px-6 py-10 text-white sm:px-10 sm:py-14">
                <div class="grid gap-10 lg:grid-cols-2 lg:items-center lg:gap-14">
                    <div>
                        <span class="text-sm font-semibold uppercase tracking-wide text-blue-400">Doelgroep</span>
                        <h2 class="mt-2 text-3xl font-bold sm:text-4xl">Voor wie is dit geschikt?</h2>
                        <p class="mt-4 leading-relaxed text-slate-300">Van klein team tot tientallen locaties: dezelfde structuur, schaalbaar mee groeiend.</p>
                        <p class="mt-3 leading-relaxed text-slate-300">Werk je met shifts en vaste rondes? Lees ook <a class="font-semibold text-blue-400 hover:text-blue-300" href="{{ route('seo.takenlijst-personeel') }}">takenlijst personeel</a>.</p>
                    </div>
                    <ul class="grid gap-3 sm:grid-cols-2">
                        @foreach(['Klein schoonmaakbedrijf', 'Groot facilitair bedrijf', 'Teamleiders', 'Freelance schoonmakers', 'Zorginstellingen', 'Facilitair managers'] as $t)
                        <li class="flex items-center gap-3 rounded-xl bg-white/10 px-4 py-3">
                            <svg class="h-5 w-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm font-medium">{{ $t }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <section class="mt-20 text-center sm:mt-24">
            <div class="rounded-3xl bg-gradient-to-r from-[#2563eb] to-[#4f46e5] px-6 py-12 text-white shadow-xl shadow-blue-500/20 sm:px-12 sm:py-16">
                <h2 class="text-3xl font-extrabold sm:text-4xl">Start vandaag gratis</h2>
                <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">Minder ruis, meer grip op schoonmaak op locatie. Probeer TaskCheck 14 dagen — geen creditcard nodig.</p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Naar dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Start 14 dagen gratis</a>
                    @endauth
                    <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-xl border-2 border-white/40 px-8 py-4 text-base font-semibold text-white transition hover:bg-white/10">Bekijk prijzen</a>
                </div>
                <p class="mt-4 text-sm text-white/80">Geen verplichtingen · Geen creditcard · Direct starten</p>
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
                    ['q' => 'Wat is een checklist app schoonmaak precies?', 'a' => 'Een app waarmee je schoonmaaktaken digitaal plant, uitvoert en controleert. Je ziet wat gedaan is, wat open staat en of bewijs is toegevoegd.'],
                    ['q' => 'Kan ik bewijs toevoegen per taak?', 'a' => 'Ja. Je kunt per taak bijvoorbeeld foto, video of tekstbewijs vragen — handig richting klant of bij een audit.'],
                    ['q' => 'Is dit ook geschikt voor kleine schoonmaakbedrijven?', 'a' => 'Ja. Ook met een klein team helpt structuur om fouten te voorkomen. Je begint compact en schaalt locaties en medewerkers mee.'],
                    ['q' => 'Kan ik meerdere locaties beheren?', 'a' => 'Ja. Checklists en voortgang zijn per locatie in te richten, met één centraal overzicht.'],
                    ['q' => 'Hoeveel kost TaskCheck?', 'a' => 'Je probeert eerst 14 dagen gratis. Bekijk actuele pakketten en prijzen op de prijzenpagina.'],
                ];
                @endphp
                @foreach($faqs as $faq)
                <details class="group cursor-pointer rounded-2xl border border-slate-200 bg-white px-5 py-4 transition hover:border-blue-200 sm:px-6">
                    <summary class="flex list-none items-center justify-between gap-3 font-semibold text-slate-900">
                        <span class="text-left">{{ $faq['q'] }}</span>
                        <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-45" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $faq['a'] }}</p>
                </details>
                @endforeach
            </div>
        </section>

        <section class="mb-4 mt-16 border-t border-slate-200 pt-12 sm:mt-20">
            <p class="text-center text-sm font-semibold text-slate-900">Gerelateerde pagina's</p>
            <div class="mx-auto mt-5 flex max-w-4xl flex-wrap justify-center gap-2">
                @foreach([
                    ['Werkcontrole app', route('seo.werkcontrole-app')],
                    ['Takenlijst personeel', route('seo.takenlijst-personeel')],
                    ['Beste checklist app 2026', route('seo.beste-checklist-app-2026')],
                    ['Checklist app voor bedrijven', route('seo.checklist-app-voor-bedrijven')],
                    ['Blog: checklist schoonmaak', route('blog.beste-checklist-app-voor-schoonmaakbedrijven')],
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
