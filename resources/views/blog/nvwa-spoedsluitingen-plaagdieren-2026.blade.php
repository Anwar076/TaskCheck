<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'NVWA: recordaantal spoedsluitingen door plaagdieren (begin 2026) | TaskCheck Blog';
        $seoDescription = 'De NVWA sloot in de eerste weken van 2026 22 winkels en horecagelegenheden vanwege plaagdieroverlast — vaak muizen en ratten. Wat betekent dit voor voedselveiligheid?';
        $seoUrl = route('blog.nvwa-spoedsluitingen-plaagdieren-2026');
        $seoImage = asset('images/blog-nvwa-plaagdier-situatie.png');
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="2026-02-13T11:24:00+01:00">
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
</head>
<body class="min-h-screen bg-white font-sans text-slate-900 antialiased">
@include('components.header')

<header class="border-b border-slate-200 bg-white pt-28 pb-10">
    <div class="max-w-3xl mx-auto px-6">
        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-5">
            <a href="{{ route('blog') }}" class="hover:text-blue-600 transition">Blog</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-500">Voedselveiligheid</span>
        </nav>
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <span class="rounded-full bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1">Nieuws</span>
            <span class="text-xs text-slate-400">13 feb 2026 · ca. 4 min lezen</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">NVWA: begin 2026 recordaantal spoedsluitingen door plaagdieren</h1>
        <p class="mt-4 text-lg text-slate-500 leading-relaxed">In de eerste weken van 2026 sloot de NVWA fors meer horeca- en winkellocaties tijdelijk vanwege plaagdieroverlast dan in dezelfde periode een jaar eerder. Wat zijn de lessen voor jouw bedrijf?</p>

        <aside class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 leading-relaxed">
            <p class="font-semibold text-slate-800">Bron</p>
            <p class="mt-1">Dit artikel is gebaseerd op een openbaar nieuwsbericht van de <a href="https://www.nvwa.nl/" class="font-semibold text-blue-600 underline underline-offset-2 hover:text-blue-800" rel="noopener noreferrer" target="_blank">Nederlandse Voedsel- en Warenautoriteit (NVWA)</a>. Voor actuele officiële informatie, waarschuwingen en inspectieresultaten verwijzen we je rechtstreeks naar de website van de NVWA.</p>
        </aside>
    </div>
</header>

