<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Checklist app schoonmaak voor schoonmaakbedrijven';
        $seoDescription = 'Checklist app schoonmaak voor meer controle, minder fouten en duidelijk bewijs per taak. Start 14 dagen gratis met TaskCheck.';
        $seoUrl = route('seo.checklist-app-schoonmaak');
        $seoImage = asset('images/seo-checklist-schoonmaak-hero.png');
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

{{-- ===================== HERO ===================== --}}
<section class="relative bg-gradient-to-br from-blue-700 via-indigo-700 to-blue-900 text-white overflow-hidden pt-28 pb-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-indigo-300 rounded-full translate-x-1/3 translate-y-1/3"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Checklist app schoonmaak</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Checklist app voor schoonmaakbedrijven</h1>
                <p class="mt-5 text-lg text-blue-100 leading-relaxed max-w-xl">Veel schoonmaakbedrijven werken nog met papieren lijsten of Excel. Daardoor verlies je snel overzicht. Met TaskCheck werk je digitaal, per locatie en met bewijs per taak.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-blue-700 font-bold px-6 py-3 hover:bg-blue-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-white/30 bg-white/10 text-white font-semibold px-6 py-3 hover:bg-white/20 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-blue-200">Geen creditcard nodig · Gratis proefperiode</p>
            </div>
            <div class="mt-12 lg:mt-0">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
                    <img src="{{ asset('images/seo-checklist-schoonmaak-hero.png') }}"
                         alt="Checklist app schoonmaak – teamleider controleert dashboard terwijl medewerkers aan het werk zijn"
                         class="w-full object-cover"
                         loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== STATS BALK ===================== --}}
<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div>
            <p class="text-3xl font-extrabold text-blue-700">98%</p>
            <p class="text-sm text-slate-500 mt-1">minder gemiste taken</p>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-blue-700">14 dagen</p>
            <p class="text-sm text-slate-500 mt-1">gratis proberen</p>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-blue-700">Realtime</p>
            <p class="text-sm text-slate-500 mt-1">voortgang per locatie</p>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-blue-700">Foto & video</p>
            <p class="text-sm text-slate-500 mt-1">bewijs per taak</p>
        </div>
    </div>
</section>

