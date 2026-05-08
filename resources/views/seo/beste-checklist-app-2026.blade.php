<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Beste checklist app 2026 | Vergelijking + top keuze | TaskCheck';
        $seoDescription = 'Wat is de beste checklist app in 2026? Vergelijk de beste tools en ontdek waarom TaskCheck de beste keuze is voor bedrijven.';
        $seoUrl = route('seo.beste-checklist-app-2026');
        $seoImage = asset('images/taskcheck-dashboard-hero.webp');
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
<section class="relative bg-gradient-to-br from-violet-700 via-purple-700 to-indigo-800 text-white overflow-hidden pt-28 pb-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-violet-300 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-indigo-300 rounded-full translate-x-1/3 translate-y-1/3"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Vergelijking 2026</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Beste checklist app voor bedrijven (2026)</h1>
                <p class="mt-5 text-lg text-violet-100 leading-relaxed max-w-xl">Op zoek naar de beste checklist app voor jouw bedrijf? In deze vergelijking zie je de sterkste opties van 2026 en waarom TaskCheck bovenaan staat.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-violet-700 font-bold px-6 py-3 hover:bg-violet-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start met TaskCheck
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-white/30 bg-white/10 text-white font-semibold px-6 py-3 hover:bg-white/20 transition">
                        Probeer 14 dagen gratis
                    </a>
                </div>
            </div>
            <div class="mt-12 lg:mt-0">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
                    <img src="{{ asset('images/taskcheck-dashboard-hero.webp') }}"
                         alt="TaskCheck dashboard – beste checklist app voor bedrijven 2026"
                         class="w-full object-cover" loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div><p class="text-3xl font-extrabold text-violet-700">#1</p><p class="text-sm text-slate-500 mt-1">beste keuze 2026</p></div>
        <div><p class="text-3xl font-extrabold text-violet-700">Bewijs</p><p class="text-sm text-slate-500 mt-1">foto · video · handtekening</p></div>
        <div><p class="text-3xl font-extrabold text-violet-700">Realtime</p><p class="text-sm text-slate-500 mt-1">inzicht per team</p></div>
        <div><p class="text-3xl font-extrabold text-violet-700">14 dagen</p><p class="text-sm text-slate-500 mt-1">gratis proberen</p></div>
    </div>
</section>

