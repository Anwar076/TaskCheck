<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Takenlijst personeel app voor bedrijven | TaskCheck';
        $seoDescription = 'Maak een duidelijke takenlijst personeel met bewijs, deadlines en controle. Ideaal voor horeca, schoonmaak en operationele teams.';
        $seoUrl = route('seo.takenlijst-personeel');
        $seoImage = asset('images/seo-takenlijst-personeel-hero.png');
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
                <pattern id="seo-takenlijst-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-takenlijst-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">Takenlijst personeel · bewijs, deadlines, eigenaarschap</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Takenlijst personeel:
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">duidelijke uitvoering</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-takenlijst-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-takenlijst-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Met TaskCheck maak je van losse taken een betrouwbaar proces. Elk teamlid weet precies wat te doen, met eigenaarschap, bewijs en realtime opvolging.
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
                    @foreach(['Eigenaarschap per taak','Bewijs & deadlines','Live overzicht managers','Geen creditcard proefperiode'] as $b)
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
                        <img src="{{ asset('images/seo-takenlijst-personeel-hero.png') }}"
                             alt="Takenlijst personeel app – medewerkers met eigen takenlijst op mobiel verbonden aan één systeem"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="900"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Persoonlijke taken op mobiel — managers zien voortgang in één overzicht.</p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-200 bg-slate-50/80">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Duidelijk</p>
                    <p class="mt-0.5 text-sm text-slate-500">wie doet wat en wanneer</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.348 2.25h5.967M16.5 18.75a4.875 4.875 0 0 0 3.438-1.41l1.395-1.395a3.375 3.375 0 0 0-4.773-4.773l-1.518 1.518a.375.375 0 0 1-.266.11H10.5M16.5 18.75h3.75a2.25 2.25 0 0 0 2.25-2.25V9a8.25 8.25 0 0 0-8.25-8.25h-1.5M16.5 18.75v-4.875a2.625 2.625 0 0 0-2.625-2.625h-2.25M16.5 18.75h3"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Bewijs</p>
                    <p class="mt-0.5 text-sm text-slate-500">per uitgevoerde taak</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Realtime</p>
                    <p class="mt-0.5 text-sm text-slate-500">voortgang voor managers</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
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

        <section class="mt-16 sm:mt-20 lg:grid lg:grid-cols-2 lg:items-start lg:gap-16">
            <div>
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Waarom het werkt</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Waarom een goede takenlijst personeel het verschil maakt</h2>
                <p class="mt-4 leading-relaxed text-slate-600">Als medewerkers niet precies weten wat verwacht wordt, ontstaan vertragingen en kwaliteitsverschillen. Een sterke takenlijst personeel voorkomt dat. Taken krijgen een duidelijke omschrijving, prioriteit en deadline.</p>
                <p class="mt-3 leading-relaxed text-slate-600">TaskCheck helpt je taken niet alleen te plannen, maar ook te controleren. Je ziet live voortgang en kunt direct ingrijpen bij afwijkingen.</p>
                <ul class="mt-5 space-y-2">
                    @foreach(['Duidelijkheid over verantwoordelijkheid per taak', 'Minder mondeling overleg en misverstanden', 'Bewijs bij elke afgeronde taak', 'Manager ziet live status zonder te bellen'] as $item)
                    <li class="flex items-start gap-2 text-sm text-slate-700">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                            <svg class="h-3 w-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-10 space-y-3 lg:mt-0">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="mb-2 text-sm font-semibold text-slate-900">Wat hoort er in een professionele takenlijst?</p>
                    <ul class="space-y-1.5">
                        @foreach(['Taakomschrijving','Verantwoordelijke medewerker','Deadline of herhaalfrequentie','Bewijsvereiste (foto, tekst, video)','Status (open, bezig, klaar)','Checklistitems voor complexe taken'] as $item)
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded bg-blue-100">
                                <svg class="h-2.5 w-2.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
                    <p class="text-sm font-semibold text-blue-900">Praktijkvoorbeeld</p>
                    <p class="mt-1 text-sm leading-relaxed text-blue-800">Controleer voorraad · Reinig werkstation · Verifieer temperatuur · Maak foto na afronding. Door taken concreet te maken, verklein je interpretatieverschillen.</p>
                </div>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Toepassingen</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Voor horeca, schoonmaak en andere bedrijven</h2>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-3">
                @php
                $sectoren = [
                    ['t' => 'Horeca', 'd' => 'Opening, service en sluitroutines per shift. Per rol: keuken, bediening, bar.', 'c' => 'text-amber-600', 'bg' => 'bg-amber-50', 'paths' => ['M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z']],
                    ['t' => 'Schoonmaak', 'd' => 'Rondes, oplevering en kwaliteitscontrole met foto als bewijs.', 'c' => 'text-sky-600', 'bg' => 'bg-sky-50', 'paths' => ['M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0']],
                    ['t' => 'Andere sectoren', 'd' => 'Inspecties, onderhoud, compliance – zelfde principe, elk bedrijf.', 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50', 'paths' => ['M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418']],
                ];
                @endphp
                @foreach($sectoren as $s)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm transition hover:border-blue-200 hover:shadow-md">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl {{ $s['bg'] }}">
                        <svg class="h-6 w-6 {{ $s['c'] }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                            @foreach($s['paths'] as $d)
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </div>
                    <h3 class="mt-4 font-bold text-slate-900">{{ $s['t'] }}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $s['d'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <section class="mt-20 rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-50 to-blue-50/50 p-8 sm:p-12 sm:mt-24">
            <div class="lg:grid lg:grid-cols-2 lg:items-center lg:gap-12">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-wide text-blue-600">Digitaliseren</span>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Van losse opdracht naar werkcontrole app</h2>
                    <p class="mt-3 leading-relaxed text-slate-600">Veel bedrijven starten met mondelinge instructies. Dat werkt op korte termijn, maar schaalt slecht. Een digitale takenlijst personeel zorgt dat opdrachten niet verdwijnen en je achteraf bewijs hebt.</p>
                    <p class="mt-3 leading-relaxed text-slate-600">Managers krijgen dashboards met open taken, afgeronde taken en uitzonderingen. Teams krijgen een duidelijke lijst per dag of shift.</p>
                </div>
                <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:mt-0">
                    @php
                    $features = [
                        ['t' => 'Opdrachten blijven traceerbaar', 'paths' => ['M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z'], 'c' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                        ['t' => 'Bewijs van uitvoering', 'paths' => ['M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z', 'M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z'], 'c' => 'text-sky-600', 'bg' => 'bg-sky-50'],
                        ['t' => 'Dashboard per shift', 'paths' => ['M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z'], 'c' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                        ['t' => 'Snel nieuw personeel inwerken', 'paths' => ['M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z'], 'c' => 'text-slate-600', 'bg' => 'bg-slate-100'],
                        ['t' => 'Openstaand werk inzichtelijk', 'paths' => ['M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.636 3.502 1.044 5.354 1.33M15 11.25a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'], 'c' => 'text-amber-600', 'bg' => 'bg-amber-50'],
                        ['t' => 'Consistent over alle locaties', 'paths' => ['M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z', 'M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z'], 'c' => 'text-indigo-600', 'bg' => 'bg-indigo-50'],
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

        <section class="mt-20 text-center sm:mt-24">
            <div class="rounded-3xl bg-gradient-to-r from-[#2563eb] to-[#4f46e5] px-6 py-12 text-white shadow-xl shadow-blue-500/20 sm:px-12 sm:py-16">
                <h2 class="text-3xl font-extrabold sm:text-4xl">Start vandaag met TaskCheck</h2>
                <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">Meer overzicht, minder fouten en betere kwaliteit. Probeer TaskCheck 14 dagen gratis.</p>
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
                @php $faqs = [['Wat moet er in een goede takenlijst voor personeel?','Minimaal: taakomschrijving, verantwoordelijke, deadline, bewijsvereiste en status. Voor terugkerende taken voeg je frequentie toe.'],['Werkt dit ook voor wisselende diensten?','Ja. Je kunt per shift andere taken aanmaken of herhalende taken automatisch inplannen.'],['Kan ik taken koppelen aan specifieke medewerkers?','Ja. Elke taak kan worden toegewezen aan één of meerdere medewerkers. Zij zien hun persoonlijke lijst op mobiel.'],['Is het geschikt voor kleine teams?','Absoluut. Ook met 3 medewerkers helpt een digitale takenlijst om structuur en bewijs te houden.']];
                @endphp
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
                    ['Checklist app schoonmaak', route('seo.checklist-app-schoonmaak')],
                    ['Werkcontrole app', route('seo.werkcontrole-app')],
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
