<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Prijzen TaskCheck - Starter, Professional en Enterprise';
        $seoDescription = 'Bekijk de TaskCheck prijzen: Starter, Professional en Enterprise. Veilige betaling via Mollie en 30 dagen gratis proefperiode.';
        $seoUrl = route('pricing');
        $seoImage = asset('icons/icon-512x512.png');
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="nl_NL">
    <meta property="og:site_name" content="TaskCheck">
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
            "@@type": "Product",
            "name": "TaskCheck abonnementen",
            "description": "{{ $seoDescription }}",
            "brand": {
                "@@type": "Brand",
                "name": "TaskCheck"
            },
            "offers": [
                {
                    "@@type": "Offer",
                    "name": "Starter",
                    "price": "29",
                    "priceCurrency": "EUR",
                    "url": "{{ $seoUrl }}"
                },
                {
                    "@@type": "Offer",
                    "name": "Professional",
                    "price": "79",
                    "priceCurrency": "EUR",
                    "url": "{{ $seoUrl }}"
                },
                {
                    "@@type": "Offer",
                    "name": "Enterprise",
                    "price": "149",
                    "priceCurrency": "EUR",
                    "url": "{{ $seoUrl }}"
                }
            ]
        }
    </script>
    <style>
        .plan-card {
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .plan-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 22px 38px -26px rgba(37, 99, 235, .45);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-sky-50 via-indigo-50 to-fuchsia-50 min-h-screen font-sans text-slate-900">
    @include('components.header')

    <section class="pt-28 pb-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl sm:text-5xl font-bold text-slate-900">Eerlijke prijzen voor elk team</h1>
                <p class="mt-4 text-slate-600 text-lg">
                    Kies een plan dat past bij je organisatie en start direct met een veilige checkout via Mollie.
                </p>
                <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-full border border-emerald-200 bg-emerald-50 text-sm text-emerald-700 font-medium">
                    <span>🎉</span> 30 dagen gratis proefperiode
                </div>
            </div>
        </div>
    </section>

    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-6">
            <article class="plan-card rounded-2xl border border-blue-100 bg-white/90 p-6">
                <h2 class="text-2xl font-bold text-slate-900">Starter</h2>
                <p class="text-sm text-slate-500 mt-1">Voor kleine teams</p>
                <p class="mt-4 text-4xl font-extrabold text-blue-600">€29<span class="text-base font-medium text-slate-500"> / maand</span></p>
                <ul class="mt-6 space-y-2 text-sm text-slate-600">
                    <li>• 1 admin account</li>
                    <li>• 5 medewerker accounts</li>
                </ul>
                @auth
                    <form action="{{ route('subscription.activate') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="plan" value="starter">
                        <button type="submit" class="w-full rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 transition">
                            Kies Starter
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mt-6 inline-flex w-full justify-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold py-3 transition">
                        Start gratis
                    </a>
                @endauth
            </article>

            <article class="plan-card rounded-2xl border-2 border-blue-500 bg-white p-6 relative">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-blue-600 text-white text-xs font-bold">Meest gekozen</span>
                <h2 class="text-2xl font-bold text-slate-900">Professional</h2>
                <p class="text-sm text-slate-500 mt-1">Alles van Starter + meer controle</p>
                <p class="mt-4 text-4xl font-extrabold text-blue-600">€79<span class="text-base font-medium text-slate-500"> / maand</span></p>
                <ul class="mt-6 space-y-2 text-sm text-slate-600">
                    <li>• Alles van Starter</li>
                    <li>• 2 admin accounts</li>
                    <li>• 10 employee accounts</li>
                    <li>• Advanced analytics</li>
                    <li>• Priority support</li>
                    <li>• AI</li>
                </ul>
                @auth
                    <form action="{{ route('subscription.activate') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="plan" value="professional">
                        <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 transition">
                            Kies Professional
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mt-6 inline-flex w-full justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 transition">
                        Start gratis
                    </a>
                @endauth
            </article>

            <article class="plan-card rounded-2xl border border-indigo-100 bg-white/90 p-6">
                <h2 class="text-2xl font-bold text-slate-900">Enterprise</h2>
                <p class="text-sm text-slate-500 mt-1">Voor grotere organisaties</p>
                <p class="mt-4 text-4xl font-extrabold text-blue-600">€149<span class="text-base font-medium text-slate-500"> / maand</span></p>
                <ul class="mt-6 space-y-2 text-sm text-slate-600">
                    <li>• Alles van Professional</li>
                    <li>• 5 admin accounts</li>
                    <li>• 20 employee accounts</li>
                    <li>• Priority support</li>
                    <li>• AI</li>
                </ul>
                @auth
                    <form action="{{ route('subscription.activate') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="plan" value="enterprise">
                        <button type="submit" class="w-full rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 transition">
                            Kies Enterprise
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mt-6 inline-flex w-full justify-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold py-3 transition">
                        Start gratis
                    </a>
                @endauth
            </article>
        </div>
    </section>

    <section class="pb-16">
        <div class="max-w-5xl mx-auto px-6 grid md:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-blue-100 bg-white/85 p-5">
                <h3 class="font-semibold text-slate-900">Hoe werkt betalen?</h3>
                <p class="text-sm text-slate-600 mt-1">Na plan-keuze ga je naar een beveiligde Mollie-checkout. Na succesvolle betaling wordt je abonnement automatisch geactiveerd.</p>
            </div>
            <div class="rounded-2xl border border-indigo-100 bg-white/85 p-5">
                <h3 class="font-semibold text-slate-900">Flexibel op- en afschalen</h3>
                <p class="text-sm text-slate-600 mt-1">Je kunt je plan later wijzigen vanuit je abonnementspagina. Zo groeit TaskCheck mee met je team.</p>
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
