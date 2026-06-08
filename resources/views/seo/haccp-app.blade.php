<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle       = 'HACCP app voor horeca | Digitaal registreren | TaskCheck';
        $seoDescription = 'Gebruik TaskCheck als HACCP app voor horeca. Registreer temperaturen, schoonmaak, controles en bewijs digitaal. Start 14 dagen gratis.';
        $seoKeywords    = 'HACCP app, digitale HACCP registratie, HACCP checklist app, HACCP horeca, temperatuurregistratie horeca, schoonmaakrooster horeca';
        $seoUrl         = route('seo.haccp-app');
        $seoImage       = asset('images/taskcheck-horeca-seo-hero.webp');
        $faqItems = [
            ['Wat is een HACCP app?', 'Een HACCP app is een digitale tool waarmee horecabedrijven voedselveiligheidscontroles vastleggen, zoals temperaturen, schoonmaak en leverancierscontrole.'],
            ['Kan ik temperatuurregistraties bijhouden?', 'Ja. Met TaskCheck kun je temperaturen registreren voor koelingen, vriezers, werkbanken en andere controlepunten.'],
            ['Kan ik foto\'s toevoegen als bewijs?', 'Ja. Je kunt per taak foto\'s, video\'s, notities of handtekeningen toevoegen.'],
            ['Is TaskCheck geschikt voor meerdere locaties?', 'Ja. Je kunt checklists en rapportages per locatie beheren.'],
            ['Vervangt TaskCheck papieren HACCP formulieren?', 'TaskCheck helpt je om controles digitaal vast te leggen. Controleer altijd zelf welke registraties voor jouw bedrijf verplicht zijn.'],
        ];
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="keywords" content="{{ $seoKeywords }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url"         content="{{ $seoUrl }}">
    <meta property="og:image"       content="{{ $seoImage }}">
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            @foreach($faqItems as $i => [$q, $a])
            {
                "@@type": "Question",
                "name": @json($q),
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": @json($a)
                }
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }
    </script>
    <style>
        .cta-btn { background: linear-gradient(135deg, #2563eb, #4f46e5); }
        .cta-btn:hover { background: linear-gradient(135deg, #1d4ed8, #4338ca); }
        .feature-card { transition: box-shadow .2s ease, border-color .2s ease; }
        .feature-card:hover { box-shadow: 0 10px 40px -20px rgba(15,23,42,.1); border-color: rgb(203 213 225); }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased overflow-x-hidden">
@include('components.header')

<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="seo-haccp-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-haccp-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">HACCP app · temperatuur, schoonmaak &amp; bewijs</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    HACCP app voor
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">horeca</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-haccp-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-haccp-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Met een HACCP app leg je controles digitaal vast. Geen losse papieren lijsten meer, maar duidelijke taken, registraties en bewijs op één plek.
                </p>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500">
                    TaskCheck helpt horecabedrijven met dagelijkse HACCP-controles zoals temperatuurregistratie, schoonmaak, ingangscontrole en hygiënecontrole.
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
                    @foreach(['Geen creditcard nodig','14 dagen gratis proberen','Digitale HACCP registratie'] as $b)
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
                             alt="HACCP app voor horeca — digitale temperatuur- en hygiënecontroles"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="800"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Controles op de vloer — met bewijs per taak en overzicht voor managers.</p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-200 bg-slate-50/80">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @php $stats = [
                ['Temperatuur', 'koeling & vriezer', 'text-blue-600', 'M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104v5.714a2.25 2.25 0 0 0 .659 1.591M5 14.5a2.25 2.25 0 0 0-2.25 2.219V17.5A2.25 2.25 0 0 0 5 19.5h14a2.25 2.25 0 0 0 2.25-2.25v-1.781a2.25 2.25 0 0 0-2.25-2.25M5 14.5l2.25-2.25M19.8 15.3 14.25 12.75 14.25 9.336'],
                ['Schoonmaak', 'roosters & taken', 'text-sky-600', 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z'],
                ['Bewijs', 'foto & notitie', 'text-emerald-600', 'M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z'],
                ['Realtime', 'overzicht managers', 'text-indigo-600', 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z'],
            ]; @endphp
            @foreach($stats as [$title, $sub, $color, $path])
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white {{ $color }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $title }}</p>
                    <p class="mt-0.5 text-sm text-slate-500">{{ $sub }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<main>
<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-start gap-16 lg:grid-cols-2">
            <div>
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Waarom digitaal</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Waarom een HACCP app gebruiken?</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    In de horeca moet je kunnen aantonen dat controles goed worden uitgevoerd. Denk aan temperaturen, schoonmaak, leverancierscontrole en bereiding.
                </p>
                <p class="mt-3 leading-relaxed text-slate-600">
                    Met papieren lijsten raak je snel overzicht kwijt. Formulieren kunnen kwijtraken, onleesbaar zijn of te laat worden ingevuld.
                </p>
                <p class="mt-3 font-medium text-slate-800">Met TaskCheck werk je digitaal en overzichtelijk.</p>
            </div>
            <div class="space-y-3">
                @foreach([
                    'Papieren formulieren raken kwijt of worden niet ingevuld',
                    'Geen realtime inzicht voor managers',
                    'Moeilijk bewijs terug te vinden bij controle',
                    'Wisselende medewerkers weten niet wat de standaard is',
                ] as $problem)
                <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">{{ $problem }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Registraties</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Wat kun je registreren met TaskCheck?</h2>
            <p class="mt-4 text-lg text-slate-500">Met TaskCheck kun je onder andere deze HACCP-controles vastleggen:</p>
        </div>
        <ul class="mx-auto mt-10 grid max-w-4xl gap-3 sm:grid-cols-2">
            @foreach([
                'Temperatuurregistratie van koelingen en vriezers',
                'Schoonmaakroosters',
                'Ingangscontrole van leveringen',
                'Bereiden en serveren',
                'Periodieke hygiënecontrole',
                'Thermometer controle',
                'Goedgekeurde leveranciers',
                'Ongekoelde presentatie',
                'Sous-vide, sushi en productieregistraties',
            ] as $item)
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                {{ $item }}
            </li>
            @endforeach
        </ul>
        <p class="mx-auto mt-8 max-w-3xl text-center text-sm leading-relaxed text-slate-500">
            Deze onderdelen sluiten aan op veelgebruikte horecaregistraties zoals
            <a href="{{ route('seo.temperatuurregistratie-horeca') }}" class="font-medium text-blue-700 hover:underline">temperatuurregistratie</a>,
            <a href="{{ route('seo.schoonmaakrooster-horeca') }}" class="font-medium text-blue-700 hover:underline">schoonmaakroosters</a>,
            ingangscontrole en periodieke hygiënecontrole.
        </p>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Checklists</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">HACCP controles digitaal uitvoeren</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Met TaskCheck maak je vaste checklists per locatie, afdeling of shift. Medewerkers zien precies wat ze moeten doen. Managers zien realtime wat klaar is en wat nog openstaat.
                </p>
                <p class="mt-4 text-sm font-semibold text-slate-700">Bijvoorbeeld:</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach(['Opening keuken','Sluiting restaurant','Koeling controleren','Vriezer controleren','Werkbanken schoonmaken','HACCP logboek invullen','Levering controleren','Afwijking melden'] as $chip)
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-800">{{ $chip }}</span>
                    @endforeach
                </div>
            </div>
            <figure class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-sm">
                <img src="{{ asset('images/seo-opening-checklist-horeca-hero.png') }}"
                     alt="HACCP checklist op telefoon in de keuken"
                     loading="lazy" decoding="async" width="800" height="600"
                     class="w-full object-cover">
            </figure>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div class="order-2 lg:order-1">
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach([
                        ['Foto van een schone werkbank', 'Schone werkplek vastleggen'],
                        ['Foto van temperatuurmeting', 'Temperatuur met bewijs'],
                        ['Notitie bij afwijking', 'Afwijking documenteren'],
                        ['Handtekening van medewerker', 'Verantwoordelijkheid vastleggen'],
                        ['Corrigerende maatregel bij fout', 'Actie bij non-conformiteit'],
                    ] as [$title, $desc])
                    <div class="feature-card rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-sm font-semibold text-slate-900">{{ $title }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $desc }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="order-1 lg:order-2">
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Bewijs</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Bewijs toevoegen per taak</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Bij belangrijke controles kun je bewijs verplicht maken. Zo kun je later aantonen wat er is gedaan — handig bij audits en inspecties.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Voordelen</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Voordelen van een HACCP app</h2>
            <p class="mt-4 text-lg text-slate-500">Met TaskCheck krijg je:</p>
        </div>
        <div class="mx-auto mt-10 grid max-w-4xl gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach([
                'Minder papierwerk',
                'Meer overzicht',
                'Minder vergeten controles',
                'Realtime inzicht',
                'Bewijs per taak',
                'Betere voorbereiding op controles',
                'Eén vaste werkwijze voor je team',
            ] as $benefit)
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3.5">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">{{ $benefit }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Doelgroep</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Voor wie is deze HACCP app geschikt?</h2>
            <p class="mt-4 text-lg text-slate-500">TaskCheck is geschikt voor:</p>
        </div>
        <div class="mx-auto mt-10 flex max-w-3xl flex-wrap justify-center gap-3">
            @foreach(['Restaurants','Cafés','Lunchrooms','Hotels','Cateringbedrijven','Bakkerijen','Keukenteams','Horecaketens met meerdere locaties'] as $target)
            <span class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">{{ $target }}</span>
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
            @foreach($faqItems as [$q, $a])
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

<section class="border-t border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-r from-[#2563eb] to-[#4f46e5] px-6 py-12 text-center text-white shadow-xl shadow-blue-500/20 sm:px-12 sm:py-16">
            <h2 class="text-3xl font-extrabold sm:text-4xl">Start met digitale HACCP registratie</h2>
            <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">
                Wil je minder gedoe met papieren formulieren en meer grip op je controles? Met TaskCheck start je snel met digitale HACCP checklists voor jouw horecazaak.
            </p>
            <p class="mt-2 text-base font-medium text-white/95">Start 14 dagen gratis. Geen creditcard nodig.</p>
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Naar dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Start 14 dagen gratis</a>
                @endauth
                <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-xl border-2 border-white/40 px-8 py-4 text-base font-semibold text-white transition hover:bg-white/10">Bekijk prijzen</a>
            </div>
        </div>
    </div>
</section>

<section class="border-t border-slate-200 bg-white py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm font-semibold text-slate-900">Gerelateerde pagina&rsquo;s</p>
        <div class="mx-auto mt-5 flex max-w-4xl flex-wrap justify-center gap-2">
            @foreach([
                ['HACCP checklist app', route('seo.haccp-checklist-app')],
                ['Temperatuurregistratie horeca', route('seo.temperatuurregistratie-horeca')],
                ['Horeca checklist app', route('seo.horeca-checklist-app')],
                ['Schoonmaakrooster horeca', route('seo.schoonmaakrooster-horeca')],
                ['Prijzen', route('pricing')],
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
