<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Wat is een checklist app? Uitleg + voordelen | TaskCheck';
        $seoDescription = 'Wat is een checklist app en hoe werkt het? Ontdek de voordelen voor bedrijven en teams. Start gratis met TaskCheck.';
        $seoUrl = route('seo.wat-is-een-checklist-app');
        $seoImage = asset('images/seo-wat-is-checklist-app-hero.png');
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
</head>
<body class="bg-white min-h-screen font-sans text-slate-900 antialiased">
@include('components.header')

{{-- HERO --}}
<section class="relative bg-gradient-to-br from-sky-600 via-blue-700 to-indigo-800 text-white overflow-hidden pt-28 pb-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/2 left-0 w-80 h-80 bg-sky-300 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-300 rounded-full translate-x-1/3 -translate-y-1/3"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Uitleg</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Wat is een checklist app?</h1>
                <p class="mt-5 text-lg text-sky-100 leading-relaxed max-w-xl">Een checklist app is een digitale tool waarmee bedrijven takenlijsten maken, beheren en controleren. Medewerkers vinken taken af en voegen bewijs toe. Eenvoudig, overzichtelijk, mobiel.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-blue-700 font-bold px-6 py-3 hover:bg-blue-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-white/30 bg-white/10 text-white font-semibold px-6 py-3 hover:bg-white/20 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-sky-200">Geen creditcard nodig · Gratis proberen</p>
            </div>
            <div class="mt-12 lg:mt-0">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
                    <img src="{{ asset('images/seo-wat-is-checklist-app-hero.png') }}"
                         alt="Wat is een checklist app – uitleg met mobiel, bewijs, planning en team"
                         class="w-full object-cover" loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div><p class="text-3xl font-extrabold text-blue-700">Digitaal</p><p class="text-sm text-slate-500 mt-1">geen papier of Excel meer</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">Bewijs</p><p class="text-sm text-slate-500 mt-1">foto · video · handtekening</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">Realtime</p><p class="text-sm text-slate-500 mt-1">inzicht voor manager</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">Mobiel</p><p class="text-sm text-slate-500 mt-1">werkt op elke telefoon</p></div>
    </div>
</section>

