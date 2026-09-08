@php
    $seoTitle = "HACCP temperatuur lijsten: zo houd je voedselveiligheid op orde";
    $seoDescription = "Alles over HACCP temperatuur lijsten voor horeca: praktisch, NVWA-proof en eenvoudig digitaal bijhouden met TaskCheck.";
    $seoUrl = route('blog.haccp-temperatuur-lijsten');
    $seoImage = asset('images/blog-haccp-temperatuur-lijsten.jpg');
    $publishedAt = '2026-06-29T08:00:00+02:00';
    $ctaHeading = 'Temperatuurregistratie zonder losse lijsten';
    $ctaLead = 'Meet, registreer en bewaar controles digitaal. 14 dagen gratis, geen creditcard nodig.';
@endphp

@extends('layouts.blog-article')

@push('head')
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
@endpush

@section('hero')
    <nav class="fade-up mb-5 flex items-center gap-2 text-xs text-slate-400">
        <a href="{{ route('blog') }}" class="transition hover:text-blue-600">Blog</a>
        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-500">Horeca</span>
    </nav>
    <div class="fade-up delay-1 mb-4 flex flex-wrap items-center gap-2">
        <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>Horeca</span>
        <span class="blog-tag">Praktijk</span>
        <span class="blog-tag">NVWA</span>
        <span class="text-xs font-medium text-slate-400">29 jun 2026 · 6 min lezen</span>
    </div>
    <h1 class="fade-up delay-1 text-3xl font-extrabold leading-[1.08] tracking-[-.045em] text-slate-900 sm:text-4xl lg:text-5xl">HACCP temperatuur lijsten: essentieel voor elke horecazaak</h1>
    <p class="fade-up delay-2 mt-4 text-lg leading-relaxed text-slate-500">Temperatuurregistratie is een van de belangrijkste onderdelen van HACCP in horeca en foodservice. Met actuele temperatuur lijsten voorkom je risico’s en voldoe je aan de eisen van de NVWA.</p>
    <aside class="blog-aside fade-up delay-2">Bron: TaskCheck redactie</aside>
@endsection

@section('content')
    <figure class="blog-figure mb-10 fade-up">
        <img src="{{ $seoImage }}" alt="Temperatuurcontrole in de horeca: een inspecteur meet de temperatuur van verse ingrediënten met een digitale thermometer" width="830" height="553" loading="eager">
        <figcaption>Temperatuurlijsten horen bij HACCP: meet, registreer en bewaar de controle — ook als bewijs bij een NVWA-inspectie.</figcaption>
    </figure>

    <article class="prose-article fade-up">
        <h2>Waarom zijn HACCP temperatuur lijsten belangrijk?</h2>
        <p>Een correcte temperatuur van koelingen, vriezers en bereid voedsel is cruciaal voor voedselveiligheid. Bacteriën groeien snel bij te hoge temperaturen – vooral tussen 7°C en 60°C. Door dagelijks te registreren, kun je direct ingrijpen bij afwijkingen en aantonen dat je als ondernemer voedselveilig werkt.</p>
        <p>De NVWA controleert bij inspecties altijd of temperatuur lijsten compleet, actueel en betrouwbaar zijn. Onvolledige of ontbrekende lijsten leveren risico’s én opmerkingen op tijdens een controle.</p>

        <h2>Wat moet je precies registreren?</h2>
        <p>Voor de meeste horecaondernemers zijn de volgende registraties verplicht of sterk aanbevolen:</p>
        <ul>
            <li>Temperatuur van alle koelingen en vriezers (minimaal 1x per dag)</li>
            <li>Kern- of serveertemperatuur van warme gerechten</li>
            <li>Temperatuur bij ontvangst van gekoelde of diepgevroren producten</li>
            <li>Soms: temperatuur van het vaatwasserproces</li>
        </ul>
        <p>Noteer altijd datum, tijd, locatie en naam van de controleur bij elke meting.</p>

        <h2>Papier of digitaal: wat zijn de voordelen?</h2>
        <p>Traditioneel gebeurde temperatuurregistratie op papier. Dit is foutgevoelig en onhandig bij NVWA-inspecties. Met digitale temperatuur lijsten via TaskCheck kun je:</p>
        <ul>
            <li>Geen lijsten meer kwijtraken</li>
            <li>Automatische herinneringen krijgen voor controles</li>
            <li>Direct foto- of videobewijs toevoegen</li>
            <li>Alle registraties veilig bewaren en snel rapporteren aan de NVWA</li>
        </ul>
        <p>Dat voorkomt stress én fouten.</p>

        <h2>Direct aan de slag: tips voor betere temperatuur lijsten</h2>
        <p>Wil je als restaurant, lunchroom, hotel of bakkerij je HACCP temperatuur lijsten structureel op orde hebben? Volg deze tips:</p>
        <ul>
            <li>Maak temperatuurcontrole onderdeel van de openingscheck</li>
            <li>Gebruik altijd gekalibreerde thermometers</li>
            <li>Voer controles altijd op vaste momenten uit</li>
            <li>Controleer ook buiten openingstijden als koelingen aan blijven staan</li>
            <li>Laat medewerkers ondertekenen of digitaal bevestigen</li>
        </ul>
        <p>Met TaskCheck richt je dit snel en eenvoudig digitaal in. Vraag gratis een proefaccount aan en ontdek het gemak.</p>
    </article>

    <div class="mt-14 fade-up">
        <p class="blog-kicker">Meer lezen</p>
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <a href="{{ route('seo.horeca-app') }}" class="blog-readmore group">
                <div>
                    <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Horeca</span>
                    <p class="mt-2 text-sm font-extrabold text-slate-900 transition group-hover:text-blue-700">Digitale horeca checklist</p>
                </div>
            </a>
            <a href="{{ route('blog') }}" class="blog-readmore group">
                <div>
                    <span class="blog-tag">Blog</span>
                    <p class="mt-2 text-sm font-extrabold text-slate-900 transition group-hover:text-blue-700">Meer praktijkblogs</p>
                </div>
            </a>
        </div>
    </div>
@endsection
