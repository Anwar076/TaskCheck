@php
    $seoTitle       = 'HACCP Checklist App | Digitale HACCP Checklists Horeca | TaskCheck';
        $seoDescription = 'Gebruik een HACCP checklist app voor horeca. Leg controles, temperaturen en schoonmaak digitaal vast met TaskCheck. Start 14 dagen gratis.';
        $seoKeywords    = 'HACCP checklist app, digitale HACCP checklist, HACCP checklist horeca, HACCP controle app, HACCP registratie app, voedselveiligheid horeca';
        $seoUrl         = route('seo.haccp-checklist-app');
        $seoImage       = asset('images/taskcheck-horeca-seo-hero.webp');
        $faqItems = [
            ['Wat is een HACCP checklist app?', 'Een HACCP checklist app is een digitale oplossing waarmee horecabedrijven voedselveiligheidscontroles uitvoeren en registreren.'],
            ['Kan ik temperaturen registreren?', 'Ja. Je kunt temperaturen van koelingen, vriezers en producten registreren.'],
            ['Kan ik foto\'s toevoegen als bewijs?', 'Ja. Medewerkers kunnen foto\'s, video\'s, opmerkingen en handtekeningen toevoegen.'],
            ['Is de app geschikt voor meerdere locaties?', 'Ja. Je kunt meerdere locaties beheren vanuit één dashboard.'],
            ['Kan ik eigen checklists maken?', 'Ja. Je kunt volledig eigen HACCP checklists aanmaken en aanpassen.'],
        ];
        $ctaHeading = 'Start vandaag met een HACCP checklist app';
        $ctaLead = 'Wil je stoppen met papieren formulieren en meer controle krijgen over HACCP-processen? Met TaskCheck maak je binnen enkele minuten digitale HACCP checklists voor jouw horecabedrijf.';
        $seoTheme = 'haccp';
@endphp

@extends('layouts.seo-page')

@push('head')
<script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            @foreach($faqItems as [$q, $a])
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
@endpush

@section('content')
<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="seo-haccp-cl-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-haccp-cl-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0 fade-up">
                <p class="blog-kicker fade-up mb-6 sm:mb-7">HACCP checklist app · voedselveiligheid horeca</p>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    HACCP Checklist App voor
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Horeca</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-haccp-cl-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-haccp-cl-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Met een HACCP checklist app zorg je ervoor dat dagelijkse controles niet worden vergeten. Medewerkers zien precies welke taken uitgevoerd moeten worden en managers hebben realtime inzicht in de voortgang.
                </p>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500">
                    TaskCheck helpt restaurants, cafés, lunchrooms, hotels en andere horecabedrijven om HACCP-controles digitaal uit te voeren en vast te leggen.
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:flex-wrap">
                    @auth
                        <a href="{{ auth()->user()->homeDashboardUrl() }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Naar dashboard
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Start 14 dagen gratis
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endauth
                    <a href="{{ route('contact') }}" class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl border border-[#e6e8ec] bg-white px-6 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 sm:w-auto sm:min-h-0 touch-manipulation">
                        Plan een demo
                    </a>
                </div>

                <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 sm:mt-6 sm:gap-x-6">
                    @foreach(['Geen creditcard nodig','14 dagen gratis proberen','Eigen checklists maken'] as $b)
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $b }}
                    </span>
                    @endforeach
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-xl lg:mx-0 lg:max-w-none">
                <div class="seo-hero-frame">
                        <img src="{{ asset('images/taskcheck-horeca-seo-hero.webp') }}"
                             alt="HACCP checklist app voor horeca — digitale controles op mobiel"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="800"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">Digitale HACCP checklists — op mobiel, tablet of computer.</p>
            </div>
        </div>
    </div>
</section>

<x-seo-product-visuals theme="haccp" placement="showcase" />

