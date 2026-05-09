<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Schoonmaak checklist voorbeeld – gratis template';
        $seoDescription = 'Gratis schoonmaak checklist voorbeeld voor kantoor, toilet en keuken. Praktische lijsten per ruimte en direct digitaal bijhouden met TaskCheck.';
        $seoUrl = url('/schoonmaak-checklist-voorbeeld');
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
</head>
<body class="bg-white min-h-screen font-sans text-slate-900 antialiased">
@include('components.header')

{{-- HERO --}}
<section class="relative bg-gradient-to-br from-teal-700 via-emerald-800 to-teal-900 text-white overflow-hidden pt-28 pb-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-teal-300 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-emerald-300 rounded-full translate-x-1/3 translate-y-1/3"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Gratis template</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Schoonmaak checklist voorbeeld: gratis template</h1>
                <p class="mt-5 text-lg text-teal-100 leading-relaxed max-w-xl">Zonder duidelijke schoonmaaklijst worden taken overgeslagen of dubbel gedaan. Hier vind je een praktisch voorbeeld per ruimte — direct te gebruiken of digitaal bij te houden in TaskCheck.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-teal-700 font-bold px-6 py-3 hover:bg-teal-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-white/30 bg-white/10 text-white font-semibold px-6 py-3 hover:bg-white/20 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-teal-200">Geen creditcard nodig · Direct starten</p>
            </div>
            <div class="mt-12 lg:mt-0">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
                    <img src="{{ asset('images/seo-checklist-schoonmaak-hero.png') }}"
                         alt="Schoonmaker met digitale checklist op tablet"
                         class="w-full object-cover" loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div><p class="text-3xl font-extrabold text-teal-600">Dagelijks</p><p class="text-sm text-slate-500 mt-1">dezelfde kwaliteit</p></div>
        <div><p class="text-3xl font-extrabold text-teal-600">Minder</p><p class="text-sm text-slate-500 mt-1">vergeten taken</p></div>
        <div><p class="text-3xl font-extrabold text-teal-600">Bewijs</p><p class="text-sm text-slate-500 mt-1">per taak vastgelegd</p></div>
        <div><p class="text-3xl font-extrabold text-teal-600">Mobiel</p><p class="text-sm text-slate-500 mt-1">op telefoon, geen papier</p></div>
    </div>
</section>

{{-- INTRO --}}
<section class="max-w-6xl mx-auto px-6 mt-12">
    <div class="max-w-3xl">
        <p class="text-slate-600 leading-relaxed text-lg">Schoonmaak gaat mis als er geen duidelijke lijst is. De ene medewerker denkt dat de ander het doet. Taken worden half gedaan of helemaal vergeten. Klanten en collega's merken het direct.</p>
        <p class="mt-4 text-slate-600 leading-relaxed text-lg">Een goede schoonmaak checklist geeft structuur. Elke taak staat erin, per ruimte en per frequentie. Op deze pagina vind je een gratis voorbeeld dat je direct kunt gebruiken.</p>
    </div>
</section>

