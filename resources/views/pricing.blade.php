<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        $seoTitle = 'Prijzen checklist app voor bedrijven en personeel | TaskCheck';
        $seoDescription =
            'Eerlijke prijzen voor TaskCheck. Starter, Professional, Business en Enterprise op aanvraag. Start 14 dagen gratis zonder creditcard.';
        $seoUrl = route('pricing');
        $seoImage = asset('images/taskcheck-dashboard-hero.webp');
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
    <style>
        .price-card {
            transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease
        }

        .price-card:hover {
            transform: translateY(-4px);
            border-color: #cbd5e1;
            box-shadow: 0 18px 42px -28px rgba(15, 23, 42, .28)
        }

        .price-card-featured {
            border-color: #6680ff;
            box-shadow: 0 18px 44px -26px rgba(79, 107, 255, .45);
            animation: featured-glow 5s ease-in-out infinite
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

        .enterprise-panel {
            background: radial-gradient(circle at 8% 92%, rgba(79, 107, 255, .07), transparent 30%), #fff;
            box-shadow: 0 20px 50px -34px rgba(15, 23, 42, .3);
            transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease
        }

        .enterprise-panel:hover {
            transform: translateY(-3px);
            border-color: #cbd5e1;
            box-shadow: 0 28px 58px -34px rgba(15, 23, 42, .34)
        }

        .faq-card {
            transition: border-color .2s ease, box-shadow .2s ease
        }

        .faq-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 10px 28px -24px rgba(15, 23, 42, .3)
        }

        .pricing-orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(60px);
            pointer-events: none;
            animation: orb-drift 18s ease-in-out infinite
        }

        .pricing-reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity .65s cubic-bezier(.16, 1, .3, 1), transform .65s cubic-bezier(.16, 1, .3, 1)
        }

        .pricing-reveal.is-visible {
            opacity: 1;
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
        <section class="relative pt-28 sm:pt-32">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
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
                <div class="mt-12 grid items-stretch gap-5 lg:grid-cols-3">
                    @foreach (['starter', 'professional', 'business'] as $key)
                        @php $featured=$key==='professional'; @endphp
                        <article data-pricing-card
                            class="price-card {{ $featured ? 'price-card-featured relative border-2' : 'border border-slate-200' }} flex min-h-[31rem] flex-col rounded-2xl bg-white p-6 sm:p-7">
                            @if ($featured)
                                <span
                                    class="absolute -top-3 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-[#4f6bff] px-5 py-1.5 text-[10px] font-bold uppercase tracking-[.12em] text-white">Meest
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
                                    class="price-button mt-7 inline-flex w-full items-center justify-center rounded-xl px-4 py-3 text-xs font-bold {{ $featured ? 'bg-[#4f6bff] text-white' : 'border border-slate-200 bg-white text-slate-800' }}">14
                                    dagen gratis proberen</a>
                            @else
                                <a href="{{ route('register') }}"
                                    class="price-button mt-7 inline-flex w-full items-center justify-center rounded-xl px-4 py-3 text-xs font-bold {{ $featured ? 'bg-[#4f6bff] text-white' : 'border border-slate-200 bg-white text-slate-800' }}">14
                                dagen gratis proberen</a> @endauth
                        </article>
                    @endforeach
                </div>
                <article
                    class="pricing-reveal enterprise-panel mt-7 overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="grid lg:grid-cols-[1.1fr_1.25fr_1fr]">
                        <div class="flex flex-col p-7 sm:p-9 lg:min-h-[24rem]">
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
                        <div class="border-t border-slate-200 p-7 sm:p-9 lg:border-l lg:border-t-0">
                            <ul>
                                @foreach (['Onbeperkte admins & medewerkers', 'Dedicated accountmanager', 'SLA met uptime-garantie', 'Persoonlijke onboarding', 'Maatwerk integraties'] as $item)
                                    <li
                                        class="flex items-center gap-4 border-b border-slate-200 py-4 first:pt-0 last:border-0 last:pb-0">
                                        <span
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700"><svg
                                                class="h-4 w-4" fill="none" stroke="currentColor"
                                                stroke-width="2.5" viewBox="0 0 24 24">
                                                <path d="m6 12 4 4 8-9" />
                                            </svg></span><span
                                            class="text-sm font-semibold text-slate-800">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="border-t border-slate-200 p-7 sm:p-9 lg:border-l lg:border-t-0">
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
                                over de beste oplossing.</p><a href="{{ route('contact') }}"
                                class="price-button mt-8 inline-flex w-full items-center justify-center gap-3 rounded-xl bg-[#3659d9] px-5 py-4 text-sm font-bold text-white">Plan
                                een adviesgesprek <span>→</span></a><a href="{{ route('contact') }}"
                                class="mt-6 inline-flex items-center gap-3 text-sm font-bold text-blue-700"><svg
                                    class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M7 3h7l4 4v14H7V3Zm7 0v5h4M10 13h5m-5 4h5" />
                                </svg>Vraag offerte aan <span>›</span></a>
                        </div>
                    </div>
                </article>
                <p class="mt-7 text-center text-xs text-slate-400">Alle prijzen zijn exclusief 21% btw. Betaling
                    verloopt veilig via Mollie.</p>
            </div>
        </section>
        <section class="relative pb-20 pt-16 sm:pb-24">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="pricing-reveal">
                    <p class="text-xs font-bold uppercase tracking-[.14em] text-slate-400">Veelgestelde vragen</p>
                    <h2 class="mt-2 text-2xl font-bold">Alles wat je wilt weten</h2>
                </div>
                <div class="mt-7 grid gap-4 md:grid-cols-2">
                    @foreach ([['Hoe werkt betalen?', 'Na je proefperiode ga je naar een beveiligde Mollie-checkout. Je abonnement wordt direct geactiveerd na betaling.'], ['Kan ik tussentijds wisselen?', 'Ja, op- en afschalen kan vanuit je abonnementspagina. Je betaalt naar wat je gebruikt.'], ['Wat na 14 dagen gratis?', 'Je kiest pas daarna een plan. Geen automatische incasso zonder jouw akkoord.'], ['Korting op jaarbetaling?', 'Neem contact op — voor jaarabonnementen maken we graag maatwerk.']] as [$question, $answer])
                        <div
                            class="pricing-reveal faq-card flex items-start gap-4 rounded-xl border border-slate-200 bg-white p-5">
                            <span
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-[#4f6bff]"><svg
                                    class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="8" />
                                    <path d="m9 12 2 2 4-5" />
                                </svg></span>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold">{{ $question }}</h3>
                                <p class="mt-1.5 text-xs leading-relaxed text-slate-500">{{ $answer }}</p>
                            </div><span class="mt-2">›</span>
                        </div>
                    @endforeach
                </div>
                <div
                    class="pricing-reveal mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-50 via-white to-blue-50 shadow-sm">
                    <div class="grid min-h-[11rem] items-center lg:grid-cols-[1.35fr_.65fr]">
                        <div class="p-7 sm:p-9">
                            <h2 class="text-xl font-bold">Nog twijfels?</h2>
                            <p class="mt-2 max-w-xl text-sm text-slate-500">We laten je graag in een kort gesprek zien
                                hoe TaskCheck in jouw processen past.</p>
                            <div class="mt-6 flex flex-col gap-3 sm:flex-row"><a href="{{ route('contact') }}"
                                    class="price-button inline-flex justify-center rounded-lg bg-[#4f6bff] px-6 py-3 text-xs font-bold text-white">Plan
                                    een demo</a><a href="{{ route('welcome') }}"
                                    class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-6 py-3 text-xs font-bold text-slate-700 shadow-sm">Terug
                                    naar homepage</a></div>
                        </div>
                        <div class="relative hidden h-full overflow-hidden lg:block"><svg
                                class="absolute bottom-0 right-6 h-[92%] w-auto" viewBox="0 0 320 180"
                                fill="none">
                                <circle cx="213" cy="60" r="28" fill="#DBEAFE" />
                                <circle cx="263" cy="66" r="25" fill="#E0E7FF" />
                                <path d="M173 174c2-49 19-81 42-81s39 32 41 81h-83Z" fill="#BFDBFE" />
                                <path d="M226 174c3-46 17-75 38-75 20 0 35 29 38 75h-76Z" fill="#C7D2FE" />
                                <rect x="103" y="103" width="99" height="66" rx="6" fill="white"
                                    stroke="#94A3B8" stroke-width="2" />
                                <circle cx="153" cy="131" r="8" fill="#C7D2FE" />
                                <path d="m149 131 3 3 6-7" stroke="#4F6BFF" stroke-width="2" />
                            </svg></div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('components.footer')
    <script>
        (function() {
            var reveals = document.querySelectorAll('.pricing-reveal');
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
