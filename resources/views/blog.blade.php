<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Blog TaskCheck – Checklist app tips voor horeca, schoonmaak en teams';
        $seoDescription = 'Lees praktische artikelen over takenlijst personeel, werkcontrole app workflows en checklist app voor bedrijven in horeca en schoonmaak.';
        $seoUrl = route('blog');
        $seoImage = asset('images/taskcheck-horeca-blog-hero.webp');
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
<body class="bg-white min-h-screen font-sans text-slate-900 antialiased">
@include('components.header')

{{-- PAGE HEADER --}}
<section class="border-b border-slate-200 bg-white pt-28 pb-12">
    <div class="max-w-6xl mx-auto px-6">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold text-blue-600 uppercase tracking-widest">Blog</p>
            <h1 class="mt-3 text-4xl sm:text-5xl font-extrabold text-slate-900 leading-tight">Praktische gidsen voor<br class="hidden sm:block"> teams en managers</h1>
            <p class="mt-4 text-lg text-slate-500 leading-relaxed">Artikelen over taakbeheer, werkcontrole en hoe bedrijven in horeca en schoonmaak dagelijks beter werken.</p>
        </div>
        <div class="mt-6 flex flex-wrap gap-2">
            @foreach(['Alle artikelen', 'Horeca', 'Schoonmaak', 'Werkcontrole'] as $tag)
            <span class="rounded-full border border-slate-200 bg-slate-50 px-4 py-1.5 text-sm font-medium text-slate-600 cursor-default">{{ $tag }}</span>
            @endforeach
        </div>
    </div>
</section>

