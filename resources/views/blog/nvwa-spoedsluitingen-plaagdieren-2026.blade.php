@php
    $seoTitle = 'NVWA: recordaantal spoedsluitingen door plaagdieren (begin 2026) | TaskCheck Blog';
    $seoDescription = 'De NVWA sloot in de eerste weken van 2026 22 winkels en horecagelegenheden vanwege plaagdieroverlast — vaak muizen en ratten. Wat betekent dit voor voedselveiligheid?';
    $seoUrl = route('blog.nvwa-spoedsluitingen-plaagdieren-2026');
    $seoImage = asset('images/blog-nvwa-plaagdier-situatie.png');
    $publishedAt = '2026-02-13T11:24:00+01:00';
    $ctaHeading = 'Checklists en werkcontrole in jouw team?';
    $ctaLead = 'Rondes, HACCP-achtige controles en bewijs per taak — TaskCheck helpt je om het vast te leggen.';
@endphp

@extends('layouts.blog-article')

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Article",
    "headline": "NVWA: begin 2026 recordaantal spoedsluitingen door plaagdieren",
    "datePublished": "2026-02-13T11:24:00+01:00",
    "author": { "@@type": "Organization", "name": "TaskCheck" },
    "publisher": { "@@type": "Organization", "name": "TaskCheck" },
    "description": "{{ $seoDescription }}",
    "mainEntityOfPage": { "@@type": "WebPage", "@@id": "{{ $seoUrl }}" }
}
</script>
@endpush

@section('hero')
    <nav class="fade-up mb-5 flex items-center gap-2 text-xs text-slate-400">
        <a href="{{ route('blog') }}" class="transition hover:text-blue-600">Blog</a>
        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-500">Voedselveiligheid</span>
    </nav>
    <div class="fade-up delay-1 mb-4 flex flex-wrap items-center gap-2">
        <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>Nieuws</span>
        <span class="text-xs font-medium text-slate-400">13 feb 2026 · ca. 4 min lezen</span>
    </div>
    <h1 class="fade-up delay-1 text-3xl font-extrabold leading-[1.08] tracking-[-.045em] text-slate-900 sm:text-4xl lg:text-5xl">NVWA: begin 2026 recordaantal spoedsluitingen door plaagdieren</h1>
    <p class="fade-up delay-2 mt-4 text-lg leading-relaxed text-slate-500">In de eerste weken van 2026 sloot de NVWA fors meer horeca- en winkellocaties tijdelijk vanwege plaagdieroverlast dan in dezelfde periode een jaar eerder. Wat zijn de lessen voor jouw bedrijf?</p>
    <aside class="blog-aside fade-up delay-2">
        <p class="font-semibold text-slate-800">Bron</p>
        <p class="mt-1">Dit artikel is gebaseerd op een openbaar nieuwsbericht van de <a href="https://www.nvwa.nl/" class="font-semibold text-blue-600 underline underline-offset-2 hover:text-blue-800" rel="noopener noreferrer" target="_blank">Nederlandse Voedsel- en Warenautoriteit (NVWA)</a>. Voor actuele officiële informatie, waarschuwingen en inspectieresultaten verwijzen we je rechtstreeks naar de website van de NVWA.</p>
    </aside>
@endsection

