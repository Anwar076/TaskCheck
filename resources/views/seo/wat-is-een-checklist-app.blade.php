<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Wat is een checklist app? | Uitleg + voordelen | TaskCheck';
        $seoDescription = 'Wat is een checklist app en hoe werkt het? Ontdek de voordelen voor bedrijven en teams. Start gratis met TaskCheck.';
        $seoUrl = route('seo.wat-is-een-checklist-app');
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
        <h1 class="text-4xl sm:text-5xl font-bold text-slate-900">Wat is een checklist app?</h1>
        <p class="mt-4 text-lg text-slate-600">Een checklist app is een digitale tool waarmee bedrijven takenlijsten maken, beheren en controleren. Medewerkers vinken taken af en voegen bewijs toe zoals foto's, video's of handtekeningen.</p>
        <p class="mt-3 text-slate-600">Met een checklist app werk je overzichtelijker en voorkom je fouten.</p>

        <div class="mt-8 grid md:grid-cols-2 gap-4">
            <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold px-6 py-3 hover:from-blue-700 hover:to-indigo-700">Start 14 dagen gratis</a>
            <a href="{{ route('pricing') }}" class="inline-flex justify-center rounded-xl border border-blue-200 bg-white text-slate-700 font-semibold px-6 py-3 hover:bg-blue-50">Bekijk prijzen</a>
        </div>

        <section class="mt-10 space-y-5 text-slate-700 leading-7">
            <p>Steeds meer bedrijven stappen over van papier naar digitale checklists omdat het sneller, duidelijker en beter controleerbaar is.</p>
            <p>In deze pagina leggen we simpel uit wat een checklist app is en hoe je het gebruikt.</p>

            <h2 class="text-2xl font-bold text-slate-900">Hoe werkt een checklist app?</h2>
            <p>Je maakt een takenlijst, wijst die toe aan medewerkers, zij voeren taken uit en vinken af, voegen bewijs toe, en jij ziet realtime wat er gebeurt. Alles werkt op mobiel en desktop.</p>

            <h2 class="text-2xl font-bold text-slate-900">Waar wordt een checklist app voor gebruikt?</h2>
            <p>Checklist apps worden gebruikt in schoonmaak, horeca, logistiek, bouw en retail. Overal waar taken gecontroleerd moeten worden, helpt checklist software om structuur te houden.</p>

            <h2 class="text-2xl font-bold text-slate-900">Voordelen van een checklist app</h2>
            <p>Met een checklist app heb je realtime inzicht, werk je zonder papier, voorkom je fouten, controleer je uitvoering en verzamel je bewijs per taak. Dat zorgt voor meer overzicht en betere kwaliteit.</p>

            <h2 class="text-2xl font-bold text-slate-900">Wat maakt TaskCheck anders?</h2>
            <p>TaskCheck is meer dan alleen een takenlijst app. Je voegt bewijs toe per taak, ziet realtime wat je team doet en maakt automatisch checklists met AI via PDF, Excel of foto. Zo bespaar je tijd en voorkom je discussies.</p>

            <h2 class="text-2xl font-bold text-slate-900">Voor wie is een checklist app geschikt?</h2>
            <p>Voor bedrijven met personeel, teams die met taken werken en managers die controle willen. Of je nu klein bent of groot, TaskCheck groeit met je mee.</p>

            <h2 class="text-2xl font-bold text-slate-900">Wat kost een checklist app?</h2>
            <p>TaskCheck start vanaf EUR 29 per maand. Je kunt gratis proberen zonder verplichtingen.</p>

            <h2 class="text-2xl font-bold text-slate-900">Start met TaskCheck</h2>
            <p>Wil je minder fouten en meer overzicht? Start vandaag met TaskCheck en probeer 14 dagen gratis.</p>

            <div class="rounded-2xl border border-blue-100 bg-white/90 p-5">
                <p class="font-semibold text-slate-900">Handige pagina's</p>
                <p class="mt-2 text-sm text-slate-600">Bekijk ook <a class="text-blue-700 font-semibold" href="{{ route('welcome') }}">homepage</a>, <a class="text-blue-700 font-semibold" href="{{ route('pricing') }}">pricing</a> en <a class="text-blue-700 font-semibold" href="{{ route('seo.schoonmaak-checklist-app') }}">voor schoonmaak</a>.</p>
            </div>

            <h2 class="text-2xl font-bold text-slate-900">Veelgestelde vragen</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Wat is een checklist app?</h3>
                    <p>Een checklist app is een digitale tool waarmee je takenlijsten maakt en controleert. Medewerkers kunnen taken afvinken en bewijs uploaden.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Waarom een checklist app gebruiken?</h3>
                    <p>Omdat je meer overzicht hebt, fouten voorkomt en werk beter kunt controleren.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Is een checklist app geschikt voor kleine bedrijven?</h3>
                    <p>Ja, ook kleine teams profiteren van meer structuur en overzicht.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Kan ik foto's toevoegen aan taken?</h3>
                    <p>Ja, met TaskCheck kun je per taak foto's, video's en handtekeningen toevoegen.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Werkt een checklist app op mobiel?</h3>
                    <p>Ja, TaskCheck werkt op telefoon en desktop.</p>
                </div>
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
