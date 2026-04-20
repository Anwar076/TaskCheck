<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Werkcontrole app voor teams en locaties | TaskCheck';
        $seoDescription = 'Werkcontrole app voor bedrijven: realtime taken, controle op uitvoering en bewijs per taak. Geschikt voor horeca, schoonmaak en meer.';
        $seoUrl = route('seo.werkcontrole-app');
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
        <h1 class="text-4xl sm:text-5xl font-bold text-slate-900">Werkcontrole app voor dagelijkse operationele processen</h1>
        <p class="mt-4 text-lg text-slate-600">TaskCheck geeft managers en teams één centrale plek voor taakuitvoering, bewijs en kwaliteitscontrole.</p>

        <div class="mt-8 grid md:grid-cols-2 gap-4">
            <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold px-6 py-3 hover:from-blue-700 hover:to-indigo-700">Start gratis</a>
            <a href="{{ route('pricing') }}" class="inline-flex justify-center rounded-xl border border-blue-200 bg-white text-slate-700 font-semibold px-6 py-3 hover:bg-blue-50">Bekijk prijzen</a>
        </div>

        <section class="mt-10 space-y-5 text-slate-700 leading-7">
            <h2 class="text-2xl font-bold text-slate-900">Wat is een werkcontrole app?</h2>
            <p>Een werkcontrole app is software waarmee je dagelijkse taken plant, uitvoert en controleert. In plaats van losse lijsten in Excel of papier werk je met digitale workflows. Teams zien hun taken in realtime, managers zien voortgang en afwijkingen direct. Dat maakt uitvoering consistenter en sneller bijstuurbaar.</p>
            <p>Voor bedrijven met meerdere medewerkers of locaties is dit essentieel. Zonder centrale werkcontrole ontstaan fouten, verschillen in kwaliteit en extra herstelwerk. Met TaskCheck leg je processen vast en maak je resultaten meetbaar.</p>

            <h2 class="text-2xl font-bold text-slate-900">Kernvoordelen voor operationele teams</h2>
            <p><strong>Realtime zicht:</strong> je ziet direct welke taken openstaan, afgerond zijn of aandacht vragen.<br>
            <strong>Betrouwbaar bewijs:</strong> foto, video, tekst of handtekening per taak voor objectieve controle.<br>
            <strong>Minder ruis:</strong> heldere rollen en taken verminderen afhankelijkheid van mondelinge overdracht.<br>
            <strong>Schaalbaarheid:</strong> templates en herhaalplanning voor meerdere teams en locaties.</p>

            <h2 class="text-2xl font-bold text-slate-900">Voor welke bedrijven werkt het?</h2>
            <p>TaskCheck wordt ingezet in horeca, schoonmaak, facilitair, logistiek en service-organisaties. Overal waar kwaliteit dagelijks moet worden uitgevoerd en gecontroleerd, biedt een werkcontrole app directe waarde. In horeca helpt het bij openings- en sluitroutines. In schoonmaak ondersteunt het rondes en oplevercontrole. In andere sectoren gebruik je dezelfde principes voor veiligheid, compliance en operationele discipline.</p>

            <h2 class="text-2xl font-bold text-slate-900">Van taakbeheer naar continue verbetering</h2>
            <p>Werkcontrole is meer dan afvinken. Door data over uitvoering te verzamelen zie je patronen: welke taken worden vaak te laat gedaan, waar ontbreekt bewijs, welke teams scoren structureel beter? Die inzichten gebruik je om processen te verbeteren, training te richten en klantresultaten te verhogen.</p>
            <p>Zo groeit de app mee van operationele basis naar strategisch stuurinstrument.</p>

            <h2 class="text-2xl font-bold text-slate-900">Volgende stap</h2>
            <p>Wil je dieper in teamuitvoering? Bekijk <a class="text-blue-700 font-semibold" href="{{ route('seo.takenlijst-personeel') }}">takenlijst personeel</a>. Werk je in foodservice? Lees <a class="text-blue-700 font-semibold" href="{{ route('seo.horeca-checklist-app') }}">horeca checklist app</a>. Voor praktijkinzicht kun je het blog bekijken: <a class="text-blue-700 font-semibold" href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}">waarom bedrijven stoppen met Excel</a>.</p>
        </section>
    </div>
</main>
@include('components.footer')
</body>
</html>