@section('content')
    <figure class="blog-figure mb-10 fade-up">
        <div class="relative">
            <img src="{{ asset('images/blog-nvwa-plaagdier-situatie.png') }}"
                 alt="Verwaarloosde ruimte met leidingen en zichtbare muizen- of rattenkeutels; voorbeeld van ernstige plaagdieroverlast in een bedrijfsomgeving (foto NVWA)"
                 class="w-full max-h-[min(28rem,70vh)] object-cover object-center sm:max-h-[min(32rem,75vh)]"
                 loading="eager">
            <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/45 to-transparent px-4 pb-4 pt-20 sm:px-5 sm:pb-5 sm:pt-24">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-white/95 sm:text-sm">Afbeelding van NVWA</p>
            </div>
        </div>
        <figcaption>Illustratie: omstandigheden waarbij de NVWA kan ingrijpen — bron NVWA.</figcaption>
    </figure>

    <article class="prose-article fade-up">

        <p>In de eerste zeven weken van 2026 heeft de Nederlandse Voedsel- en Warenautoriteit (NVWA) <strong>22 winkels en horecagelegenheden</strong> gesloten vanwege plaagdieroverlast. In dezelfde periode vorig jaar moesten <strong>7 locaties</strong> tijdelijk dicht van de NVWA. Bij het overgrote deel ging het om een muizenplaag; in een aantal gevallen ook om overlast van ratten. Bijna de helft van de spoedsluitingen waren in Amsterdam.</p>

        <h2>Wat is een spoedsluiting?</h2>
        <p>Als inspecteurs van de NVWA ernstige risico’s voor de voedselveiligheid constateren, kunnen zij een bedrijf met spoed tijdelijk sluiten. Een spoedsluiting is één van de zwaarste maatregelen die de NVWA kan opleggen. Ernstige overlast door plaagdieren is een reden om dat te doen.</p>
        <p>Muizen en andere plaagdieren dragen bacteriën en ziektes mee die ze kunnen verspreiden via hun uitwerpselen. Een bedrijf mag pas weer open wanneer:</p>
        <ul>
            <li>Alle besmette voedsel is verwijderd,</li>
            <li>Het pand grondig is gereinigd,</li>
            <li>Er effectieve maatregelen zijn om plaagdieren te bestrijden en buiten te houden.</li>
        </ul>
        <p>De NVWA voert vervolgens een herinspectie uit.</p>

        <h2>Voorlichting en trends</h2>
        <p>Het aantal spoedsluitingen neemt al jaren toe. De NVWA zet naast handhaving steeds meer in op voorlichting. In 2026 organiseert de toezichthouder als proef speciale bijeenkomsten over plaagdierproblematiek in enkele steden, voor alle ondernemers in die stad. De eerste bijeenkomst in Leiden werd onlangs goed bezocht.</p>
        <p>Ondernemers krijgen praktische tips over onder meer het voorkomen van plaagdieren, hygiëne in de keuken, opslag van levensmiddelen, voorraadbeheer en afvalverwerking. Ook de rol van de gemeente, samenwerking bij plaagdierbestrijding, de taken van een plaagdierbestrijder en het toezicht door de NVWA komen aan bod.</p>

        <h2>Melden</h2>
        <p>Consumenten die muizen, ratten, duiven, kakkerlakken, vliegen of andere plaagdieren aantreffen in een horecagelegenheid of levensmiddelenbedrijf, kunnen dit melden bij de NVWA — onder andere via <strong>0900-03 88</strong> (gebruikelijke belkosten) of via het online formulier op de website van de <a href="https://www.nvwa.nl/" rel="noopener noreferrer" target="_blank">NVWA</a>.</p>
        <p>Wie wil zien hoe een bedrijf is beoordeeld tijdens een inspectie, kan de openbare inspectieresultaten van de NVWA raadplegen.</p>

        <div class="callout">
            <p class="font-semibold text-slate-900 mb-1">Wat betekent dit voor jouw operationeel proces?</p>
            <p>Plaagdierbeheer en hygiëne horen vast te zitten in je dagelijkse routines: denk aan schoonmaak na sluiting, correcte opslag, afvalstromen en vastlegging van controles. Met vaste <a href="{{ route('seo.restaurant-checklist-app') }}">digitale checklists</a>, <a href="{{ route('seo.haccp-formulieren') }}">HACCP formulieren</a> en bewijs per ronde maak je aantoonbaar dat je het proces serieus neemt — handig bij interne kwaliteit en als je moet verantwoorden wat er wél gedaan wordt om risico’s te beperken.</p>
        </div>

        <h2>Meer informatie bij de NVWA</h2>
        <p>Consumenten en bedrijven kunnen contact opnemen met het Klantcontactcentrum van de NVWA. Journalisten kunnen voor vragen over dit soort nieuwsberichten terecht bij de persvoorlichters van de NVWA — zie <a href="https://www.nvwa.nl/" rel="noopener noreferrer" target="_blank">nvwa.nl</a> voor actuele contactgegevens en publicaties.</p>

        <p>Zoals altijd: bij twijfel voor interpretatie van regelgeving of maatregelen is de NVWA of een gespecialiseerde adviseur leidend; dit blogartikel is geen juridisch advies. Lees ook onze pagina's over de <a href="{{ route('seo.horeca-app') }}">horeca app</a> en <a href="{{ route('seo.opening-checklist-horeca') }}">opening checklist horeca</a>.</p>

    </article>

    @include('components.blog-related-solutions', [
        'solutions' => [
            ['Horeca App', 'Hygiëne, checklists en werkcontrole voor horeca.', 'seo.horeca-app'],
            ['HACCP Formulieren', 'Digitale registratie voor voedselveiligheid.', 'seo.haccp-formulieren'],
            ['Restaurant Checklist App', 'Opening, sluiting en hygiënerondes vastleggen.', 'seo.restaurant-checklist-app'],
        ],
    ])

    <div class="mt-14 fade-up">
        <p class="blog-kicker">Meer lezen</p>
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}" class="blog-readmore group">
                <img src="{{ asset('images/taskcheck-horeca-blog-hero.webp') }}" alt="" class="h-16 w-20 shrink-0 rounded-xl object-cover">
                <div>
                    <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Horeca</span>
                    <p class="mt-2 text-sm font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700">Horeca personeel controleren met een checklist app</p>
                </div>
            </a>
            <a href="{{ route('blog.waarom-horeca-stopt-met-papieren-checklists') }}" class="blog-readmore group">
                <img src="{{ asset('images/blog-waarom-horeca-stopt-met-papieren-checklists.jpg') }}" alt="" class="h-16 w-20 shrink-0 rounded-xl object-cover">
                <div>
                    <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>Horeca</span>
                    <p class="mt-2 text-sm font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700">Stoppen met papieren checklists</p>
                </div>
            </a>
        </div>
    </div>
@endsection
