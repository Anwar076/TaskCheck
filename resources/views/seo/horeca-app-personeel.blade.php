<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Horeca app personeel voor checklists en werkcontrole | TaskCheck';
        $seoDescription = 'Horeca app personeel: plan taken per shift, controleer uitvoering met bewijs en houd grip op kwaliteit in restaurant, keuken en bediening.';
        $seoUrl = route('seo.horeca-app-personeel');
        $seoImage = asset('images/taskcheck-horeca-personeel-seo-hero.webp');
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
        <h1 class="text-4xl sm:text-5xl font-bold text-slate-900">Horeca app personeel voor dagelijkse taken en controle</h1>
        <p class="mt-4 text-lg text-slate-600">TaskCheck helpt horeca teams om taken per dienst duidelijk te verdelen, uit te voeren en te controleren met bewijs.</p>

        <div class="mt-8 grid md:grid-cols-2 gap-4">
            <a href="{{ route('pricing') }}" class="inline-flex justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold px-6 py-3 hover:from-blue-700 hover:to-indigo-700">Bekijk prijzen</a>
            <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl border border-blue-200 bg-white text-slate-700 font-semibold px-6 py-3 hover:bg-blue-50">Start 14 dagen gratis</a>
        </div>

        <div class="mt-8 rounded-2xl border border-blue-100 bg-white p-2 shadow-sm">
            <img src="{{ asset('images/taskcheck-horeca-personeel-seo-hero.webp') }}" alt="Horeca app personeel met TaskCheck dashboard en mobiele takenlijsten" class="w-full rounded-xl" loading="lazy">
        </div>

        <section class="mt-10 space-y-5 text-slate-700 leading-7">
            <h2 class="text-2xl font-bold text-slate-900">Een horeca check app die op de vloer werkt</h2>
            <p>In een restaurant wisselt de druk per moment. Daardoor is het lastig om kwaliteit vast te houden met losse lijstjes of mondelinge overdracht. Met TaskCheck werk je met een horeca check app waarin opening, mise-en-place, service en sluiting als vaste workflows klaarstaan.</p>
            <p>Medewerkers zien direct wat hun verantwoordelijkheid is. Teamleiders zien waar taken blijven liggen en kunnen snel bijsturen zonder extra administratie.</p>

            <h2 class="text-2xl font-bold text-slate-900">Restaurant checklist app voor keuken, bar en bediening</h2>
            <p>Per taak kun je instructies, deadlines en verplicht bewijs instellen. Denk aan temperatuurcontrole, HACCP-rondes, schoonmaak van werkstations en voorraadcontrole. Zo wordt je restaurant checklist app niet alleen een afvinklijst, maar een betrouwbaar controle-instrument.</p>
            <p>Omdat bewijs centraal wordt opgeslagen, kun je achteraf eenvoudig laten zien wat wanneer is gedaan. Dat helpt bij kwaliteitsgesprekken, audits en onboarding van nieuw personeel.</p>

            <h2 class="text-2xl font-bold text-slate-900">Waarom dit helpt bij personeelssturing</h2>
            <p>Als taken duidelijk zijn, verbetert de uitvoering vanzelf: minder misverstanden, minder herstelwerk en stabielere service. Met realtime inzicht zie je trends per team of locatie en kun je gericht coachen op taken die vaak te laat of onvolledig worden afgerond.</p>
            <p>Daarmee groeit TaskCheck van taakbeheer online naar een praktische aanpak voor continue verbetering in je horeca operatie.</p>

            <h2 class="text-2xl font-bold text-slate-900">Gerelateerde pagina's</h2>
            <p>Wil je verder vergelijken? Bekijk ook <a class="text-blue-700 font-semibold" href="{{ route('seo.horeca-checklist-app') }}">horeca checklist app</a> en <a class="text-blue-700 font-semibold" href="{{ route('seo.werkcontrole-app') }}">werkcontrole app</a>. Voor extra context kun je ons artikel lezen over <a class="text-blue-700 font-semibold" href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}">horeca personeel controleren met checklist app</a>.</p>

            <h2 class="text-2xl font-bold text-slate-900">Veelgestelde vragen over horeca app personeel</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Wat is een horeca app voor personeel precies?</h3>
                    <p>Een horeca app voor personeel is een digitale takenomgeving waarin medewerkers per dienst zien wat er moet gebeuren. Denk aan opening, keukencontrole, schoonmaak en sluiting met duidelijke deadlines en bewijs per taak.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Kan ik keuken, bar en bediening apart aansturen?</h3>
                    <p>Ja, in TaskCheck kun je takenlijsten opdelen per rol, team of locatie. Zo krijgt elk team alleen de taken die voor die dienst relevant zijn, met heldere instructies en opvolging.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Helpt dit ook bij HACCP en kwaliteitscontroles?</h3>
                    <p>Ja, je kunt bewijs zoals foto, video en notities verplicht maken voor kritieke controles. Daardoor heb je aantoonbaar overzicht bij audits, interne checks en kwaliteitsgesprekken.</p>
                </div>
            </div>
        </section>
    </div>
</main>
@include('components.footer')
</body>
</html>