<div>
<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-start gap-16 lg:grid-cols-2">
            <div>
                <p class="blog-kicker mb-3">Waarom digitaal</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Waarom kiezen voor een HACCP checklist app?</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Veel horecabedrijven werken nog met papieren formulieren of Excel-bestanden. Dit kost tijd en zorgt voor fouten.
                </p>
                <p class="mt-4 font-medium text-slate-800">Met een digitale HACCP checklist app worden deze problemen opgelost.</p>
            </div>
            <div class="space-y-3">
                @foreach([
                    'Vergeten controles',
                    'Onleesbare formulieren',
                    'Geen bewijs van uitvoering',
                    'Geen overzicht bij meerdere locaties',
                    'Moeilijk terugzoeken van registraties',
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

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="blog-kicker mb-3">Basis</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Wat is een HACCP checklist?</h2>
            <p class="mt-4 text-lg text-slate-500">
                Een HACCP checklist is een lijst met controles die uitgevoerd moeten worden om voedselveiligheid te waarborgen. Met TaskCheck worden deze controles digitaal uitgevoerd via mobiel, tablet of computer.
            </p>
        </div>
        <ul class="mx-auto mt-10 grid max-w-4xl gap-3 sm:grid-cols-2">
            @foreach([
                'Temperatuurcontrole van koelingen',
                'Temperatuurcontrole van vriezers',
                'Schoonmaakcontroles',
                'Ingangscontrole van goederen',
                'Persoonlijke hygiëne',
                'Productregistraties',
                'Leverancierscontrole',
            ] as $item)
            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                {{ $item }}
            </li>
            @endforeach
        </ul>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto mb-14 max-w-2xl text-center">
            <p class="blog-kicker mb-3">Checklists</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Digitale HACCP checklists voor horeca</h2>
            <p class="mt-4 text-lg text-slate-500">Met TaskCheck maak je eenvoudig checklists voor elke fase van de dag.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            @php
            $checklistTypes = [
                [
                    'title' => 'Opening Checklist',
                    'desc' => 'Voor opening van de zaak:',
                    'items' => ['Koelingen controleren', 'Werkplekken controleren', 'Apparatuur controleren', 'Temperaturen registreren'],
                    'link' => ['Opening checklist horeca', route('seo.opening-checklist-horeca')],
                    'color' => '#2563eb',
                ],
                [
                    'title' => 'Sluitingschecklist',
                    'desc' => 'Voor het einde van de werkdag:',
                    'items' => ['Schoonmaak controleren', 'Apparatuur uitschakelen', 'Afval verwijderen', 'Registraties afronden'],
                    'link' => ['Sluitingschecklist horeca', route('seo.sluitings-checklist-horeca')],
                    'color' => '#4f46e5',
                ],
                [
                    'title' => 'Schoonmaak Checklist',
                    'desc' => 'Dagelijkse schoonmaak van:',
                    'items' => ['Werkbanken', 'Keukenapparatuur', 'Koelingen', 'Vriezers', 'Sanitair'],
                    'link' => ['Schoonmaak checklist', route('seo.schoonmaak-checklist')],
                    'color' => '#059669',
                ],
                [
                    'title' => 'Temperatuur Checklist',
                    'desc' => 'Controle van:',
                    'items' => ['Koelcellen', 'Koelingen', 'Vriezers', 'Producttemperaturen'],
                    'link' => ['Temperatuurregistratie horeca', route('seo.temperatuurregistratie-horeca')],
                    'color' => '#0891b2',
                ],
            ];
            @endphp
            @foreach($checklistTypes as $cl)
            <div class="feature-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg text-sm font-bold text-white" style="background:{{ $cl['color'] }}">{{ mb_substr($cl['title'], 0, 1) }}</span>
                    <h3 class="text-lg font-bold text-slate-900">{{ $cl['title'] }}</h3>
                </div>
                <p class="mb-3 text-sm text-slate-500">{{ $cl['desc'] }}</p>
                <ul class="space-y-2">
                    @foreach($cl['items'] as $item)
                    <li class="flex items-center gap-2 text-sm text-slate-700">
                        <span class="h-1.5 w-1.5 shrink-0 rounded-full" style="background:{{ $cl['color'] }}"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                @if($cl['link'])
                <a href="{{ $cl['link'][1] }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-blue-700 hover:underline">
                    {{ $cl['link'][0] }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <p class="blog-kicker mb-3">Bewijs</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Foto- en videobewijs per controle</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Een groot voordeel van TaskCheck is dat je bewijs kunt toevoegen aan iedere controle. Bij iedere taak kan een medewerker:
                </p>
                <ul class="mt-6 space-y-3">
                    @foreach(['Een foto maken', 'Een video uploaden', 'Een opmerking plaatsen', 'Een handtekening toevoegen'] as $proof)
                    <li class="flex items-center gap-3 text-sm text-slate-700">
                        <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $proof }}
                    </li>
                    @endforeach
                </ul>
                <p class="mt-4 text-sm text-slate-600">Hierdoor kun je altijd aantonen dat controles zijn uitgevoerd.</p>
            </div>
            <figure class="seo-hero-frame"><img src="{{ asset('images/seo-opening-checklist-horeca-hero.png') }}"
                     alt="HACCP checklist met fotobewijs op telefoon"
                     loading="lazy" decoding="async" width="800" height="600"
                     class="w-full object-cover"></figure>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div class="order-2 lg:order-1 grid gap-3 sm:grid-cols-2">
                @foreach([
                    ['Afgerond', 'Welke controles zijn afgerond', 'text-emerald-600', 'bg-emerald-50'],
                    ['Open', 'Welke taken nog openstaan', 'text-amber-600', 'bg-amber-50'],
                    ['Afgekeurd', 'Welke controles zijn afgekeurd', 'text-red-600', 'bg-red-50'],
                    ['Locaties', 'Welke locaties achterlopen', 'text-blue-600', 'bg-blue-50'],
                ] as [$tag, $desc, $color, $bg])
                <div class="rounded-xl border border-slate-200 {{ $bg }} p-4">
                    <p class="text-sm font-bold {{ $color }}">{{ $tag }}</p>
                    <p class="mt-1 text-xs text-slate-600">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
            <div class="order-1 lg:order-2">
                <p class="blog-kicker mb-3">Dashboard</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Realtime inzicht voor managers</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Als manager zie je direct welke controles zijn afgerond, welke taken nog openstaan en welke locaties achterlopen. Zo hoef je niet meer achter formulieren aan te gaan.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <p class="blog-kicker mb-3">Schalen</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">HACCP checklist app voor meerdere locaties</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Heb je meerdere vestigingen? Met TaskCheck beheer je alle locaties vanuit één dashboard. Ideaal voor horecaketens en bedrijven met meerdere locaties.
                </p>
                <ul class="mt-6 space-y-3">
                    @foreach(['Centrale controle', 'Standaard werkwijze', 'Overzicht per locatie', 'Rapportages per vestiging'] as $benefit)
                    <li class="flex items-center gap-3 text-sm text-slate-700">
                        <svg class="h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $benefit }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-blue-50/60 p-8">
                <p class="blog-kicker">Doelgroep</p>
                <h3 class="mt-2 text-xl font-bold text-slate-900">Voor welke bedrijven is deze HACCP checklist app geschikt?</h3>
                <p class="mt-2 text-sm text-slate-500">TaskCheck wordt gebruikt door:</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach(['Restaurants','Cafés','Lunchrooms','Hotels','Cateringbedrijven','Fastfoodrestaurants','Bakkerijen','IJssalons','Horecaketens'] as $target)
                    <span class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700">{{ $target }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="blog-kicker mb-3">TaskCheck</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Waarom TaskCheck?</h2>
            <p class="mt-4 text-lg text-slate-500">Met TaskCheck krijg je:</p>
        </div>
        <div class="mx-auto mt-10 grid max-w-3xl gap-3 sm:grid-cols-2">
            @foreach([
                'Digitale HACCP checklists',
                'Foto- en videobewijs',
                'Realtime dashboards',
                'Controle per locatie',
                'Automatische rapportages',
                'Minder papierwerk',
                'Meer grip op voedselveiligheid',
            ] as $benefit)
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-white px-4 py-3.5 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span class="text-sm font-medium text-slate-800">{{ $benefit }}</span>
            </div>
            @endforeach
        </div>
        <p class="mx-auto mt-8 max-w-2xl text-center text-sm text-slate-500">
            Meer over <a href="{{ route('seo.digitale-haccp-registratie') }}" class="font-medium text-blue-700 hover:underline">digitale HACCP registratie</a>
            of bekijk onze <a href="{{ route('seo.haccp-app') }}" class="font-medium text-blue-700 hover:underline">HACCP app voor horeca</a>.
        </p>
    </div>
</section>

<x-seo-product-visuals theme="haccp" placement="story" />

<section class="bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <p class="blog-kicker mb-3">FAQ</p>
            <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Veelgestelde vragen</h2>
        </div>
        <div class="space-y-3">
            @foreach($faqItems as [$q, $a])
            <details class="group cursor-pointer rounded-2xl border border-[#e6e8ec] bg-white px-5 py-4 transition hover:border-[#d7e2f7] sm:px-6">
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

<section class="border-t border-slate-200 bg-white py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="blog-kicker mx-auto">Gerelateerde pagina&rsquo;s</p>
        <div class="mx-auto mt-5 flex max-w-4xl flex-wrap justify-center gap-2">
            @foreach([
                ['HACCP app', route('seo.haccp-app')],
                ['Digitale HACCP registratie', route('seo.digitale-haccp-registratie')],
                ['Temperatuurregistratie horeca', route('seo.temperatuurregistratie-horeca')],
                ['Opening checklist horeca', route('seo.opening-checklist-horeca')],
                ['Sluitingschecklist horeca', route('seo.sluitings-checklist-horeca')],
                ['Prijzen', route('pricing')],
            ] as $link)
            <a href="{{ $link[1] }}" class="inline-flex items-center gap-1.5 rounded-full border border-[#e6e8ec] bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-[#d7e2f7] hover:text-blue-700">
                {{ $link[0] }}
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
            @endforeach
        </div>
    </div>
</section>
</div>
@endsection
