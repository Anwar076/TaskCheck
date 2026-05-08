<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Checklist app voor bedrijven en teams | TaskCheck';
        $seoDescription = 'Slimme checklist app voor teams. Beheer taken, verzamel bewijs en krijg realtime inzicht. Start 14 dagen gratis met TaskCheck.';
        $seoUrl = route('seo.checklist-app-voor-bedrijven');
        $seoImage = asset('images/taskcheck-platform-overview.webp');
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
<section class="relative bg-gradient-to-br from-blue-600 via-indigo-700 to-slate-900 text-white overflow-hidden pt-28 pb-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-1/4 w-80 h-80 bg-blue-300 rounded-full -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-1/4 w-64 h-64 bg-indigo-300 rounded-full translate-y-1/3"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Checklist app bedrijven</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Checklist app voor bedrijven en teams</h1>
                <p class="mt-5 text-lg text-blue-100 leading-relaxed max-w-xl">TaskCheck is de slimme checklist app voor bedrijven die grip willen op hun werk. Beheer taken, controleer uitvoering en verzamel bewijs op één plek.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-blue-700 font-bold px-6 py-3 hover:bg-blue-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-white/30 bg-white/10 text-white font-semibold px-6 py-3 hover:bg-white/20 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-blue-200">Geen creditcard nodig · Direct starten</p>
            </div>
            <div class="mt-12 lg:mt-0">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
                    <img src="{{ asset('images/taskcheck-platform-overview.webp') }}"
                         alt="TaskCheck checklist app dashboard en mobiele app overzicht voor bedrijven"
                         class="w-full object-cover" loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div><p class="text-3xl font-extrabold text-blue-700">Eén plek</p><p class="text-sm text-slate-500 mt-1">voor alle taken en controle</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">Bewijs</p><p class="text-sm text-slate-500 mt-1">foto · video · handtekening</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">Schaalbaar</p><p class="text-sm text-slate-500 mt-1">van 5 tot 500+ medewerkers</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">€29</p><p class="text-sm text-slate-500 mt-1">per maand, gratis proberen</p></div>
    </div>
</section>

