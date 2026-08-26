<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'NVWA Controle Horeca 2026: Waar Wordt Op Gecontroleerd? | TaskCheck';
        $seoDescription = 'Wat controleert de NVWA bij horeca in 2026? Lees over HACCP, hygiëne, temperatuur, allergenen en hoe je dagelijkse controles organiseert.';
        $seoKeywords = 'NVWA controle horeca, NVWA controle restaurant, NVWA inspectie horeca, NVWA controle checklist, HACCP controle horeca, voedselveiligheid horeca, NVWA temperatuur horeca, NVWA hygiëne horeca, NVWA controle voorbereiden';
        $seoUrl = route('blog.nvwa-controle-horeca-2026');
        $seoImage = asset('images/blog-nvwa-controle-horeca-2026.jpg').'?v=2';
        $publishedAt = '2026-08-25T10:00:00+02:00';
        $faqItems = [
            ['Wat controleert de NVWA bij een restaurant?', 'De NVWA houdt toezicht op voedselveiligheid. Daarbij kunnen onder andere hygiënisch werken, temperatuur en houdbaarheid, voedselveiligheidsprocedures, allergenen en plaagdierbeheersing relevant zijn.'],
            ['Is HACCP verplicht voor horeca?', 'Horecabedrijven moeten voedselveilig werken volgens de geldende voedselveiligheidsregels. Ondernemers kunnen daarvoor onder voorwaarden gebruikmaken van een goedgekeurde hygiënecode of een eigen voedselveiligheidsplan op basis van HACCP.'],
            ['Moet ik temperatuurregistraties bewaren?', 'Welke registraties in jouw situatie nodig zijn en hoe je aan de geldende eisen moet voldoen, hangt af van je processen en de toepasselijke regels of hygiënecode. Raadpleeg daarvoor altijd de actuele officiële richtlijnen.'],
            ['Kan ik HACCP-controles digitaal bijhouden?', 'Digitale systemen kunnen worden gebruikt om relevante werkzaamheden en registraties overzichtelijk vast te leggen. Zorg er wel voor dat jouw werkwijze aansluit bij de eisen die voor je bedrijf gelden.'],
            ['Is TaskCheck een vervanging voor HACCP?', 'Nee. TaskCheck is een hulpmiddel voor checklists, taken, registraties en werkcontrole. Het vervangt geen HACCP-plan, hygiënecode, deskundig advies of wettelijke verplichtingen.'],
        ];
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="keywords" content="{{ $seoKeywords }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="{{ $publishedAt }}">
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
            "headline": "NVWA-controle horeca in 2026: waar wordt op gecontroleerd?",
            "description": @json($seoDescription),
            "datePublished": "{{ $publishedAt }}",
            "author": { "@@type": "Organization", "name": "TaskCheck" },
            "publisher": { "@@type": "Organization", "name": "TaskCheck" },
            "image": "{{ $seoImage }}",
            "mainEntityOfPage": { "@@type": "WebPage", "@@id": "{{ $seoUrl }}" }
        }
    </script>
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "FAQPage",
            "mainEntity": [
                @foreach($faqItems as $i => $item)
                {
                    "@@type": "Question",
                    "name": @json($item[0]),
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": @json($item[1])
                    }
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }
    </script>
</head>
<body class="min-h-screen bg-white font-sans text-slate-900 antialiased">
@include('components.header')

<header class="border-b border-slate-200 bg-white pt-28 pb-10">
    <div class="max-w-3xl mx-auto px-6">
        <nav class="mb-5 flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('blog') }}" class="hover:text-blue-600 transition">Blog</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-500">Voedselveiligheid</span>
        </nav>
        <div class="mb-4 flex flex-wrap items-center gap-3">
            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">Nieuws</span>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">Horeca</span>
            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">NVWA</span>
            <span class="text-xs text-slate-400">25 aug 2026 · 9 min lezen</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight text-slate-900">NVWA-controle horeca in 2026: waar wordt op gecontroleerd?</h1>
        <p class="mt-4 text-lg leading-relaxed text-slate-500">Wat controleert de NVWA bij restaurants, cafés en hotels? Lees over HACCP, hygiëne, temperatuur, allergenen en hoe je dagelijkse controles overzichtelijk vastlegt.</p>
        <aside class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm leading-relaxed text-slate-600">
            <p class="font-semibold text-slate-800">Bron</p>
            <p class="mt-1">Gebaseerd op openbare informatie van de <a href="https://www.nvwa.nl/" class="font-semibold text-blue-600 underline underline-offset-2 hover:text-blue-800" rel="noopener noreferrer" target="_blank">Nederlandse Voedsel- en Warenautoriteit (NVWA)</a>. Controleer voor actuele wettelijke eisen altijd de officiële NVWA-informatie.</p>
        </aside>
    </div>
