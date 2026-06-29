<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = "Logboek horeca: essentieel voor NVWA, HACCP en dagelijkse controle";
        $seoDescription = "Ontdek waarom een digitaal logboek horeca onmisbaar is voor HACCP, NVWA-inspecties en dagelijkse hygiënechecks. Praktische tips voor ondernemers.";
        $seoUrl = route('blog.logboek-horeca');
        $seoImage = asset('images/taskcheck-horeca-blog-hero.webp');
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="2026-06-29T08:00:00+02:00">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <script type="application/ld+json">
    {
      "@@context":"https://schema.org",
      "@@type":"Article",
      "headline": "Logboek horeca: waarom en hoe je grip houdt op voedselveiligheid",
      "datePublished":"2026-06-29T08:00:00+02:00",
      "author":{"@@type":"Organization","name":"TaskCheck"},
      "publisher":{"@@type":"Organization","name":"TaskCheck"},
      "mainEntityOfPage":{"@@type":"WebPage","@@id":"{{ $seoUrl }}"}
    }
    </script>
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">
@include('components.header')

<header class="border-b border-slate-200 bg-white pt-28 pb-10">
    <div class="max-w-3xl mx-auto px-6">
        <nav class="mb-5 flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('blog') }}" class="hover:text-blue-600">Blog</a>
            <span>/</span>
            <span class="text-slate-500">Horeca|Praktijk|NVWA</span>
        </nav>
        <div class="mb-4 flex flex-wrap items-center gap-3">
            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">Horeca|Praktijk|NVWA</span>
            <span class="text-xs text-slate-400">29 jun 2026 · 6 min lezen</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight text-slate-900">Logboek horeca: waarom en hoe je grip houdt op voedselveiligheid</h1>
        <p class="mt-4 text-lg text-slate-500 leading-relaxed">Een goed logboek is de ruggengraat van elke horecazaak. Van HACCP tot NVWA-inspecties: zo helpt een digitaal logboek bij dagelijkse controles en hygiëne.</p>
        <aside class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
            Bron: TaskCheck redactie
        </aside>
    </div>
</header>

<main class="max-w-3xl mx-auto px-6 py-10">
    <figure class="mb-10 overflow-hidden rounded-2xl ring-1 ring-slate-200/80">
        <img src="{{ asset('images/taskcheck-horeca-blog-hero.webp') }}" alt="Horecamedewerker voert digitale controle uit in de keuken met een tablet" class="w-full object-cover" loading="eager">
    </figure>

        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">Het belang van een logboek in de horeca</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>Voor restaurants, lunchrooms, hotels en bakkers is een logboek een onmisbaar hulpmiddel. In een logboek houd je dagelijks bij of alle controles, schoonmaakrondes en temperatuurchecks zijn uitgevoerd. Dit is niet alleen handig voor de eigen organisatie, maar ook verplicht volgens de HACCP-richtlijnen en wordt streng gecontroleerd door de NVWA.</p><p>Een actueel logboek laat direct zien hoe serieus jouw bedrijf voedselveiligheid en hygiëne neemt. Bij een onverwachte NVWA-inspectie kun je zo aantonen dat processen onder controle zijn en dat medewerkers weten wat van hen verwacht wordt.</p></div>
        </section>
        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">Wat noteer je in een horeca logboek?</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>Een horeca logboek bevat meer dan alleen schoonmaaklijsten. Denk bijvoorbeeld aan:</p><ul><li><strong>Temperatuurregistraties</strong> van koelingen en vriezers</li><li><strong>Openings- en sluitchecks</strong></li><li>Schoonmaak- en desinfectierondes</li><li>Onderhoud en reparaties</li><li>Incidenten of afwijkingen (zoals te hoge temperatuur of beschadigde verpakking)</li><li>NVWA- of interne inspecties</li></ul><p>Zo’n logboek helpt je om structureel te werken en geen stappen over te slaan, ook op drukke momenten.</p></div>
        </section>
        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">Voordelen van een digitaal logboek</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>Steeds meer horecazaken stappen over op een digitaal logboek. Dit biedt duidelijke voordelen:</p><ul><li>Altijd up-to-date en direct inzichtelijk voor het hele team</li><li>Automatische herinneringen voor taken en checks</li><li>Foto- en videobewijs toevoegen bij controles</li><li>Snelle rapportages bij inspecties of audits</li><li>Minder kans op fouten of vergeten controles</li></ul><p>Met een digitaal systeem zoals TaskCheck werk je efficiënter en kun je bij een NVWA-inspectie direct aantonen dat alles op orde is.</p></div>
        </section>
        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">Tips voor het opzetten en bijhouden van je logboek</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>Maak het logboek toegankelijk voor alle medewerkers en zorg voor duidelijke instructies. Hou het simpel: alleen registreren wat relevant is en zoveel mogelijk automatiseren. Plan vaste momenten voor controles en koppel hier meldingen aan.</p><p>Controleer regelmatig of het logboek volledig is ingevuld en bespreek afwijkingen direct in het team. Zo blijft voedselveiligheid altijd een gedeelde verantwoordelijkheid.</p></div>
        </section>

    <div class="mt-12 border-t border-slate-200 pt-8">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Meer lezen</h3>
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('seo.horeca-app') }}" class="group flex gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-blue-300">
                <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">De beste horeca app voor controles</span>
            </a>
            <a href="{{ route('blog') }}" class="group flex gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-blue-300">
                <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">Meer blogs over voedselveiligheid</span>
            </a>
        </div>
    </div>
</main>

@include('components.footer')
</body>
</html>