<main class="pb-20">
    <div class="max-w-6xl mx-auto px-6">

        {{-- ===================== WAAROM CONTROLE ===================== --}}
        <section class="mt-16 lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Waarom het belangrijk is</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Waarom schoonmaak controle belangrijk is</h2>
                <p class="mt-4 text-slate-600 leading-relaxed">In de schoonmaak draait alles om vaste kwaliteit. Klanten verwachten dat elke ruimte goed wordt gedaan, elke keer opnieuw. Zonder duidelijke controle ontstaan snel fouten, klachten en extra herstelwerk.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">Met een checklist app schoonmaak maak je controle simpel: je ziet direct wat af is, wat open staat en waar bewijs ontbreekt.</p>
                <ul class="mt-5 space-y-2">
                    @foreach(['Minder klachten van opdrachtgevers', 'Minder herstelwerk en extra ritten', 'Bewijs voor discussies en audits', 'Consistente kwaliteit over alle locaties'] as $item)
                    <li class="flex items-start gap-2 text-slate-700 text-sm">
                        <span class="mt-0.5 flex-shrink-0 w-5 h-5 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-10 lg:mt-0">
                <img src="{{ asset('images/seo-checklist-schoonmaak-locaties.png') }}"
                     alt="Schoonmaakbedrijf beheert meerdere locaties in één checklist app"
                     class="w-full rounded-2xl shadow-xl border border-slate-100"
                     loading="lazy">
            </div>
        </section>

        {{-- ===================== HOE WERKT HET ===================== --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Werkwijze</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Hoe werkt een checklist app voor schoonmaak</h2>
                <p class="mt-3 text-slate-500">In vier stappen heb je grip op de uitvoering.</p>
            </div>
            <div class="mt-10">
                <img src="{{ asset('images/seo-checklist-schoonmaak-workflow.png') }}"
                     alt="Workflow checklist app schoonmaak: checklist maken, toewijzen, foto bewijs, goedkeuren"
                     class="w-full rounded-2xl shadow-lg border border-slate-100"
                     loading="lazy">
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                $steps = [
                    ['nr' => '1', 'title' => 'Checklist aanmaken', 'desc' => 'Maak een checklist per locatie of type dienst. Voeg taken, instructies en bewijs regels toe.'],
                    ['nr' => '2', 'title' => 'Taken toewijzen', 'desc' => 'Wijs taken toe aan de juiste medewerkers. Ze zien direct wat ze moeten doen.'],
                    ['nr' => '3', 'title' => 'Bewijs uploaden', 'desc' => 'Medewerkers vinken taken af op mobiel en voegen foto of tekst toe als bewijs.'],
                    ['nr' => '4', 'title' => 'Realtime controleren', 'desc' => 'Teamleiders zien live de voortgang en kunnen direct bijsturen waar nodig.'],
                ];
                @endphp
                @foreach($steps as $step)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <span class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-blue-600 text-white font-bold text-sm">{{ $step['nr'] }}</span>
                    <h3 class="mt-3 font-bold text-slate-900">{{ $step['title'] }}</h3>
                    <p class="mt-1 text-sm text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ===================== VOORBEELDEN ===================== --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Toepassingen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Praktische voorbeelden</h2>
                <p class="mt-3 text-slate-500">De checklist app schoonmaak werkt voor veel verschillende sectoren.</p>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                $examples = [
                    ['icon' => '🏢', 'title' => 'Kantoor', 'desc' => 'Werkplekken, pantry, sanitair, entree en vergaderruimtes.'],
                    ['icon' => '🏨', 'title' => 'Hotel', 'desc' => 'Hotelkamers, gangen, lobby en algemene ruimtes.'],
                    ['icon' => '🍽️', 'title' => 'Restaurant', 'desc' => 'Keukenzones, toiletten en hygiënecontroles.'],
                    ['icon' => '💪', 'title' => 'Sportschool', 'desc' => 'Kleedkamers, fitnessruimte en douches.'],
                    ['icon' => '🏥', 'title' => 'Zorg', 'desc' => 'Zorgcomplexen, gangen en hygiënisch gevoelige ruimtes.'],
                    ['icon' => '🔑', 'title' => 'Oplevering', 'desc' => 'Eindcontrole na grote schoonmaak met bewijs per zone.'],
                ];
                @endphp
                @foreach($examples as $example)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex gap-4 items-start hover:border-blue-200 hover:shadow-md transition">
                    <span class="text-2xl">{{ $example['icon'] }}</span>
                    <div>
                        <h3 class="font-bold text-slate-900">{{ $example['title'] }}</h3>
                        <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">{{ $example['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ===================== VOORDELEN ===================== --}}
        <section class="mt-20 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-8 sm:p-12">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                <div>
                    <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Voordelen</span>
                    <h2 class="mt-2 text-3xl font-bold text-slate-900">Voordelen voor jouw bedrijf</h2>
                    <p class="mt-3 text-slate-600">Met een checklist app schoonmaak krijg je rust, overzicht en bewijs.</p>
                    <div class="mt-6 grid grid-cols-1 gap-3">
                        @php
                        $benefits = [
                            ['icon' => '✅', 'title' => 'Minder klachten', 'desc' => 'Vaste werkwijze per locatie = minder fouten en herstelwerk.'],
                            ['icon' => '👁️', 'title' => 'Realtime inzicht', 'desc' => 'Zie direct wat open staat, zonder te hoeven bellen of mailen.'],
                            ['icon' => '📸', 'title' => 'Bewijs per taak', 'desc' => 'Foto of tekst bewijs voorkomt discussies met klanten.'],
                            ['icon' => '⚡', 'title' => 'Sneller inwerken', 'desc' => 'Nieuwe medewerkers volgen checklists zonder opleiding.'],
                            ['icon' => '📍', 'title' => 'Per locatie', 'desc' => 'Beheer tientallen objecten vanuit één dashboard.'],
                        ];
                        @endphp
                        @foreach($benefits as $b)
                        <div class="flex gap-3 bg-white rounded-xl p-4 shadow-sm border border-white/80">
                            <span class="text-xl">{{ $b['icon'] }}</span>
                            <div>
                                <p class="font-semibold text-slate-900 text-sm">{{ $b['title'] }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $b['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-10 lg:mt-0 space-y-4">
                    <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100">
                        <p class="text-slate-700 italic leading-relaxed">"Sinds we TaskCheck gebruiken, hebben we veel meer overzicht en veel minder herstelwerk. We zien meteen waar iets mis is gegaan."</p>
                        <div class="mt-4 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">M</div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Mark, teamleider schoonmaak</p>
                                <p class="text-xs text-slate-500">Facilitair bedrijf, 12 locaties</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100">
                        <p class="text-slate-700 italic leading-relaxed">"Onze opdrachtgevers zijn blij met de foto's als bewijs. Het bespaart ons veel discussies."</p>
                        <div class="mt-4 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">S</div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Sandra, eigenaar schoonmaakbedrijf</p>
                                <p class="text-xs text-slate-500">Schoonmaakbedrijf, 3 medewerkers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== WAAROM TASKCHECK ===================== --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">TaskCheck</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Waarom TaskCheck</h2>
                <p class="mt-3 text-slate-500">Gemaakt voor teams die praktisch willen werken. Geen ingewikkeld systeem.</p>
            </div>
            <div class="mt-8 grid sm:grid-cols-3 gap-5">
                @php
                $features = [
                    ['icon' => '📱', 'title' => 'Werkt op mobiel', 'desc' => 'Medewerkers werken op hun telefoon. Geen laptop nodig.'],
                    ['icon' => '🗂️', 'title' => 'Per locatie ingesteld', 'desc' => 'Elke locatie heeft zijn eigen checklists en planning.'],
                    ['icon' => '🔔', 'title' => 'Realtime notificaties', 'desc' => 'Ontvang een melding als een taak te laat is of bewijs mist.'],
                    ['icon' => '📊', 'title' => 'Duidelijke rapportages', 'desc' => 'Alle data centraal, geen handmatig dossier samenstellen.'],
                    ['icon' => '🔁', 'title' => 'Herhalende taken', 'desc' => 'Plan dagelijkse, wekelijkse of maandelijkse schoonmaak.'],
                    ['icon' => '🔒', 'title' => 'Veilig en betrouwbaar', 'desc' => 'Jouw data is veilig opgeslagen en altijd beschikbaar.'],
                ];
                @endphp
                @foreach($features as $f)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center hover:border-blue-200 hover:shadow-md transition">
                    <span class="text-3xl">{{ $f['icon'] }}</span>
                    <h3 class="mt-3 font-bold text-slate-900">{{ $f['title'] }}</h3>
                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
            <div class="mt-6 text-center text-sm text-slate-500">
                Wil je ook je werkcontrole verbeteren? Bekijk ook onze pagina over de
                <a class="text-blue-700 font-semibold hover:underline" href="{{ route('seo.werkcontrole-app') }}">werkcontrole app</a>.
            </div>
        </section>

        {{-- ===================== VOOR WIE ===================== --}}
        <section class="mt-20">
            <div class="bg-slate-900 rounded-3xl p-8 sm:p-12 text-white">
                <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                    <div>
                        <span class="text-blue-400 text-sm font-semibold uppercase tracking-wide">Doelgroep</span>
                        <h2 class="mt-2 text-3xl font-bold">Voor wie is dit geschikt</h2>
                        <p class="mt-3 text-slate-300 leading-relaxed">TaskCheck werkt voor kleine teams met 2 medewerkers maar ook voor grote schoonmaakbedrijven met tientallen locaties. Het schaalt mee met jouw organisatie.</p>
                        <p class="mt-3 text-slate-300 leading-relaxed">Werk je met personeel in shifts? Lees ook over <a class="text-blue-400 font-semibold hover:text-blue-300" href="{{ route('seo.takenlijst-personeel') }}">takenlijst personeel</a>.</p>
                    </div>
                    <div class="mt-8 lg:mt-0 grid grid-cols-2 gap-3">
                        @php
                        $targets = ['Klein schoonmaakbedrijf', 'Groot facilitair bedrijf', 'Teamleiders', 'Freelance schoonmakers', 'Zorginstellingen', 'Facilitair managers'];
                        @endphp
                        @foreach($targets as $t)
                        <div class="flex items-center gap-2 bg-white/10 rounded-xl px-4 py-3">
                            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm font-medium">{{ $t }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== CTA ===================== --}}
        <section class="mt-20 text-center">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-10 sm:p-14 text-white shadow-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Start vandaag gratis</h2>
                <p class="mt-3 text-lg text-blue-100 max-w-xl mx-auto">Wil je minder fouten en meer grip op uitvoering? Probeer TaskCheck 14 dagen gratis. Geen creditcard nodig.</p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-blue-700 font-bold px-8 py-4 text-lg hover:bg-blue-50 transition shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center rounded-xl border-2 border-white/40 text-white font-semibold px-8 py-4 text-lg hover:bg-white/10 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-blue-200">Geen verplichtingen · Geen creditcard · Direct starten</p>
            </div>
        </section>

        {{-- ===================== FAQ ===================== --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-blue-600 text-sm font-semibold uppercase tracking-wide">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mt-8 max-w-3xl mx-auto space-y-3">
                @php
                $faqs = [
                    ['q' => 'Wat is een checklist app schoonmaak precies?', 'a' => 'Een app waarmee je schoonmaaktaken digitaal plant, uitvoert en controleert. Je ziet direct wat gedaan is, wat open staat en of bewijs is toegevoegd.'],
                    ['q' => 'Kan ik bewijs toevoegen per taak?', 'a' => 'Ja. Je kunt per taak foto, video of tekstbewijs vragen. Zo heb je altijd duidelijk bewijs richting klant of leidinggevende.'],
                    ['q' => 'Is dit ook geschikt voor kleine schoonmaakbedrijven?', 'a' => 'Zeker. Ook met een klein team helpt een checklist app schoonmaak om structuur te houden en fouten te voorkomen. Je begint klein en groeit mee.'],
                    ['q' => 'Kan ik meerdere locaties beheren?', 'a' => 'Ja, je kunt checklists en voortgang per locatie bijhouden. Dat is handig als je verschillende objecten of teams hebt.'],
                    ['q' => 'Hoeveel kost TaskCheck?', 'a' => 'Je kunt 14 dagen gratis proberen. Daarna start het abonnement. Bekijk de actuele prijzen op onze prijzenpagina.'],
                ];
                @endphp
                @foreach($faqs as $faq)
                <details class="group bg-white border border-slate-200 rounded-2xl px-6 py-4 cursor-pointer hover:border-blue-200 transition">
                    <summary class="flex justify-between items-center font-semibold text-slate-900 list-none">
                        {{ $faq['q'] }}
                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0 group-open:rotate-45 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </summary>
                    <p class="mt-3 text-slate-600 leading-relaxed text-sm">{{ $faq['a'] }}</p>
                </details>
                @endforeach
            </div>
        </section>

        {{-- ===================== INTERNE LINKS ===================== --}}
        <section class="mt-16 mb-4">
            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6">
                <p class="font-semibold text-slate-900 mb-3">Gerelateerde pagina's</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('seo.werkcontrole-app') }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Werkcontrole app</a>
                    <a href="{{ route('seo.takenlijst-personeel') }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Takenlijst personeel</a>
                    <a href="{{ route('seo.beste-checklist-app-2026') }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Beste checklist app 2026</a>
                    <a href="{{ route('seo.checklist-app-voor-bedrijven') }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Checklist app voor bedrijven</a>
                    <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Blog: beste checklist app schoonmaak</a>
                </div>
            </div>
        </section>

    </div>
</main>

@include('components.footer')
</body>
</html>
