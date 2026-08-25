<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = "HACCP temperatuur lijsten: zo houd je voedselveiligheid op orde";
        $seoDescription = "Alles over HACCP temperatuur lijsten voor horeca: praktisch, NVWA-proof en eenvoudig digitaal bijhouden met TaskCheck.";
        $seoUrl = route('blog.haccp-temperatuur-lijsten');
        $seoImage = asset('images/blog-haccp-temperatuur-lijsten.jpg');
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
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    <script type="application/ld+json">
    {
      "@@context":"https://schema.org",
      "@@type":"Article",
      "headline": "HACCP temperatuur lijsten: essentieel voor elke horecazaak",
      "datePublished":"2026-06-29T08:00:00+02:00",
      "author":{"@@type":"Organization","name":"TaskCheck"},
      "publisher":{"@@type":"Organization","name":"TaskCheck"},
      "image": "{{ $seoImage }}",
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
        <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight text-slate-900">HACCP temperatuur lijsten: essentieel voor elke horecazaak</h1>
        <p class="mt-4 text-lg text-slate-500 leading-relaxed">Temperatuurregistratie is een van de belangrijkste onderdelen van HACCP in horeca en foodservice. Met actuele temperatuur lijsten voorkom je risico’s en voldoe je aan de eisen van de NVWA.</p>
        <aside class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
            Bron: TaskCheck redactie
        </aside>
    </div>
</header>

<main class="max-w-3xl mx-auto px-6 py-10">
    <figure class="mb-10 overflow-hidden rounded-2xl ring-1 ring-slate-200/80">
        <img src="{{ $seoImage }}" alt="Temperatuurcontrole in de horeca: een inspecteur meet de temperatuur van verse ingrediënten met een digitale thermometer" class="w-full object-cover object-center" width="830" height="553" loading="eager">
        <figcaption class="bg-slate-50 px-4 py-3 text-center text-xs text-slate-500">Temperatuurlijsten horen bij HACCP: meet, registreer en bewaar de controle — ook als bewijs bij een NVWA-inspectie.</figcaption>
    </figure>

        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">Waarom zijn HACCP temperatuur lijsten belangrijk?</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>Een correcte temperatuur van koelingen, vriezers en bereid voedsel is cruciaal voor voedselveiligheid. Bacteriën groeien snel bij te hoge temperaturen – vooral tussen 7°C en 60°C. Door dagelijks te registreren, kun je direct ingrijpen bij afwijkingen en aantonen dat je als ondernemer voedselveilig werkt.</p><p>De NVWA controleert bij inspecties altijd of temperatuur lijsten compleet, actueel en betrouwbaar zijn. Onvolledige of ontbrekende lijsten leveren risico’s én opmerkingen op tijdens een controle.</p></div>
        </section>
        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">Wat moet je precies registreren?</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>Voor de meeste horecaondernemers zijn de volgende registraties verplicht of sterk aanbevolen:</p><ul><li>Temperatuur van alle koelingen en vriezers (minimaal 1x per dag)</li><li>Kern- of serveertemperatuur van warme gerechten</li><li>Temperatuur bij ontvangst van gekoelde of diepgevroren producten</li><li>Soms: temperatuur van het vaatwasserproces</li></ul><p>Noteer altijd datum, tijd, locatie en naam van de controleur bij elke meting.</p></div>
        </section>
        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">Papier of digitaal: wat zijn de voordelen?</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>Traditioneel gebeurde temperatuurregistratie op papier. Dit is foutgevoelig en onhandig bij NVWA-inspecties. Met digitale temperatuur lijsten via TaskCheck kun je:</p><ul><li>Geen lijsten meer kwijtraken</li><li>Automatische herinneringen krijgen voor controles</li><li>Direct foto- of videobewijs toevoegen</li><li>Alle registraties veilig bewaren en snel rapporteren aan de NVWA</li></ul><p>Dat voorkomt stress én fouten.</p></div>
        </section>
        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">Direct aan de slag: tips voor betere temperatuur lijsten</h2>
            <div class="mt-3 text-slate-600 leading-relaxed"><p>Wil je als restaurant, lunchroom, hotel of bakkerij je HACCP temperatuur lijsten structureel op orde hebben? Volg deze tips:</p><ul><li>Maak temperatuurcontrole onderdeel van de openingscheck</li><li>Gebruik altijd gekalibreerde thermometers</li><li>Voer controles altijd op vaste momenten uit</li><li>Controleer ook buiten openingstijden als koelingen aan blijven staan</li><li>Laat medewerkers ondertekenen of digitaal bevestigen</li></ul><p>Met TaskCheck richt je dit snel en eenvoudig digitaal in. Vraag gratis een proefaccount aan en ontdek het gemak.</p></div>
        </section>

    <div class="mt-12 border-t border-slate-200 pt-8">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Meer lezen</h3>
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('seo.horeca-app') }}" class="group flex gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-blue-300">
                <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">Digitale horeca checklist</span>
            </a>
            <a href="{{ route('blog') }}" class="group flex gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-blue-300">
                <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">Meer praktijkblogs</span>
            </a>
        </div>
    </div>
</main>

@include('components.footer')
</body>
</html>
