<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Horeca checklist app voor restaurants en keukens | TaskCheck';
        $seoDescription = 'Horeca checklist app voor restaurants: taken beheren, personeel controleren en bewijs verzamelen met foto en video. Start 14 dagen gratis.';
        $seoUrl = route('seo.horeca-checklist-app');
        $seoImage = asset('images/taskcheck-horeca-seo-hero.webp');
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
        <h1 class="text-4xl sm:text-5xl font-bold text-slate-900">Horeca checklist app voor restaurants en keukens</h1>
        <p class="mt-4 text-lg text-slate-600">Met TaskCheck werk je met een duidelijke horeca checklist app die teams helpt om opening, service en sluiting consistent uit te voeren.</p>

        <div class="mt-8 grid md:grid-cols-2 gap-4">
            <a href="{{ route('pricing') }}" class="inline-flex justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold px-6 py-3 hover:from-blue-700 hover:to-indigo-700">Bekijk prijzen</a>
            <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl border border-blue-200 bg-white text-slate-700 font-semibold px-6 py-3 hover:bg-blue-50">Start 14 dagen gratis</a>
        </div>

        <div class="mt-8 rounded-2xl border border-blue-100 bg-white p-2 shadow-sm">
            <img src="{{ asset('images/taskcheck-horeca-seo-hero.webp') }}" alt="Horeca checklist app van TaskCheck met dashboard en mobiele werkcontrole" class="w-full rounded-xl" loading="lazy">
        </div>

        <section class="mt-10 space-y-5 text-slate-700 leading-7">
            <h2 class="text-2xl font-bold text-slate-900">Grip op dagelijkse horeca taken</h2>
            <p>In horeca draait kwaliteit op ritme. Als de opening niet strak loopt of de sluiting half gedaan wordt, voel je dat direct in service, hygiëne en klantbeleving. TaskCheck geeft je team vaste takenlijsten per shift. Daardoor weet iedereen wat er moet gebeuren en wanneer.</p>
            <p>Je kunt taken per rol inrichten: keuken, bediening, bar of teamleider. Elke taak krijgt duidelijke instructie en waar nodig bewijs. Zo bouw je een proces dat niet leunt op toeval of mondelinge afspraken.</p>

            <h2 class="text-2xl font-bold text-slate-900">Personeel controleren zonder discussie</h2>
            <p>Met een werkcontrole app zie je niet alleen of iets is afgevinkt, maar ook of het goed uitgevoerd is. Voor kritieke taken kun je foto- of videobewijs verplicht maken. Denk aan temperatuurcontrole, schoonmaak van werkstations of voorraadchecks. Dat geeft objectieve opvolging en minder discussie achteraf.</p>
            <p>Managers krijgen realtime inzicht per locatie of team. Je ziet direct waar achterstand zit, waar bewijs ontbreekt en welke taken vaak terugkomen als aandachtspunt. Daarmee stuur je op feiten in plaats van aannames.</p>

            <h2 class="text-2xl font-bold text-slate-900">Waarom horeca teams kiezen voor TaskCheck</h2>
            <p>TaskCheck is gebouwd voor operationele teams. Dat betekent: mobiel werken op de vloer, snelle afhandeling en duidelijke dashboards voor leidinggevenden. Je hoeft geen complexe software te implementeren om direct resultaat te zien. Start met je belangrijkste lijst, verbeter op basis van data en schaal daarna door naar alle processen.</p>
            <p>Veel restaurants combineren TaskCheck met periodieke kwaliteitsrondes en onboarding van nieuwe medewerkers. Door standaardtaken zichtbaar te maken, werk je sneller in en blijft de kwaliteit consistent, ook bij personeelswisselingen.</p>

            <h2 class="text-2xl font-bold text-slate-900">Interne links voor jouw volgende stap</h2>
            <p>Wil je vergelijken per sector? Bekijk ook <a class="text-blue-700 font-semibold" href="{{ route('seo.schoonmaak-checklist-app') }}">schoonmaak checklist app</a> en <a class="text-blue-700 font-semibold" href="{{ route('seo.werkcontrole-app') }}">werkcontrole app</a>. Liever eerst praktijkvoorbeelden? Lees ons artikel over <a class="text-blue-700 font-semibold" href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}">horeca personeel controleren met checklist app</a>.</p>
        </section>
    </div>
</main>
@include('components.footer')
</body>
</html>
