<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle       = 'Horeca checklist app voor restaurants en keukens | TaskCheck';
        $seoDescription = 'Horeca checklist app voor restaurants: taken beheren, personeel controleren en bewijs verzamelen met foto en video. Start 14 dagen gratis.';
        $seoUrl         = route('seo.horeca-checklist-app');
        $seoImage       = asset('images/taskcheck-horeca-seo-hero.webp');
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url"         content="{{ $seoUrl }}">
    <meta property="og:image"       content="{{ $seoImage }}">
    <style>
        .cta-btn { background: linear-gradient(135deg, #2563eb, #4f46e5); }
        .cta-btn:hover { background: linear-gradient(135deg, #1d4ed8, #4338ca); }
        .bar-track { background: #e2e8f0; border-radius: 9999px; overflow: hidden; }
        .bar-fill { height: 100%; border-radius: 9999px; }
        .star { color: #fbbf24; }
        .operatie-card { transition: box-shadow .2s ease, border-color .2s ease; }
        .operatie-card:hover { box-shadow: 0 10px 40px -20px rgba(15,23,42,.1); border-color: rgb(203 213 225); }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased overflow-x-hidden">
@include('components.header')

<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="seo-horeca-cl-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-horeca-cl-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.06)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">Horeca checklist app · opening, service, sluiting</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Checklists die
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">meedraaien met je zaak</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-horeca-cl-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-horeca-cl-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Opening, service en sluiting vastgelegd met bewijs per taak en overzicht voor de leiding — minder ruis in de app-groep, meer zekerheid op de vloer.
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
                    @foreach(['Geen creditcard nodig','14 dagen gratis proberen','Geschikt voor kleine en grote teams'] as $b)
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
                             alt="Horeca checklist app in gebruik — restaurant team"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="800"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Zelfde werkwijze per dienst — iedereen ziet wat klaar is en wat nog openstaat.</p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-200 bg-slate-50/80">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Per shift</p>
                    <p class="mt-0.5 text-sm text-slate-500">taken per dienst ingericht</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">HACCP</p>
                    <p class="mt-0.5 text-sm text-slate-500">controles met bewijs</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Realtime</p>
                    <p class="mt-0.5 text-sm text-slate-500">voortgang per team</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 18.75a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75ZM6 12.75a.75.75 0 0 0-.75.75v3c0 .414.336.75.75.75h12a.75.75 0 0 0 .75-.75v-3a.75.75 0 0 0-.75-.75H6Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5A2.25 2.25 0 0 1 8.25 2.25h7.5A2.25 2.25 0 0 1 18 4.5v15a2.25 2.25 0 0 1-2.25 2.25h-7.5A2.25 2.25 0 0 1 6 19.5v-15Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">Mobiel</p>
                    <p class="mt-0.5 text-sm text-slate-500">op de vloer, geen laptop</p>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Dagelijkse operatie</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">
                    Eén helder stappenplan voor elke dienst
                </h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Zet opening, service, sluiting en HACCP in vaste checklists. Iedereen ziet wat klaar is en wat nog openstaat — met optioneel foto- of videobewijs waar jij dat verplicht stelt.
                </p>

                <div class="mt-10 grid gap-4 sm:grid-cols-2">
                    @php $steps = [
                        ['Opening', 'Koeling, mise-en-place en schoonmaak: afgevinkt vóór de eerste gasten.', '#2563eb', 'Ochtend'],
                        ['Service', 'Controles op de vloer zonder dat er taken tussen twee rondes vergeten worden.', '#4f46e5', 'Dienst'],
                        ['Sluiting', 'Afsluitrondes en veiligheid: niemand naar huis voordat de lijst klopt.', '#059669', 'Slot'],
                        ['HACCP & audit', 'Logs en rondes op één plek, terug te vinden wanneer je wordt gecontroleerd.', '#d97706', 'Continu'],
                    ]; @endphp
                    @foreach($steps as $idx => [$title,$desc,$col,$when])
                    <div class="operatie-card rounded-xl border border-slate-200 bg-white p-5">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <span class="inline-flex h-7 min-w-[1.75rem] items-center justify-center rounded-md px-2 text-xs font-bold text-white"
                                  style="background:{{ $col }}">{{ $idx + 1 }}</span>
                            <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">{{ $when }}</span>
                        </div>
                        <p class="mb-1 text-sm font-semibold text-slate-900">{{ $title }}</p>
                        <p class="text-xs leading-relaxed text-slate-500">{{ $desc }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div>
                <figure class="overflow-hidden rounded-xl border border-slate-200/90 bg-slate-50 shadow-[0_20px_50px_-24px_rgba(15,23,42,0.18)]">
                    <img src="{{ asset('images/seo-opening-checklist-horeca-hero.png') }}"
                         alt="Opening checklist horeca op telefoon"
                         loading="lazy" decoding="async" width="800" height="600"
                         class="w-full object-cover">
                </figure>
                <figcaption class="mx-auto mt-4 max-w-md text-center text-sm leading-relaxed text-slate-500 lg:text-left">
                    Zo ziet een checklist eruit op de telefoon: kort, per shift, direct op de vloer.
                </figcaption>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Resultaten</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">
                    Wat verandert er na TaskCheck?
                </h2>
                <p class="mt-4 max-w-md text-lg leading-relaxed text-slate-500">
                    Teams die overstappen van papier of WhatsApp zien snel het verschil: minder fouten, minder discussie, meer rust in de operatie.
                </p>
                <ul class="mt-8 space-y-3">
                    @foreach([
                        'Openingschecklist consequent volledig ingevuld',
                        'HACCP vastgelegd zonder extra administratieve stap',
                        'Managers hoeven minder na te bellen',
                        'Nieuw personeel weet meteen wat er verwacht wordt',
                    ] as $item)
                    <li class="flex items-start gap-3 text-sm text-slate-600">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                <p class="mb-1 font-bold text-slate-900">Effect na implementatie TaskCheck</p>
                <p class="mb-7 text-xs text-slate-400">Illustratief — gebaseerd op wat horecateams vaak rapporteren; geen garantie voor jouw situatie.</p>

                @php $bars = [
                    ['Vergeten taken',      82, 6,  '#ef4444'],
                    ['HACCP-fouten',        68, 5,  '#f97316'],
                    ['Discussies personeel',74, 10, '#f59e0b'],
                    ['Klachten opdrachtgever',60, 8,'#6366f1'],
                ]; @endphp

                <div class="space-y-6">
                @foreach($bars as [$label, $before, $after, $col])
                <div>
                    <div class="mb-2.5 flex items-baseline justify-between">
                        <span class="text-sm font-semibold text-slate-800">{{ $label }}</span>
                        <span class="text-sm font-extrabold text-emerald-600">-{{ $before - $after }}% <span class="font-medium text-slate-400">(voorbeeld)</span></span>
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-3">
                            <span class="w-16 shrink-0 text-right text-xs text-slate-400">Zonder</span>
                            <div class="bar-track h-3 flex-1">
                                <div class="bar-fill" style="background:{{ $col }};opacity:.75;width:{{ $before }}%"></div>
                            </div>
                            <span class="w-8 tabular-nums text-xs text-slate-500">{{ $before }}%</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-16 shrink-0 text-right text-xs font-bold text-emerald-600">TaskCheck</span>
                            <div class="bar-track h-3 flex-1">
                                <div class="bar-fill bg-emerald-500" style="width:{{ $after }}%"></div>
                            </div>
                            <span class="w-8 tabular-nums text-xs font-bold text-emerald-600">{{ $after }}%</span>
                        </div>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto mb-14 max-w-2xl text-center">
            <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Waarom TaskCheck</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Alles wat je op de vloer nodig hebt</h2>
            <p class="mt-4 text-lg leading-relaxed text-slate-500">
                Eén plek om af te vinken, bewijs vast te leggen en overzicht te houden — zonder losse apps of eindeloze groepschats.
            </p>
        </div>

        @php $whyCards = [
            ['Mobiel op de vloer', 'Geen laptop tussen de diensten: afvinken op de telefoon, waar je ook staat.', '#2563eb', 'Mobiel'],
            ['Bewijs bij de taak', 'Foto of video als jij dat verplicht — handig bij discussies en audits.', '#4f46e5', 'Bewijs'],
            ['HACCP overzichtelijk', 'Metingen en rondes op één plek; terugzoeken gaat sneller dan in een map.', '#059669', 'HACCP'],
            ['Realtime overzicht', 'Zie wat openstaat en wat klaar is — minder bellen, minder micromanagement.', '#d97706', 'Live'],
            ['Snel ingewerkt', 'Nieuwe mensen dezelfde lijsten als de rest; minder mondelinge overdracht.', '#0891b2', 'Team'],
            ['Meerdere locaties', 'Meerdere zaken bijhouden zonder voor elke plek een ander systeem.', '#2563eb', 'Schalen'],
        ]; @endphp

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($whyCards as $idx => [$title, $desc, $col, $tag])
            <div class="operatie-card flex h-full flex-col rounded-xl border border-slate-200 bg-white p-5">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <span class="inline-flex h-7 min-w-[1.75rem] items-center justify-center rounded-md px-2 text-xs font-bold text-white"
                          style="background:{{ $col }}">{{ $idx + 1 }}</span>
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-500">{{ $tag }}</span>
                </div>
                <p class="mb-1 text-sm font-semibold text-slate-900">{{ $title }}</p>
                <p class="flex-1 text-xs leading-relaxed text-slate-500">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="border-y border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        <div class="mb-20 grid items-center gap-10 lg:grid-cols-5 lg:gap-16">
            <div class="lg:col-span-3">
                <div class="mb-6 flex gap-0.5">
                    @for($i=0;$i<5;$i++)
                    <svg class="star h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-2xl font-semibold leading-snug text-slate-900 sm:text-3xl">
                    &ldquo;Wij draaiden op papiertjes en WhatsApp-groepjes. Dat leidde constant tot discussies. Sinds TaskCheck is de opening-en-sluiting echt een routine — nieuwe medewerkers snappen het binnen tien minuten.&rdquo;
                </p>
                <div class="mt-8 flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-base font-extrabold text-white" style="background:#2563eb">R</div>
                    <div>
                        <p class="font-bold text-slate-900">Roel van der Berg</p>
                        <p class="text-sm text-slate-500">Horecamanager — Restaurant De Hoek, Amsterdam</p>
                        <p class="mt-0.5 text-xs text-slate-400">2 locaties · 14 medewerkers</p>
                    </div>
                </div>
            </div>
            <div class="hidden lg:col-span-2 lg:block">
                <div class="flex aspect-[4/3] items-center justify-center overflow-hidden rounded-2xl" style="background:linear-gradient(135deg,#eff6ff,#f0f9ff)">
                    <div class="p-8 text-center">
                        <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Op de vloer</p>
                        <p class="mt-3 text-2xl font-extrabold text-slate-900">Opening · Service · Sluiting</p>
                        <p class="mt-2 text-sm text-slate-600">Eén proces — iedere dienst hetzelfde</p>
                        <div class="mt-8 flex flex-wrap justify-center gap-2">
                            @foreach(['HACCP','Bewijs','Rollen','Locaties'] as $chip)
                            <span class="rounded-full border border-blue-200 bg-white px-3 py-1 text-xs font-semibold text-blue-800">{{ $chip }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-3">
            @foreach([
                ['L','Lisa Hartman','Keukenmanager, Grand Café Markt','Rotterdam','HACCP-controles waren altijd een drama bij de audit. Nu exporteer ik één overzicht. De inspecteur was vorig jaar echt verrast.','#4f46e5'],
                ['M','Marco Jansen','Eigenaar, Pizzeria Bella Italia','Utrecht · 3 vestigingen','Iedere vestiging deed het anders. Met TaskCheck zit iedereen op één lijn. De app werkt ook voor medewerkers die niet tech-savvy zijn.','#0891b2'],
                ['S','Sara el Amrani','F&B Manager, Hotel Centraal','Den Haag · 22 medewerkers','We gebruikten Excel maar dat werkte niet op de vloer. Het dashboard geeft mij elke ochtend direct inzicht, zonder dat ik hoef te bellen.','#4f46e5'],
            ] as [$init,$name,$role,$detail,$quote,$col])
            <div class="border-t-2 pt-6" style="border-color:{{ $col }}">
                <div class="mb-4 flex gap-0.5">
                    @for($i=0;$i<5;$i++)
                    <svg class="star h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-sm leading-relaxed text-slate-700">&ldquo;{{ $quote }}&rdquo;</p>
                <div class="mt-5 flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold text-white" style="background:{{ $col }}">{{ $init }}</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $name }}</p>
                        <p class="text-xs text-slate-400">{{ $role }}, {{ $detail }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">FAQ</p>
            <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Veelgestelde vragen</h2>
        </div>
        <div class="space-y-3">
            @foreach([
                ['Werkt dit ook voor kleine horecazaken?','Ja, ook met 3–4 medewerkers helpt een horeca checklist app om structuur te houden. Je begint met je belangrijkste checklist en bouwt daarna verder.'],
                ['Kan ik HACCP-controles vastleggen?','Ja. Je kunt foto, video of tekst verplicht stellen voor kritieke taken. Dat geeft aantoonbaar overzicht bij audits en inspecties.'],
                ['Kan ik taken per rol instellen?','Zeker. Je deelt taken op per rol — keuken, bar, bediening of teamleider. Elk team ziet de taken die voor die shift relevant zijn.'],
                ['Werkt de app op mobiel?','Ja, TaskCheck werkt op elke smartphone. Medewerkers kunnen via de browser werken; waar beschikbaar ook als installeerbare app.'],
                ['Wat als een medewerker vergeet in te loggen?','Taken die niet zijn afgerond, staan zichtbaar als open of gemist in het dashboard. Je ziet snel waar iets blijft liggen.'],
            ] as [$q,$a])
            <details class="group cursor-pointer rounded-2xl border border-slate-200 bg-white px-5 py-4 transition hover:border-blue-200 sm:px-6">
                <summary class="flex list-none items-center justify-between gap-3 font-semibold text-slate-900">
                    <span class="text-left text-sm">{{ $q }}</span>
                    <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-45" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </summary>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $a }}</p>
            </details>
            @endforeach
        </div>
    </div>
</section>

<section class="pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-r from-[#2563eb] to-[#4f46e5] px-6 py-12 text-center text-white shadow-xl shadow-blue-500/20 sm:px-12 sm:py-16">
            <h2 class="text-3xl font-extrabold sm:text-4xl">Start vandaag gratis</h2>
            <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">Probeer TaskCheck 14 dagen gratis. Geen creditcard nodig.</p>
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Naar dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Start 14 dagen gratis</a>
                @endauth
                <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-xl border-2 border-white/40 px-8 py-4 text-base font-semibold text-white transition hover:bg-white/10">Bekijk prijzen</a>
            </div>
            <p class="mt-4 text-sm text-white/80">Geen verplichtingen · Direct aan de slag</p>
        </div>
    </div>
</section>

<section class="border-t border-slate-200 bg-white py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm font-semibold text-slate-900">Gerelateerde pagina&rsquo;s</p>
        <div class="mx-auto mt-5 flex max-w-4xl flex-wrap justify-center gap-2">
            @foreach([
                ['Horeca app personeel', route('seo.horeca-app-personeel')],
                ['Werkcontrole app', route('seo.werkcontrole-app')],
                ['Checklist app schoonmaak', route('seo.checklist-app-schoonmaak')],
                ['Blog: horeca personeel controleren', route('blog.horeca-personeel-controleren-checklist-app')],
            ] as $link)
            <a href="{{ $link[1] }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-blue-700 transition hover:border-blue-200 hover:bg-blue-50">
                {{ $link[0] }}
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
            @endforeach
        </div>
    </div>
</section>
</main>

@include('components.footer')
</body>
</html>
