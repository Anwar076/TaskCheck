<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Prijzen checklist app voor bedrijven en personeel | TaskCheck';
        $seoDescription = 'Eerlijke prijzen voor TaskCheck. Starter, Professional, Business en Enterprise op aanvraag. Start 14 dagen gratis zonder creditcard.';
        $seoUrl = route('pricing');
        $seoImage = asset('images/taskcheck-dashboard-hero.webp');
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
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
</head>
<body class="bg-gradient-to-br from-sky-50 via-indigo-50 to-fuchsia-50 min-h-screen font-sans text-slate-900">
    @include('components.header')

    <section class="pt-28 pb-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-slate-900">Eerlijke prijzen voor elk team</h1>
            <p class="mt-4 text-slate-600 text-lg">Kies het plan dat past bij jouw organisatie. Start direct en ervaar TaskCheck 14 dagen gratis.</p>
            <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-full border border-emerald-200 bg-emerald-50 text-sm text-emerald-700 font-medium">
                <span>🎉</span> 14 dagen gratis proberen - geen creditcard nodig
            </div>
            
        </div>
    </section>

    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-4 md:grid-cols-2 gap-6 items-stretch">
            <article class="rounded-2xl border border-blue-100 bg-white/90 p-6 flex flex-col h-full">
                <div class="min-h-[82px]">
                    <h2 class="text-2xl font-bold text-slate-900 leading-tight">Starter</h2>
                    <p class="text-sm text-slate-500 mt-1 leading-snug">Voor kleine teams die willen starten met structuur</p>
                </div>
                <p class="mt-4 text-4xl font-extrabold text-blue-600 leading-none">€39<span class="text-base font-medium text-slate-500"> / maand</span></p>
                <p class="mt-3 text-sm text-slate-600 min-h-[72px]">Alles wat je nodig hebt om direct te beginnen met digitale checklists en controle.</p>
                <ul class="mt-5 space-y-2 text-sm text-slate-600 flex-1">
                    <li>• 1 admin account</li>
                    <li>• 5 medewerker accounts</li>
                    <li>• 1 locatie</li>
                    <li>• Taken met foto- en videobewijs</li>
                    <li>• Realtime voortgangsoverzicht</li>
                    <li>• Mobiele webapp (installeerbaar)</li>
                </ul>
                <form action="{{ route('subscription.activate') }}" method="POST" class="mt-6">
                    @csrf
                    <input type="hidden" name="plan" value="starter">
                    <button type="submit" class="w-full rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 transition">Start 14 dagen gratis</button>
                </form>
            </article>

            <article class="rounded-2xl border-2 border-blue-500 bg-white p-6 relative flex flex-col h-full">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-blue-600 text-white text-xs font-bold">Meest gekozen</span>
                <div class="min-h-[82px]">
                    <h2 class="text-2xl font-bold text-slate-900 leading-tight">Professional</h2>
                    <p class="text-sm text-slate-500 mt-1 leading-snug">Voor teams die meer controle en automatisering willen</p>
                </div>
                <p class="mt-4 text-4xl font-extrabold text-blue-600 leading-none">€99<span class="text-base font-medium text-slate-500"> / maand</span></p>
                <p class="mt-3 text-sm text-slate-600 min-h-[72px]">Meer inzicht, minder handmatig werk en sneller schakelen dankzij AI en rapportages.</p>
                <ul class="mt-5 space-y-2 text-sm text-slate-600 flex-1">
                    <li>• 2 admin accounts</li>
                    <li>• 10 medewerker accounts</li>
                    <li>• 2 locaties</li>
                    <li>• AI-import (PDF, Excel, Word of foto)</li>
                    <li>• Weekoverzicht & rapportages</li>
                    <li>• Taken met foto- en videobewijs</li>
                    <li>• Realtime voortgangsoverzicht</li>
                    <li>• Mobiele webapp (installeerbaar)</li>
                    <li>• Priority support</li>
                </ul>
                <form action="{{ route('subscription.activate') }}" method="POST" class="mt-6">
                    @csrf
                    <input type="hidden" name="plan" value="professional">
                    <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 transition">Start 14 dagen gratis</button>
                </form>
            </article>

            <article class="rounded-2xl border border-indigo-100 bg-white/90 p-6 flex flex-col h-full">
                <div class="min-h-[82px]">
                    <h2 class="text-2xl font-bold text-slate-900 leading-tight">Business</h2>
                    <p class="text-sm text-slate-500 mt-1 leading-snug">Voor bedrijven met meerdere locaties en grotere teams</p>
                </div>
                <p class="mt-4 text-4xl font-extrabold text-blue-600 leading-none">€179<span class="text-base font-medium text-slate-500"> / maand</span></p>
                <p class="mt-3 text-sm text-slate-600 min-h-[72px]">Volledige controle over meerdere locaties, met diep inzicht in prestaties en kwaliteit per vestiging.</p>
                <ul class="mt-5 space-y-2 text-sm text-slate-600 flex-1">
                    <li>• 5 admin accounts</li>
                    <li>• 20 medewerker accounts</li>
                    <li>• 3 locaties</li>
                    <li>• Uitgebreide rapportages per locatie</li>
                    <li>• Inzicht in prestaties per team en locatie</li>
                    <li>• Taken met foto- en videobewijs</li>
                    <li>• Realtime voortgangsoverzicht</li>
                    <li>• Mobiele webapp (installeerbaar)</li>
                    <li>• Priority support</li>
                </ul>
                <form action="{{ route('subscription.activate') }}" method="POST" class="mt-6">
                    @csrf
                    <input type="hidden" name="plan" value="business">
                    <button type="submit" class="w-full rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 transition">Start 14 dagen gratis</button>
                </form>
            </article>

            <article class="rounded-2xl border border-fuchsia-100 bg-white/90 p-6 flex flex-col h-full">
                <div class="min-h-[82px]">
                    <h2 class="text-2xl font-bold text-slate-900 leading-tight">Enterprise</h2>
                    <p class="text-sm text-slate-500 mt-1 leading-snug">Voor grotere organisaties en ketens</p>
                </div>
                <p class="mt-4 text-4xl font-extrabold text-blue-600 leading-none">Op aanvraag</p>
                <p class="mt-3 text-sm text-slate-600 min-h-[72px]">Volledig op maat ingericht voor jouw organisatie, met maximale flexibiliteit en ondersteuning.</p>
                <ul class="mt-5 space-y-2 text-sm text-slate-600 flex-1">
                    <li>• Onbeperkte admins & medewerkers</li>
                    <li>• Dedicated accountmanager</li>
                    <li>• SLA met uptime-garantie</li>
                    <li>• Persoonlijke onboarding</li>
                    <li>• Maatwerk integraties</li>
                </ul>
                <a href="{{ route('contact') }}" class="mt-6 inline-flex w-full justify-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold py-3 transition">Vraag een offerte aan</a>
            </article>
        </div>
        <div class="max-w-7xl mx-auto px-6 mt-5">
            <p class="text-center text-sm text-slate-600">
                Er wordt bij het afrekenen 21% btw in rekening gebracht, het standaardtarief in Nederland.
            </p>
        </div>
    </section>

    <section class="pb-16">
        <div class="max-w-5xl mx-auto px-6 space-y-4">
            <div class="rounded-2xl border border-blue-100 bg-white/90 p-5">
                <h3 class="font-semibold text-slate-900">Hoe werkt betalen?</h3>
                <p class="text-sm text-slate-600 mt-1">Na je plankeuze ga je naar een beveiligde Mollie-checkout. Je abonnement wordt direct geactiveerd na betaling.</p>
            </div>
            <div class="rounded-2xl border border-indigo-100 bg-white/90 p-5">
                <h3 class="font-semibold text-slate-900">Kan ik tussentijds wisselen van plan?</h3>
                <p class="text-sm text-slate-600 mt-1">Ja, je kunt op- en afschalen vanuit je abonnementspagina. Je betaalt alleen wat je gebruikt.</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white/90 p-5">
                <h3 class="font-semibold text-slate-900">Wat gebeurt er na 14 dagen gratis?</h3>
                <p class="text-sm text-slate-600 mt-1">Je kiest een plan en betaalt pas dan. Geen automatische afschrijving zonder jouw akkoord.</p>
            </div>
            <div class="rounded-2xl border border-fuchsia-100 bg-white/90 p-5">
                <h3 class="font-semibold text-slate-900">Hebben jullie kortingen voor jaarlijkse betaling?</h3>
                <p class="text-sm text-slate-600 mt-1">Neem contact op. Voor jaarabonnementen bieden we maatwerk.</p>
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
