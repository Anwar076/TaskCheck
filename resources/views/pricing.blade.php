<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        $seoTitle = 'TaskCheck prijzen en abonnementen | 14 dagen gratis';
        $seoDescription =
            'Bekijk de prijzen van TaskCheck voor kleine teams, meerdere locaties en grotere organisaties. Probeer alle functies 14 dagen gratis, zonder creditcard.';
        $seoUrl = route('pricing');
        $seoImage = asset('images/taskcheck-pricing-social.png');
        $displayPrice = static function (float $amount): string {
            $formatted = number_format($amount, 2, ',', '.');
            return str_ends_with($formatted, ',00') ? substr($formatted, 0, -3) : $formatted;
        };
        $billingSuffix = static fn(array $plan): string => \App\Models\Organisation\Company::billingPeriod(
            $plan['billing_period'] ?? 'monthly',
        )['suffix'];
        $planCopy = [
            'starter' => ['Starter', 'Voor kleine teams die willen starten met structuur.', null],
            'professional' => [
                'Professional',
                'Voor teams die meer controle en automatisering willen.',
                'Alles in Starter, plus:',
            ],
            'business' => [
                'Business',
                'Voor bedrijven met meerdere locaties en grotere teams.',
                'Alles in Professional, plus:',
            ],
        ];
        $publicPlanKeys = ['starter', 'professional', 'business'];
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $seoTitle,
            'description' => $seoDescription,
            'url' => $seoUrl,
            'inLanguage' => 'nl-NL',
            'mainEntity' => [
                '@type' => 'ItemList',
                'name' => 'TaskCheck abonnementen',
                'itemListElement' => array_map(
                    static function (string $key, int $index) use ($plans, $planCopy, $seoUrl): array {
                        return [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'item' => [
                                '@type' => 'SoftwareApplication',
                                'name' => 'TaskCheck ' . $planCopy[$key][0],
                                'applicationCategory' => 'BusinessApplication',
                                'operatingSystem' => 'Web, iOS, Android',
                                'description' => $planCopy[$key][1],
                                'url' => $seoUrl . '#' . $key,
                                'offers' => [
                                    '@type' => 'Offer',
                                    'price' => number_format((float) $plans[$key]['billing_amount'], 2, '.', ''),
                                    'priceCurrency' => 'EUR',
                                    'availability' => 'https://schema.org/InStock',
                                    'url' => $seoUrl . '#' . $key,
                                ],
                            ],
                        ];
                    },
                    $publicPlanKeys,
                    array_keys($publicPlanKeys),
                ),
            ],
        ];
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head', ['includeDefaultMetaDescription' => false])
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
    <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <style>
        :root {
            --pricing-section-gap: 4rem
        }

        .price-card {
            min-width: 0;
            transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease
        }

        .price-card li span:last-child {
            min-width: 0;
            overflow-wrap: anywhere
        }

        .price-card:hover {
            transform: translateY(-4px);
            border-color: #cbd5e1;
            box-shadow: 0 18px 42px -28px rgba(15, 23, 42, .28)
        }

        .price-card-featured {
            position: relative;
            z-index: 1;
            border-color: #6680ff;
            box-shadow: 0 18px 44px -26px rgba(79, 107, 255, .45);
            animation: featured-glow 5s ease-in-out infinite
        }

        .featured-plan-badge {
            position: absolute;
            z-index: 10;
            top: -.875rem;
            left: 50%;
            display: inline-flex;
            min-height: 1.75rem;
            transform: translateX(-50%);
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            background: #4f6bff;
            padding: .35rem 1.25rem;
            color: #fff;
            font-size: .625rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: .12em;
            text-transform: uppercase;
            white-space: nowrap;
            box-shadow: 0 8px 20px -10px rgba(49, 87, 235, .8)
        }

        .price-button {
            position: relative;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease
        }

        .price-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px -12px rgba(37, 99, 235, .5)
        }

        .price-button::after {
            content: '';
            position: absolute;
            inset: 0;
            left: -130%;
            width: 65%;
            background: linear-gradient(105deg, transparent, rgba(255, 255, 255, .32), transparent);
            transform: skewX(-18deg);
            transition: left .55s ease
        }

        .price-button:hover::after {
            left: 130%
        }

        .primary-action {
            color: #fff !important;
            background: linear-gradient(135deg, #3157eb, #5b6ff5) !important;
            box-shadow: 0 10px 24px -14px rgba(49, 87, 235, .65)
        }

        .pricing-hero-section {
            padding-top: 4.5rem
        }

        .pricing-plans-grid {
            min-width: 0;
            margin-top: calc(var(--pricing-section-gap) - 1rem);
            padding-top: 1rem;
            overflow: visible
        }

        .pricing-plans-grid .price-card {
            overflow: visible
        }

        .enterprise-panel {
            margin-top: var(--pricing-section-gap);
            background: radial-gradient(circle at 8% 92%, rgba(79, 107, 255, .07), transparent 30%), #fff;
            box-shadow: 0 20px 50px -34px rgba(15, 23, 42, .3);
            transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease
        }

        .enterprise-panel:hover {
            transform: translateY(-3px);
            border-color: #cbd5e1;
            box-shadow: 0 28px 58px -34px rgba(15, 23, 42, .34)
        }

        .enterprise-grid {
            display: grid;
            min-width: 0;
            grid-template-columns: 1fr
        }

        .enterprise-column,
        .enterprise-feature-copy {
            min-width: 0;
            overflow-wrap: anywhere
        }

        .enterprise-column + .enterprise-column {
            border-top: 1px solid #e2e8f0
        }

        .enterprise-feature {
            display: flex;
            align-items: center;
            gap: 1rem
        }

        .enterprise-feature-copy {
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.15rem 0
        }

        .enterprise-feature:first-child .enterprise-feature-copy {
            padding-top: .25rem
        }

        .enterprise-feature:last-child .enterprise-feature-copy {
            border-bottom: 0;
            padding-bottom: .25rem
        }

        .enterprise-benefits-column,
        .enterprise-advice-column {
            display: flex;
            flex-direction: column
        }

        .enterprise-benefits-column {
            justify-content: center
        }

        .enterprise-advice-column {
            justify-content: center
        }

        .pricing-final-cta {
            min-height: 11rem
        }

        .pricing-final-cta-wrap {
            margin-top: var(--pricing-section-gap)
        }

        .pricing-final-cta-media {
            display: block;
            min-height: 12rem
        }

        @media (min-width: 900px) {
            .enterprise-grid {
                grid-template-columns: minmax(0, 1.08fr) minmax(0, 1.24fr) minmax(0, 1fr)
            }

            .enterprise-column + .enterprise-column {
                border-top: 0;
                border-left: 1px solid #e2e8f0
            }

            .pricing-final-cta {
                display: grid;
                grid-template-columns: minmax(0, 1.25fr) minmax(20rem, .75fr);
                height: 12rem;
                min-height: 12rem
            }

        }

        .faq-card {
            transition: border-color .2s ease, box-shadow .2s ease
        }

        .faq-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 10px 28px -24px rgba(15, 23, 42, .3)
        }

        .pricing-faq-section {
            padding-top: var(--pricing-section-gap);
            padding-bottom: var(--pricing-section-gap)
        }

        @media (min-width: 640px) {
            :root {
                --pricing-section-gap: 5rem
            }

            .pricing-hero-section {
                padding-top: 5rem
            }
        }

        .pricing-orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(60px);
            pointer-events: none;
            animation: orb-drift 18s ease-in-out infinite
        }

        .pricing-reveal {
            opacity: 1;
            transform: translateY(18px);
            transition: transform .65s cubic-bezier(.16, 1, .3, 1)
        }

        .pricing-reveal.is-visible {
            transform: translateY(0)
        }

        .pricing-reveal-delay-1.is-visible {
            transition-delay: .08s
        }

        .pricing-reveal-delay-2.is-visible {
            transition-delay: .16s
        }

        .pricing-reveal-delay-3.is-visible {
            transition-delay: .24s
        }

        .float-detail {
            animation: float-detail 5.5s ease-in-out infinite
        }

        @keyframes orb-drift {

            0%,
            100% {
                transform: translate3d(0, 0, 0) scale(1)
            }

            50% {
                transform: translate3d(18px, -14px, 0) scale(1.05)
            }
        }

        @keyframes featured-glow {

            0%,
            100% {
                box-shadow: 0 18px 44px -26px rgba(79, 107, 255, .38)
            }

            50% {
                box-shadow: 0 24px 52px -24px rgba(79, 107, 255, .58)
            }
        }

        @keyframes float-detail {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-6px)
            }
        }

        @media(prefers-reduced-motion:reduce) {

            .price-card,
            .price-button,
            .enterprise-panel {
                transition: none
            }

            .price-card:hover,
            .price-button:hover,
            .enterprise-panel:hover {
                transform: none
            }

            .price-card-featured,
            .pricing-orb,
            .float-detail {
                animation: none
            }

            .pricing-reveal {
                opacity: 1;
                transform: none;
                transition: none
            }

            .price-button::after {
                display: none
            }
        }
    </style>
