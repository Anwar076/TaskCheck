<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Checklist app voor bedrijven en teams | TaskCheck';
        $seoDescription = 'Slimme checklist app voor teams. Beheer taken, verzamel bewijs en krijg realtime inzicht. Start 14 dagen gratis met TaskCheck.';
        $seoUrl = route('seo.checklist-app-voor-bedrijven');
        $seoImage = asset('images/taskcheck-platform-overview.webp');
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
                <pattern id="seo-bedrijven-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-bedrijven-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">Checklist app &amp; taakbeheer voor teams</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Checklist app voor
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">bedrijven en teams</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-bedrijven-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-bedrijven-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    TaskCheck helpt bedrijven om werk vast te leggen, uitvoering te controleren en bewijs te verzamelen op één plek. Minder papier, minder discussie, meer overzicht voor managers en mensen op de vloer.
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
                    @foreach(['Realtime overzicht', 'Bewijs per taak', 'Mobiel & web', 'Geen creditcard proefperiode'] as $b)
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
                        <img src="{{ asset('images/taskcheck-platform-overview.webp') }}"
                             alt="TaskCheck checklist app dashboard en mobiele app overzicht voor bedrijven"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="800"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Dashboard voor sturing, telefoon voor uitvoering — hetzelfde proces overal.</p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-200 bg-slate-50/80">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Eén plek</p>
                    <p class="mt-0.5 text-sm text-slate-500">taken, controle &amp; bewijs</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Bewijs</p>
                    <p class="mt-0.5 text-sm text-slate-500">foto · video · handtekening</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.09 9.09 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Schaalbaar</p>
                    <p class="mt-0.5 text-sm text-slate-500">klein team tot groei</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m0 0H21a.75.75 0 0 0-.75.75v.75m0 0H3a.75.75 0 0 1-.75-.75V15m0 0h18"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">€39</p>
                    <p class="mt-0.5 text-sm text-slate-500">per maand · proefperiode</p>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <section class="mt-16 lg:grid lg:grid-cols-2 lg:items-center lg:gap-16 sm:mt-20">
            <div>
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Platform</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Wat is TaskCheck?</h2>
                <p class="mt-4 leading-relaxed text-slate-600">TaskCheck is digitale checklist-software en taakbeheer in één: je plant werk, medewerkers voeren uit op mobiel en jij ziet voortgang en bewijs realtime. Geen losse papieren of e-mailketens meer rond “is het gebeurd?”.</p>
                <p class="mt-3 leading-relaxed text-slate-600">Geschikt voor onder meer schoonmaak, logistiek, horeca, bouw en retail — overal waar teams dezelfde kwaliteit moeten halen.</p>
                <ul class="mt-6 space-y-2.5">
                    @foreach(['Realtime inzicht in taken en voortgang','Bewijs per taak met foto, video en handtekening','Minder fouten en betere controle','Alles op mobiel en desktop','Eenvoudig opstarten, geen traject van maanden'] as $item)
                    <li class="flex items-start gap-2.5 text-sm text-slate-700">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                            <svg class="h-3 w-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-10 grid grid-cols-2 gap-3 lg:mt-0">
                @php
                $platformMini = [
                    ['t' => 'Takenlijsten', 'd' => 'Per team, locatie of shift.', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z']],
                    ['t' => 'Bewijs', 'd' => 'Foto, video of tekst.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z', 'M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z']],
                    ['t' => 'Realtime', 'd' => 'Live overzicht.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z']],
                    ['t' => 'Herhalen', 'd' => 'Dag-, week-, maand.', 'c' => 'text-slate-700', 'bg' => 'bg-slate-100', 'paths' => ['M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99']],
                    ['t' => 'Locaties', 'd' => 'Meerdere objecten.', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z', 'M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z']],
                    ['t' => 'Rapportage', 'd' => 'Data voor verbetering.', 'c' => 'text-blue-700', 'bg' => 'bg-blue-50', 'paths' => ['M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z']],
                ];
                @endphp
                @foreach($platformMini as $f)
                <div class="rounded-2xl border border-slate-100 bg-white p-4 text-center shadow-sm transition hover:border-blue-200">
                    <div class="mx-auto flex h-9 w-9 items-center justify-center rounded-lg {{ $f['bg'] }}">
                        <svg class="h-5 w-5 {{ $f['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            @foreach($f['paths'] as $d)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </div>
                    <p class="mt-2 text-xs font-bold text-slate-900">{{ $f['t'] }}</p>
                    <p class="mt-0.5 text-[11px] leading-relaxed text-slate-500">{{ $f['d'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Sectoren</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Voor wie is deze checklist app?</h2>
                <p class="mt-3 text-slate-500">Voor teams die met vaste routines en controle werken — groot of klein.</p>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php
                $sectoren = [
                    ['t' => 'Schoonmaak', 'd' => 'Rondes, oplevering en kwaliteitscontroles.', 'c' => 'text-blue-600', 'bg' => 'bg-blue-50', 'paths' => ['M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z', 'M18.259 8.715a4.5 4.5 0 0 1 1.305 6.91l-2.432 2.432a4.5 4.5 0 0 1-6.91 1.305 4.5 4.5 0 0 1-1.305-6.91l2.432-2.432a4.5 4.5 0 0 1 6.91-1.305Z']],
                    ['t' => 'Horeca', 'd' => 'Opening, mise-en-place en HACCP per shift.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z']],
                    ['t' => 'Logistiek', 'd' => 'Ontvangst, opslag en uitgifte workflows.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M20.25 7.5l-.415 8.212a1.875 1.875 0 0 1-1.398 1.288H5.563a1.875 1.875 0 0 1-1.473-1.288L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z']],
                    ['t' => 'Retail', 'd' => 'Schapcontrole, opening en sluitprocedures.', 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'paths' => ['M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 11.477a1.5 1.5 0 0 1-1.484 1.016H5.82a1.5 1.5 0 0 1-1.484-1.016L5.59 8.507']],
                    ['t' => 'Zorg', 'd' => 'Hygiëne, controles en vastlegging per ronde.', 'c' => 'text-rose-600', 'bg' => 'bg-rose-50', 'paths' => ['M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z']],
                    ['t' => 'Technisch', 'd' => 'Onderhoudsinspecties en compliance taken.', 'c' => 'text-amber-600', 'bg' => 'bg-amber-50', 'paths' => ['M3.75 13.5 10.5 6.75 12 10.5h8.25L9.75 21.75 12 13.5H3.75Z']],
                ];
                @endphp
                @foreach($sectoren as $s)
                <div class="flex gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $s['bg'] }}">
                        <svg class="h-6 w-6 {{ $s['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            @foreach($s['paths'] as $d)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-slate-900">{{ $s['t'] }}</h3>
                        <p class="mt-0.5 text-sm leading-relaxed text-slate-500">{{ $s['d'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 rounded-3xl bg-gradient-to-br from-slate-50 to-blue-50/60 p-8 sm:p-12 sm:mt-24">
            <div class="lg:grid lg:grid-cols-2 lg:items-center lg:gap-12">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Bewijs &amp; controle</span>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Bewijs per taak</h2>
                    <p class="mt-4 leading-relaxed text-slate-600">Vraag per taak het bewijs dat bij jullie proces hoort: een foto na schoonmaak, een korte video van een controle of een handtekening van de klant. Zo voorkom je discussies en ben je voorbereid op audits.</p>
                    <p class="mt-3 leading-relaxed text-slate-600">Voor klantgerichte en compliance-gevoelige processen is dat vaak het verschil tussen “wij zeggen” en “wij tonen”.</p>
                </div>
                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3 lg:mt-0">
                    @php
                    $bewijs = [
                        ['t' => 'Foto', 'd' => 'Visueel bewijs van uitvoering.', 'c' => 'text-sky-600', 'bg' => 'bg-white', 'paths' => ['M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z', 'M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z']],
                        ['t' => 'Video', 'd' => 'Korte clip als situatiebewijs.', 'c' => 'text-indigo-600', 'bg' => 'bg-white', 'paths' => ['m15.75 10.5 4.875-3.375a.75.75 0 0 1 1.125.65v7.45a.75.75 0 0 1-1.125.65L15.75 10.5', 'M4.5 9.75h9a2.25 2.25 0 0 1 2.25 2.25v3a2.25 2.25 0 0 1-2.25 2.25h-9A2.25 2.25 0 0 1 2.25 14.25v-3A2.25 2.25 0 0 1 4.5 9.75Z']],
                        ['t' => 'Handtekening', 'd' => 'Medewerker of klant rondt af.', 'c' => 'text-blue-600', 'bg' => 'bg-white', 'paths' => ['M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H9']],
                    ];
                    @endphp
                    @foreach($bewijs as $b)
                    <div class="rounded-2xl border border-blue-100 {{ $b['bg'] }} p-4 text-center shadow-sm">
                        <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50">
                            <svg class="h-5 w-5 {{ $b['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                @foreach($b['paths'] as $d)
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                                @endforeach
                            </svg>
                        </div>
                        <p class="mt-3 text-sm font-bold text-slate-900">{{ $b['t'] }}</p>
                        <p class="mt-0.5 text-xs text-slate-500">{{ $b['d'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="mt-20 text-center sm:mt-24">
            <div class="rounded-3xl bg-gradient-to-r from-[#2563eb] to-[#4f46e5] px-6 py-12 text-white shadow-xl shadow-blue-500/20 sm:px-12 sm:py-16">
                <h2 class="text-3xl font-extrabold sm:text-4xl">Start vandaag nog</h2>
                <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">Meer overzicht en minder fouten. Probeer TaskCheck 14 dagen gratis.</p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Naar dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Start 14 dagen gratis</a>
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
                    ['Wat is een checklist app voor bedrijven?','Een digitale tool waarmee je takenlijsten maakt en controleert. Medewerkers vinken taken af en voegen bewijs toe zoals foto\'s, video\'s of handtekeningen.'],
                    ['Is het geschikt voor kleine bedrijven?','Ja. Ook kleine teams profiteren van structuur en overzicht. TaskCheck schaalt mee als je groeit.'],
                    ['Kan ik foto\'s toevoegen aan taken?','Ja. Per taak kun je onder meer foto, video en handtekening als bewijs vragen.'],
                    ['Werkt het op mobiel?','Ja. Medewerkers werken op smartphone; managers kunnen ook op desktop het overzicht bekijken.'],
                    ['Wat kost een checklist app?','TaskCheck start vanaf €29 per maand. Je kunt 14 dagen gratis proberen zonder verplichtingen.'],
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
            <p class="text-center text-sm font-semibold text-slate-900">Gerelateerde pagina's</p>
            <div class="mx-auto mt-5 flex max-w-4xl flex-wrap justify-center gap-2">
                @foreach([
                    ['Homepage', route('welcome')],
                    ['Checklist app schoonmaak', route('seo.checklist-app-schoonmaak')],
                    ['Horeca checklist app', route('seo.horeca-checklist-app')],
                    ['Beste checklist app 2026', route('seo.beste-checklist-app-2026')],
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
</main>

@include('components.footer')
</body>
</html>
