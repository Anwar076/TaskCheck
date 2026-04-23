<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Blog TaskCheck - Checklist app tips voor horeca, schoonmaak en teams';
        $seoDescription = 'Lees praktische artikelen over takenlijst personeel, werkcontrole app workflows en checklist app voor bedrijven in horeca en schoonmaak.';
        $seoUrl = route('blog');
        $seoImage = asset('logos/taskcheck-logo.png');
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
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
</head>
<body class="bg-gradient-to-br from-sky-50 via-indigo-50 to-fuchsia-50 min-h-screen font-sans text-slate-900">
    @include('components.header')

    <section class="pt-28 pb-10">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-slate-900">TaskCheck Blog: van Excel naar slimme werkcontrole</h1>
            <p class="mt-4 text-lg text-slate-600 max-w-3xl mx-auto">
                Leer hoe bedrijven met een checklist app voor bedrijven betere taakopvolging krijgen, personeel slimmer aansturen en bewijs borgen met foto en video.
            </p>
        </div>
    </section>

    <section class="pb-16">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-6">
            <article class="rounded-2xl border border-blue-100 bg-white/90 p-6 shadow-sm">
                <p class="text-xs font-semibold text-blue-700">Horeca</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900">Hoe horeca personeel beter te controleren met een checklist app</h2>
                <p class="mt-3 text-sm text-slate-600">Van openingscheck tot HACCP-rondes: zo richt je een takenlijst personeel in die echt wordt uitgevoerd.</p>
                <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}" class="mt-4 inline-flex text-blue-700 font-semibold hover:text-blue-800">Lees artikel</a>
            </article>

            <article class="rounded-2xl border border-emerald-100 bg-white/90 p-6 shadow-sm">
                <p class="text-xs font-semibold text-emerald-700">Schoonmaak</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900">Beste checklist app voor schoonmaakbedrijven</h2>
                <p class="mt-3 text-sm text-slate-600">Kwaliteitscontrole per locatie met bewijs per taak en realtime inzicht voor planners en leidinggevenden.</p>
                <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}" class="mt-4 inline-flex text-blue-700 font-semibold hover:text-blue-800">Lees artikel</a>
            </article>

            <article class="rounded-2xl border border-fuchsia-100 bg-white/90 p-6 shadow-sm">
                <p class="text-xs font-semibold text-fuchsia-700">Algemeen</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900">Waarom bedrijven stoppen met Excel en overstappen op checklist apps</h2>
                <p class="mt-3 text-sm text-slate-600">Waarom losse spreadsheets zorgen voor fouten en hoe een werkcontrole app processen schaalbaar maakt.</p>
                <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}" class="mt-4 inline-flex text-blue-700 font-semibold hover:text-blue-800">Lees artikel</a>
            </article>
        </div>
    </section>

    <section class="pb-16">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-4">
            <a href="{{ route('seo.horeca-checklist-app') }}" class="rounded-2xl border border-blue-100 bg-white/85 p-5 hover:bg-white transition">
                <h3 class="font-semibold text-slate-900">Horeca checklist app</h3>
                <p class="text-sm text-slate-600 mt-1">Lees hoe restaurants, keukens en teams dagelijks controle houden.</p>
            </a>
            <a href="{{ route('seo.horeca-app-personeel') }}" class="rounded-2xl border border-indigo-100 bg-white/85 p-5 hover:bg-white transition">
                <h3 class="font-semibold text-slate-900">Horeca app personeel</h3>
                <p class="text-sm text-slate-600 mt-1">Stuur teams per shift aan met duidelijke taken en realtime werkcontrole.</p>
            </a>
            <a href="{{ route('seo.schoonmaak-checklist-app') }}" class="rounded-2xl border border-emerald-100 bg-white/85 p-5 hover:bg-white transition">
                <h3 class="font-semibold text-slate-900">Schoonmaak checklist app</h3>
                <p class="text-sm text-slate-600 mt-1">Werk met vaste rondes, bewijs en rapportage per gebouw of opdrachtgever.</p>
            </a>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
