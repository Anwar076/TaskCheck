<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle       = 'Digitale HACCP Registratie voor Horeca | NVWA-proof & Gratis Proberen | TaskCheck';
        $seoDescription = 'Direct NVWA-proof werken met digitale HACCP registratie. TaskCheck is dé app voor horeca, bakkerij, hotel, slagerij: HACCP, temperatuur, schoonmaak en meer. Start 14 dagen gratis – geen creditcard!';
        $seoKeywords    = 'digitale HACCP registratie, HACCP registratie digitaal, digitale HACCP app, HACCP registraties online, voedselveiligheid registratie, HACCP software horeca';
        $seoUrl         = route('seo.digitale-haccp-registratie');
        $seoImage       = asset('images/taskcheck-horeca-seo-hero.webp');
        $faqItems = [['Wat is digitale HACCP registratie?', 'Digitale HACCP registratie betekent dat voedselveiligheidscontroles digitaal worden vastgelegd in plaats van op papier.'],
            ['Kan ik temperatuurregistraties digitaal uitvoeren?', 'Ja. Met TaskCheck registreer je eenvoudig temperaturen van koelingen, vriezers en producten.'],
            ['Kan ik foto\'s toevoegen als bewijs?', 'Ja. Bij iedere registratie kun je foto\'s, video\'s, opmerkingen en handtekeningen toevoegen.'],
            ['Is TaskCheck geschikt voor meerdere locaties?', 'Ja. Je kunt meerdere vestigingen beheren vanuit één dashboard.'],
            ['Vervangt TaskCheck papieren HACCP formulieren?', 'TaskCheck helpt bedrijven om HACCP-processen digitaal vast te leggen en overzichtelijk te beheren.'],
            ['Hoe voldoet TaskCheck aan de eisen van de NVWA tijdens inspecties?', 'TaskCheck houdt rekening met de actuele NVWA-richtlijnen. Rapportages zijn direct te tonen tijdens een controle en voldoen aan alle wettelijke eisen.'],
            ['Is TaskCheck veilig en voldoen mijn gegevens aan de AVG?', 'Ja, TaskCheck slaat alle gegevens veilig op binnen de EU en voldoet volledig aan de AVG (GDPR). Alleen bevoegde medewerkers hebben toegang tot de data.'],
            ['Hoe stap ik eenvoudig over van papieren HACCP naar digitaal?', 'Met TaskCheck importeer je bestaande formulieren eenvoudig of gebruik je kant-en-klare sjablonen. Je team werkt direct digitaal via mobiel of tablet.'],
            ['Welke horecaondernemingen gebruiken TaskCheck voor HACCP registratie?', 'TaskCheck wordt gebruikt door restaurants, lunchrooms, hotels, bakkerijen, slagerijen en fastfoodconcepten in heel Nederland.'],];
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
    <style>
        .cta-btn { background: linear-gradient(135deg, #2563eb, #4f46e5); }
        .cta-btn:hover { background: linear-gradient(135deg, #1d4ed8, #4338ca); }
        .feature-card { transition: box-shadow .2s ease, border-color .2s ease; }
        .feature-card:hover { box-shadow: 0 10px 40px -20px rgba(15,23,42,.1); border-color: rgb(203 213 225); }
        .cta-banner-haccp {
            background: linear-gradient(to right, #2563eb, #4f46e5);
            box-shadow: 0 20px 50px -20px rgba(37, 99, 235, 0.45);
        }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased overflow-x-hidden">
@include('components.header')

<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="seo-dig-haccp-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-dig-haccp-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="text-left leading-snug">Digitale HACCP registratie · voedselveiligheid horeca</span>
                </div>

                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    Digitale HACCP Registratie voor de
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Horeca</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-dig-haccp-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-dig-haccp-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Steeds meer horecabedrijven stappen over van papieren formulieren naar digitale HACCP registratie. Dat bespaart tijd, voorkomt fouten en zorgt voor meer overzicht.
                </p>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500">
                    Met TaskCheck registreer je HACCP-controles digitaal via smartphone, tablet of computer. Alle registraties worden automatisch opgeslagen en zijn direct terug te vinden.
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
                    @foreach(['Geen creditcard nodig','14 dagen gratis proberen','Alles op één plek'] as $b)
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
                             alt="Digitale HACCP registratie in de horeca"
                             class="h-auto w-full object-cover"
                             width="1200"
                             height="800"
                             loading="eager"
                             fetchpriority="high">
                    </div>
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">HACCP-controles digitaal — direct zichtbaar voor managers.</p>
            </div>
        </div>
    </div>
</section>

<main>
<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-start gap-16 lg:grid-cols-2">
            <div>
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Waarom digitaal</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Waarom overstappen op digitale HACCP registratie?</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Veel horecabedrijven gebruiken nog papieren HACCP-formulieren. Met een digitale oplossing werk je sneller, overzichtelijker en professioneler.
                </p>
            </div>
            <div class="space-y-3">
                @foreach([
                    'Formulieren raken kwijt',
                    'Onleesbare handschriften',
                    'Vergeten registraties',
                    'Geen realtime overzicht',
                    'Tijdrovende administratie',
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
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Uitleg</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Wat is digitale HACCP registratie?</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Digitale HACCP registratie betekent dat je alle voedselveiligheidscontroles online vastlegt in één centraal systeem. In plaats van losse papieren formulieren registreer je controles direct via een app of dashboard.
                </p>
                <p class="mt-4 text-sm font-semibold text-slate-700">Daardoor kun je altijd zien:</p>
                <ul class="mt-4 space-y-3">
                    @foreach([
                        'Welke controles zijn uitgevoerd',
                        'Wanneer controles zijn uitgevoerd',
                        'Wie controles heeft uitgevoerd',
                        'Welke afwijkingen zijn geconstateerd',
                        'Welk bewijs is toegevoegd',
                    ] as $item)
                    <li class="flex items-center gap-3 text-sm text-slate-700">
                        <svg class="h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <figure class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-sm">
                <img src="{{ asset('images/seo-opening-checklist-horeca-hero.png') }}"
                     alt="HACCP registraties online beheren op mobiel"
                     loading="lazy" decoding="async" width="800" height="600"
                     class="w-full object-cover">
            </figure>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto mb-14 max-w-2xl text-center">
            <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Digitaliseren</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Welke HACCP registraties kun je digitaliseren?</h2>
            <p class="mt-4 text-lg text-slate-500">Met TaskCheck kun je vrijwel alle HACCP-processen digitaal vastleggen.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            @php
            $registrationTypes = [
                [
                    'title' => 'Temperatuurregistratie',
                    'items' => ['Koelcellen', 'Koelkasten', 'Werkbankkoelingen', 'Vriezers', 'Producttemperaturen'],
                    'link' => ['Temperatuurregistratie horeca', route('seo.temperatuurregistratie-horeca')],
                    'color' => '#0284c7',
                ],
                [
                    'title' => 'Schoonmaakregistratie',
                    'items' => ['Werkbanken', 'Apparatuur', 'Koelingen', 'Vloeren', 'Toiletten', 'Contactpunten'],
                    'link' => ['Schoonmaak checklist', route('seo.schoonmaak-checklist')],
                    'color' => '#059669',
                ],
                [
                    'title' => 'Leverancierscontrole',
                    'items' => ['Verpakking', 'Houdbaarheidsdatum', 'Temperatuur bij ontvangst', 'Productkwaliteit'],
                    'link' => null,
                    'color' => '#4f46e5',
                ],
                [
                    'title' => 'Hygiënecontroles',
                    'items' => ['Persoonlijke hygiëne', 'Werkplekken', 'Opslagruimtes', 'Productverwerking'],
                    'link' => null,
                    'color' => '#d97706',
                ],
            ];
            @endphp
            @foreach($registrationTypes as $type)
            <div class="feature-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg text-sm font-bold text-white" style="background:{{ $type['color'] }}">{{ mb_substr($type['title'], 0, 1) }}</span>
                    <h3 class="text-lg font-bold text-slate-900">{{ $type['title'] }}</h3>
                </div>
                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Controle van:</p>
                <ul class="space-y-2">
                    @foreach($type['items'] as $item)
                    <li class="flex items-center gap-2 text-sm text-slate-700">
                        <span class="h-1.5 w-1.5 shrink-0 rounded-full" style="background:{{ $type['color'] }}"></span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
                @if($type['link'])
                <a href="{{ $type['link'][1] }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-blue-700 hover:underline">
                    {{ $type['link'][0] }}
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
            <div class="order-2 lg:order-1 grid gap-3 sm:grid-cols-2">
                @foreach(['Foto\'s', 'Video\'s', 'Notities', 'Handtekeningen'] as $proof)
                <div class="feature-card rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                    <p class="text-sm font-semibold text-slate-900">{{ $proof }}</p>
                </div>
                @endforeach
            </div>
            <div class="order-1 lg:order-2">
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Bewijs</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Alles aantoonbaar met bewijs</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Met TaskCheck kun je bewijs toevoegen aan iedere registratie. Hierdoor kun je altijd aantonen dat controles daadwerkelijk zijn uitgevoerd. Dat voorkomt discussies en geeft extra zekerheid.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Dashboard</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Realtime inzicht in alle HACCP controles</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Managers hoeven niet meer te wachten op formulieren. In het dashboard zie je direct openstaande controles, afgeronde registraties, afwijkingen, foto bewijs en resultaten per locatie.
                </p>
                <p class="mt-3 font-medium text-slate-800">Zo houd je continu grip op voedselveiligheid.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach([
                    ['Openstaand', 'Openstaande controles', 'text-amber-600', 'bg-amber-50'],
                    ['Klaar', 'Afgeronde registraties', 'text-emerald-600', 'bg-emerald-50'],
                    ['Afwijking', 'Afwijkingen', 'text-red-600', 'bg-red-50'],
                    ['Locatie', 'Resultaten per locatie', 'text-blue-600', 'bg-blue-50'],
                ] as [$tag, $desc, $color, $bg])
                <div class="rounded-xl border border-slate-200 {{ $bg }} p-4">
                    <p class="text-sm font-bold {{ $color }}">{{ $tag }}</p>
                    <p class="mt-1 text-xs text-slate-600">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Schalen</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Digitale HACCP registratie voor meerdere locaties</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-500">
                    Heb je meerdere restaurants of vestigingen? Met TaskCheck kun je locaties apart beheren, centrale rapportages bekijken en standaarden toepassen op alle vestigingen. Ideaal voor horecaketens en franchiseorganisaties.
                </p>
                <ul class="mt-6 space-y-3">
                    @foreach(['Locaties apart beheren', 'Centrale rapportages bekijken', 'Standaarden toepassen op alle vestigingen', 'Afwijkingen sneller signaleren'] as $benefit)
                    <li class="flex items-center gap-3 text-sm text-slate-700">
                        <svg class="h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $benefit }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-blue-50/60 p-8">
                <p class="text-sm font-bold uppercase tracking-wider text-blue-600">Efficiëntie</p>
                <h3 class="mt-2 text-xl font-bold text-slate-900">Minder administratie, meer controle</h3>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                    Een digitale HACCP registratie bespaart veel tijd. Medewerkers hoeven geen formulieren meer in te vullen en managers hoeven geen papierwerk meer te controleren. Alle gegevens worden automatisch opgeslagen en zijn direct beschikbaar.
                </p>
                <p class="mt-3 text-sm font-medium text-slate-800">Daardoor besteed je minder tijd aan administratie en meer tijd aan je bedrijf.</p>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-slate-50 py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-2">
            <div>
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Voordelen</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Voordelen van digitale HACCP registratie</h2>
                <div class="mt-8 grid gap-2 sm:grid-cols-2">
                    @foreach(['Digitale HACCP formulieren','Temperatuurregistratie','Schoonmaakregistratie','Leverancierscontrole','Foto- en videobewijs','Realtime dashboards','Minder papierwerk','Meer controle over voedselveiligheid'] as $benefit)
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $benefit }}
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Doelgroep</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Voor welke bedrijven is TaskCheck geschikt?</h2>
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach(['Restaurants','Cafés','Lunchrooms','Hotels','Cateringbedrijven','Fastfoodrestaurants','Bakkerijen','IJssalons','Horecaketens'] as $target)
                    <span class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700">{{ $target }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-100 bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="mb-3 text-sm font-bold uppercase tracking-wider text-blue-600">Platform</p>
            <h2 class="text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">Waarom kiezen voor TaskCheck?</h2>
            <p class="mt-4 text-lg text-slate-500">
                TaskCheck is ontwikkeld voor bedrijven die dagelijks met controles en checklists werken. Je krijgt één centraal platform voor HACCP registraties, schoonmaakcontroles, temperatuurregistraties, takenlijsten, werkcontrole en rapportages — alles overzichtelijk op één plek.
            </p>
        </div>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ route('seo.haccp-app') }}" class="rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-800 hover:bg-blue-100">HACCP app</a>
            <a href="{{ route('seo.haccp-checklist-app') }}" class="rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-800 hover:bg-blue-100">HACCP checklist app</a>
            <a href="{{ route('seo.opening-checklist-horeca') }}" class="rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-800 hover:bg-blue-100">Opening checklist</a>
            <a href="{{ route('seo.sluitings-checklist-horeca') }}" class="rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-800 hover:bg-blue-100">Sluitingschecklist</a>
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
        <div class="cta-banner-haccp rounded-3xl px-6 py-12 text-center sm:px-12 sm:py-16">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">Start vandaag met digitale HACCP registratie</h2>
            <p class="mx-auto mt-3 max-w-xl text-lg text-white/90">
                Wil je stoppen met papieren formulieren en meer controle krijgen over voedselveiligheid? Met TaskCheck maak je binnen enkele minuten digitale HACCP registraties voor jouw horecabedrijf.
            </p>
            <p class="mt-2 text-base font-medium text-white">Start vandaag 14 dagen gratis.</p>
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Naar dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Start 14 dagen gratis</a>
                @endauth
                <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-xl border-2 border-white/60 bg-white/10 px-8 py-4 text-base font-semibold text-white transition hover:bg-white/20">Bekijk prijzen</a>
            </div>
            <p class="mt-4 text-sm text-white/80">Geen creditcard nodig · Direct aan de slag</p>
        </div>
    </div>
</section>

<section class="border-t border-slate-200 bg-white py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm font-semibold text-slate-900">Gerelateerde pagina&rsquo;s</p>
        <div class="mx-auto mt-5 flex max-w-4xl flex-wrap justify-center gap-2">
            @foreach([
                ['HACCP app', route('seo.haccp-app')],
                ['HACCP checklist app', route('seo.haccp-checklist-app')],
                ['Temperatuurregistratie horeca', route('seo.temperatuurregistratie-horeca')],
                ['Opening checklist horeca', route('seo.opening-checklist-horeca')],
                ['Sluitingschecklist horeca', route('seo.sluitings-checklist-horeca')],
                ['Schoonmaak checklist', route('seo.schoonmaak-checklist')],
                ['Prijzen', route('pricing')],                    ['Digitale checklist app', route('seo.digitale-checklist-app')],
                    ['Horeca checklist app', route('seo.horeca-checklist-app')],
                    ['Temperatuurregistratie app', route('seo.temperatuurregistratie-app')],
                    ['Checklist app met foto-bewijs', route('seo.checklist-app-met-foto-bewijs')],
                    ['Schoonmaak checklist app', route('seo.schoonmaak-checklist-app')],
            ] as $link)
            <a href="{{ $link[1] }}" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-blue-700 transition hover:border-blue-200 hover:bg-blue-50">
                {{ $link[0] }}
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
            @endforeach
        </div>
    </div>
</section>
<section class="bg-blue-50 py-16"><div class="max-w-5xl mx-auto px-4"><h2 class="text-2xl font-bold mb-6">Hoe helpt TaskCheck bij een NVWA-inspectie?</h2><p class="mb-4">Tijdens een NVWA-inspectie moet je direct kunnen aantonen dat je HACCP-controles actueel en compleet zijn. Met TaskCheck toon je alle digitale registraties – inclusief temperatuurmetingen, schoonmaakchecks en inspectierapporten – direct op je tablet of smartphone. De NVWA accepteert digitale rapportages, waardoor je geen papieren mappen meer hoeft te laten zien.</p><ul class="list-disc ml-6 mb-4"><li>Altijd actuele registratie, nooit meer vergeten</li><li>Foto- en videobewijs direct bij elke controle</li><li>Rapportages delen met inspecteur in één klik</li></ul><p class="mb-4">Bekijk hoe een restaurant TaskCheck succesvol inzet bij de jaarlijkse NVWA-controle:</p><blockquote class="bg-white border-l-4 border-blue-500 p-4 text-blue-900 mb-4">“Tijdens de inspectie konden we alles direct laten zien. De inspecteur was onder de indruk van de overzichtelijke rapportages. Geen stress meer!” – Restaurantmanager, Utrecht</blockquote></div></section><section class="bg-white py-10"><div class="max-w-5xl mx-auto px-4"><h2 class="text-xl font-bold mb-4">USP’s per branche</h2><div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div><h3 class="font-semibold mb-1">Voor restaurants & lunchrooms</h3><ul class="list-disc ml-6 text-sm"><li>Snelle dagelijkse checks</li><li>Meerdere locaties beheren</li><li>Realtime inzicht voor managers</li></ul></div><div><h3 class="font-semibold mb-1">Voor bakkerijen & slagerijen</h3><ul class="list-disc ml-6 text-sm"><li>Temperatuurregistratie vriezers/koelingen</li><li>Bewijs toevoegen bij productie en schoonmaak</li><li>Voldoen aan branche-eisen</li></ul></div><div><h3 class="font-semibold mb-1">Voor hotels & fastfood</h3><ul class="list-disc ml-6 text-sm"><li>Personeel werkt eenvoudig via mobiel</li><li>Rapportages per afdeling/vestiging</li><li>Snelle onboarding nieuwe medewerkers</li></ul></div></div></div></section>
</main>

@include('components.footer')
</body>
</html>
