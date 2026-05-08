<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Takenlijst personeel app voor bedrijven | TaskCheck';
        $seoDescription = 'Maak een duidelijke takenlijst personeel met bewijs, deadlines en controle. Ideaal voor horeca, schoonmaak en operationele teams.';
        $seoUrl = route('seo.takenlijst-personeel');
        $seoImage = asset('images/seo-takenlijst-personeel-hero.png');
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
<section class="relative bg-gradient-to-br from-teal-600 via-blue-700 to-indigo-800 text-white overflow-hidden pt-28 pb-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-1/2 w-96 h-96 bg-teal-300 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Takenlijst personeel</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Takenlijst personeel die zorgt voor duidelijke uitvoering</h1>
                <p class="mt-5 text-lg text-teal-100 leading-relaxed max-w-xl">Met TaskCheck maak je van losse taken een betrouwbaar proces. Elk teamlid weet precies wat te doen, met eigenaarschap, bewijs en realtime opvolging.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-blue-700 font-bold px-6 py-3 hover:bg-blue-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-white/30 bg-white/10 text-white font-semibold px-6 py-3 hover:bg-white/20 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-teal-200">Geen creditcard nodig · Gratis proefperiode</p>
            </div>
            <div class="mt-12 lg:mt-0">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
                    <img src="{{ asset('images/seo-takenlijst-personeel-hero.png') }}"
                         alt="Takenlijst personeel app – drie medewerkers met eigen takenlijst op mobiel verbonden aan één systeem"
                         class="w-full object-cover" loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div><p class="text-3xl font-extrabold text-blue-700">Duidelijk</p><p class="text-sm text-slate-500 mt-1">wie doet wat en wanneer</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">Bewijs</p><p class="text-sm text-slate-500 mt-1">per uitgevoerde taak</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">Realtime</p><p class="text-sm text-slate-500 mt-1">voortgang voor managers</p></div>
        <div><p class="text-3xl font-extrabold text-blue-700">Mobiel</p><p class="text-sm text-slate-500 mt-1">werkt op elke telefoon</p></div>
    </div>
</section>

