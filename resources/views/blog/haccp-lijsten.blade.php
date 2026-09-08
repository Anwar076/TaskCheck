@php
    $seoTitle = "HACCP lijsten: onmisbaar voor elke horecazaak | TaskCheck";
    $seoDescription = "Lees hoe HACCP lijsten bijdragen aan voedselveiligheid, NVWA-controle en soepelere dagelijkse processen in de horeca.";
    $seoUrl = route('blog.haccp-lijsten');
    $seoImage = asset('images/blog-haccp-lijsten.jpg');
    $publishedAt = '2026-06-29T08:00:00+02:00';
    $ctaHeading = 'HACCP-lijsten altijd op orde';
    $ctaLead = 'Digitale checklists, bewijs per taak en rapportages voor inspecties. Start 14 dagen gratis.';
@endphp

@extends('layouts.blog-article')

@push('head')
<script type="application/ld+json">
{
  "@@context":"https://schema.org",
  "@@type":"Article",
  "headline": "HACCP lijsten voor horeca: grip op voedselveiligheid en controle",
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
    <h1 class="fade-up delay-1 text-3xl font-extrabold leading-[1.08] tracking-[-.045em] text-slate-900 sm:text-4xl lg:text-5xl">HACCP lijsten voor horeca: grip op voedselveiligheid en controle</h1>
    <p class="fade-up delay-2 mt-4 text-lg leading-relaxed text-slate-500">HACCP lijsten zijn essentieel voor restaurants, lunchrooms, hotels en andere horecabedrijven. Ze helpen je aan de NVWA-eisen te voldoen en houden voedselveiligheid structureel op orde.</p>
    <aside class="blog-aside fade-up delay-2">Bron: TaskCheck redactie</aside>
@endsection

@section('content')
    <figure class="blog-figure mb-10 fade-up">
        <img src="{{ $seoImage }}" alt="Kok vinkt een digitale HACCP-checklist af op een tablet in de horecakeuken" width="1024" height="682" loading="eager">
        <figcaption>Digitale HACCP-lijsten maken opening, temperatuur, schoonmaak en voorraadcontrole zichtbaar voor het hele team.</figcaption>
    </figure>

    <article class="prose-article fade-up">
        <h2>Wat is een HACCP lijst en waarom heb je die nodig?</h2>
        <p>Een HACCP lijst is een controlelijst waarmee je structureel alle kritische punten voor voedselveiligheid controleert. Denk aan temperatuurmetingen, schoonmaakacties en de controle van ingrediënten.</p>
        <p>Met een goed ingevulde HACCP lijst toon je aan dat je werkt volgens de wettelijke eisen. Dit is belangrijk bij een inspectie van de NVWA. Daarnaast helpt het jou en je medewerkers om dagelijkse routines te waarborgen en risico’s te verkleinen.</p>

        <h2>Praktische voordelen voor jouw horecabedrijf</h2>
        <p>Digitale HACCP lijsten besparen tijd en voorkomen fouten. Medewerkers weten precies wat er van ze verwacht wordt. Taken als temperatuurregistratie, schoonmaakcontroles en sluitrondes worden niet vergeten.</p>
        <p>Bovendien kun je met digitale tools, zoals TaskCheck, eenvoudig foto- of videobewijs toevoegen en automatisch rapportages genereren. Zo is de administratie altijd op orde en ben je voorbereid op een NVWA-controle.</p>

        <h2>HACCP lijsten in de dagelijkse praktijk</h2>
        <p>Voor restaurants, lunchrooms, fastfoodzaken, hotels, bakkerijen en slagerijen zijn HACCP lijsten onmisbaar. Ze ondersteunen bij het vastleggen van de juiste werkwijze rond onder andere ontvangst van goederen, temperatuurcontroles, reiniging en allergenenbeheer.</p>
        <p>Met heldere checklists kunnen medewerkers zelfstandig controles uitvoeren en eventuele afwijkingen snel melden. Zo wordt voedselveiligheid een vast onderdeel van de werkdag.</p>

        <h2>HACCP lijsten en de NVWA: altijd voorbereid</h2>
        <p>De NVWA verwacht dat je kunt aantonen dat je volgens de HACCP principes werkt. Tijdens een inspectie vraagt de inspecteur vaak direct naar je controlelijsten en registraties.</p>
        <p>Met een digitaal platform als TaskCheck heb je alle lijsten, controles en bewijsmateriaal overzichtelijk op één plek. Zo kun je bij een controle direct laten zien dat je processen op orde zijn en minimaliseer je het risico op opmerkingen of boetes.</p>
    </article>

    <div class="mt-14 fade-up">
        <p class="blog-kicker">Meer lezen</p>
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <a href="{{ route('seo.horeca-app') }}" class="blog-readmore group">
                <div>
                    <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Horeca</span>
                    <p class="mt-2 text-sm font-extrabold text-slate-900 transition group-hover:text-blue-700">Horeca checklist app</p>
                </div>
            </a>
            <a href="{{ route('blog') }}" class="blog-readmore group">
                <div>
                    <span class="blog-tag">Blog</span>
                    <p class="mt-2 text-sm font-extrabold text-slate-900 transition group-hover:text-blue-700">Alle blogartikelen</p>
                </div>
            </a>
        </div>
    </div>
@endsection