<main class="pb-20">
    <div class="max-w-6xl mx-auto px-6">

        {{-- CRITERIA --}}
        <section class="mt-16">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide">Criteria</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Waar moet een goede checklist app aan voldoen?</h2>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php $criteria = [['📱','Mobiel werken','Medewerkers werken op hun telefoon, zonder laptop.'],['👁️','Realtime inzicht','Manager ziet direct wat open staat en wat af is.'],['📸','Bewijs per taak','Foto, video, tekst of handtekening als objectief bewijs.'],['🔁','Herhalende taken','Dagelijkse, wekelijkse of maandelijkse planning.'],['📍','Meerdere locaties','Beheer tientallen objecten vanuit één dashboard.'],['⚡','Eenvoudig starten','Geen complexe implementatie, direct aan de slag.']]; @endphp
                @foreach($criteria as $c)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center hover:border-violet-200 hover:shadow-md transition">
                    <span class="text-2xl">{{ $c[0] }}</span>
                    <h3 class="mt-2 font-bold text-slate-900 text-sm">{{ $c[1] }}</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $c[2] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- VERGELIJKING --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide">Vergelijking</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Top checklist apps in 2026</h2>
            </div>
            <div class="mt-8 space-y-4">
                <div class="bg-white rounded-2xl border-2 border-violet-400 shadow-md p-6 relative overflow-hidden">
                    <div class="absolute top-4 right-4 bg-violet-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">Beste keuze</div>
                    <div class="flex items-start gap-4">
                        <span class="text-3xl">🏆</span>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-slate-900">1. TaskCheck</h3>
                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">Moderne checklist app voor bedrijven met bewijs per taak, realtime inzicht, herhalende taken en een simpele workflow. Ideaal voor horeca, schoonmaak, facilitair en logistiek.</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach(['Bewijs per taak','Realtime dashboard','Meerdere locaties','Mobiel + desktop','14 dagen gratis'] as $tag)
                                <span class="bg-violet-100 text-violet-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-start gap-4">
                        <span class="text-3xl">2️⃣</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">iAuditor (SafetyCulture)</h3>
                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">Sterk voor inspecties en audits met veel templates. Minder simpel in gebruik en kan duur zijn voor kleine teams.</p>
                            <div class="mt-2 flex gap-2 flex-wrap">
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-0.5 rounded-full">+ Veel templates</span>
                                <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-0.5 rounded-full">– Hogere prijs</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-start gap-4">
                        <span class="text-3xl">3️⃣</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Jotform Checklist</h3>
                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">Flexibel met veel integraties, maar minder gericht op teamuitvoering en realtime controle. Goed voor eenvoudige formulieren.</p>
                            <div class="mt-2 flex gap-2 flex-wrap">
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-0.5 rounded-full">+ Flexibel</span>
                                <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-0.5 rounded-full">– Minder teamgericht</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- VOOR WIE --}}
        <section class="mt-20 bg-slate-900 rounded-3xl p-8 sm:p-12 text-white">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                <div>
                    <span class="text-violet-400 text-sm font-semibold uppercase tracking-wide">Doelgroep</span>
                    <h2 class="mt-2 text-3xl font-bold">Voor wie is TaskCheck geschikt?</h2>
                    <p class="mt-3 text-slate-300 leading-relaxed">Voor bedrijven die werk willen controleren, bewijs nodig hebben en tijd willen besparen. Sterk in schoonmaak, horeca, logistiek en facilitair.</p>
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-slate-400 mb-2">Prijzen</p>
                        <p class="text-slate-300">Vanaf <span class="text-white font-bold">€29 per maand</span> met 14 dagen gratis proefperiode.</p>
                    </div>
                </div>
                <div class="mt-8 lg:mt-0 grid grid-cols-2 gap-3">
                    @foreach(['Schoonmaakbedrijven','Horeca & restaurants','Logistieke teams','Facilitair managers','Retail ketens','Bouw & technisch'] as $t)
                    <div class="flex items-center gap-2 bg-white/10 rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-violet-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm font-medium">{{ $t }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-20 text-center">
            <div class="bg-gradient-to-r from-violet-600 to-indigo-600 rounded-3xl p-10 sm:p-14 text-white shadow-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Probeer de beste checklist app van 2026</h2>
                <p class="mt-3 text-lg text-violet-100 max-w-xl mx-auto">14 dagen gratis, geen creditcard, direct starten.</p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-violet-700 font-bold px-8 py-4 text-lg hover:bg-violet-50 transition shadow-lg">Start met TaskCheck</a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center rounded-xl border-2 border-white/40 text-white font-semibold px-8 py-4 text-lg hover:bg-white/10 transition">Bekijk prijzen</a>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mt-8 max-w-3xl mx-auto space-y-3">
                @php $faqs = [['Wat is de beste checklist app in 2026?','Dat hangt af van je behoeften. Voor bedrijven die realtime inzicht, bewijs per taak en meerdere locaties nodig hebben, is TaskCheck de sterkste keuze.'],['Zijn er gratis checklist apps?','Sommige apps bieden een gratis basis, maar die hebben beperkte functies. TaskCheck biedt een volledige 14-daagse gratis proefperiode.'],['Welke checklist app is het beste voor kleine bedrijven?','TaskCheck is geschikt voor teams van 2 tot 100+ medewerkers. De interface is eenvoudig en je betaalt pas na de proefperiode.'],['Kan ik een checklist app koppelen aan meerdere locaties?','Ja. TaskCheck ondersteunt meerdere locaties vanuit één dashboard, elk met eigen checklists en voortgang.']]; @endphp
                @foreach($faqs as $faq)
                <details class="group bg-white border border-slate-200 rounded-2xl px-6 py-4 cursor-pointer hover:border-violet-200 transition">
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
                <p class="font-semibold text-slate-900 mb-3">Gerelateerde pagina's</p>
                <div class="flex flex-wrap gap-2">
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
