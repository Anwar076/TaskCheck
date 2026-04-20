<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Schoonmaak checklist app voor kwaliteitscontrole | TaskCheck';
        $seoDescription = 'Schoonmaak checklist app voor bedrijven: werkcontrole per locatie, takenlijst personeel en bewijs met foto/video. Plan een proefperiode.';
        $seoUrl = route('seo.schoonmaak-checklist-app');
        $seoImage = asset('icons/taskcheck-logo.png');
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
<body class="bg-gradient-to-br from-sky-50 via-indigo-50 to-fuchsia-50 min-h-screen font-sans text-slate-900">
@include('components.header')
<main class="pt-28 pb-16">
    <div class="max-w-5xl mx-auto px-6">
        <h1 class="text-4xl sm:text-5xl font-bold text-slate-900">Schoonmaak checklist app met bewijs en realtime controle</h1>
        <p class="mt-4 text-lg text-slate-600">TaskCheck helpt schoonmaakbedrijven om taken zichtbaar te maken, kwaliteit te borgen en opdrachtgevers professioneel te rapporteren.</p>

        <div class="mt-8 grid md:grid-cols-2 gap-4">
            <a href="{{ route('pricing') }}" class="inline-flex justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold px-6 py-3 hover:from-blue-700 hover:to-indigo-700">Bekijk prijzen</a>
            <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl border border-blue-200 bg-white text-slate-700 font-semibold px-6 py-3 hover:bg-blue-50">Start 30 dagen gratis</a>
        </div>

        <section class="mt-10 space-y-5 text-slate-700 leading-7">
            <h2 class="text-2xl font-bold text-slate-900">Betere kwaliteitscontrole per locatie</h2>
            <p>In schoonmaakorganisaties is kwaliteit vaak verspreid over meerdere teams en locaties. Met papieren lijsten of losse Excel-bestanden is het lastig om consistent te blijven. TaskCheck maakt per locatie duidelijke checklists met vaste taken, frequenties en bewijsregels. Zo werkt elk team volgens dezelfde standaard.</p>
            <p>Je ziet in realtime welke rondes zijn afgerond, waar taken nog openstaan en waar bewijs ontbreekt. Daardoor kun je sneller bijsturen en voorkom je klachten of herstelwerk.</p>

            <h2 class="text-2xl font-bold text-slate-900">Takenlijst personeel die echt gebruikt wordt</h2>
            <p>Een goede takenlijst personeel moet eenvoudig zijn op mobiel. Medewerkers willen niet zoeken in tabbladen of lange documenten. Met TaskCheck krijgen ze per dienst een overzichtelijke lijst met concrete acties. Per taak kun je checkitems toevoegen, prioriteit aangeven en instructies opnemen.</p>
            <p>Voor teamleiders betekent dat minder controle achteraf en meer grip tijdens de uitvoering. Afwijkingen worden direct zichtbaar, zodat je meteen kunt corrigeren of herplannen.</p>

            <h2 class="text-2xl font-bold text-slate-900">Bewijs voor klanten en audits</h2>
            <p>Opdrachtgevers willen aantoonbare kwaliteit. Daarom kun je in TaskCheck foto-, video- of tekstbewijs koppelen aan taken. Bij discussies heb je direct inzicht in wat er op welk moment is uitgevoerd. Dat maakt communicatie professioneler en versterkt vertrouwen in je dienstverlening.</p>
            <p>Rapportages worden ook eenvoudiger: je hebt alle data centraal en hoeft niet handmatig dossiers samen te stellen uit losse bronnen.</p>

            <h2 class="text-2xl font-bold text-slate-900">Schaalbaar voor groeiende schoonmaakbedrijven</h2>
            <p>Of je nu één team hebt of tientallen locaties: met templates en herhaalbare workflows schaal je processen zonder kwaliteitsverlies. Nieuwe medewerkers werken sneller in en teamleiders houden overzicht zonder extra administratie. Dat maakt TaskCheck een praktische checklist app voor bedrijven die willen groeien met controle.</p>
            <p>Wil je meer context? Lees ook onze pagina over <a class="text-blue-700 font-semibold" href="{{ route('seo.takenlijst-personeel') }}">takenlijst personeel</a> en het blog over de <a class="text-blue-700 font-semibold" href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}">beste checklist app voor schoonmaakbedrijven</a>.</p>
        </section>
    </div>
</main>
@include('components.footer')
</body>
</html>
