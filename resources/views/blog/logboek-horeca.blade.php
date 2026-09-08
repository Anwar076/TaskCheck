@php
    $seoTitle = "Logboek horeca: essentieel voor NVWA, HACCP en dagelijkse controle";
    $seoDescription = "Ontdek waarom een digitaal logboek horeca onmisbaar is voor HACCP, NVWA-inspecties en dagelijkse hygiënechecks. Praktische tips voor ondernemers.";
    $seoUrl = route('blog.logboek-horeca');
    $seoImage = asset('images/blog-logboek-horeca.jpg');
    $publishedAt = '2026-06-29T08:00:00+02:00';
    $ctaHeading = 'Zet je horeca-logboek digitaal';
    $ctaLead = 'Registreer controles, bewijs en hygiënerondes in minuten. 14 dagen gratis, geen creditcard nodig.';
@endphp

@extends('layouts.blog-article')

@push('head')
<script type="application/ld+json">
{
  "@@context":"https://schema.org",
  "@@type":"Article",
  "headline": "Logboek horeca: waarom en hoe je grip houdt op voedselveiligheid",
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
    <h1 class="fade-up delay-1 text-3xl font-extrabold leading-[1.08] tracking-[-.045em] text-slate-900 sm:text-4xl lg:text-5xl">Logboek horeca: waarom en hoe je grip houdt op voedselveiligheid</h1>
    <p class="fade-up delay-2 mt-4 text-lg leading-relaxed text-slate-500">Een goed logboek is de ruggengraat van elke horecazaak. Van HACCP tot NVWA-inspecties: zo helpt een digitaal logboek bij dagelijkse controles en hygiëne.</p>
    <aside class="blog-aside fade-up delay-2">Bron: TaskCheck redactie</aside>
@endsection

@section('content')
    <figure class="blog-figure mb-10 fade-up">
        <img src="{{ $seoImage }}" alt="Kok vult een horeca-logboek in aan de RVS-werkbank in de keuken" width="1024" height="682" loading="eager">
        <figcaption>Een actueel logboek maakt temperatuurchecks, schoonmaak en inspecties aantoonbaar voor het team én de NVWA.</figcaption>
    </figure>

    <article class="prose-article fade-up">
        <h2>Het belang van een logboek in de horeca</h2>
        <p>Voor restaurants, lunchrooms, hotels en bakkers is een logboek een onmisbaar hulpmiddel. In een logboek houd je dagelijks bij of alle controles, schoonmaakrondes en temperatuurchecks zijn uitgevoerd. Dit is niet alleen handig voor de eigen organisatie, maar ook verplicht volgens de HACCP-richtlijnen en wordt streng gecontroleerd door de NVWA.</p>
        <p>Een actueel logboek laat direct zien hoe serieus jouw bedrijf voedselveiligheid en hygiëne neemt. Bij een onverwachte NVWA-inspectie kun je zo aantonen dat processen onder controle zijn en dat medewerkers weten wat van hen verwacht wordt.</p>

        <h2>Wat noteer je in een horeca logboek?</h2>
        <p>Een horeca logboek bevat meer dan alleen schoonmaaklijsten. Denk bijvoorbeeld aan:</p>
        <ul>
            <li><strong>Temperatuurregistraties</strong> van koelingen en vriezers</li>
            <li><strong>Openings- en sluitchecks</strong></li>
            <li>Schoonmaak- en desinfectierondes</li>
            <li>Onderhoud en reparaties</li>
            <li>Incidenten of afwijkingen (zoals te hoge temperatuur of beschadigde verpakking)</li>
            <li>NVWA- of interne inspecties</li>
        </ul>
        <p>Zo’n logboek helpt je om structureel te werken en geen stappen over te slaan, ook op drukke momenten.</p>

        <h2>Voordelen van een digitaal logboek</h2>
        <p>Steeds meer horecazaken stappen over op een digitaal logboek. Dit biedt duidelijke voordelen:</p>
        <ul>
            <li>Altijd up-to-date en direct inzichtelijk voor het hele team</li>
            <li>Automatische herinneringen voor taken en checks</li>
            <li>Foto- en videobewijs toevoegen bij controles</li>
            <li>Snelle rapportages bij inspecties of audits</li>
            <li>Minder kans op fouten of vergeten controles</li>
        </ul>
        <p>Met een digitaal systeem zoals TaskCheck werk je efficiënter en kun je bij een NVWA-inspectie direct aantonen dat alles op orde is.</p>

        <h2>Tips voor het opzetten en bijhouden van je logboek</h2>
        <p>Maak het logboek toegankelijk voor alle medewerkers en zorg voor duidelijke instructies. Hou het simpel: alleen registreren wat relevant is en zoveel mogelijk automatiseren. Plan vaste momenten voor controles en koppel hier meldingen aan.</p>
        <p>Controleer regelmatig of het logboek volledig is ingevuld en bespreek afwijkingen direct in het team. Zo blijft voedselveiligheid altijd een gedeelde verantwoordelijkheid.</p>
    </article>

    <div class="mt-14 fade-up">
        <p class="blog-kicker">Meer lezen</p>
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <a href="{{ route('seo.horeca-app') }}" class="blog-readmore group">
                <div>
                    <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Horeca</span>
                    <p class="mt-2 text-sm font-extrabold text-slate-900 transition group-hover:text-blue-700">De beste horeca app voor controles</p>
                </div>
            </a>
            <a href="{{ route('blog') }}" class="blog-readmore group">
                <div>
                    <span class="blog-tag">Blog</span>
                    <p class="mt-2 text-sm font-extrabold text-slate-900 transition group-hover:text-blue-700">Meer blogs over voedselveiligheid</p>
                </div>
            </a>
        </div>
    </div>
@endsection
