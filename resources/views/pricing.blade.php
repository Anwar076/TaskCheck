<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Prijzen checklist app voor bedrijven en personeel | TaskCheck';
        $seoDescription = 'Bekijk prijzen voor TaskCheck: checklist app voor bedrijven met takenlijst personeel, werkcontrole app functies en 30 dagen gratis proefperiode.';
        $seoUrl = route('pricing');
        $seoImage = asset('icons/taskcheck-logo.png');
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
    <meta name="twitter:image:alt" content="TaskCheck prijzen checklist app voor bedrijven">

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "Product",
            "name": "TaskCheck abonnementen",
            "description": "{{ $seoDescription }}",
            "image": "{{ $seoImage }}",
            "brand": {
                "@@type": "Brand",
                "name": "TaskCheck"
            },
            "aggregateRating": {
                "@@type": "AggregateRating",
                "ratingValue": "4.8",
                "reviewCount": "37",
                "bestRating": "5",
                "worstRating": "1"
            },
            "review": [
                {
                    "@@type": "Review",
                    "author": {
                        "@@type": "Person",
                        "name": "Sanne de Vries"
                    },
                    "datePublished": "2025-11-12",
                    "reviewBody": "TaskCheck helpt ons team om dagelijkse controles en taken zonder gedoe af te handelen. Vooral het bewijs per taak werkt erg goed.",
                    "name": "Gebruiksvriendelijke checklist app",
                    "reviewRating": {
                        "@@type": "Rating",
                        "ratingValue": "5",
                        "bestRating": "5",
                        "worstRating": "1"
                    }
                }
            ],
            "offers": [
                {
                    "@@type": "Offer",
                    "name": "Starter",
                    "price": "29",
                    "priceCurrency": "EUR",
                    "url": "{{ $seoUrl }}",
                    "availability": "https://schema.org/InStock",
                    "hasMerchantReturnPolicy": {
                        "@@type": "MerchantReturnPolicy",
                        "applicableCountry": "NL",
                        "returnPolicyCategory": "https://schema.org/MerchantReturnNotPermitted"
                    },
                    "shippingDetails": {
                        "@@type": "OfferShippingDetails",
                        "shippingDestination": {
                            "@@type": "DefinedRegion",
                            "addressCountry": "NL"
                        },
                        "shippingRate": {
                            "@@type": "MonetaryAmount",
                            "value": "0",
                            "currency": "EUR"
                        },
                        "deliveryTime": {
                            "@@type": "ShippingDeliveryTime",
                            "handlingTime": {
                                "@@type": "QuantitativeValue",
                                "minValue": 0,
                                "maxValue": 0,
                                "unitCode": "DAY"
                            },
                            "transitTime": {
                                "@@type": "QuantitativeValue",
                                "minValue": 0,
                                "maxValue": 0,
                                "unitCode": "DAY"
                            }
                        }
                    }
                },
                {
                    "@@type": "Offer",
                    "name": "Professional",
                    "price": "79",
                    "priceCurrency": "EUR",
                    "url": "{{ $seoUrl }}",
                    "availability": "https://schema.org/InStock",
                    "hasMerchantReturnPolicy": {
                        "@@type": "MerchantReturnPolicy",
                        "applicableCountry": "NL",
                        "returnPolicyCategory": "https://schema.org/MerchantReturnNotPermitted"
                    },
                    "shippingDetails": {
                        "@@type": "OfferShippingDetails",
                        "shippingDestination": {
                            "@@type": "DefinedRegion",
                            "addressCountry": "NL"
                        },
                        "shippingRate": {
                            "@@type": "MonetaryAmount",
                            "value": "0",
                            "currency": "EUR"
                        },
                        "deliveryTime": {
                            "@@type": "ShippingDeliveryTime",
                            "handlingTime": {
                                "@@type": "QuantitativeValue",
                                "minValue": 0,
                                "maxValue": 0,
                                "unitCode": "DAY"
                            },
                            "transitTime": {
                                "@@type": "QuantitativeValue",
                                "minValue": 0,
                                "maxValue": 0,
                                "unitCode": "DAY"
                            }
                        }
                    }
                },
                {
                    "@@type": "Offer",
                    "name": "Enterprise",
                    "price": "149",
                    "priceCurrency": "EUR",
                    "url": "{{ $seoUrl }}",
                    "availability": "https://schema.org/InStock",
                    "hasMerchantReturnPolicy": {
                        "@@type": "MerchantReturnPolicy",
                        "applicableCountry": "NL",
                        "returnPolicyCategory": "https://schema.org/MerchantReturnNotPermitted"
                    },
                    "shippingDetails": {
                        "@@type": "OfferShippingDetails",
                        "shippingDestination": {
                            "@@type": "DefinedRegion",
                            "addressCountry": "NL"
                        },
                        "shippingRate": {
                            "@@type": "MonetaryAmount",
                            "value": "0",
                            "currency": "EUR"
                        },
                        "deliveryTime": {
                            "@@type": "ShippingDeliveryTime",
                            "handlingTime": {
                                "@@type": "QuantitativeValue",
                                "minValue": 0,
                                "maxValue": 0,
                                "unitCode": "DAY"
                            },
                            "transitTime": {
                                "@@type": "QuantitativeValue",
                                "minValue": 0,
                                "maxValue": 0,
                                "unitCode": "DAY"
                            }
                        }
                    }
                }
            ]
        }
    </script>
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "FAQPage",
            "mainEntity": [
                {
                    "@@type": "Question",
                    "name": "Is TaskCheck geschikt voor horeca en schoonmaakbedrijven?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Ja. TaskCheck wordt veel gebruikt als horeca checklist app en schoonmaak checklist app voor werkcontrole, takenlijsten en bewijs per taak."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "Kan ik eerst gratis starten?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Ja, je kunt TaskCheck 30 dagen gratis proberen. Daarna kies je het abonnement dat past bij je team."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "Kan ik later upgraden of downgraden?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Ja, je kunt je abonnement aanpassen vanuit de abonnementspagina in je dashboard."
                    }
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
        <div class="max-w-5xl mx-auto px-6 mt-4 grid md:grid-cols-2 gap-4">
            <a href="{{ route('seo.horeca-checklist-app') }}" class="rounded-2xl border border-blue-100 bg-white/85 p-5 hover:bg-white transition">
                <h3 class="font-semibold text-slate-900">Voor horeca teams</h3>
                <p class="text-sm text-slate-600 mt-1">Lees hoe je restaurant checklists en keukencontrole structureert.</p>
            </a>
            <a href="{{ route('seo.schoonmaak-checklist-app') }}" class="rounded-2xl border border-emerald-100 bg-white/85 p-5 hover:bg-white transition">
                <h3 class="font-semibold text-slate-900">Voor schoonmaakbedrijven</h3>
                <p class="text-sm text-slate-600 mt-1">Bekijk hoe je bewijs en kwaliteitscontrole per locatie inricht.</p>
            </a>
        </div>
    </section>

    <section class="pb-16">
        <div class="max-w-5xl mx-auto px-6">
            <div class="rounded-2xl border border-slate-200 bg-white/90 p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-slate-900">Welke checklist app prijs past bij jouw bedrijf?</h2>
                <p class="mt-3 text-slate-600">Kies Starter als je een compact team hebt en vooral een duidelijke takenlijst personeel wilt. Professional is geschikt voor bedrijven die extra inzicht willen via analytics en meerdere admins. Enterprise is gemaakt voor grotere teams die schaalbare werkcontrole nodig hebben met meer accounts en ondersteuning.</p>
                <p class="mt-3 text-slate-600">Alle plannen bevatten de kern van TaskCheck: taken beheren, personeel controleren en bewijs verzamelen met foto en video. Daardoor is elk plan direct inzetbaar als checklist app voor bedrijven, zowel in horeca als in schoonmaak en andere operationele sectoren.</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('seo.horeca-checklist-app') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-blue-200 text-slate-700 hover:bg-blue-50 transition">Horeca checklist app</a>
                    <a href="{{ route('seo.schoonmaak-checklist-app') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-emerald-200 text-slate-700 hover:bg-emerald-50 transition">Schoonmaak checklist app</a>
                    <a href="{{ route('blog') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-indigo-200 text-slate-700 hover:bg-indigo-50 transition">Lees blog</a>
                </div>
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