<main class="pb-20">
    <div class="max-w-6xl mx-auto px-6">

        {{-- WAAROM --}}
        <section class="mt-16 lg:grid lg:grid-cols-2 lg:gap-16 lg:items-start">
            <div>
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Waarom het werkt</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Waarom een goede takenlijst personeel het verschil maakt</h2>
                <p class="mt-4 text-slate-600 leading-relaxed">Als medewerkers niet precies weten wat verwacht wordt, ontstaan vertragingen en kwaliteitsverschillen. Een sterke takenlijst personeel voorkomt dat. Taken krijgen een duidelijke omschrijving, prioriteit en deadline.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">TaskCheck helpt je taken niet alleen te plannen, maar ook te controleren. Je ziet live voortgang en kunt direct ingrijpen bij afwijkingen.</p>
                <ul class="mt-5 space-y-2">
                    @foreach(['Duidelijkheid over verantwoordelijkheid per taak', 'Minder mondeling overleg en misverstanden', 'Bewijs bij elke afgeronde taak', 'Manager ziet live status zonder te bellen'] as $item)
                    <li class="flex items-start gap-2 text-slate-700 text-sm">
                        <span class="mt-0.5 flex-shrink-0 w-5 h-5 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-10 lg:mt-0 space-y-3">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <p class="text-sm font-semibold text-slate-900 mb-2">Wat hoort er in een professionele takenlijst?</p>
                    <ul class="space-y-1.5">
                        @foreach(['Taakomschrijving','Verantwoordelijke medewerker','Deadline of herhaalfrequentie','Bewijsvereiste (foto, tekst, video)','Status (open, bezig, klaar)','Checklistitems voor complexe taken'] as $item)
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <span class="w-4 h-4 bg-blue-100 rounded flex items-center justify-center flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5">
                    <p class="text-sm font-semibold text-blue-900">Praktijkvoorbeeld</p>
                    <p class="text-sm text-blue-700 mt-1 leading-relaxed">Controleer voorraad · Reinig werkstation · Verifieer temperatuur · Maak foto na afronding. Door taken concreet te maken, verklein je interpretatieverschillen.</p>
                </div>
            </div>
        </section>

        {{-- SECTOREN --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Toepassingen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Voor horeca, schoonmaak en andere bedrijven</h2>
            </div>
            <div class="mt-8 grid sm:grid-cols-3 gap-4">
                @php $sectoren = [['🍽️','Horeca','Opening, service en sluitroutines per shift. Per rol: keuken, bediening, bar.'],['🧹','Schoonmaak','Rondes, oplevering en kwaliteitscontrole met foto als bewijs.'],['🏢','Andere sectoren','Inspecties, onderhoud, compliance – zelfde principe, elk bedrijf.']]; @endphp
                @foreach($sectoren as $s)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center hover:border-blue-200 hover:shadow-md transition">
                    <span class="text-3xl">{{ $s[0] }}</span>
                    <h3 class="mt-3 font-bold text-slate-900">{{ $s[1] }}</h3>
                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ $s[2] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- VAN LOSSE OPDRACHT --}}
        <section class="mt-20 bg-slate-900 rounded-3xl p-8 sm:p-12 text-white">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                <div>
                    <span class="text-teal-400 text-sm font-semibold uppercase tracking-wide">Digitaliseren</span>
                    <h2 class="mt-2 text-3xl font-bold">Van losse opdracht naar werkcontrole app</h2>
                    <p class="mt-3 text-slate-300 leading-relaxed">Veel bedrijven starten met mondelinge instructies. Dat werkt op korte termijn, maar schaalt slecht. Een digitale takenlijst personeel zorgt dat opdrachten niet verdwijnen en je achteraf bewijs hebt.</p>
                    <p class="mt-3 text-slate-300 leading-relaxed">Managers krijgen dashboards met open taken, afgeronde taken en uitzonderingen. Teams krijgen een duidelijke lijst per dag of shift.</p>
                </div>
                <div class="mt-8 lg:mt-0 grid grid-cols-2 gap-3">
                    @foreach(['Opdrachten verdwijnen nooit','Bewijs van uitvoering','Dashboard per shift','Snel inwerken nieuw personeel','Bijsturen zonder bellen','Consistent over alle locaties'] as $item)
                    <div class="flex items-center gap-2 bg-white/10 rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-teal-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm font-medium">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-20 text-center">
            <div class="bg-gradient-to-r from-teal-600 to-blue-600 rounded-3xl p-10 sm:p-14 text-white shadow-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Start vandaag met TaskCheck</h2>
                <p class="mt-3 text-lg text-teal-100 max-w-xl mx-auto">Meer overzicht, minder fouten en betere kwaliteit. Probeer TaskCheck 14 dagen gratis.</p>
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
                @php $faqs = [['Wat moet er in een goede takenlijst voor personeel?','Minimaal: taakomschrijving, verantwoordelijke, deadline, bewijsvereiste en status. Voor terugkerende taken voeg je frequentie toe.'],['Werkt dit ook voor wisselende diensten?','Ja. Je kunt per shift andere taken aanmaken of herhalende taken automatisch inplannen.'],['Kan ik taken koppelen aan specifieke medewerkers?','Ja. Elke taak kan worden toegewezen aan één of meerdere medewerkers. Zij zien hun persoonlijke lijst op mobiel.'],['Is het geschikt voor kleine teams?','Absoluut. Ook met 3 medewerkers helpt een digitale takenlijst om structuur en bewijs te houden.']]; @endphp
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
                    <a href="{{ route('seo.horeca-checklist-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Horeca checklist app</a>
                    <a href="{{ route('seo.checklist-app-schoonmaak') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Checklist app schoonmaak</a>
                    <a href="{{ route('seo.werkcontrole-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Werkcontrole app</a>
                    <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Blog: stoppen met Excel</a>
                </div>
            </div>
        </section>
    </div>
</main>

@include('components.footer')
</body>
</html>
