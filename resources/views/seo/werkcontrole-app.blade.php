<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Werkcontrole app voor teams en locaties | TaskCheck';
        $seoDescription = 'Werkcontrole app voor bedrijven: realtime taken, controle op uitvoering en bewijs per taak. Geschikt voor horeca, schoonmaak en meer.';
        $seoUrl = route('seo.werkcontrole-app');
        $seoImage = asset('images/seo-werkcontrole-hero.png');
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
<section class="relative bg-gradient-to-br from-indigo-700 via-blue-700 to-blue-900 text-white overflow-hidden pt-28 pb-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-300 rounded-full translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-blue-300 rounded-full -translate-x-1/3 translate-y-1/3"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Werkcontrole app</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Werkcontrole app voor dagelijkse operationele processen</h1>
                <p class="mt-5 text-lg text-blue-100 leading-relaxed max-w-xl">Geen losse lijsten meer in Excel of op papier. Met TaskCheck weet je altijd wat je team doet, wat af is en waar aandacht nodig is. Realtime, per locatie.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-blue-700 font-bold px-6 py-3 hover:bg-blue-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-white/30 bg-white/10 text-white font-semibold px-6 py-3 hover:bg-white/20 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-blue-200">Geen creditcard nodig · 14 dagen gratis proberen</p>
            </div>
            <div class="mt-12 lg:mt-0">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
                    <img src="{{ asset('images/seo-werkcontrole-hero.png') }}"
                         alt="Werkcontrole app – manager met tablet controleert taakvoortgang van team in real-time"
                         class="w-full object-cover" loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div><p class="text-3xl font-extrabold text-blue-700">Realtime</p><p class="text-sm text-slate-500 mt-1">voortgang per locatie</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">Bewijs</p><p class="text-sm text-slate-500 mt-1">foto · video · tekst</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">14 dagen</p><p class="text-sm text-slate-500 mt-1">gratis proberen</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">Mobiel</p><p class="text-sm text-slate-500 mt-1">werkt op elke telefoon</p></div>
    </div>
</section>

