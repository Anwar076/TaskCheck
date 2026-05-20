<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Opening checklist horeca – voorkom fouten bij de start';
        $seoDescription = 'Een goede opening checklist horeca voorkomt fouten, stress en gemiste taken. Bekijk een praktisch voorbeeld en start gratis met TaskCheck.';
        $seoUrl = route('seo.opening-checklist-horeca');
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
                <pattern id="seo-opening-horeca-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-opening-horeca-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">Opening checklist horeca · elke shift dezelfde start</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Opening checklist horeca:
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">voorkom fouten bij de start</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-opening-horeca-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-opening-horeca-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Een foute opening kost tijd en klanten. Met een vaste opening checklist weet elk teamlid wat er moet gebeuren — elke dag, bij elke shift, met bewijs waar jij dat nodig hebt.
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
                    @foreach(['Zelfde start, elk team','Minder vergeten taken','Bewijs per taak mogelijk','Geen creditcard proefperiode'] as $b)
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
                             alt="Restaurant manager met opening checklist op tablet, personeel bereidt zich voor op de dag"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="900"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Digitale checklist op mobiel — geen kwijtgeraakte papiertjes tussen shifts.</p>
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
                    <p class="mt-0.5 text-sm text-slate-500">dezelfde start, elk team</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Minder</p>
                    <p class="mt-0.5 text-sm text-slate-500">gemiste taken &amp; stress</p>
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

        <section class="mt-16 sm:mt-20 lg:grid lg:grid-cols-2 lg:items-start lg:gap-16">
            <div>
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Het probleem</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Waarom een opening checklist belangrijk is</h2>
                <p class="mt-4 leading-relaxed text-slate-600">In horeca begint een goede dag met een goede opening. Als de koeling niet gecontroleerd is, de kassa niet klaarstaat of het terras niet op tijd in orde is, merk je dat direct. Klanten wachten, stress loopt op en fouten kosten geld.</p>
                <p class="mt-3 leading-relaxed text-slate-600">Veel horecazaken werken nog met mondelinge overdracht of een geprint velletje. Dat werkt bij een vast team, maar zodra er wisselende medewerkers of meerdere shifts zijn, gaan er dingen mis — niet omdat mensen niet willen werken, maar omdat het onduidelijk is wat er van hen verwacht wordt.</p>
                <p class="mt-3 leading-relaxed text-slate-600">Een vaste digitale opening checklist geeft structuur. Iedereen weet wat er gedaan moet worden, in welke volgorde en met welk bewijs. De manager hoeft niet elke ochtend alles fysiek na te lopen.</p>
            </div>
            <div class="mt-10 space-y-3 lg:mt-0">
                @php $problemen = [['Medewerkers vergeten taken bij een drukke start'],['Kwaliteitsverschil tussen ochtendploeg en avondploeg'],['Manager weet niet wat er gedaan is zonder zelf te kijken'],['Discussie achteraf over wie iets had moeten doen'],['Papieren lijstjes raken kwijt of worden niet ingevuld']]; @endphp
                @foreach($problemen as $p)
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">{{ $p[0] }}</span>
                </div>
                @endforeach
                <div class="mt-2 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                    <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm font-semibold text-emerald-800">Een vaste checklist lost dit stap voor stap op</span>
                </div>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Inhoud</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Wat hoort in een horeca opening checklist</h2>
                <p class="mt-3 text-slate-500">Een goede opening dekt vier gebieden: veiligheid, hygiëne, voorbereiding en service.</p>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @php
                $gebieden = [
                    ['t' => 'Veiligheid', 'd' => 'Nooduitgangen vrij, brandblussers aanwezig, sloten controleren, alarm uit.', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z']],
                    ['t' => 'Hygiëne', 'd' => 'Koeltemperaturen meten, schoonmaak keuken, sanitair, handhygiëne.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104v5.714a2.25 2.25 0 0 0 .659 1.591M5 14.5a2.25 2.25 0 0 0-2.25 2.219V17.5A2.25 2.25 0 0 0 5 19.5h14a2.25 2.25 0 0 0 2.25-2.25v-1.781a2.25 2.25 0 0 0-2.25-2.25M5 14.5l2.25-2.25M19.8 15.3 14.25 12.75 14.25 9.336m1.5-6.231a24.32 24.32 0 0 1 0 5.696m-1.5-6.231V9.75']],
                    ['t' => 'Voorbereiding', 'd' => 'Mise en place, voorraad, kassa opstarten, tafels dekken.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z']],
                    ['t' => 'Service', 'd' => 'Menukaarten, specials, briefing team, muziek en licht.', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z']],
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
                    <p class="mt-3 font-bold text-slate-900">{{ $g['t'] }}</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $g['d'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 rounded-3xl bg-gradient-to-br from-slate-50 to-blue-50/60 p-8 sm:p-12 sm:mt-24">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Voorbeeld</span>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Praktisch voorbeeld: opening checklist restaurant</h2>
                    <p class="mt-3 leading-relaxed text-slate-600">Dit is een voorbeeld voor een restaurant. Pas het aan op jouw menu, locatie en wetgeving.</p>
                    <div class="mt-4 space-y-1.5">
                        @php $keuken = ['Koeling 1 controleren (temp. max. 4°C)', 'Koeling 2 controleren (temp. max. 4°C)', 'Datumcontrole gekoelde producten', 'Werkstations reinigen en desinfecteren', 'Friteuses voorverwarmen', 'Mise en place voorbereiden voor lunch', 'Snijplanken en messen gereed']; @endphp
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
                    @php $zaal = ['Tafels dekken (bestek, glazen, menukaart)', 'Terras inrichten en controleren', 'Toiletten schoonmaken en voorraden bijvullen', 'Muziek en verlichting instellen', 'Reserveringen doornemen', 'Dagschotel en specials noteren', 'Personeel briefen over bijzonderheden']; @endphp
                    <p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-500">Zaal &amp; bediening</p>
                    @foreach($zaal as $item)
                    <div class="flex items-center gap-2.5 rounded-lg border border-blue-100 bg-white px-3.5 py-2.5 text-sm text-slate-700">
                        <span class="flex h-4 w-4 shrink-0 rounded border-2 border-slate-300" aria-hidden="true"></span>
                        {{ $item }}
                    </div>
                    @endforeach
                    @php $kassa = ['Kassa opstarten en wisselgeld tellen', 'PIN-apparaat testen', 'Reserveringssysteem controleren']; @endphp
                    <p class="mb-2 mt-4 text-xs font-bold uppercase tracking-wide text-slate-500">Kassa &amp; systemen</p>
                    @foreach($kassa as $item)
                    <div class="flex items-center gap-2.5 rounded-lg border border-blue-100 bg-white px-3.5 py-2.5 text-sm text-slate-700">
                        <span class="flex h-4 w-4 shrink-0 rounded border-2 border-slate-300" aria-hidden="true"></span>
                        {{ $item }}
                    </div>
                    @endforeach
                </div>
            </div>
            <p class="mt-6 text-center text-xs text-slate-500">Dit is een basisvoorbeeld. In TaskCheck maak je je eigen checklist per locatie en rol.</p>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Valkuilen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900 sm:text-4xl">Veelgemaakte fouten bij de opening</h2>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2">
                @php $fouten = [['Te algemene taken omschrijven','Schrijf niet "keuken controleren" maar "koeling 1 temperatuur meten, foto van display maken". Hoe specifieker, hoe minder ruimte voor interpretatie.'],['Geen vaste volgorde aanhouden','Zonder logische volgorde slaat iemand stappen over of doet dingen dubbel. Een checklist helpt een vaste route af te dwingen.'],['Geen bewijs vragen bij kritieke taken','Bij temperatuurcontroles, HACCP-punten en schoonmaak is bewijs vaak nodig. Leg vast wat je verwacht in de taak zelf.'],['Checklist nooit bijwerken','Een checklist die maanden niet wordt geüpdatet, sluit niet meer aan op de praktijk. Plan periodiek een korte review met het team.'],['Papier i.p.v. digitaal','Papier raakt kwijt, is lastig doorzoekbaar en levert weinig sturingsinformatie. Digitaal is overzichtelijker en schaalbaar.'],['Medewerkers niet betrekken','Wat bovenaf wordt opgelegd zonder input van de vloer, wordt minder consequent nageleefd. Betrek het team bij de inhoud.']]; @endphp
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
                    <p class="mt-3 leading-relaxed text-slate-600">TaskCheck is digitale checklist-software voor operationele teams. Je bouwt je eigen opening checklist in korte tijd, wijst hem toe aan de juiste medewerkers en ziet of alles afgevinkt is.</p>
                    <p class="mt-3 leading-relaxed text-slate-600">Medewerkers werken op de smartphone. Als een taak verplicht bewijs nodig heeft, kunnen ze pas verder als er bijvoorbeeld een foto of notitie staat.</p>
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
                <h2 class="mt-2 text-3xl font-bold text-slate-900 sm:text-4xl">Voor wie is dit geschikt</h2>
                <p class="mt-3 text-slate-500">Voor iedereen die dagelijks een ploeg moet starten met de juiste kwaliteit.</p>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php $doelgroepen = [['Restaurant en bistro','Van kleine eetcafés tot grotere restaurants met meerdere ploegenstructuren.'],['Hotel en B&B','Ochtendshift voor ontbijt, ruimtes gereed en receptie open.'],['Lunchroom en café','Dagelijkse opening met verse bereidingen en schoonmaak.'],['Cateringbedrijf','Voorbereiding op locatie: dezelfde vaste kwaliteit, andere setting.'],['Keten en franchise','Meerdere vestigingen, één standaard voor elke opening.'],['Teamleiders en managers','Overzicht op meerdere teams zonder overal tegelijk te zijn.']]; @endphp
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
                <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">Maak je eigen opening checklist in minuten. Geen creditcard nodig, 14 dagen gratis proberen.</p>
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
                    ['Wat is een opening checklist horeca precies?','Een opening checklist horeca is een vaste lijst met taken die vóór opening worden afgerond. Denk aan koelcontroles, schoonmaak, mise en place, kassa en personeelsbriefing — afgestemd op jouw zaak.'],
                    ['Hoe lang duurt het om een opening checklist te maken in TaskCheck?','Een eerste versie kun je in enkele minuten neerzetten: taken toevoegen, volgorde bepalen en bewijs per taak instellen waar nodig. Daarna verfijn je samen met het team.'],
                    ['Kan ik de checklist aanpassen per locatie of dag?','Ja. Je maakt aparte checklists per locatie, shift of type dag — bijvoorbeeld weekend versus doordeweeks.'],
                    ['Wat als een medewerker een taak vergeet?','Openstaande taken blijven zichtbaar in het overzicht. Afhankelijk van je instellingen kun je daar ook op geattendeerd worden, zodat je snel kunt bijsturen.'],
                    ['Werkt het ook voor kleine horecazaken met 2 of 3 medewerkers?','Ja. Ook met een klein team helpt een vaste checklist om kwaliteit te bewaken en fouten te voorkomen.'],
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