<main class="pb-20">
    <div class="max-w-6xl mx-auto px-6">

        {{-- WAT IS TASKCHECK --}}
        <section class="mt-16 lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Platform</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Wat is TaskCheck?</h2>
                <p class="mt-4 text-slate-600 leading-relaxed">TaskCheck is een digitale checklist software en taakbeheer app. Je maakt eenvoudig takenlijsten voor je team en volgt alles realtime. Geen losse papieren meer: alles digitaal, duidelijk en overzichtelijk.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">Perfect voor bedrijven in schoonmaak, logistiek, horeca, bouw en retail.</p>
                <div class="mt-6 space-y-2">
                    @foreach(['Realtime inzicht in taken en voortgang','Bewijs per taak met foto, video en handtekening','Minder fouten en betere controle','Alles op mobiel en desktop','Makkelijk in gebruik, geen training nodig'] as $item)
                    <div class="flex items-center gap-2 text-slate-700 text-sm">
                        <span class="w-5 h-5 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        {{ $item }}
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-10 lg:mt-0 grid grid-cols-2 gap-3">
                @php $features = [['📋','Takenlijsten','Maak checklists per team, locatie of shift.'],['📸','Bewijs','Foto, video of tekst als objectief bewijs.'],['👁️','Realtime','Live dashboard voor managers.'],['🔁','Herhalen','Dagelijks, wekelijks, maandelijks.'],['📍','Locaties','Meerdere objecten, één overzicht.'],['📊','Rapportage','Data voor continue verbetering.']]; @endphp
                @foreach($features as $f)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 text-center hover:border-blue-200 transition">
                    <span class="text-xl">{{ $f[0] }}</span>
                    <p class="mt-1 font-bold text-slate-900 text-xs">{{ $f[1] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $f[2] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- SECTOREN --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Sectoren</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Voor wie is deze checklist app?</h2>
                <p class="mt-3 text-slate-500">TaskCheck is gemaakt voor teams die met taken werken. Of je nu 5 of 100 medewerkers hebt.</p>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php $sectoren = [['🧹','Schoonmaak','Rondes, oplevering en kwaliteitscontroles.'],['🍽️','Horeca','Opening, mise-en-place en HACCP per shift.'],['📦','Logistiek','Ontvangst, opslag en uitgifte workflows.'],['🛒','Retail','Schapcontrole, opening en sluitprocedures.'],['🏥','Zorg','Hygiëne, medicatie en veiligheidschecks.'],['🔧','Technisch','Onderhoudsinspecties en compliance taken.']]; @endphp
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

        {{-- BEWIJS --}}
        <section class="mt-20 bg-gradient-to-br from-blue-50 to-slate-50 rounded-3xl p-8 sm:p-12">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                <div>
                    <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Bewijs & controle</span>
                    <h2 class="mt-2 text-3xl font-bold text-slate-900">Bewijs per taak</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">Laat medewerkers bewijs uploaden bij elke taak. Foto van schoonmaak, video van controle of handtekening van klant. Zo voorkom je discussies en heb je altijd objectief bewijs.</p>
                    <p class="mt-3 text-slate-600 leading-relaxed">Zeker bij klantgerichte of compliance-gevoelige processen is bewijs essentieel.</p>
                </div>
                <div class="mt-8 lg:mt-0 grid grid-cols-3 gap-4">
                    @foreach([['📷','Foto','Visueel bewijs van uitvoering.'],['🎥','Video','Korte clip als bewijs.'],['✍️','Handtekening','Klant of medewerker tekent af.']] as $b)
                    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-4 text-center">
                        <span class="text-3xl">{{ $b[0] }}</span>
                        <p class="mt-2 font-bold text-slate-900 text-sm">{{ $b[1] }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $b[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-20 text-center">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-10 sm:p-14 text-white shadow-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Start vandaag nog</h2>
                <p class="mt-3 text-lg text-blue-100 max-w-xl mx-auto">Wil je meer overzicht en minder fouten? Start vandaag met TaskCheck en probeer 14 dagen gratis.</p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-blue-700 font-bold px-8 py-4 text-lg hover:bg-blue-50 transition shadow-lg">Start 14 dagen gratis</a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center rounded-xl border-2 border-white/40 text-white font-semibold px-8 py-4 text-lg hover:bg-white/10 transition">Bekijk prijzen</a>
                </div>
                <p class="mt-4 text-sm text-blue-200">Geen verplichtingen · Geen creditcard · Direct starten</p>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mt-8 max-w-3xl mx-auto space-y-3">
                @php $faqs = [['Wat is een checklist app voor bedrijven?','Een digitale tool waarmee je takenlijsten maakt en controleert. Medewerkers vinken taken af en voegen bewijs toe zoals foto\'s, video\'s of handtekeningen.'],['Is het geschikt voor kleine bedrijven?','Ja, ook kleine teams profiteren van meer structuur en overzicht. TaskCheck is betaalbaar en schaalbaar.'],['Kan ik foto\'s toevoegen aan taken?','Ja, met TaskCheck kun je per taak foto\'s, video\'s en handtekeningen toevoegen als bewijs van uitvoering.'],['Werkt het op mobiel?','Ja, TaskCheck werkt volledig op smartphone. Medewerkers hebben geen laptop nodig.'],['Wat kost een checklist app?','TaskCheck start vanaf €29 per maand. Je kunt 14 dagen gratis proberen zonder verplichtingen.']]; @endphp
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
                    <a href="{{ route('welcome') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Homepage</a>
                    <a href="{{ route('seo.checklist-app-schoonmaak') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Checklist app schoonmaak</a>
                    <a href="{{ route('seo.horeca-checklist-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Horeca checklist app</a>
                    <a href="{{ route('seo.beste-checklist-app-2026') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Beste checklist app 2026</a>
                    <a href="{{ route('pricing') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Prijzen</a>
                </div>
            </div>
        </section>
    </div>
</main>

@include('components.footer')
</body>
</html>