<main class="pb-20">
    <div class="max-w-6xl mx-auto px-6">

        {{-- WAT IS HET --}}
        <section class="mt-16 lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Uitleg</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Wat is een werkcontrole app?</h2>
                <p class="mt-4 text-slate-600 leading-relaxed">Een werkcontrole app is software waarmee je dagelijkse taken plant, uitvoert en controleert. In plaats van losse lijsten in Excel of papier werk je met digitale workflows. Teams zien hun taken realtime, managers zien voortgang en afwijkingen direct.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">Voor bedrijven met meerdere medewerkers of locaties is dit essentieel. Zonder centrale werkcontrole ontstaan fouten, kwaliteitsverschillen en extra herstelwerk.</p>
            </div>
            <div class="mt-10 lg:mt-0 grid grid-cols-2 gap-3">
                @php $wats = [['📋','Taken plannen','Maak checklists per team, locatie of shift.'],['👁️','Live volgen','Zie direct wat open staat of aandacht vraagt.'],['📸','Bewijs vragen','Foto of tekst per taak als objectief bewijs.'],['📊','Patronen zien','Data over uitvoering voor continue verbetering.']]; @endphp
                @foreach($wats as $w)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 text-center hover:border-blue-200 transition">
                    <span class="text-2xl">{{ $w[0] }}</span>
                    <p class="mt-2 font-bold text-slate-900 text-sm">{{ $w[1] }}</p>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $w[2] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- VOORDELEN --}}
        <section class="mt-20 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-8 sm:p-12">
            <div class="text-center max-w-2xl mx-auto mb-8">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Voordelen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Kernvoordelen voor operationele teams</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php $voordelen = [['⚡','Realtime zicht','Direct zien welke taken openstaan, afgerond zijn of aandacht vragen.'],['🔒','Betrouwbaar bewijs','Foto, video of tekst per taak voor objectieve controle.'],['🤫','Minder ruis','Heldere rollen en taken verminderen mondelinge overdracht.'],['📍','Schaalbaar','Templates en herhaalplanning voor meerdere teams en locaties.'],['⏱️','Sneller bijsturen','Inzicht zonder te hoeven bellen of mailen.'],['📈','Continue verbetering','Data over patronen gebruikt voor gerichte coaching.']]; @endphp
                @foreach($voordelen as $v)
                <div class="bg-white rounded-2xl border border-white shadow-sm p-5 flex gap-3">
                    <span class="text-xl flex-shrink-0">{{ $v[0] }}</span>
                    <div>
                        <p class="font-semibold text-slate-900 text-sm">{{ $v[1] }}</p>
                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $v[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- SECTOREN --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Sectoren</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Voor welke bedrijven werkt het?</h2>
                <p class="mt-3 text-slate-500">Overal waar kwaliteit dagelijks wordt uitgevoerd en gecontroleerd.</p>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php $sectoren = [['🍽️','Horeca','Opening, mise-en-place, service en sluitroutines.'],['🧹','Schoonmaak','Rondes, oplevering en kwaliteitscontrole per locatie.'],['🏭','Facilitair','Inspecties, onderhoud en veiligheidscontroles.'],['📦','Logistiek','Ontvangst, opslag en uitgifte workflows.'],['🛒','Retail','Opening, schapcontrole en sluitprocedures.'],['🔧','Technisch','Onderhoudsinspecties en compliance taken.']]; @endphp
                @foreach($sectoren as $s)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex gap-4 items-start hover:border-blue-200 hover:shadow-md transition">
                    <span class="text-2xl">{{ $s[0] }}</span>
                    <div>
                        <h3 class="font-bold text-slate-900">{{ $s[1] }}</h3>
                        <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">{{ $s[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-20 text-center">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-3xl p-10 sm:p-14 text-white shadow-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Van taakbeheer naar continue verbetering</h2>
                <p class="mt-3 text-lg text-blue-100 max-w-xl mx-auto">Door data over uitvoering te verzamelen zie je patronen. Die inzichten gebruik je om processen te verbeteren. Zo groeit TaskCheck mee van operationele basis naar strategisch stuurinstrument.</p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-blue-700 font-bold px-8 py-4 text-lg hover:bg-blue-50 transition shadow-lg">
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center rounded-xl border-2 border-white/40 text-white font-semibold px-8 py-4 text-lg hover:bg-white/10 transition">
                        Bekijk prijzen
                    </a>
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
                @php $faqs = [['Wat is een werkcontrole app precies?','Een werkcontrole app is software waarmee je dagelijkse taken digitaal plant, uitvoert en controleert. Teams zien hun taken in realtime, managers zien voortgang direct.'],['Voor welke sectoren is dit geschikt?','Horeca, schoonmaak, facilitair, logistiek, retail en technisch onderhoud. Overal waar kwaliteit dagelijks uitgevoerd en gecontroleerd moet worden.'],['Kan ik bewijs opvragen per taak?','Ja. Je kunt per taak foto, video of tekstbewijs verplicht stellen. Dat geeft objectieve controle en voorkomt discussies.'],['Werkt het ook voor meerdere locaties?','Zeker. Elke locatie heeft zijn eigen checklists en voortgang. Je beheert alles vanuit één centraal dashboard.']]; @endphp
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
                <p class="font-semibold text-slate-900 mb-3">Gerelateerde pagina's</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('seo.takenlijst-personeel') }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Takenlijst personeel</a>
                    <a href="{{ route('seo.horeca-checklist-app') }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Horeca checklist app</a>
                    <a href="{{ route('seo.checklist-app-schoonmaak') }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Checklist app schoonmaak</a>
                    <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Blog: stoppen met Excel</a>
                </div>
            </div>
        </section>
    </div>
</main>

@include('components.footer')
</body>
</html>