</head>

<body class="min-h-screen bg-white font-sans text-slate-900 antialiased">
    @include('components.header')
    <main class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-[48rem] overflow-hidden" aria-hidden="true">
            <div class="pricing-orb -right-32 top-24 h-96 w-96 bg-blue-200/30"></div>
            <div class="pricing-orb -left-32 top-80 h-80 w-80 bg-emerald-100/35" style="animation-delay:-7s"></div>
            <div class="absolute inset-0 opacity-[.025]"
                style="background-image:radial-gradient(#334155 1px,transparent 1px);background-size:24px 24px"></div>
        </div>
        <section class="pricing-hero-section relative">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="pricing-reveal text-center">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-[11px] font-bold uppercase tracking-[.12em] text-slate-600 shadow-sm">14
                        dagen gratis proberen <svg class="h-3.5 w-3.5 text-blue-600" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" d="M12 8v4l2.5 1.5" />
                        </svg></span>
                    <h1
                        class="mx-auto mt-6 max-w-3xl text-4xl font-extrabold leading-[1.08] tracking-tight sm:text-5xl lg:text-6xl">
                        Eerlijke prijzen,<span class="block text-[#4f6bff]">gemaakt voor elk team</span></h1>
                    <p class="mx-auto mt-5 max-w-2xl text-sm leading-relaxed text-slate-500 sm:text-base">Kies het plan
                        dat past bij jouw organisatie. Start direct — geen creditcard nodig voor je proefperiode.</p>
                    <div
                        class="mx-auto mt-8 grid max-w-3xl grid-cols-2 gap-x-5 gap-y-3 text-left text-xs font-medium text-slate-500 sm:flex sm:flex-wrap sm:justify-center sm:gap-x-9">
                        @foreach (['14 dagen gratis', 'Geen creditcard nodig', 'Flexibel op- of afschalen', 'Altijd opzegbaar'] as $item)
                            <span class="inline-flex items-center gap-2"><svg class="h-4 w-4 shrink-0 text-[#6680ff]"
                                    fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                                </svg>{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="pricing-plans-grid grid items-stretch gap-5 lg:grid-cols-3">
                    @foreach (['starter', 'professional', 'business'] as $key)
                        @php $featured=$key==='professional'; @endphp
                        <article id="{{ $key }}" data-pricing-card
                            class="price-card {{ $featured ? 'price-card-featured relative border-2' : 'border border-slate-200' }} flex min-h-[31rem] flex-col rounded-2xl bg-white p-6 sm:p-7">
                            @if ($featured)
                                <span class="featured-plan-badge">Meest
                                    gekozen</span>
                            @endif
                            <h2 class="text-xl font-bold">{{ $planCopy[$key][0] }}</h2>
                            <p class="mt-2 min-h-[2.75rem] text-sm leading-relaxed text-slate-500">
                                {{ $planCopy[$key][1] }}</p>
                            <div class="mt-6 flex items-baseline gap-1"><span
                                    class="text-4xl font-extrabold tracking-tight {{ $featured ? 'text-[#4164f5]' : 'text-slate-900' }}">€{{ $displayPrice((float) $plans[$key]['billing_amount']) }}</span><span
                                    class="text-xs font-semibold text-slate-500">/{{ $billingSuffix($plans[$key]) }}</span>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-400">excl. 21% btw</p>
                            @if ($planCopy[$key][2])
                                <p class="mt-6 text-xs font-bold text-slate-600">{{ $planCopy[$key][2] }}</p>
                            @endif
                            <ul class="mt-5 flex-1 space-y-3 text-[13px] leading-snug text-slate-600">
                                @foreach (\App\Models\Organisation\Company::planMarketingFeatures($key) as $item)
                                    <li class="flex gap-2.5"><svg
                                            class="mt-0.5 h-4 w-4 shrink-0 {{ $featured ? 'text-[#4f6bff]' : 'text-emerald-600' }}"
                                            fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="8.5" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m8.5 12 2.2 2.2 4.8-5" />
                                        </svg><span>{{ $item }}</span></li>
                                @endforeach
                            </ul>
                            @auth <a href="{{ route('subscription.choose-plan') }}"
                                    class="price-button {{ $featured ? 'primary-action' : '' }} mt-7 inline-flex w-full items-center justify-center rounded-xl px-4 py-3 text-xs font-bold {{ $featured ? 'bg-[#4f6bff] text-white' : 'border border-slate-200 bg-white text-slate-800' }}">14
                                    dagen gratis proberen</a>
                            @else
                                <a href="{{ route('register') }}"
                                    class="price-button {{ $featured ? 'primary-action' : '' }} mt-7 inline-flex w-full items-center justify-center rounded-xl px-4 py-3 text-xs font-bold {{ $featured ? 'bg-[#4f6bff] text-white' : 'border border-slate-200 bg-white text-slate-800' }}">14
                                dagen gratis proberen</a> @endauth
                        </article>
                    @endforeach
                </div>
                <article
                    class="pricing-reveal enterprise-panel overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="enterprise-grid">
                        <div class="enterprise-column flex flex-col p-7 sm:p-9 lg:min-h-[24rem]">
                            <p class="text-xs font-bold uppercase tracking-[.13em] text-slate-500">Voor grotere
                                organisaties</p>
                            <h2 class="mt-5 text-4xl font-extrabold tracking-tight">Enterprise</h2>
                            <p class="mt-3 max-w-sm text-sm leading-relaxed text-slate-500">Voor organisaties met
                                meerdere teams, locaties of specifieke proceswensen.</p>
                            <svg class="float-detail mt-8 h-auto w-60 max-w-full lg:mt-auto" viewBox="0 0 300 135"
                                fill="none" aria-hidden="true">
                                <circle cx="64" cy="78" r="53" fill="#EFF6FF" />
                                <path d="M26 119V68h40v51M66 119V25h63v94M129 119V55h53v64M15 119h215" stroke="#94A3B8"
                                    stroke-width="2" />
                                <path d="M41 82h10m-10 17h10m49-56h13m-13 20h13m-13 20h13m-13 20h13m35-30h12m-12 20h12"
                                    stroke="#CBD5E1" stroke-width="4" />
                                <path d="m194 67 28-10 28 10v24c0 20-12 34-28 41-16-7-28-21-28-41V67Z" fill="white"
                                    stroke="#3659D9" stroke-width="3" />
                                <path d="m211 92 7 7 15-18" stroke="#3659D9" stroke-width="3" />
                            </svg>
                        </div>
                        <div class="enterprise-column enterprise-benefits-column p-7 sm:p-9">
                            <ul class="w-full">
                                @foreach (['Onbeperkte admins & medewerkers', 'Dedicated accountmanager', 'SLA met uptime-garantie', 'Persoonlijke onboarding', 'Maatwerk integraties'] as $item)
                                    <li class="enterprise-feature">
                                        <span
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700"><svg
                                                class="h-4 w-4" fill="none" stroke="currentColor"
                                                stroke-width="2.5" viewBox="0 0 24 24">
                                                <path d="m6 12 4 4 8-9" />
                                            </svg></span><span
                                            class="enterprise-feature-copy text-sm font-semibold text-slate-800">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="enterprise-column enterprise-advice-column p-7 sm:p-9">
                            <div class="flex items-start gap-4"><span
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-blue-700"><svg
                                        class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M4.5 17V12a7.5 7.5 0 0 1 15 0v5M4.5 11H3v6h1.5m15-6H21v6h-1.5" />
                                    </svg></span>
                                <h3 class="pt-1 text-lg font-bold leading-snug">Persoonlijk advies voor jouw
                                    organisatie</h3>
                            </div>
                            <p class="mt-6 text-sm leading-relaxed text-slate-500">Onze experts denken graag met je mee
                                over de beste oplossing.</p><a href="{{ route('contact', ['subject' => 'demo']) }}"
                                class="price-button primary-action mt-8 inline-flex w-full items-center justify-center gap-3 rounded-xl px-5 py-4 text-sm font-bold">Plan
                                een adviesgesprek <span>→</span></a><a
                                href="{{ route('contact', ['subject' => 'sales']) }}"
                                class="mt-6 inline-flex items-center gap-3 text-sm font-bold text-blue-700"><svg
                                    class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M7 3h7l4 4v14H7V3Zm7 0v5h4M10 13h5m-5 4h5" />
                                </svg>Vraag offerte aan <span>›</span></a>
                        </div>
                    </div>
                </article>
            </div>
        </section>
        <section class="pricing-faq-section relative">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="pricing-reveal">
                    <p class="text-xs font-bold uppercase tracking-[.14em] text-slate-400">Veelgestelde vragen</p>
                    <h2 class="mt-2 text-2xl font-bold">Alles wat je wilt weten</h2>
                </div>
                <div class="mt-7 grid gap-4 md:grid-cols-2">
                    @foreach ([['Hoe werkt betalen?', 'Na je proefperiode ga je naar een beveiligde Mollie-checkout. Je abonnement wordt direct geactiveerd na betaling.'], ['Kan ik tussentijds wisselen?', 'Ja, op- en afschalen kan vanuit je abonnementspagina. Je betaalt naar wat je gebruikt.'], ['Wat na 14 dagen gratis?', 'Je kiest pas daarna een plan. Geen automatische incasso zonder jouw akkoord.'], ['Korting op jaarbetaling?', 'Neem contact op — voor jaarabonnementen maken we graag maatwerk.']] as [$question, $answer])
                        <div class="pricing-reveal faq-card flex items-start gap-4 rounded-xl border border-slate-200 bg-white p-5">
                            <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-[#4f6bff]"><svg
                                        class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="8" />
                                        <path d="m9 12 2 2 4-5" />
                                </svg></span>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold text-slate-900">{{ $question }}</h3>
                                <p class="mt-1.5 text-xs leading-relaxed text-slate-500">{{ $answer }}</p>
                            </div>
                            <span class="mt-2 text-slate-700" aria-hidden="true">›</span>
                        </div>
                    @endforeach
                </div>
                <div
                    class="pricing-reveal pricing-final-cta-wrap overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-50 via-white to-blue-50 shadow-sm">
                    <div class="pricing-final-cta grid items-center">
                        <div class="relative z-10 p-7 sm:p-9">
                            <h2 class="text-xl font-bold">Nog twijfels?</h2>
                            <p class="mt-2 max-w-xl text-sm text-slate-500">We laten je graag in een kort gesprek zien
                                hoe TaskCheck in jouw processen past.</p>
                            <div class="mt-6 flex flex-col gap-3 sm:flex-row"><a
                                    href="{{ route('contact', ['subject' => 'demo']) }}"
                                    class="price-button primary-action inline-flex justify-center rounded-lg px-6 py-3 text-xs font-bold">Plan
                                    een demo</a><a href="{{ route('welcome') }}"
                                    class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-6 py-3 text-xs font-bold text-slate-700 shadow-sm">Terug
                                    naar homepage</a></div>
                        </div>
                        <div class="pricing-final-cta-media relative h-full overflow-hidden" role="img"
                            aria-label="TaskCheck in gebruik bij een operationeel team"
                            style="background-image:linear-gradient(90deg,rgba(255,255,255,.98) 0%,rgba(255,255,255,.58) 32%,rgba(255,255,255,.08) 62%,rgba(255,255,255,0) 100%),url('{{ asset('images/oplossing-taskcheck-multi-sector.png') }}');background-position:center;background-size:cover;background-repeat:no-repeat">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('components.footer')
    <script>
        (function() {
            var cards = document.querySelectorAll('[data-pricing-card]');
            cards.forEach(function(card, index) {
                card.classList.add('pricing-reveal', 'pricing-reveal-delay-' + Math.min(index + 1, 3));
            });

            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
                document.querySelectorAll('.pricing-reveal').forEach(function(element) {
                    element.classList.add('is-visible');
                });
                return;
            }

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, {
                threshold: .08,
                rootMargin: '0px 0px -30px 0px'
            });

            document.querySelectorAll('.pricing-reveal').forEach(function(element) {
                observer.observe(element);
            });
        })();
    </script>
</body>

</html>
