<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Sluitings checklist horeca – sluit je zaak zonder fouten';
        $seoDescription = 'Sluit je horecazaak elke avond zonder fouten. Bekijk een praktische sluitings checklist horeca en start gratis met TaskCheck.';
        $seoUrl = url('/sluitings-checklist-horeca');
        $seoImage = asset('images/seo-opening-checklist-horeca-hero.png');
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
<section class="relative bg-gradient-to-br from-slate-800 via-indigo-900 to-slate-900 text-white overflow-hidden pt-28 pb-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-400 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-slate-400 rounded-full translate-x-1/3 translate-y-1/3"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Sluitings checklist horeca</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Sluitings checklist horeca: sluit je zaak zonder fouten</h1>
                <p class="mt-5 text-lg text-indigo-200 leading-relaxed max-w-xl">Een slechte sluiting kost je de volgende dag direct tijd. Met een vaste sluitings checklist weet elk teamlid precies wat er moet gebeuren — elke avond, bij elke shift.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-indigo-700 font-bold px-6 py-3 hover:bg-indigo-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-white/30 bg-white/10 text-white font-semibold px-6 py-3 hover:bg-white/20 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-indigo-300">Geen creditcard nodig · Direct starten</p>
            </div>
            <div class="mt-12 lg:mt-0">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
                    <img src="{{ asset('images/seo-opening-checklist-horeca-hero.png') }}"
                         alt="Horecamedewerker sluit restaurant af met checklist op telefoon"
                         class="w-full object-cover" loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div><p class="text-3xl font-extrabold text-indigo-600">Elke avond</p><p class="text-sm text-slate-500 mt-1">dezelfde afsluiting</p></div>
        <div><p class="text-3xl font-extrabold text-indigo-600">Minder</p><p class="text-sm text-slate-500 mt-1">vergeten taken en stress</p></div>
        <div><p class="text-3xl font-extrabold text-indigo-600">Bewijs</p><p class="text-sm text-slate-500 mt-1">per taak vastgelegd</p></div>
        <div><p class="text-3xl font-extrabold text-indigo-600">Mobiel</p><p class="text-sm text-slate-500 mt-1">op telefoon, geen papier</p></div>
    </div>
</section>

{{-- INTRO --}}
<section class="max-w-6xl mx-auto px-6 mt-12">
    <div class="max-w-3xl">
        <p class="text-slate-600 leading-relaxed text-lg">Aan het einde van een drukke avond is er maar één gedachte: naar huis. Maar als je de sluiting overhaast doet, betaal je daar de volgende dag de prijs voor. Koeling die open blijft, kassa die niet klopt, alarm dat niet aan staat. Kleine fouten met grote gevolgen.</p>
        <p class="mt-4 text-slate-600 leading-relaxed text-lg">Een vaste sluitings checklist voorkomt dit. Niet één keer, maar elke avond. Voor elk teamlid, op elke locatie.</p>
    </div>
</section>

