<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Checklist app schoonmaak | Controle & bewijs | TaskCheck';
        $seoDescription = 'Checklist app voor schoonmaakbedrijven. Controleer werk, verzamel bewijs en voorkom fouten. Start gratis met TaskCheck.';
        $seoUrl = route('seo.checklist-app-schoonmaak');
        $seoImage = asset('logos/taskcheck-logo.png');
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
<body class="bg-gradient-to-br from-sky-50 via-indigo-50 to-fuchsia-50 min-h-screen font-sans text-slate-900">
@include('components.header')
<main class="pt-28 pb-16">
    <div class="max-w-5xl mx-auto px-6">
        <h1 class="text-4xl sm:text-5xl font-bold text-slate-900">Checklist app voor schoonmaakbedrijven</h1>
        <p class="mt-4 text-lg text-slate-600">Met een checklist app voor schoonmaakbedrijven controleer je eenvoudig of werk goed is uitgevoerd. Geen papieren lijsten meer, maar alles digitaal met realtime inzicht.</p>

        <div class="mt-8 grid md:grid-cols-2 gap-4">
            <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold px-6 py-3 hover:from-blue-700 hover:to-indigo-700">Start 14 dagen gratis met TaskCheck</a>
            <a href="{{ route('pricing') }}" class="inline-flex justify-center rounded-xl border border-blue-200 bg-white text-slate-700 font-semibold px-6 py-3 hover:bg-blue-50">Bekijk prijzen</a>
        </div>

        <section class="mt-10 space-y-5 text-slate-700 leading-7">
            <h2 class="text-2xl font-bold text-slate-900">Waarom een checklist app voor schoonmaak?</h2>
            <p>In de schoonmaak is controle belangrijk. Met papieren lijsten raak je snel overzicht kwijt. Met TaskCheck zie je direct wat is schoongemaakt, krijg je bewijs per taak met foto of video, voorkom je fouten en klachten, en werk je sneller en efficiënter.</p>

            <h2 class="text-2xl font-bold text-slate-900">Hoe werkt het?</h2>
            <p>Maak een schoonmaak checklist, wijs taken toe aan medewerkers, laat ze taken afvinken en foto's uploaden, en volg alles realtime op mobiel of desktop.</p>

            <h2 class="text-2xl font-bold text-slate-900">Voorbeelden van schoonmaak checklists</h2>
            <p>Gebruik TaskCheck voor kantoor schoonmaak checklist, hotel kamer controle, sportschool schoonmaak, restaurant hygiene controle en oplever schoonmaak. Alles is op maat te maken.</p>

            <h2 class="text-2xl font-bold text-slate-900">Bewijs per taak</h2>
            <p>Laat medewerkers foto's maken van schone vloeren, toiletten of lege prullenbakken. Zo voorkom je discussies met klanten en heb je altijd bewijs bij de hand.</p>

            <h2 class="text-2xl font-bold text-slate-900">Voordelen voor jouw bedrijf</h2>
            <p>Minder klachten van klanten, meer controle over personeel, betere kwaliteit van werk en minder tijd kwijt aan controle.</p>

            <h2 class="text-2xl font-bold text-slate-900">AI schoonmaak checklists maken</h2>
            <p>Upload een PDF, Excel of foto en TaskCheck maakt automatisch een checklist voor je. Snel en makkelijk.</p>

            <h2 class="text-2xl font-bold text-slate-900">Prijzen</h2>
            <p>Vanaf EUR 29 per maand, 14 dagen gratis en geen verplichtingen.</p>

            <h2 class="text-2xl font-bold text-slate-900">Start vandaag</h2>
            <p>Wil je meer controle en minder fouten? Start vandaag met TaskCheck en probeer gratis.</p>

            <h2 class="text-2xl font-bold text-slate-900">Veelgestelde vragen</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Wat is een checklist app voor schoonmaak?</h3>
                    <p>Een app waarmee je schoonmaaktaken digitaal bijhoudt en controleert.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Kan ik foto's toevoegen?</h3>
                    <p>Ja, per taak kun je foto's en video's uploaden.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Is dit geschikt voor kleine schoonmaakbedrijven?</h3>
                    <p>Ja, ook kleine teams hebben hier veel voordeel van.</p>
                </div>
            </div>

            <div class="rounded-2xl border border-blue-100 bg-white/90 p-5">
                <p class="font-semibold text-slate-900">Gerelateerde pagina's</p>
                <p class="mt-2 text-sm text-slate-600">Bekijk ook <a class="text-blue-700 font-semibold" href="{{ route('welcome') }}">homepage</a>, <a class="text-blue-700 font-semibold" href="{{ route('pricing') }}">pricing</a> en <a class="text-blue-700 font-semibold" href="{{ route('seo.beste-checklist-app-2026') }}">beste checklist app 2026</a>.</p>
            </div>

            <div class="mt-6 grid md:grid-cols-2 gap-4">
                <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold px-6 py-3 hover:from-blue-700 hover:to-indigo-700">Start vandaag met TaskCheck</a>
                <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl border border-blue-200 bg-white text-slate-700 font-semibold px-6 py-3 hover:bg-blue-50">Probeer 14 dagen gratis</a>
            </div>
        </section>
    </div>
</main>
@include('components.footer')
</body>
</html>