<div class="max-w-3xl mx-auto px-6 py-10">

    <figure class="mb-10">
        <div class="relative overflow-hidden rounded-2xl shadow-md ring-1 ring-slate-200/80">
            <img src="{{ asset('images/blog-nvwa-plaagdier-situatie.png') }}"
                 alt="Verwaarloosde ruimte met leidingen en zichtbare muizen- of rattenkeutels; voorbeeld van ernstige plaagdieroverlast in een bedrijfsomgeving (foto NVWA)"
                 class="w-full max-h-[min(28rem,70vh)] object-cover object-center sm:max-h-[min(32rem,75vh)]"
                 loading="eager">
            <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/45 to-transparent px-4 pb-4 pt-20 sm:px-5 sm:pb-5 sm:pt-24">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-white/95 sm:text-sm">Afbeelding van NVWA</p>
            </div>
        </div>
        <figcaption class="mt-3 text-center text-xs text-slate-500">Illustratie: omstandigheden waarbij de NVWA kan ingrijpen — bron NVWA.</figcaption>
    </figure>

    <article class="prose-article">

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
            <p>Plaagdierbeheer en hygiëne horen vast te zitten in je dagelijkse routines: denk aan schoonmaak na sluiting, correcte opslag, afvalstromen en vastlegging van controles. Met vaste digitale checklists en bewijs per ronde maak je aantoonbaar dat je het proces serieus neemt — handig bij interne kwaliteit en als je moet verantwoorden wat er wél gedaan wordt om risico’s te beperken.</p>
        </div>

        <h2>Meer informatie bij de NVWA</h2>
        <p>Consumenten en bedrijven kunnen contact opnemen met het Klantcontactcentrum van de NVWA. Journalisten kunnen voor vragen over dit soort nieuwsberichten terecht bij de persvoorlichters van de NVWA — zie <a href="https://www.nvwa.nl/" rel="noopener noreferrer" target="_blank">nvwa.nl</a> voor actuele contactgegevens en publicaties.</p>

        <p>Zoals altijd: bij twijfel voor interpretatie van regelgeving of maatregelen is de NVWA of een gespecialiseerde adviseur leidend; dit blogartikel is geen juridisch advies.</p>

    </article>

    <div class="border-t border-slate-100 my-12"></div>

    <div class="rounded-2xl bg-slate-900 p-7 sm:p-9 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div>
            <p class="text-lg font-bold text-white">Checklists en werkcontrole in jouw team?</p>
            <p class="mt-1 text-sm text-slate-400 leading-relaxed max-w-md">Rondes, HACCP-achtige controles en bewijs per taak — TaskCheck helpt je om het vast te leggen.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-white font-semibold text-sm hover:bg-blue-500 transition whitespace-nowrap">Start 14 dagen gratis</a>
            <a href="{{ route('seo.horeca-checklist-app') }}" class="inline-flex items-center justify-center rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-white font-semibold text-sm hover:bg-white/20 transition whitespace-nowrap">Horeca checklist app</a>
        </div>
    </div>

    <div class="mt-12">
        <p class="text-sm font-semibold text-slate-500 uppercase tracking-widest mb-5">Meer lezen</p>
        <div class="grid sm:grid-cols-2 gap-5">
            <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}"
               class="group flex gap-4 rounded-2xl border border-slate-200 bg-white p-4 hover:border-blue-300 hover:shadow-sm transition">
                <img src="{{ asset('images/taskcheck-horeca-blog-hero.webp') }}" alt="" class="w-20 h-16 rounded-xl object-cover flex-shrink-0">
                <div>
                    <span class="text-xs font-semibold text-blue-600">Horeca</span>
                    <p class="mt-0.5 text-sm font-semibold text-slate-900 group-hover:text-blue-700 transition leading-snug">Horeca personeel controleren met een checklist app</p>
                </div>
            </a>
            <a href="{{ route('blog.waarom-horeca-stopt-met-papieren-checklists') }}"
               class="group flex gap-4 rounded-2xl border border-slate-200 bg-white p-4 hover:border-blue-300 hover:shadow-sm transition">
                <img src="{{ asset('images/taskcheck-horeca-blog-hero.webp') }}" alt="" class="w-20 h-16 rounded-xl object-cover flex-shrink-0">
                <div>
                    <span class="text-xs font-semibold text-orange-600">Horeca</span>
                    <p class="mt-0.5 text-sm font-semibold text-slate-900 group-hover:text-blue-700 transition leading-snug">Stoppen met papieren checklists</p>
                </div>
            </a>
        </div>
    </div>

</div>

<style>
.prose-article h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin-top: 2.5rem;
    margin-bottom: 0.75rem;
    padding-left: 0.75rem;
    border-left: 3px solid #2563eb;
    line-height: 1.35;
}
.prose-article h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin-top: 1.75rem;
    margin-bottom: 0.5rem;
}
.prose-article p {
    font-size: 1rem;
    line-height: 1.8;
    color: #475569;
    margin-bottom: 1rem;
}
.prose-article ul {
    list-style: none;
    padding: 0;
    margin: 1rem 0 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.prose-article ul li {
    display: flex;
    align-items: flex-start;
    gap: 0.625rem;
    font-size: 0.9375rem;
    color: #475569;
    line-height: 1.6;
}
.prose-article ul li::before {
    content: '';
    display: inline-block;
    width: 0.375rem;
    height: 0.375rem;
    background: #2563eb;
    border-radius: 50%;
    margin-top: 0.6rem;
    flex-shrink: 0;
}
.prose-article a {
    color: #2563eb;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.prose-article a:hover { color: #1d4ed8; }
.prose-article .callout {
    background: #f0f9ff;
    border-left: 3px solid #0ea5e9;
    border-radius: 0.75rem;
    padding: 1rem 1.25rem;
    margin: 1.5rem 0;
    color: #475569;
    font-size: 0.9375rem;
    line-height: 1.7;
}
</style>

@include('components.footer')
</body>
</html>