<main class="pb-20">
    <div class="max-w-6xl mx-auto px-6">

        {{-- WAAROM BELANGRIJK --}}
        <section class="mt-16 lg:grid lg:grid-cols-2 lg:gap-16 lg:items-start">
            <div>
                <span class="text-indigo-600 text-sm font-semibold uppercase tracking-wide">Het probleem</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Waarom een sluitings checklist belangrijk is</h2>
                <p class="mt-4 text-slate-600 leading-relaxed">Een sluiting die niet goed gaat, merk je de volgende ochtend direct. Producten die slecht zijn geworden, een vieze keuken, een kassa die niet klopt. Je verliest tijd, geld en soms ook klanten.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">Veel horecazaken laten de sluiting over aan wie toevallig als laatste weg gaat. Zonder vaste lijst vergeet iemand de koeling te controleren of het alarm in te stellen. Niet omdat die persoon slordig is, maar omdat het druk was en er geen structuur was.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">Met een vaste digitale sluitings checklist heeft elk teamlid een duidelijke takenlijst. Afgevinkt is afgevinkt. En als iets niet gedaan is, weet de manager dat direct.</p>
            </div>
            <div class="mt-10 lg:mt-0 space-y-3">
                @php $problemen = [
                    ['Koeling blijft open of is te warm door vergeten controle'],
                    ['Kassa klopt niet door overhaast afsluiten'],
                    ['Alarm staat niet aan bij vertrek'],
                    ['Schoonmaak wordt half gedaan omdat niemand weet wat zijn taak is'],
                    ['Manager weet niet of de sluiting goed is verlopen'],
                ]; @endphp
                @foreach($problemen as $p)
                <div class="flex items-start gap-3 bg-red-50 border border-red-100 rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">{{ $p[0] }}</span>
                </div>
                @endforeach
                <div class="mt-2 flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm font-semibold text-emerald-800">Een vaste sluitings checklist lost dit direct op</span>
                </div>
            </div>
        </section>

        {{-- WAT MOET ER IN --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-indigo-600 text-sm font-semibold uppercase tracking-wide">Inhoud</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Wat moet er in een sluitings checklist horeca</h2>
                <p class="mt-3 text-slate-500">Een goede sluitings checklist dekt vier gebieden: voedselveiligheid, schoonmaak, kassa en beveiliging.</p>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php $gebieden = [
                    ['Voedselveiligheid','Koeltemperaturen controleren, restjes goed opbergen, houdbaarheidsdatums checken.'],
                    ['Schoonmaak','Werkstations reinigen, vloer dweilen, vuilnis buiten zetten, toiletten controleren.'],
                    ['Kassa & systemen','Dagomzet opmaken, kassa afsluiten, PIN-apparaat uitzetten, reserveringen doornemen.'],
                    ['Beveiliging','Alle ramen en deuren dicht, terras opgeruimd, alarm inschakelen, verlichting uit.'],
                ]; @endphp
                @foreach($gebieden as $g)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-indigo-200 hover:shadow-md transition">
                    <p class="font-bold text-slate-900 mb-2">{{ $g[0] }}</p>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $g[1] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- PRAKTISCH VOORBEELD --}}
        <section class="mt-20 bg-gradient-to-br from-indigo-50 to-slate-100 rounded-3xl p-8 sm:p-12">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12">
                <div>
                    <span class="text-indigo-600 text-sm font-semibold uppercase tracking-wide">Voorbeeld</span>
                    <h2 class="mt-2 text-3xl font-bold text-slate-900">Praktisch voorbeeld: sluitings checklist restaurant</h2>
                    <p class="mt-3 text-slate-600 leading-relaxed">Dit is een voorbeeld van een sluitings checklist voor een restaurant. Pas het aan op jouw situatie.</p>
                    <div class="mt-4 space-y-1.5">
                        @php $keuken = [
                            'Koeling 1 controleren en temperatuur noteren',
                            'Koeling 2 controleren en temperatuur noteren',
                            'Resterende producten goed afdekken en opbergen',
                            'Houdbaarheidsdatums gecontroleerd',
                            'Friteuses uitgeschakeld en afgedekt',
                            'Werkstations gereinigd en gedesinfecteerd',
                            'Vloer geveegd en gedweild',
                            'Vuilniszakken gesloten en buiten gezet',
                        ]; @endphp
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Keuken</p>
                        @foreach($keuken as $item)
                        <div class="flex items-center gap-2.5 bg-white rounded-lg border border-indigo-100 px-3.5 py-2.5 text-sm text-slate-700">
                            <span class="w-4 h-4 rounded border-2 border-slate-300 flex-shrink-0"></span>
                            {{ $item }}
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-8 lg:mt-0 space-y-1.5">
                    @php $zaal = [
                        'Tafels afruimen en reinigen',
                        'Stoelen op tafel zetten',
                        'Terras opgeruimd en eventueel vastgezet',
                        'Bar schoongemaakt',
                        'Toiletten gecontroleerd en bijgevuld',
                        'Muziek en verlichting uitgeschakeld',
                        'Alle ramen en deuren gecontroleerd en gesloten',
                    ]; @endphp
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Zaal &amp; bediening</p>
                    @foreach($zaal as $item)
                    <div class="flex items-center gap-2.5 bg-white rounded-lg border border-indigo-100 px-3.5 py-2.5 text-sm text-slate-700">
                        <span class="w-4 h-4 rounded border-2 border-slate-300 flex-shrink-0"></span>
                        {{ $item }}
                    </div>
                    @endforeach
                    @php $kassa = [
                        'Kassa opgemaakt en dagomzet genoteerd',
                        'Kassalade veilig opgeborgen',
                        'PIN-apparaat afgesloten',
                        'Reserveringen voor morgen doorgelezen',
                        'Alarm ingeschakeld bij vertrek',
                    ]; @endphp
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mt-4 mb-2">Kassa &amp; beveiliging</p>
                    @foreach($kassa as $item)
                    <div class="flex items-center gap-2.5 bg-white rounded-lg border border-indigo-100 px-3.5 py-2.5 text-sm text-slate-700">
                        <span class="w-4 h-4 rounded border-2 border-slate-300 flex-shrink-0"></span>
                        {{ $item }}
                    </div>
                    @endforeach
                </div>
            </div>
            <p class="mt-6 text-xs text-slate-500 text-center">Dit is een basisvoorbeeld. In TaskCheck maak je je eigen sluitings checklist per locatie en rol.</p>
        </section>

        {{-- FOUTEN --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-indigo-600 text-sm font-semibold uppercase tracking-wide">Valkuilen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgemaakte fouten bij sluiten</h2>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 gap-4">
                @php $fouten = [
                    ['Koeling niet controleren bij sluiting', 'Dit is een van de duurste fouten. Als de koeling te warm staat of niet goed dicht is, zijn producten de volgende ochtend slecht. Voeg een verplichte temperatuurcheck toe met foto als bewijs.'],
                    ['Geen vaste volgorde in de checklist', 'Zonder volgorde slaat iemand stappen over. Zet de keuken altijd voor de zaal, en beveiliging altijd als laatste. Zo kun je niets missen.'],
                    ['Kassa overhaast afsluiten', 'Aan het einde van een lange dienst wil iedereen snel weg. Maar een kassa die niet klopt, kost de volgende dag meer tijd. Maak het een verplichte stap.'],
                    ['Geen bewijs vragen bij kritieke taken', 'Bij koeltemperaturen en schoonmaak is bewijs belangrijk voor HACCP en bij discussies achteraf. Laat medewerkers een foto of notitie toevoegen.'],
                    ['Sluiting overlaten aan de laatste persoon', 'Iedereen denkt dat de ander het doet. Met een vaste checklist is er altijd één persoon verantwoordelijk per taak.'],
                    ['Checklist nooit bijwerken', 'Een checklist uit 2022 sluit niet meer aan op je huidige situatie. Plan elk kwartaal een korte review met je team.'],
                ]; @endphp
                @foreach($fouten as $f)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-indigo-200 transition">
                    <p class="font-bold text-slate-900 text-sm mb-1.5">{{ $f[0] }}</p>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $f[1] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- HOE TASKCHECK HELPT --}}
        <section class="mt-20 bg-slate-900 rounded-3xl p-8 sm:p-12 text-white">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                <div>
                    <span class="text-indigo-400 text-sm font-semibold uppercase tracking-wide">TaskCheck</span>
                    <h2 class="mt-2 text-3xl font-bold">Hoe TaskCheck helpt</h2>
                    <p class="mt-3 text-slate-300 leading-relaxed">TaskCheck is een digitale checklist app speciaal voor operationele teams. Je bouwt je eigen sluitings checklist in een paar minuten, wijst hem toe aan de juiste medewerkers en ziet realtime of alles afgevinkt is.</p>
                    <p class="mt-3 text-slate-300 leading-relaxed">Medewerkers werken op hun telefoon. Geen papier, geen verwarring. Bij kritieke taken zoals koeltemperaturen kunnen ze pas verder als ze een foto hebben toegevoegd. Zo weet jij als manager altijd wat er is gedaan — ook als je er zelf niet bij was.</p>
                </div>
                <div class="mt-8 lg:mt-0 grid grid-cols-2 gap-3">
                    @foreach([
                        'Sluitings checklist per shift of locatie',
                        'Verplicht bewijs bij kritieke taken',
                        'Live voortgang voor manager',
                        'Automatisch herhalen elke dag',
                        'Melding bij gemiste of te late taken',
                        'Snel inwerken van nieuw personeel',
                    ] as $feature)
                    <div class="flex items-center gap-2 bg-white/10 rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm font-medium">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- VOOR WIE --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-indigo-600 text-sm font-semibold uppercase tracking-wide">Doelgroep</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Voor wie is dit geschikt</h2>
                <p class="mt-3 text-slate-500">Voor iedereen die dagelijks een ploeg moet afsluiten met de juiste kwaliteit en veiligheid.</p>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php $doelgroepen = [
                    ['Restaurant en bistro', 'Van kleine eetcafés tot grotere restaurants met meerdere sluitingsrondes per week.'],
                    ['Hotel en B&B', 'Avondshift voor receptie, keuken en gemeenschappelijke ruimtes netjes afsluiten.'],
                    ['Lunchroom en café', 'Dagelijkse sluiting met vaste schoonmaaktaken en voorraadbeheer.'],
                    ['Cateringbedrijf', 'Afsluiting op locatie met wisselend personeel en vaste kwaliteitsstandaard.'],
                    ['Keten en franchise', 'Meerdere vestigingen, één standaard voor elke sluiting. Bewijs per locatie.'],
                    ['Teamleiders en managers', 'Houd overzicht op meerdere teams zonder elke avond aanwezig te zijn.'],
                ]; @endphp
                @foreach($doelgroepen as $d)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-indigo-200 hover:shadow-md transition">
                    <p class="font-bold text-slate-900 text-sm">{{ $d[0] }}</p>
                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ $d[1] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-20 text-center">
            <div class="bg-gradient-to-r from-indigo-700 to-slate-800 rounded-3xl p-10 sm:p-14 text-white shadow-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Start vandaag</h2>
                <p class="mt-3 text-lg text-indigo-200 max-w-xl mx-auto">Maak je eigen sluitings checklist in minuten. Geen creditcard nodig, 14 dagen gratis proberen.</p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-indigo-700 font-bold px-8 py-4 text-lg hover:bg-indigo-50 transition shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center rounded-xl border-2 border-white/40 text-white font-semibold px-8 py-4 text-lg hover:bg-white/10 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-indigo-300">Geen verplichtingen · Geen creditcard · Vandaag live</p>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-indigo-600 text-sm font-semibold uppercase tracking-wide">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mt-8 max-w-3xl mx-auto space-y-3">
                @php $faqs = [
                    ['Wat is een sluitings checklist horeca precies?', 'Een sluitings checklist horeca is een vaste lijst met taken die elke avond afgewerkt moeten worden voordat de zaak dicht gaat. Denk aan koeltemperaturen controleren, schoonmaken, kassa opmaken en het alarm instellen.'],
                    ['Hoe lang duurt het om een sluitings checklist te maken in TaskCheck?', 'Je maakt een basischecklist in 5 tot 10 minuten. Je voegt taken toe, stelt een volgorde in en bepaalt welke taken bewijs vereisen. Daarna staat de checklist klaar voor je team.'],
                    ['Kan ik de checklist aanpassen per locatie of dag?', 'Ja. In TaskCheck maak je aparte checklists per locatie, shift of dag. De vrijdagavond heeft misschien andere taken dan een rustige woensdagavond.'],
                    ['Wat als een medewerker een taak vergeet bij sluiting?', 'Als een taak niet is afgevinkt voor de ingestelde sluitingstijd, ontvang jij als manager een melding. Zo weet je direct wat er nog open staat, ook als je zelf al naar huis bent.'],
                    ['Werkt het ook voor een kleine horecazaak met 2 of 3 medewerkers?', 'Ja. Ook met een klein team helpt een vaste sluitings checklist om kwaliteit te bewaken en fouten te voorkomen. TaskCheck is betaalbaar en direct te gebruiken.'],
                ]; @endphp
                @foreach($faqs as $faq)
                <details class="group bg-white border border-slate-200 rounded-2xl px-6 py-4 cursor-pointer hover:border-indigo-200 transition">
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
                    <a href="{{ route('seo.opening-checklist-horeca') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Opening checklist horeca</a>
                    <a href="{{ route('seo.horeca-checklist-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Horeca checklist app</a>
                    <a href="{{ route('seo.horeca-app-personeel') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Horeca app personeel</a>
                    <a href="{{ route('seo.takenlijst-personeel') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Takenlijst personeel</a>
                    <a href="{{ route('seo.werkcontrole-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Werkcontrole app</a>
                    <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Blog: personeel controleren</a>
                </div>
            </div>
        </section>

    </div>
</main>

@include('components.footer')
</body>
</html>
