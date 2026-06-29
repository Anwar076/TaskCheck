<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = "HACCP lijsten: onmisbaar voor elke horecazaak | TaskCheck";
        $seoDescription = "Lees hoe HACCP lijsten bijdragen aan voedselveiligheid, NVWA-controle en soepelere dagelijkse processen in de horeca.";
        $seoUrl = route('blog.haccp-lijsten');
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
      "headline": "HACCP lijsten voor horeca: grip op voedselveiligheid en controle",
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
        <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight text-slate-900">HACCP lijsten voor horeca: grip op voedselveiligheid en controle</h1>
        <p class="mt-4 text-lg text-slate-500 leading-relaxed">HACCP lijsten zijn essentieel voor restaurants, lunchrooms, hotels en andere horecabedrijven. Ze helpen je aan de NVWA-eisen te voldoen en houden voedselveiligheid structureel op orde.</p>
        <aside class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
            Bron: TaskCheck redactie
        </aside>
    </div>
</header>

<main class="max-w-3xl mx-auto px-6 py-10">
    <figure class="mb-10 overflow-hidden rounded-2xl ring-1 ring-slate-200/80">
        <img src="{{ asset('images/taskcheck-horeca-blog-hero.webp') }}" alt="Digitale HACCP checklist op tablet in restaurantkeuken" class="w-full object-cover" loading="eager">
    </figure>

        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">Wat is een HACCP lijst en waarom heb je die nodig?</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>Een HACCP lijst is een controlelijst waarmee je structureel alle kritische punten voor voedselveiligheid controleert. Denk aan temperatuurmetingen, schoonmaakacties en de controle van ingrediënten.</p><p>Met een goed ingevulde HACCP lijst toon je aan dat je werkt volgens de wettelijke eisen. Dit is belangrijk bij een inspectie van de NVWA. Daarnaast helpt het jou en je medewerkers om dagelijkse routines te waarborgen en risico’s te verkleinen.</p></div>
        </section>
        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">Praktische voordelen voor jouw horecabedrijf</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>Digitale HACCP lijsten besparen tijd en voorkomen fouten. Medewerkers weten precies wat er van ze verwacht wordt. Taken als temperatuurregistratie, schoonmaakcontroles en sluitrondes worden niet vergeten.</p><p>Bovendien kun je met digitale tools, zoals TaskCheck, eenvoudig foto- of videobewijs toevoegen en automatisch rapportages genereren. Zo is de administratie altijd op orde en ben je voorbereid op een NVWA-controle.</p></div>
        </section>
        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">HACCP lijsten in de dagelijkse praktijk</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>Voor restaurants, lunchrooms, fastfoodzaken, hotels, bakkerijen en slagerijen zijn HACCP lijsten onmisbaar. Ze ondersteunen bij het vastleggen van de juiste werkwijze rond onder andere ontvangst van goederen, temperatuurcontroles, reiniging en allergenenbeheer.</p><p>Met heldere checklists kunnen medewerkers zelfstandig controles uitvoeren en eventuele afwijkingen snel melden. Zo wordt voedselveiligheid een vast onderdeel van de werkdag.</p></div>
        </section>
        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">HACCP lijsten en de NVWA: altijd voorbereid</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>De NVWA verwacht dat je kunt aantonen dat je volgens de HACCP principes werkt. Tijdens een inspectie vraagt de inspecteur vaak direct naar je controlelijsten en registraties.</p><p>Met een digitaal platform als TaskCheck heb je alle lijsten, controles en bewijsmateriaal overzichtelijk op één plek. Zo kun je bij een controle direct laten zien dat je processen op orde zijn en minimaliseer je het risico op opmerkingen of boetes.</p></div>
        </section>

    <div class="mt-12 border-t border-slate-200 pt-8">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Meer lezen</h3>
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('seo.horeca-app') }}" class="group flex gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-blue-300">
                <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">Horeca checklist app</span>
            </a>
            <a href="{{ route('blog') }}" class="group flex gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-blue-300">
                <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">Alle blogartikelen</span>
            </a>
        </div>
    </div>
</main>

@include('components.footer')
</body>
</html>
