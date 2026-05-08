<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Opening checklist horeca – voorkom fouten bij de start';
        $seoDescription = 'Een goede opening checklist horeca voorkomt fouten, stress en gemiste taken. Bekijk een praktisch voorbeeld en start gratis met TaskCheck.';
        $seoUrl = route('seo.opening-checklist-horeca');
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
<section class="relative bg-gradient-to-br from-amber-700 via-orange-700 to-red-800 text-white overflow-hidden pt-28 pb-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-amber-300 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-red-300 rounded-full translate-x-1/3 translate-y-1/3"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Opening checklist horeca</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Opening checklist horeca: voorkom fouten bij de start</h1>
                <p class="mt-5 text-lg text-amber-100 leading-relaxed max-w-xl">Een foute opening kost tijd en klanten. Met een vaste opening checklist weet elk teamlid precies wat er moet gebeuren — elke dag, bij elke shift.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-orange-700 font-bold px-6 py-3 hover:bg-orange-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-white/30 bg-white/10 text-white font-semibold px-6 py-3 hover:bg-white/20 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-amber-200">Geen creditcard nodig · Direct starten</p>
            </div>
            <div class="mt-12 lg:mt-0">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
                    <img src="{{ asset('images/seo-opening-checklist-horeca-hero.png') }}"
                         alt="Restaurant manager met opening checklist op tablet, personeel bereidt zich voor op de dag"
                         class="w-full object-cover" loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div><p class="text-3xl font-extrabold text-orange-600">Dagelijks</p><p class="text-sm text-slate-500 mt-1">dezelfde start, elk team</p></div>
        <div><p class="text-3xl font-extrabold text-orange-600">Minder</p><p class="text-sm text-slate-500 mt-1">vergeten taken en stress</p></div>
        <div><p class="text-3xl font-extrabold text-orange-600">Bewijs</p><p class="text-sm text-slate-500 mt-1">per taak vastgelegd</p></div>
        <div><p class="text-3xl font-extrabold text-orange-600">Mobiel</p><p class="text-sm text-slate-500 mt-1">op telefoon, geen papier</p></div>
    </div>
</section>