<main class="pb-20">
    <div class="max-w-6xl mx-auto px-6">

        {{-- WAAROM BELANGRIJK --}}
        <section class="mt-16 lg:grid lg:grid-cols-2 lg:gap-16 lg:items-start">
            <div>
                <span class="text-teal-600 text-sm font-semibold uppercase tracking-wide">Het probleem</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Waarom een schoonmaak checklist belangrijk is</h2>
                <p class="mt-4 text-slate-600 leading-relaxed">Zonder checklist weet niemand precies wat zijn of haar taak is. De ene keer wordt de vloer gedweild, de andere keer niet. Bij een klacht kun je ook niks aantonen — er is geen bewijs van wat er gedaan is.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">Een schoonmaak checklist geeft je grip. Elke taak is duidelijk omschreven. Medewerkers weten wat er van ze verwacht wordt en managers kunnen controleren of alles gedaan is — zonder er zelf bij te staan.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">Voor bedrijven met meerdere locaties of wisselend personeel is een digitale checklist nog waardevoller. Eén standaard voor iedereen, overal.</p>
            </div>
            <div class="mt-10 lg:mt-0 space-y-3">
                @php $problemen = [
                    ['Taken worden vergeten of overgeslagen'],
                    ['Kwaliteitsverschil tussen medewerkers onderling'],
                    ['Geen bewijs bij klachten van klanten of opdrachtgevers'],
                    ['Discussie over wie wat had moeten doen'],
                    ['Papieren lijstjes raken kwijt of worden niet bijgehouden'],
                ]; @endphp
                @foreach($problemen as $p)
                <div class="flex items-start gap-3 bg-red-50 border border-red-100 rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">{{ $p[0] }}</span>
                </div>
                @endforeach
                <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm font-semibold text-emerald-800">Een vaste schoonmaak checklist lost dit direct op</span>
                </div>
            </div>
        </section>

        {{-- SCHOONMAAK CHECKLIST VOORBEELD --}}
        <section class="mt-20 bg-gradient-to-br from-teal-50 to-emerald-50 rounded-3xl p-8 sm:p-12">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-teal-600 text-sm font-semibold uppercase tracking-wide">Gratis voorbeeld</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Schoonmaak checklist voorbeeld</h2>
                <p class="mt-3 text-slate-500">Een algemene dagelijkse schoonmaaklijst die je direct kunt gebruiken als basis. Pas de taken aan op jouw situatie.</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
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

                {{-- Algemeen --}}
                <div class="bg-white rounded-2xl border border-teal-100 shadow-sm p-5">
                    <p class="text-xs font-bold text-teal-600 uppercase tracking-wide mb-3">Dagelijkse taken (algemeen)</p>
                    <ul class="space-y-1.5">
                        @foreach($algemeen as $item)
                        <li class="flex items-center gap-2.5 text-sm text-slate-700">
                            <span class="w-4 h-4 rounded border-2 border-slate-300 flex-shrink-0"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Toilet --}}
                <div class="bg-white rounded-2xl border border-teal-100 shadow-sm p-5">
                    <p class="text-xs font-bold text-teal-600 uppercase tracking-wide mb-3">Sanitair &amp; toiletten</p>
                    <ul class="space-y-1.5">
                        @foreach($toiletten as $item)
                        <li class="flex items-center gap-2.5 text-sm text-slate-700">
                            <span class="w-4 h-4 rounded border-2 border-slate-300 flex-shrink-0"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Keuken --}}
                <div class="bg-white rounded-2xl border border-teal-100 shadow-sm p-5">
                    <p class="text-xs font-bold text-teal-600 uppercase tracking-wide mb-3">Keuken &amp; pantry</p>
                    <ul class="space-y-1.5">
                        @foreach($keuken as $item)
                        <li class="flex items-center gap-2.5 text-sm text-slate-700">
                            <span class="w-4 h-4 rounded border-2 border-slate-300 flex-shrink-0"></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <p class="mt-6 text-xs text-slate-500 text-center">Dit is een basisvoorbeeld. In TaskCheck maak je je eigen checklist per ruimte, locatie en frequentie.</p>
        </section>

        {{-- CHECKLIST PER RUIMTE --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-teal-600 text-sm font-semibold uppercase tracking-wide">Per ruimte</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Checklist per ruimte: toilet, kantoor en keuken</h2>
                <p class="mt-3 text-slate-500">Elke ruimte heeft andere taken en een andere frequentie. Hier zie je de belangrijkste punten per ruimte.</p>
            </div>
            <div class="mt-10 grid sm:grid-cols-3 gap-6">

                {{-- Toilet --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-red-50 border-b border-red-100 px-5 py-4">
                        <p class="font-bold text-slate-900">Toilet</p>
                        <p class="text-xs text-slate-500 mt-0.5">Minimaal 2x per dag bij gebruik</p>
                    </div>
                    <ul class="p-5 space-y-2">
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
                            <svg class="w-4 h-4 text-teal-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Kantoor --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-blue-50 border-b border-blue-100 px-5 py-4">
                        <p class="font-bold text-slate-900">Kantoor</p>
                        <p class="text-xs text-slate-500 mt-0.5">Dagelijks of 3x per week</p>
                    </div>
                    <ul class="p-5 space-y-2">
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
                            <svg class="w-4 h-4 text-teal-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Keuken --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-amber-50 border-b border-amber-100 px-5 py-4">
                        <p class="font-bold text-slate-900">Keuken</p>
                        <p class="text-xs text-slate-500 mt-0.5">Dagelijks, koel/diepvries wekelijks</p>
                    </div>
                    <ul class="p-5 space-y-2">
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
                            <svg class="w-4 h-4 text-teal-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Frequentietabel --}}
            <div class="mt-8 bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <p class="font-bold text-slate-900">Frequentie per taak</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white border-b border-slate-200">
                            <tr>
                                <th class="text-left px-6 py-3 text-slate-600 font-semibold">Taak</th>
                                <th class="text-center px-4 py-3 text-slate-600 font-semibold">Dagelijks</th>
                                <th class="text-center px-4 py-3 text-slate-600 font-semibold">Wekelijks</th>
                                <th class="text-center px-4 py-3 text-slate-600 font-semibold">Maandelijks</th>
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
                                <td class="text-center px-4 py-3">
                                    @if($val)
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-teal-100 rounded-full">
                                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
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

        {{-- VEELGEMAAKTE FOUTEN --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-teal-600 text-sm font-semibold uppercase tracking-wide">Valkuilen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgemaakte fouten bij schoonmaakchecklists</h2>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 gap-4">
                @php $fouten = [
                    ['Taken zijn te vaag omschreven', 'Schrijf niet "toilet schoonmaken" maar "toiletpot schrobben met reiniger, bril desinfecteren, vloer dweilen". Hoe duidelijker de taak, hoe minder ruimte voor interpretatie.'],
                    ['Geen frequentie bij elke taak', 'Sommige taken zijn dagelijks, andere wekelijks. Als dat niet staat, doet iedereen het anders. Vermeld altijd hoe vaak een taak gedaan moet worden.'],
                    ['Checklist nooit bijwerken', 'Een lijst die maanden oud is, sluit niet meer aan op de praktijk. Plan elk kwartaal een korte review met je team.'],
                    ['Geen bewijs vragen', 'Bij een klacht kun je niks aantonen als er geen registratie is. Vraag medewerkers om taken af te vinken en eventueel een foto toe te voegen.'],
                    ['Papier gebruiken in plaats van digitaal', 'Papieren lijstjes raken kwijt, worden niet goed ingevuld en geven je geen overzicht. Digitaal is sneller, overzichtelijker en je kunt het overal bekijken.'],
                    ['Medewerkers niet betrekken bij het opstellen', 'Een checklist die van bovenaf is opgelegd, wordt minder nageleefd. Vraag je team welke taken ze missen of onduidelijk vinden.'],
                ]; @endphp
                @foreach($fouten as $f)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-teal-200 transition">
                    <p class="font-bold text-slate-900 text-sm mb-1.5">{{ $f[0] }}</p>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $f[1] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- HOE TASKCHECK HELPT --}}
        <section class="mt-20 bg-slate-900 rounded-3xl p-8 sm:p-12 text-white">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                <div>
                    <span class="text-teal-400 text-sm font-semibold uppercase tracking-wide">TaskCheck</span>
                    <h2 class="mt-2 text-3xl font-bold">Hoe TaskCheck helpt</h2>
                    <p class="mt-3 text-slate-300 leading-relaxed">TaskCheck is een digitale checklist app voor operationele teams. Je bouwt je eigen schoonmaakchecklist in een paar minuten, wijs hem toe aan de juiste medewerkers en zie realtime of alles afgevinkt is.</p>
                    <p class="mt-3 text-slate-300 leading-relaxed">Medewerkers werken op hun telefoon. Geen papier, geen verwarring. Bij taken waar bewijs nodig is, kunnen ze pas verder als ze een foto of notitie hebben toegevoegd. Jij als manager ziet het direct — ook als je er niet bij bent.</p>
                    <div class="mt-6">
                        <a href="{{ route('seo.checklist-app-schoonmaak') }}" class="inline-flex items-center gap-2 text-teal-300 hover:text-white text-sm font-semibold transition">
                            Meer over de checklist app voor schoonmaak
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                <div class="mt-8 lg:mt-0 grid grid-cols-2 gap-3">
                    @foreach([
                        'Checklist per ruimte of locatie',
                        'Verplicht bewijs bij kritieke taken',
                        'Live voortgang voor manager',
                        'Automatisch herhalen dagelijks',
                        'Melding bij gemiste of te late taken',
                        'Snel inwerken van nieuw personeel',
                    ] as $feature)
                    <div class="flex items-center gap-2 bg-white/10 rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-teal-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm font-medium">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- VOOR WIE --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-teal-600 text-sm font-semibold uppercase tracking-wide">Doelgroep</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Voor wie is dit geschikt</h2>
                <p class="mt-3 text-slate-500">Voor iedereen die schoonmaaktaken wil structureren en bewaken — ongeacht de branche.</p>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php $doelgroepen = [
                    ['Schoonmaakbedrijf', 'Dagelijks meerdere locaties, wisselend personeel en klanten die bewijs willen zien.'],
                    ['Kantoor en bedrijfspand', 'Eigen schoonmaakdienst of externe partij aansturen met duidelijke taakverdeling.'],
                    ['Horeca en restaurant', 'Hygiëne en HACCP-vereisten bijhouden, keuken en sanitair structureel schoonhouden.'],
                    ['Zorg en welzijn', 'Strikte hygiënenormen met verplicht bewijs per taak en locatie.'],
                    ['Retail en winkels', 'Dagelijkse schoonmaak van vloer, etalage en sanitair vastleggen per medewerker.'],
                    ['Teamleiders en managers', 'Overzicht houden op meerdere locaties zonder er zelf bij te zijn.'],
                ]; @endphp
                @foreach($doelgroepen as $d)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-teal-200 hover:shadow-md transition">
                    <p class="font-bold text-slate-900 text-sm">{{ $d[0] }}</p>
                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ $d[1] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-20 text-center">
            <div class="bg-gradient-to-r from-teal-600 to-emerald-700 rounded-3xl p-10 sm:p-14 text-white shadow-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Start vandaag</h2>
                <p class="mt-3 text-lg text-teal-100 max-w-xl mx-auto">Maak je eigen schoonmaak checklist in minuten. Geen creditcard nodig, 14 dagen gratis proberen.</p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-teal-700 font-bold px-8 py-4 text-lg hover:bg-teal-50 transition shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center rounded-xl border-2 border-white/40 text-white font-semibold px-8 py-4 text-lg hover:bg-white/10 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-teal-200">Geen verplichtingen · Geen creditcard · Vandaag live</p>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-teal-600 text-sm font-semibold uppercase tracking-wide">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mt-8 max-w-3xl mx-auto space-y-3">
                @php $faqs = [
                    ['Wat staat er in een schoonmaak checklist?', 'Een schoonmaak checklist bevat alle taken per ruimte, de frequentie en wie verantwoordelijk is. Voorbeelden zijn: vloer dweilen, prullenbakken legen, toiletten reinigen en desinfecteren, spiegels schoonmaken en oppervlakken afstoffen.'],
                    ['Hoe maak ik een schoonmaak checklist voor mijn bedrijf?', 'Begin met een lijst van alle ruimtes in je bedrijf. Schrijf daarna per ruimte de concrete taken op. Bepaal hoe vaak elke taak gedaan moet worden en wie er verantwoordelijk voor is. Gebruik dan een digitale tool zoals TaskCheck om de lijst te beheren en bij te houden.'],
                    ['Wat is het verschil tussen een dagelijkse en wekelijkse schoonmaakchecklist?', 'Een dagelijkse lijst bevat taken zoals vloer vegen, prullenbakken legen en sanitair reinigen. Een wekelijkse lijst bevat diepere taken zoals ramen wassen, koelkast reinigen en stoelen afnemen. Beide zijn nodig voor een schone werkomgeving.'],
                    ['Hoe lang duurt het om een checklist te maken in TaskCheck?', 'Je maakt een basischecklist in 5 tot 10 minuten. Je voegt taken toe, stelt frequenties in en bepaalt welke taken bewijs vereisen. Daarna staat de checklist klaar voor je medewerkers.'],
                    ['Kan ik de checklist per locatie instellen?', 'Ja. In TaskCheck maak je aparte checklists per locatie, ruimte of medewerker. Zo heeft elke vestiging zijn eigen standaard zonder dat je alles opnieuw hoeft in te stellen.'],
                ]; @endphp
                @foreach($faqs as $faq)
                <details class="group bg-white border border-slate-200 rounded-2xl px-6 py-4 cursor-pointer hover:border-teal-200 transition">
                    <summary class="flex justify-between items-center font-semibold text-slate-900 list-none">
                        {{ $faq[0] }}
                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0 group-open:rotate-45 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </summary>
                    <p class="mt-3 text-slate-600 leading-relaxed text-sm">{{ $faq[1] }}</p>
                </details>
                @endforeach
            </div>
        </section>

        {{-- INTERNE LINKS --}}
        <section class="mt-16 mb-4">
            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6">
                <p class="font-semibold text-slate-900 mb-3">Gerelateerde pagina's</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('seo.checklist-app-schoonmaak') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Checklist app schoonmaak</a>
                    <a href="{{ route('seo.werkcontrole-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Werkcontrole app</a>
                    <a href="{{ route('seo.horeca-checklist-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Horeca checklist app</a>
                    <a href="{{ route('seo.takenlijst-personeel') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Takenlijst personeel</a>
                    <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Blog: beste checklist app schoonmaak</a>
                </div>
            </div>
        </section>

    </div>
</main>

@include('components.footer')
</body>
</html>
