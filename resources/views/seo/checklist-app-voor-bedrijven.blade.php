<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Checklist app voor bedrijven | TaskCheck';
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
<body class="bg-gradient-to-br from-sky-50 via-indigo-50 to-fuchsia-50 min-h-screen font-sans text-slate-900">
@include('components.header')
<main class="pt-28 pb-16">
    <div class="max-w-5xl mx-auto px-6">
        <h1 class="text-4xl sm:text-5xl font-bold text-slate-900">Checklist app voor bedrijven en teams</h1>
        <p class="mt-4 text-lg text-slate-600">TaskCheck is de slimme checklist app voor bedrijven die grip willen op hun werk. Beheer taken, controleer werk en verzamel bewijs op een plek.</p>

        <div class="mt-8 grid md:grid-cols-2 gap-4">
            <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold px-6 py-3 hover:from-blue-700 hover:to-indigo-700">Start 14 dagen gratis</a>
            <a href="{{ route('pricing') }}" class="inline-flex justify-center rounded-xl border border-blue-200 bg-white text-slate-700 font-semibold px-6 py-3 hover:bg-blue-50">Bekijk prijzen</a>
        </div>

        <div class="mt-8 rounded-2xl border border-blue-100 bg-white p-2 shadow-sm">
            <img src="{{ asset('images/taskcheck-platform-overview.webp') }}" alt="TaskCheck checklist app dashboard en mobiele app overzicht" class="w-full rounded-xl" loading="lazy">
        </div>

        <section class="mt-10 space-y-5 text-slate-700 leading-7">
            <h2 class="text-2xl font-bold text-slate-900">Wat is TaskCheck?</h2>
            <p>TaskCheck is een digitale checklist software en taakbeheer app. Je maakt eenvoudig takenlijsten voor je team en volgt alles realtime. Geen losse papieren meer: alles digitaal, duidelijk en overzichtelijk.</p>
            <p>Perfect voor bedrijven in schoonmaak, logistiek, horeca, bouw en retail.</p>

            <h2 class="text-2xl font-bold text-slate-900">Waarom TaskCheck gebruiken?</h2>
            <p>Met TaskCheck weet je altijd wat er gebeurt op de werkvloer.</p>
            <p>Realtime inzicht in taken. Bewijs per taak met foto, video en handtekening. Minder fouten en betere controle. Alles op mobiel en desktop. Makkelijk in gebruik.</p>
            <p>Je ziet direct of werk goed is uitgevoerd.</p>

            <h2 class="text-2xl font-bold text-slate-900">Bewijs per taak</h2>
            <p>Laat medewerkers bewijs uploaden bij elke taak. Bijvoorbeeld een foto van schoonmaak, video van controle of handtekening van klant. Zo voorkom je discussies en heb je altijd bewijs.</p>

            <h2 class="text-2xl font-bold text-slate-900">Voor wie is deze checklist app?</h2>
            <p>TaskCheck is gemaakt voor teams die met taken werken. Gebruik het voor schoonmaak controles, werkinspecties, opening en closing checklists, kwaliteitscontroles en dagelijkse taken.</p>
            <p>Of je nu 5 of 100 medewerkers hebt: TaskCheck groeit met je mee.</p>

            <h2 class="text-2xl font-bold text-slate-900">AI checklists maken</h2>
            <p>Upload een PDF, Excel of foto en TaskCheck maakt automatisch een checklist voor je. Geen gedoe meer met handmatig invoeren, binnen enkele seconden klaar.</p>

            <h2 class="text-2xl font-bold text-slate-900">Prijzen</h2>
            <p>Gebruik TaskCheck al vanaf EUR 29 per maand. Geen installatie nodig, direct starten en 14 dagen gratis proberen.</p>

            <h2 class="text-2xl font-bold text-slate-900">Start vandaag nog</h2>
            <p>Wil je meer overzicht en minder fouten? Start vandaag met TaskCheck en probeer 14 dagen gratis.</p>
        </section>
    </div>
</main>
@include('components.footer')
</body>
</html>