<main class="pb-20">
    <div class="max-w-6xl mx-auto px-6">

        {{-- HOE WERKT HET --}}
        <section class="mt-16">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Uitleg</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Hoe werkt een checklist app?</h2>
                <p class="mt-3 text-slate-500">In vier stappen van taak naar bewijs.</p>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php $stappen = [['1','Takenlijst maken','Maak een checklist met taken, instructies en bewijs regels.'],['2','Toewijzen aan team','Medewerkers krijgen hun persoonlijke takenlijst op mobiel.'],['3','Uitvoeren en afvinken','Taken worden afgevinkt, bewijs toegevoegd.'],['4','Manager controleert','Realtime inzicht, bijsturen waar nodig.']]; @endphp
                @foreach($stappen as $s)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center hover:border-blue-200 transition">
                    <span class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-blue-600 text-white font-bold text-sm">{{ $s[0] }}</span>
                    <h3 class="mt-3 font-bold text-slate-900 text-sm">{{ $s[1] }}</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $s[2] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- WAARVOOR GEBRUIKT --}}
        <section class="mt-20 lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Toepassingen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Waar wordt een checklist app voor gebruikt?</h2>
                <p class="mt-4 text-slate-600 leading-relaxed">Checklist apps worden gebruikt in schoonmaak, horeca, logistiek, bouw en retail. Overal waar taken gecontroleerd moeten worden, helpt checklist software om structuur te houden.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">Steeds meer bedrijven stappen over van papier naar digitale checklists, omdat het sneller, duidelijker en beter controleerbaar is.</p>
            </div>
            <div class="mt-10 lg:mt-0 grid grid-cols-2 gap-3">
                @foreach(['🧹 Schoonmaak','🍽️ Horeca','📦 Logistiek','🏗️ Bouw','🛒 Retail','🏥 Zorg','🔧 Technisch','✈️ Facilitair'] as $sector)
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm px-4 py-3 text-sm font-medium text-slate-700 text-center hover:border-blue-200 transition">{{ $sector }}</div>
                @endforeach
            </div>
        </section>

        {{-- WAT MAAKT TASKCHECK ANDERS --}}
        <section class="mt-20 bg-gradient-to-br from-sky-50 to-blue-50 rounded-3xl p-8 sm:p-12">
            <div class="text-center max-w-2xl mx-auto mb-8">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">TaskCheck</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Wat maakt TaskCheck anders?</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php $punten = [['📸','Bewijs per taak','Foto, video of handtekening voorkomt discussies.'],['👁️','Realtime inzicht','Live dashboard voor managers, geen gebel.'],['📍','Per locatie','Meerdere objecten, één overzicht.'],['🔁','Herhalend','Dagelijks, wekelijks, maandelijks automatisch.'],['📊','Rapportage','Data voor continue verbetering.'],['📱','100% mobiel','Werkt op elke smartphone.']]; @endphp
                @foreach($punten as $p)
                <div class="bg-white rounded-2xl border border-white shadow-sm p-5 flex gap-3">
                    <span class="text-xl flex-shrink-0">{{ $p[0] }}</span>
                    <div>
                        <p class="font-semibold text-slate-900 text-sm">{{ $p[1] }}</p>
                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $p[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-20 text-center">
            <div class="bg-gradient-to-r from-sky-600 to-blue-700 rounded-3xl p-10 sm:p-14 text-white shadow-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Start met TaskCheck</h2>
                <p class="mt-3 text-lg text-sky-100 max-w-xl mx-auto">Wil je minder fouten en meer overzicht? Probeer TaskCheck 14 dagen gratis. Geen creditcard nodig.</p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-blue-700 font-bold px-8 py-4 text-lg hover:bg-blue-50 transition shadow-lg">Start 14 dagen gratis</a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center rounded-xl border-2 border-white/40 text-white font-semibold px-8 py-4 text-lg hover:bg-white/10 transition">Bekijk prijzen</a>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mt-8 max-w-3xl mx-auto space-y-3">
                @php $faqs = [['Wat is een checklist app?','Een checklist app is een digitale tool waarmee je takenlijsten maakt en controleert. Medewerkers kunnen taken afvinken en bewijs uploaden zoals foto\'s, video\'s of handtekeningen.'],['Waarom een checklist app gebruiken?','Omdat je meer overzicht hebt, fouten voorkomt en werk beter kunt controleren dan met papier of Excel.'],['Is een checklist app geschikt voor kleine bedrijven?','Ja, ook kleine teams profiteren van meer structuur en overzicht. TaskCheck is betaalbaar en direct te gebruiken.'],['Kan ik foto\'s toevoegen aan taken?','Ja, met TaskCheck kun je per taak foto\'s, video\'s en handtekeningen toevoegen als bewijs.'],['Werkt een checklist app op mobiel?','Ja, TaskCheck werkt volledig op smartphone en desktop. Medewerkers werken op hun telefoon.'],['Wat kost een checklist app?','TaskCheck start vanaf €29 per maand. Je kunt gratis proberen zonder verplichtingen.']]; @endphp
                @foreach($faqs as $faq)
                <details class="group bg-white border border-slate-200 rounded-2xl px-6 py-4 cursor-pointer hover:border-blue-200 transition">
                    <summary class="flex justify-between items-center font-semibold text-slate-900 list-none">
                        {{ $faq[0] }}
                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0 group-open:rotate-45 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </summary>
                    <p class="mt-3 text-slate-600 leading-relaxed text-sm">{{ $faq[1] }}</p>
                </details>
                @endforeach
            </div>
        </section>

        {{-- INTERNE LINKS --}}
        <section class="mt-16 mb-4">
            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6">
                <p class="font-semibold text-slate-900 mb-3">Handige pagina's</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('welcome') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Homepage</a>
                    <a href="{{ route('seo.checklist-app-voor-bedrijven') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Checklist app voor bedrijven</a>
                    <a href="{{ route('seo.checklist-app-schoonmaak') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Checklist app schoonmaak</a>
                    <a href="{{ route('seo.horeca-checklist-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Horeca checklist app</a>
                    <a href="{{ route('pricing') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Prijzen</a>
                </div>
            </div>
        </section>
    </div>
</main>

@include('components.footer')
</body>
</html>