</header>

<div class="max-w-3xl mx-auto px-6 py-10">
    <figure class="mb-10 overflow-hidden rounded-2xl ring-1 ring-slate-200/80">
        <img src="{{ $seoImage }}" alt="NVWA-inspectie in de horeca: een inspecteur controleert voedselcontainers in de koelcel terwijl een medewerker toekijkt" class="w-full object-cover object-center" width="1024" height="576" loading="eager">
        <figcaption class="bg-slate-50 px-4 py-3 text-center text-xs text-slate-500">Zo ziet een horeca-inspectie eruit: de NVWA controleert onder meer opslag, houdbaarheid en hygiëne.</figcaption>
    </figure>

    <article class="prose-article">
        <p>Een bezoek van de Nederlandse Voedsel- en Warenautoriteit (NVWA) kan voor horecaondernemers spannend zijn. Zijn alle registraties bijgehouden? Zijn de temperaturen in orde? Is de keuken schoon en kan het personeel laten zien dat er volgens de juiste procedures wordt gewerkt?</p>
        <p>De NVWA houdt toezicht op de voedselveiligheid bij onder andere restaurants, cafés, hotels, lunchrooms en andere bedrijven die eten en drinken bereiden of verkopen.</p>
        <p>In dit artikel lees je waar je als horecaondernemer in 2026 rekening mee moet houden en hoe digitale checklists kunnen helpen om dagelijkse controles overzichtelijk vast te leggen.</p>

        <h2>Wat controleert de NVWA in de horeca?</h2>
        <p>Bij toezicht op voedselveiligheid kijkt de NVWA naar verschillende onderdelen van de bedrijfsvoering. Welke punten precies relevant zijn, hangt af van het bedrijf en de situatie.</p>
        <p>Belangrijke onderwerpen zijn onder andere:</p>
        <ul>
            <li>veilig omgaan met voedsel;</li>
            <li>hygiënisch werken;</li>
            <li>temperatuur en houdbaarheid;</li>
            <li>werken volgens voedselveiligheidsprocedures;</li>
            <li>informatie over allergenen;</li>
            <li>plaagdierbeheersing.</li>
        </ul>
        <p>Het gaat dus niet alleen om hoe schoon een keuken eruitziet. Ook de manier waarop medewerkers dagelijks werken en controles uitvoeren is belangrijk.</p>

        <h2>1. HACCP en voedselveilig werken</h2>
        <p>Horecabedrijven moeten voedselveilig werken. Hiervoor kan een onderneming werken volgens een goedgekeurde hygiënecode of een eigen voedselveiligheidsplan op basis van HACCP.</p>
        <p>In de dagelijkse praktijk betekent dit dat belangrijke voedselveiligheidsprocessen goed moeten worden uitgevoerd. Denk bijvoorbeeld aan:</p>
        <ul>
            <li>ontvangst van producten;</li>
            <li>opslag van levensmiddelen;</li>
            <li>bereiden van eten;</li>
            <li>koelen en verhitten;</li>
            <li>schoonmaken;</li>
            <li>voorkomen van kruisbesmetting;</li>
            <li>controleren van kritische punten.</li>
        </ul>
        <p>Een systeem met vaste checklists kan medewerkers helpen om deze werkzaamheden consequent uit te voeren.</p>
        <p><strong>Meer informatie:</strong> <a href="{{ route('seo.haccp-app') }}">HACCP-app</a></p>

        <h2>2. Temperatuurcontroles</h2>
        <p>Temperatuurbeheersing speelt een belangrijke rol bij voedselveiligheid. Afhankelijk van je werkzaamheden kan het bijvoorbeeld nodig zijn aandacht te besteden aan de temperatuur van:</p>
        <ul>
            <li>koelkasten;</li>
            <li>koelcellen;</li>
            <li>vriezers;</li>
            <li>ontvangen producten;</li>
            <li>bereide of bewaarde levensmiddelen.</li>
        </ul>
        <p>Een veelvoorkomend probleem met papieren registraties is dat controles worden vergeten of dat formulieren later moeilijk terug te vinden zijn. Met een digitale temperatuurregistratie kunnen medewerkers controles direct vastleggen.</p>
        <p><strong>Bekijk ook:</strong> <a href="{{ route('seo.temperatuurregistratie-app') }}">Temperatuurregistratie-app</a></p>

        <h2>3. Schoonmaak en hygiëne</h2>
        <p>Een goede hygiëne is essentieel wanneer je met voedsel werkt. Daarom is het belangrijk dat er duidelijke afspraken bestaan over bijvoorbeeld:</p>
        <ul>
            <li>werkbanken;</li>
            <li>keukenapparatuur;</li>
            <li>vloeren;</li>
            <li>koelingen;</li>
            <li>opslagruimtes;</li>
            <li>toiletten;</li>
            <li>andere relevante contactoppervlakken.</li>
        </ul>
        <p>Met een vaste schoonmaakchecklist weet iedere medewerker welke werkzaamheden uitgevoerd moeten worden en wanneer. Digitale checklists kunnen daarnaast inzicht geven in welke taken al zijn afgerond en welke nog openstaan.</p>

        <h2>4. Persoonlijke hygiëne</h2>
        <p>Niet alleen de locatie moet schoon zijn. Ook medewerkers moeten hygiënisch omgaan met voedsel.</p>
        <p>Denk bijvoorbeeld aan goede handhygiëne, schone werkkleding en het voorkomen van onnodige besmetting van levensmiddelen. Het is daarom verstandig om belangrijke hygiëneprocedures onderdeel te maken van de dagelijkse werkwijze en medewerkers hier goed over te instrueren.</p>

        <h2>5. Allergenen</h2>
        <p>Allergeneninformatie blijft een belangrijk aandachtspunt binnen voedselveiligheid. Horecaondernemers moeten consumenten kunnen informeren over de aanwezigheid van de 14 wettelijk vastgestelde allergenen wanneer dat van toepassing is.</p>
        <p>Sinds 1 januari 2026 gelden daarnaast nieuwe regels voor waarschuwingen voor mogelijke en onbedoelde aanwezigheid van allergenen door kruisbesmetting bij voorverpakte levensmiddelen. De NVWA heeft aangegeven hier in 2026 toezicht op te houden.</p>
        <p>Voor horecaondernemers is het daarom belangrijk om niet alleen naar eigen recepten te kijken, maar ook goed naar informatie op etiketten en ingrediënten van leveranciers.</p>

        <h2>6. Plaagdierbeheersing</h2>
        <p>Ongedierte kan een risico vormen voor voedselveiligheid. Daarom is het belangrijk om signalen van plaagdieren tijdig te herkennen en passende maatregelen te nemen.</p>
        <p>Een periodieke controlelijst kan bijvoorbeeld helpen om aandacht te houden voor risicoplekken en geconstateerde problemen intern vast te leggen.</p>

        <h2>7. Registraties en aantoonbaarheid</h2>
        <p>Goede procedures zijn belangrijk, maar in de praktijk wil je als ondernemer ook overzicht hebben over wat er daadwerkelijk gebeurt. Papieren checklists kunnen daarbij onhandig zijn:</p>
        <ul>
            <li>formulieren kunnen kwijtraken;</li>
            <li>registraties kunnen worden vergeten;</li>
            <li>informatie staat verspreid over verschillende mappen;</li>
            <li>managers hebben niet direct overzicht;</li>
            <li>bewijs en registraties moeten handmatig worden verzameld.</li>
        </ul>
        <p>Digitale registratie kan deze administratie overzichtelijker maken.</p>

        <h2>Hoe TaskCheck hierbij kan helpen</h2>
        <p>TaskCheck is ontwikkeld voor bedrijven die met terugkerende taken, checklists en controles werken. Je kunt bijvoorbeeld digitale lijsten maken voor:</p>
        <ul>
            <li>HACCP-gerelateerde controles;</li>
            <li>temperatuurregistraties;</li>
            <li>opening en sluiting;</li>
            <li>schoonmaakwerkzaamheden;</li>
            <li>dagelijkse taken;</li>
            <li>controles per locatie.</li>
        </ul>
        <p>Medewerkers voeren de taken uit via hun telefoon, tablet of computer. Afhankelijk van de ingestelde taak kunnen daarbij ook foto's, video's, opmerkingen of handtekeningen als bewijs worden toegevoegd.</p>

        <h2>Realtime overzicht voor managers</h2>
        <p>In plaats van aan het einde van de week verschillende papieren formulieren te verzamelen, kun je digitaal volgen wat er gebeurt. Zo kun je bijvoorbeeld zien:</p>
        <ul>
            <li>welke taken zijn afgerond;</li>
            <li>welke taken nog openstaan;</li>
            <li>welke controles zijn uitgevoerd;</li>
            <li>waar bewijs is toegevoegd;</li>
            <li>welke locatie aandacht nodig heeft.</li>
        </ul>
        <p>Hierdoor kun je eerder bijsturen wanneer werkzaamheden niet volgens planning verlopen.</p>

        <h2>Meerdere horecalocaties beheren</h2>
        <p>Voor restaurants of horecaketens met meerdere vestigingen wordt standaardisatie extra belangrijk. Iedere locatie moet volgens duidelijke procedures kunnen werken.</p>
        <p>Met TaskCheck kun je checklists en werkzaamheden per locatie en team organiseren. Zo ontstaat één centrale werkwijze en heeft het management meer overzicht over de uitvoering.</p>

        <h2>Digitale checklists vervangen geen voedselveiligheidsbeleid</h2>
        <p>Een app alleen maakt een bedrijf niet automatisch HACCP-compliant en garandeert ook niet dat een NVWA-inspectie goed verloopt. De verantwoordelijkheid voor voedselveilig werken blijft bij de onderneming.</p>
        <p>Digitale checklists kunnen wel helpen om procedures duidelijk te maken, terugkerende controles te organiseren en uitgevoerde werkzaamheden overzichtelijk vast te leggen.</p>
        <p>Controleer voor de actuele wettelijke eisen en officiële informatie altijd de informatie van de NVWA en andere bevoegde instanties.</p>

        <h2>Voorbereiden op een NVWA-controle</h2>
        <p>Een goede voorbereiding begint niet op de dag van een inspectie. Het is vooral belangrijk dat voedselveilig werken onderdeel is van de dagelijkse routine.</p>
        <p>Een praktische aanpak is:</p>
        <ol>
            <li>Leg belangrijke procedures duidelijk vast.</li>
            <li>Geef medewerkers vaste verantwoordelijkheden.</li>
            <li>Voer benodigde controles consequent uit.</li>
            <li>Leg relevante registraties overzichtelijk vast.</li>
            <li>Controleer regelmatig of taken daadwerkelijk worden uitgevoerd.</li>
            <li>Pak afwijkingen en problemen tijdig aan.</li>
            <li>Houd procedures en instructies actueel.</li>
        </ol>
        <p>Zo wordt voedselveiligheid onderdeel van het dagelijkse proces in plaats van iets waar pas vlak voor een controle naar wordt gekeken.</p>

        <h2>Van papier naar digitaal</h2>
        <p>Werk je momenteel met papieren HACCP-formulieren, temperatuurregistraties en schoonmaaklijsten? Dan kan digitaliseren een logische volgende stap zijn. TaskCheck brengt checklists, taken, registraties en bewijs samen in één platform.</p>
        <p>Lees ook:</p>
        <ul>
            <li><a href="{{ route('seo.haccp-app') }}">HACCP App</a></li>
            <li><a href="{{ route('seo.haccp-formulieren') }}">HACCP Formulieren</a></li>
            <li><a href="{{ route('seo.digitale-haccp-registratie') }}">Digitale HACCP Registratie</a></li>
            <li><a href="{{ route('seo.temperatuurregistratie-app') }}">Temperatuurregistratie App</a></li>
            <li><a href="{{ route('seo.restaurant-checklist-app') }}">Restaurant Checklist App</a></li>
            <li><a href="{{ route('seo.horeca-app') }}">Horeca App</a></li>
        </ul>

        <h2>Conclusie</h2>
        <p>De NVWA kijkt bij toezicht op horecabedrijven naar verschillende aspecten van voedselveiligheid. Hygiëne, temperatuurbeheersing, voedselveilig werken, allergenen en plaagdierbeheersing kunnen daarbij belangrijke onderwerpen zijn.</p>
        <p>Voor horecaondernemers is het daarom verstandig om controles niet alleen uit te voeren, maar dagelijkse processen ook goed te organiseren. Met digitale checklists kun je medewerkers duidelijke instructies geven, werkzaamheden registreren en sneller inzicht krijgen in wat wel en niet is uitgevoerd.</p>
        <p>Wil je jouw dagelijkse horeca-controles digitaliseren?</p>
    </article>

    <div class="mt-10 rounded-2xl border border-slate-200 bg-slate-900 p-6 sm:p-8">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <p class="text-lg font-bold leading-snug text-white sm:text-xl">Probeer TaskCheck 14 dagen gratis</p>
                <p class="mt-2 max-w-md text-sm leading-relaxed text-slate-400">Digitale checklists, temperatuurregistratie en bewijs per taak. Geen creditcard nodig.</p>
                <div class="mt-4 flex flex-wrap gap-x-4 gap-y-2 text-xs font-medium text-slate-400">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Direct starten
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Op telefoon en computer
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Opzeggen wanneer je wilt
                    </span>
                </div>
            </div>
            <div class="flex flex-col gap-2.5 sm:flex-shrink-0">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500">Naar dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500">Start 14 dagen gratis</a>
                @endauth
                <a href="{{ route('pricing') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-white/15 bg-white/5 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">Bekijk prijzen</a>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <h2 class="text-2xl font-bold text-slate-900" style="padding-left:0.75rem;border-left:3px solid #2563eb;">Veelgestelde vragen</h2>
        <div class="mt-5 space-y-3">
            @foreach($faqItems as $item)
            <details class="group rounded-2xl border border-slate-200 bg-white px-5 py-4 transition hover:border-blue-200">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 font-semibold text-slate-900">
                    <span class="text-left text-sm">{{ $item[0] }}</span>
                    <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-45" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </summary>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $item[1] }}</p>
            </details>
            @endforeach
        </div>
    </div>

    <div class="mt-12 border-t border-slate-200 pt-8">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Meer lezen</h3>
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('seo.haccp-app') }}" class="group flex gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-blue-300">
                <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">HACCP-app</span>
            </a>
            <a href="{{ route('seo.opening-checklist-horeca') }}" class="group flex gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-blue-300">
                <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">Opening checklist horeca</span>
            </a>
            <a href="{{ route('seo.sluitings-checklist-horeca') }}" class="group flex gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-blue-300">
                <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">Sluitingschecklist horeca</span>
            </a>
            <a href="{{ route('pricing') }}" class="group flex gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-blue-300">
                <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">Prijzen</span>
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
.prose-article p {
    font-size: 1rem;
    line-height: 1.8;
    color: #475569;
    margin-bottom: 1rem;
}
.prose-article ul, .prose-article ol {
    padding: 0;
    margin: 1rem 0 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.prose-article ul { list-style: none; }
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
.prose-article ol {
    list-style: none;
    counter-reset: nvwa;
}
.prose-article ol li {
    counter-increment: nvwa;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    font-size: 0.9375rem;
    color: #475569;
    line-height: 1.6;
}
.prose-article ol li::before {
    content: counter(nvwa);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 1.5rem;
    height: 1.5rem;
    border-radius: 9999px;
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
    margin-top: 0.05rem;
}
.prose-article a {
    color: #2563eb;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.prose-article a:hover { color: #1d4ed8; }
details summary::-webkit-details-marker { display: none; }
</style>

@include('components.footer')
</body>
</html>