<main class="max-w-6xl mx-auto px-6 py-14">

    {{-- FEATURED ARTICLE --}}
    <article class="group lg:grid lg:grid-cols-2 lg:gap-10 lg:items-center mb-14 pb-14 border-b border-slate-100">
        <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}" class="block overflow-hidden rounded-2xl shadow-md">
            <img src="{{ asset('images/taskcheck-horeca-blog-hero.webp') }}"
                 alt="Horeca personeel controleren met een checklist app"
                 class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="eager">
        </a>
        <div class="mt-8 lg:mt-0">
            <div class="flex items-center gap-3 mb-4">
                <span class="rounded-full bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1">Horeca</span>
                <span class="text-xs text-slate-400">8 min lezen</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-snug group-hover:text-blue-700 transition">
                <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}">Hoe horeca personeel beter te controleren met een checklist app</a>
            </h2>
            <p class="mt-3 text-slate-500 leading-relaxed">Van openingscheck tot HACCP-rondes: zo richt je een takenlijst personeel in die echt wordt uitgevoerd en waarbij je als manager altijd weet wat er speelt.</p>
            <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}"
               class="mt-5 inline-flex items-center gap-2 text-blue-700 font-semibold text-sm hover:text-blue-800 transition">
                Lees artikel
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </article>

    {{-- ARTICLE GRID --}}
    <div class="grid md:grid-cols-2 gap-8">

        <article class="group flex flex-col bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:border-slate-300 transition">
            <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}" class="block overflow-hidden">
                <img src="{{ asset('images/taskcheck-schoonmaak-blog-hero.webp') }}"
                     alt="Beste checklist app voor schoonmaakbedrijven"
                     class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-500"
                     loading="lazy">
            </a>
            <div class="p-6 flex-1 flex flex-col">
                <div class="flex items-center gap-3 mb-3">
                    <span class="rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1">Schoonmaak</span>
                    <span class="text-xs text-slate-400">6 min lezen</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900 leading-snug group-hover:text-blue-700 transition">
                    <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}">Beste checklist app voor schoonmaakbedrijven</a>
                </h2>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed flex-1">Kwaliteitscontrole per locatie met bewijs per taak en realtime inzicht voor planners en leidinggevenden.</p>
                <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}"
                   class="mt-4 inline-flex items-center gap-2 text-blue-700 font-semibold text-sm hover:text-blue-800 transition">
                    Lees artikel
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </article>

        <article class="group flex flex-col bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:border-slate-300 transition">
            <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}" class="block overflow-hidden">
                <img src="{{ asset('images/taskcheck-excel-blog-hero.webp') }}"
                     alt="Waarom bedrijven stoppen met Excel en overstappen op checklist apps"
                     class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-500"
                     loading="lazy">
            </a>
            <div class="p-6 flex-1 flex flex-col">
                <div class="flex items-center gap-3 mb-3">
                    <span class="rounded-full bg-slate-100 text-slate-600 text-xs font-semibold px-3 py-1">Algemeen</span>
                    <span class="text-xs text-slate-400">7 min lezen</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900 leading-snug group-hover:text-blue-700 transition">
                    <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}">Waarom bedrijven stoppen met Excel en overstappen op checklist apps</a>
                </h2>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed flex-1">Waarom losse spreadsheets zorgen voor fouten en hoe een werkcontrole app processen schaalbaar maakt.</p>
                <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}"
                   class="mt-4 inline-flex items-center gap-2 text-blue-700 font-semibold text-sm hover:text-blue-800 transition">
                    Lees artikel
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </article>

    </div>

    {{-- DIVIDER --}}
    <div class="my-14 border-t border-slate-100"></div>

    {{-- RELATED PAGES --}}
    <div class="mb-14">
        <h2 class="text-lg font-bold text-slate-900 mb-5">Meer lezen per onderwerp</h2>
        <div class="grid sm:grid-cols-3 gap-4">
            <a href="{{ route('seo.horeca-checklist-app') }}"
               class="group flex flex-col gap-1 rounded-2xl border border-slate-200 bg-white p-5 hover:border-blue-300 hover:bg-blue-50/30 transition">
                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Horeca</span>
                <h3 class="font-semibold text-slate-900 group-hover:text-blue-700 transition leading-snug">Horeca checklist app</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Dagelijkse controle voor restaurants, keukens en teams.</p>
            </a>
            <a href="{{ route('seo.horeca-app-personeel') }}"
               class="group flex flex-col gap-1 rounded-2xl border border-slate-200 bg-white p-5 hover:border-blue-300 hover:bg-blue-50/30 transition">
                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Horeca</span>
                <h3 class="font-semibold text-slate-900 group-hover:text-blue-700 transition leading-snug">Horeca app personeel</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Taken per shift aansturen met realtime werkcontrole.</p>
            </a>
            <a href="{{ route('seo.checklist-app-schoonmaak') }}"
               class="group flex flex-col gap-1 rounded-2xl border border-slate-200 bg-white p-5 hover:border-blue-300 hover:bg-blue-50/30 transition">
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">Schoonmaak</span>
                <h3 class="font-semibold text-slate-900 group-hover:text-blue-700 transition leading-snug">Checklist app schoonmaak</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Rondes, bewijs en rapportage per gebouw of opdrachtgever.</p>
            </a>
        </div>
    </div>

    {{-- CTA --}}
    <div class="rounded-2xl bg-slate-900 p-8 sm:p-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div>
            <h2 class="text-xl font-bold text-white">Wil je dit direct toepassen in jouw team?</h2>
            <p class="mt-1 text-slate-400 text-sm leading-relaxed max-w-lg">Start met TaskCheck en zet je eerste digitale checklist live in minuten. Inclusief bewijs, voortgang en realtime inzicht.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
            <a href="{{ route('pricing') }}"
               class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-white font-semibold text-sm hover:bg-blue-500 transition whitespace-nowrap">
                Bekijk prijzen
            </a>
            <a href="{{ route('contact') }}"
               class="inline-flex items-center justify-center rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-white font-semibold text-sm hover:bg-white/20 transition whitespace-nowrap">
                Plan een demo
            </a>
        </div>
    </div>

</main>

@include('components.footer')
</body>
</html>