<main class="pb-20">
    <div class="max-w-6xl mx-auto px-6">

        {{-- WAAROM BELANGRIJK --}}
        <section class="mt-16 lg:grid lg:grid-cols-2 lg:gap-16 lg:items-start">
            <div>
                <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">Het probleem</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Waarom een opening checklist belangrijk is</h2>
                <p class="mt-4 text-slate-600 leading-relaxed">In horeca begint een goede dag met een goede opening. Als de koeling niet gecontroleerd is, de kassa niet klaarstaat of het terras niet op tijd in orde is, merk je dat direct. Klanten wachten, stress loopt op en fouten kosten geld.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">Veel horecazaken werken nog met mondelinge overdracht of een geprint velletje. Dat werkt bij een vast team, maar zodra er wisselende medewerkers of meerdere shifts zijn, gaan er dingen mis. Niet omdat mensen niet willen werken, maar omdat het onduidelijk is wat er precies van hen verwacht wordt.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">Een vaste digitale opening checklist geeft structuur. Iedereen weet wat er gedaan moet worden, in welke volgorde en met welk bewijs. De manager hoeft niet elke ochtend alles na te lopen.</p>
            </div>
            <div class="mt-10 lg:mt-0 space-y-3">
                @php $problemen = [['Medewerkers vergeten taken bij een drukke start'],['Kwaliteitsverschil tussen ochtendploeg en avondploeg'],['Manager weet niet wat er gedaan is zonder zelf te kijken'],['Discussie achteraf over wie iets had moeten doen'],['Papieren lijstjes raken kwijt of worden niet ingevuld']]; @endphp
                @foreach($problemen as $p)
                <div class="flex items-start gap-3 bg-red-50 border border-red-100 rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="text-sm text-slate-700">{{ $p[0] }}</span>
                </div>
                @endforeach
                <div class="mt-2 flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm font-semibold text-emerald-800">Een vaste checklist lost dit direct op</span>
                </div>
            </div>
        </section>

        {{-- WAT MOET ER IN --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">Inhoud</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Wat moet er in een horeca opening checklist</h2>
                <p class="mt-3 text-slate-500">Een goede opening checklist dekt vier gebieden: veiligheid, hygiëne, voorbereiding en service.</p>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php $gebieden = [['Veiligheid','Nooduitgangen vrij, brandblussers aanwezig, sloten controleren, alarm uit.'],['Hygiëne','Koeltemperaturen meten, schoonmaakstatus keuken, sanitair checken, handen.'],['Voorbereiding','Mise en place, voorraadcheck, kassasysteem opstarten, tafels dekken.'],['Service','Menukaarten gereed, specials noteren, personeel gebriefd, muziek/licht aan.']]; @endphp
                @foreach($gebieden as $g)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-orange-200 hover:shadow-md transition">
                    <p class="font-bold text-slate-900 mb-2">{{ $g[0] }}</p>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $g[1] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- PRAKTISCH VOORBEELD --}}
        <section class="mt-20 bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl p-8 sm:p-12">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12">
                <div>
                    <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">Voorbeeld</span>
                    <h2 class="mt-2 text-3xl font-bold text-slate-900">Praktisch voorbeeld: opening checklist restaurant</h2>
                    <p class="mt-3 text-slate-600 leading-relaxed">Dit is een voorbeeld van een opening checklist voor een restaurant. Pas het aan op jouw situatie.</p>
                    <div class="mt-4 space-y-1.5">
                        @php $keuken = ['Koeling 1 controleren (temp. max. 4°C)', 'Koeling 2 controleren (temp. max. 4°C)', 'Datumcontrole gekoelde producten', 'Werkstations reinigen en desinfecteren', 'Friteuses voorverwarmen', 'Mise en place voorbereiden voor lunch', 'Snijplanken en messen gereed']; @endphp
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Keuken</p>
                        @foreach($keuken as $item)
                        <div class="flex items-center gap-2.5 bg-white rounded-lg border border-orange-100 px-3.5 py-2.5 text-sm text-slate-700">
                            <span class="w-4 h-4 rounded border-2 border-slate-300 flex-shrink-0"></span>
                            {{ $item }}
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-8 lg:mt-0 space-y-1.5">
                    @php $zaal = ['Tafels dekken (bestek, glazen, menukaart)', 'Terras inrichten en controleren', 'Toiletten schoonmaken en voorraden bijvullen', 'Muziek en verlichting instellen', 'Reserveringen doornemen', 'Dagschotel en specials noteren', 'Personeel briefen over bijzonderheden']; @endphp
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Zaal &amp; bediening</p>
                    @foreach($zaal as $item)
                    <div class="flex items-center gap-2.5 bg-white rounded-lg border border-orange-100 px-3.5 py-2.5 text-sm text-slate-700">
                        <span class="w-4 h-4 rounded border-2 border-slate-300 flex-shrink-0"></span>
                        {{ $item }}
                    </div>
                    @endforeach
                    @php $kassa = ['Kassa opstarten en wisselgeld tellen', 'PIN-apparaat testen', 'Reserveringssysteem controleren']; @endphp
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mt-4 mb-2">Kassa &amp; systemen</p>
                    @foreach($kassa as $item)
                    <div class="flex items-center gap-2.5 bg-white rounded-lg border border-orange-100 px-3.5 py-2.5 text-sm text-slate-700">
                        <span class="w-4 h-4 rounded border-2 border-slate-300 flex-shrink-0"></span>
                        {{ $item }}
                    </div>
                    @endforeach
                </div>
            </div>
            <p class="mt-6 text-xs text-slate-500 text-center">Dit is een basisvoorbeeld. In TaskCheck maak je je eigen checklist per locatie en rol.</p>
        </section>

        {{-- FOUTEN --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">Valkuilen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgemaakte fouten bij de opening</h2>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 gap-4">
                @php $fouten = [['Te algemene taken omschrijven','Schrijf niet "keuken controleren" maar "koeling 1 temperatuur meten, foto van display maken". Hoe specifieker, hoe minder ruimte voor interpretatie.'],['Geen vaste volgorde aanhouden','Zonder volgorde slaat iemand stappen over of doet dingen dubbel. Een checklist dwingt een logische route af.'],['Geen bewijs vragen bij kritieke taken','Bij temperatuurcontroles, HACCP-punten en schoonmaak is bewijs cruciaal. Leg dat vast in de checklist.'],['Checklist nooit bijwerken','Een checklist die maanden oud is, sluit niet meer aan op de praktijk. Plan elk kwartaal een review.'],['Papier gebruiken in plaats van digitaal','Papier raakt kwijt, is niet doorzoekbaar en levert geen data op. Digitaal is sneller, overzichtelijker en schaalbaar.'],['Medewerkers niet betrekken','Een checklist die van bovenaf is opgelegd, wordt minder goed nageleefd. Vraag het team om input.']]; @endphp
                @foreach($fouten as $f)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-orange-200 transition">
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
                    <span class="text-amber-400 text-sm font-semibold uppercase tracking-wide">TaskCheck</span>
                    <h2 class="mt-2 text-3xl font-bold">Hoe TaskCheck helpt</h2>
                    <p class="mt-3 text-slate-300 leading-relaxed">TaskCheck is een digitale checklist app speciaal voor operationele teams. Je bouwt je eigen opening checklist in een paar minuten, wijst hem toe aan de juiste medewerkers en ziet realtime of alles afgevinkt is.</p>
                    <p class="mt-3 text-slate-300 leading-relaxed">Medewerkers werken op hun telefoon. Geen papier, geen verwarring. Als een taak verplicht bewijs heeft, kunnen ze pas verder als ze een foto of notitie hebben toegevoegd.</p>
                </div>
                <div class="mt-8 lg:mt-0 grid grid-cols-2 gap-3">
                    @foreach(['Checklist per shift of locatie','Verplicht bewijs per taak','Live voortgang voor manager','Automatisch herhalen dagelijks','Meldingen bij gemiste taken','Snel inwerken nieuw personeel'] as $feature)
                    <div class="flex items-center gap-2 bg-white/10 rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm font-medium">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- VOOR WIE --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">Doelgroep</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Voor wie is dit geschikt</h2>
                <p class="mt-3 text-slate-500">Voor iedereen die dagelijks een ploeg moet starten met de juiste kwaliteit.</p>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php $doelgroepen = [['Restaurant en bistro','Van kleine eetcafés tot grotere restaurants met meerdere ploegenstructuren.'],['Hotel en B&B','Ochtendshift voor ontbijt, ruimtes gereed en receptie openstelling.'],['Lunchroom en café','Dagelijkse opening met verse bereidingen en schoonmaak.'],['Cateringbedrijf','Voorbereiding op locatie, geen vaste keuken maar dezelfde vaste kwaliteit.'],['Keten en franchise','Meerdere vestigingen, één standaard voor elke opening.'],['Teamleiders en managers','Houd overzicht op meerdere teams zonder overal tegelijk te zijn.']]; @endphp
                @foreach($doelgroepen as $d)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-orange-200 hover:shadow-md transition">
                    <p class="font-bold text-slate-900 text-sm">{{ $d[0] }}</p>
                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ $d[1] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-20 text-center">
            <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-3xl p-10 sm:p-14 text-white shadow-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Start vandaag</h2>
                <p class="mt-3 text-lg text-amber-100 max-w-xl mx-auto">Maak je eigen opening checklist in minuten. Geen creditcard nodig, 14 dagen gratis proberen.</p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-orange-700 font-bold px-8 py-4 text-lg hover:bg-orange-50 transition shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center rounded-xl border-2 border-white/40 text-white font-semibold px-8 py-4 text-lg hover:bg-white/10 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-amber-200">Geen verplichtingen · Geen creditcard · Vandaag live</p>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mt-8 max-w-3xl mx-auto space-y-3">
                @php $faqs = [
                    ['Wat is een opening checklist horeca precies?','Een opening checklist horeca is een vaste lijst met taken die elke ochtend afgewerkt moeten worden voordat de deuren opengaan. Denk aan koelcontroles, schoonmaak, mise en place, kassa en personeelsbriefing.'],
                    ['Hoe lang duurt het om een opening checklist te maken in TaskCheck?','Je kunt een basischecklist in 5 tot 10 minuten aanmaken. Je voegt taken toe, stelt een volgorde in en bepaalt welke taken bewijs vereisen. Daarna staat hij klaar voor je team.'],
                    ['Kan ik de checklist aanpassen per locatie of dag?','Ja. In TaskCheck maak je aparte checklists per locatie, shift of dag. Zo heeft een weekendochtend andere taken dan een doordeweekse opening.'],
                    ['Wat als een medewerker een taak vergeet?','Als een taak niet is afgevinkt voor de ingestelde tijd, ontvang je als manager een melding. Zo weet je direct wat er nog open staat zonder zelf op de vloer te staan.'],
                    ['Werkt het ook voor kleine horecazaken met 2 of 3 medewerkers?','Ja. Ook met een klein team helpt een vaste checklist om kwaliteit te bewaken en fouten te voorkomen. TaskCheck is betaalbaar en direct te gebruiken.'],
                ]; @endphp
                @foreach($faqs as $faq)
                <details class="group bg-white border border-slate-200 rounded-2xl px-6 py-4 cursor-pointer hover:border-orange-200 transition">
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
