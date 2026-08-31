<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Prijzen checklist app voor bedrijven en personeel | TaskCheck';
        $seoDescription = 'Eerlijke prijzen voor TaskCheck. Starter, Professional, Business en Enterprise op aanvraag. Start 14 dagen gratis zonder creditcard.';
        $seoUrl = route('pricing');
        $seoImage = asset('images/taskcheck-dashboard-hero.webp');
        $displayPrice = static function (float $amount): string {
            $formatted = number_format($amount, 2, ',', '.');

            return str_ends_with($formatted, ',00') ? substr($formatted, 0, -3) : $formatted;
        };
        $billingSuffix = static fn (array $plan): string => \App\Models\Organisation\Company::billingPeriod($plan['billing_period'] ?? 'monthly')['suffix'];
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
        .pricing-scene { isolation: isolate; }

        .pricing-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(56px);
            pointer-events: none;
            animation: pricing-orb-drift 20s ease-in-out infinite;
        }
        .pricing-orb--1 {
            width: min(22rem, 55vw);
            height: min(22rem, 55vw);
            right: -8%;
            top: 15%;
            background: radial-gradient(circle at 35% 35%, rgba(37, 99, 235, 0.2), rgba(99, 102, 241, 0.08) 50%, transparent 70%);
            animation-duration: 24s;
        }
        .pricing-orb--2 {
            width: min(18rem, 45vw);
            height: min(18rem, 45vw);
            left: -5%;
            top: 55%;
            background: radial-gradient(circle at center, rgba(16, 185, 129, 0.14), transparent 68%);
            animation-duration: 18s;
            animation-delay: -6s;
        }
        .pricing-orb--3 {
            width: min(12rem, 30vw);
            height: min(12rem, 30vw);
            left: 40%;
            top: 5%;
            opacity: 0.55;
            background: radial-gradient(circle at center, rgba(99, 102, 241, 0.18), transparent 65%);
            animation-duration: 15s;
            animation-delay: -9s;
        }
        @keyframes pricing-orb-drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            40% { transform: translate(-12px, 14px) scale(1.03); }
            70% { transform: translate(10px, -8px) scale(0.98); }
        }

        .pricing-float-y { animation: pricing-float-y 5.5s ease-in-out infinite; }
        .pricing-float-y--2 { animation-delay: -1.6s; }
        .pricing-float-y--3 { animation-delay: -3s; }
        .pricing-float-y--4 { animation-delay: -4.2s; }
        @keyframes pricing-float-y {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }

        .pricing-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .pricing-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .pricing-reveal-d1.visible { transition-delay: 0.05s; }
        .pricing-reveal-d2.visible { transition-delay: 0.11s; }
        .pricing-reveal-d3.visible { transition-delay: 0.17s; }
        .pricing-reveal-d4.visible { transition-delay: 0.23s; }

        .cta-btn {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #2563eb, #6366f1);
            box-shadow: 0 12px 28px -8px rgba(37, 99, 235, 0.4);
            transition: transform 0.22s cubic-bezier(0.2, 0.8, 0.2, 1), filter 0.2s ease, box-shadow 0.25s ease;
        }
        .cta-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            left: -120%;
            width: 65%;
            background: linear-gradient(105deg, transparent 0%, rgba(255, 255, 255, 0.32) 50%, transparent 100%);
            transform: skewX(-16deg);
            transition: left 0.55s ease;
            pointer-events: none;
        }
        .cta-btn:hover {
            background: linear-gradient(135deg, #1d4ed8, #4f46e5);
            transform: translateY(-2px);
            box-shadow: 0 16px 40px -10px rgba(37, 99, 235, 0.48);
        }
        .cta-btn:hover::after {
            left: 120%;
        }

        .pricing-btn-dark {
            position: relative;
            overflow: hidden;
            transition: transform 0.22s ease, box-shadow 0.25s ease, background 0.2s ease;
        }
        .pricing-btn-dark:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -12px rgba(15, 23, 42, 0.35);
        }

        .pricing-pill {
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.25s ease;
        }
        .pricing-pill:hover {
            transform: translateY(-2px);
            border-color: rgb(191 219 254);
            box-shadow: 0 10px 28px -14px rgba(37, 99, 235, 0.18);
        }
        .pricing-pill::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 25%, rgba(255, 255, 255, 0.45) 50%, transparent 75%);
            transform: translateX(-100%);
            transition: transform 0.55s ease;
            pointer-events: none;
        }
        .pricing-pill:hover::after {
            transform: translateX(100%);
        }

        .pricing-card {
            position: relative;
            transition: transform 0.32s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.32s ease, border-color 0.25s ease;
        }
        .pricing-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.12), rgba(99, 102, 241, 0.06), rgba(226, 232, 240, 0.9));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .pricing-card:hover::before {
            opacity: 1;
        }
        .pricing-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 56px -28px rgba(15, 23, 42, 0.14);
        }

        .pricing-card--featured {
            box-shadow: 0 24px 56px -24px rgba(37, 99, 235, 0.24);
            animation: pricing-featured-glow 5s ease-in-out infinite;
        }
        .pricing-card--featured:hover {
            box-shadow: 0 32px 64px -24px rgba(37, 99, 235, 0.32);
        }
        @keyframes pricing-featured-glow {
            0%, 100% {
                box-shadow: 0 24px 56px -24px rgba(37, 99, 235, 0.22);
            }
            50% {
                box-shadow: 0 28px 62px -22px rgba(99, 102, 241, 0.3);
            }
        }

        .pricing-badge-pop {
            animation: pricing-badge-bob 3.5s ease-in-out infinite;
        }
        @keyframes pricing-badge-bob {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(-3px); }
        }

        .pricing-faq-card {
            transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.2s ease;
        }
        .pricing-faq-card:hover {
            transform: translateY(-4px) scale(1.01);
            border-color: rgb(203 213 225);
            box-shadow: 0 16px 40px -24px rgba(37, 99, 235, 0.12);
        }

        .pricing-cta-bottom {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgb(248 250 252) 0%, rgb(255 255 255) 45%, rgb(239 246 255 / 0.5) 100%);
        }
        .pricing-cta-bottom::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -20%;
            width: min(24rem, 80vw);
            height: min(24rem, 80vw);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12), transparent 65%);
            pointer-events: none;
        }

        .pricing-glass-btn {
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease, box-shadow 0.25s ease;
        }
        .pricing-glass-btn:hover {
            transform: translateY(-2px);
            border-color: rgba(37, 99, 235, 0.25);
            background: rgb(255 255 255);
            box-shadow: 0 12px 32px -16px rgba(37, 99, 235, 0.15);
        }

        @media (prefers-reduced-motion: reduce) {
            .pricing-orb,
            .pricing-float-y,
            .pricing-float-y--2,
            .pricing-float-y--3,
            .pricing-float-y--4,
            .pricing-card--featured,
            .pricing-badge-pop {
                animation: none !important;
            }
            .pricing-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }
            .pricing-reveal-d1.visible,
            .pricing-reveal-d2.visible,
            .pricing-reveal-d3.visible,
            .pricing-reveal-d4.visible {
                transition-delay: 0s !important;
            }
            .cta-btn:hover,
            .pricing-btn-dark:hover,
            .pricing-card:hover,
            .pricing-faq-card:hover,
            .pricing-pill:hover,
            .pricing-glass-btn:hover {
                transform: none;
            }
            .cta-btn::after,
            .pricing-pill::after {
                display: none;
            }
        }
    </style>
</head>
<body class="min-h-screen overflow-x-hidden bg-white font-sans text-slate-900 antialiased">
    @include('components.header')

    <div class="pricing-scene">
        {{-- Hero --}}
        <section class="relative overflow-hidden pt-28 pb-14 sm:pb-16">
            <div class="pointer-events-none absolute inset-0">
                <svg class="absolute inset-0 h-full w-full opacity-[0.035]" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <defs>
                        <pattern id="pricing-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                            <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#pricing-dots)"/>
                </svg>
                <div class="absolute -right-[200px] -top-[300px] h-[800px] w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,0.1)_0%,transparent_65%)]"></div>
                <div class="absolute -left-[100px] bottom-0 h-[400px] w-[400px] rounded-full bg-[radial-gradient(circle,rgba(16,185,129,0.07)_0%,transparent_65%)]"></div>
                <div class="pricing-orb pricing-orb--1 hidden sm:block" aria-hidden="true"></div>
                <div class="pricing-orb pricing-orb--2 hidden md:block" aria-hidden="true"></div>
                <div class="pricing-orb pricing-orb--3 hidden lg:block" aria-hidden="true"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
                <div class="pricing-reveal mx-auto inline-flex items-center gap-2 rounded-full border border-blue-200/90 bg-blue-50/90 px-4 py-2 text-xs font-semibold text-blue-700 shadow-md shadow-blue-500/5 ring-1 ring-white/70 backdrop-blur-sm">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-50"></span>
                        <span class="relative h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    </span>
                    Transparante prijzen · 14 dagen gratis
                </div>
                <h1 class="pricing-reveal pricing-reveal-d1 mx-auto mt-7 max-w-4xl text-4xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl lg:text-[3.25rem]">
                    Eerlijke prijzen voor
                    <span class="mt-1 block bg-gradient-to-r from-[#2563eb] via-[#4f6af8] to-[#6366f1] bg-clip-text text-transparent">elk team</span>
                </h1>
                <p class="pricing-reveal pricing-reveal-d2 mx-auto mt-5 max-w-2xl text-base leading-relaxed text-slate-500 sm:text-lg">
                    Kies het plan dat past bij jouw organisatie. Start direct — geen creditcard nodig voor je proefperiode.
                </p>
                <div class="pricing-reveal pricing-reveal-d3 mx-auto mt-9 flex max-w-2xl flex-wrap justify-center gap-2.5 sm:gap-3">
                    @foreach (['14 dagen gratis', 'Mollie-checkout', 'Flexibel schalen', 'excl. btw getoond'] as $i => $trust)
                        <span class="pricing-pill pricing-float-y {{ $i === 1 ? 'pricing-float-y--2' : ($i === 2 ? 'pricing-float-y--3' : ($i === 3 ? 'pricing-float-y--4' : '')) }} inline-flex items-center gap-2 rounded-full border border-slate-200/90 bg-white/85 px-3.5 py-2 text-xs font-medium text-slate-700 shadow-sm ring-1 ring-white/60 backdrop-blur-sm sm:text-sm">
                            <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            {{ $trust }}
                        </span>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Plans --}}
        <section class="relative pb-16 sm:pb-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-stretch gap-6 md:grid-cols-2 lg:grid-cols-4 lg:gap-7">
                    {{-- Starter --}}
                    <article class="pricing-reveal pricing-reveal-d1 group pricing-card flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200/90 bg-white/92 p-6 shadow-sm ring-1 ring-white/70 backdrop-blur-sm sm:p-7">
                        <div class="pointer-events-none absolute -right-8 top-8 h-28 w-28 rounded-full bg-[radial-gradient(circle,rgba(37,99,235,0.07),transparent_68%)] opacity-0 transition-opacity duration-500 group-hover:opacity-100" aria-hidden="true"></div>
                        <div class="relative min-h-[5.25rem]">
                            <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">Starter</h2>
                            <p class="mt-1.5 text-sm leading-snug text-slate-500">Voor kleine teams die willen starten met structuur</p>
                        </div>
                        <div class="relative mt-5">
                            <p class="text-4xl font-extrabold tabular-nums text-slate-900 sm:text-[2.35rem]">€{{ $displayPrice((float) $plans['starter']['billing_amount']) }}<span class="ml-1 text-lg font-semibold text-slate-500">/{{ $billingSuffix($plans['starter']) }}</span></p>
                            <p class="mt-1 text-xs font-medium text-slate-400">excl. 21% btw</p>
                        </div>
                        <p class="relative mt-4 min-h-[3.5rem] text-sm leading-relaxed text-slate-600">Alles wat je nodig hebt om direct te beginnen met digitale checklists en controle.</p>
                        <ul class="relative mt-5 flex flex-1 flex-col gap-2.5 text-sm text-slate-600">
                            @foreach (\App\Models\Organisation\Company::planMarketingFeatures('starter') as $item)
                                <li class="flex gap-2.5 transition-[transform] duration-200 hover:translate-x-0.5">
                                    <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-md bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100/80">
                                        <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="relative mt-7">
                            @auth
                                <a href="{{ route('subscription.choose-plan') }}" class="pricing-btn-dark inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 py-3.5 text-sm font-semibold text-white">Start 14 dagen gratis</a>
                            @else
                                <a href="{{ route('register') }}" class="pricing-btn-dark inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 py-3.5 text-sm font-semibold text-white">Start 14 dagen gratis</a>
                            @endauth
                        </div>
                    </article>

                    {{-- Professional — featured --}}
                    <article class="pricing-reveal pricing-reveal-d2 pricing-card pricing-card--featured group relative z-[1] flex h-full flex-col overflow-visible rounded-3xl border-2 border-blue-500/50 bg-white p-6 sm:p-7 md:-mt-1 md:mb-1 lg:mt-0 lg:mb-0">
                        <span class="pricing-badge-pop absolute -top-3.5 left-1/2 z-30 -translate-x-1/2 whitespace-nowrap rounded-full bg-gradient-to-r from-[#2563eb] to-[#6366f1] px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.14em] text-white shadow-lg shadow-blue-500/35 ring-2 ring-white">Meest gekozen</span>
                        <div class="pointer-events-none absolute -left-12 top-1/3 h-36 w-36 rounded-full bg-[radial-gradient(circle,rgba(37,99,235,0.12),transparent_68%)] blur-2xl" aria-hidden="true"></div>
                        <div class="relative min-h-[5.25rem] pt-1">
                            <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">Professional</h2>
                            <p class="mt-1.5 text-sm leading-snug text-slate-500">Voor teams die meer controle en automatisering willen</p>
                        </div>
                        <div class="relative mt-5">
                            <div class="flex flex-wrap items-baseline gap-1">
                                <span class="bg-gradient-to-r from-[#2563eb] to-[#6366f1] bg-clip-text text-4xl font-extrabold tabular-nums text-transparent sm:text-[2.35rem]">€{{ $displayPrice((float) $plans['professional']['billing_amount']) }}</span>
                                <span class="text-lg font-semibold text-slate-500">/{{ $billingSuffix($plans['professional']) }}</span>
                            </div>
                            <p class="mt-1 text-xs font-medium text-slate-400">excl. 21% btw</p>
                        </div>
                        <p class="relative mt-4 min-h-[3.5rem] text-sm leading-relaxed text-slate-600">Meer inzicht, minder handmatig werk en sneller schakelen dankzij AI en rapportages.</p>
                        <ul class="relative mt-5 flex flex-1 flex-col gap-2.5 text-sm text-slate-600">
                            @foreach (\App\Models\Organisation\Company::planMarketingFeatures('professional') as $item)
                                <li class="flex gap-2.5 transition-[transform] duration-200 hover:translate-x-0.5">
                                    <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-md bg-blue-50 text-blue-600 ring-1 ring-blue-100/90">
                                        <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="relative mt-7">
                            @auth
                                <a href="{{ route('subscription.choose-plan') }}" class="cta-btn inline-flex w-full items-center justify-center rounded-2xl py-3.5 text-sm font-bold text-white">Start 14 dagen gratis</a>
                            @else
                                <a href="{{ route('register') }}" class="cta-btn inline-flex w-full items-center justify-center rounded-2xl py-3.5 text-sm font-bold text-white">Start 14 dagen gratis</a>
                            @endauth
                        </div>
                    </article>

                    {{-- Business --}}
                    <article class="pricing-reveal pricing-reveal-d3 group pricing-card flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200/90 bg-white/92 p-6 shadow-sm ring-1 ring-white/70 backdrop-blur-sm sm:p-7">
                        <div class="pointer-events-none absolute -right-6 top-24 h-24 w-24 rounded-full bg-[radial-gradient(circle,rgba(16,185,129,0.1),transparent_68%)] opacity-0 transition-opacity duration-500 group-hover:opacity-100" aria-hidden="true"></div>
                        <div class="relative min-h-[5.25rem]">
                            <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">Business</h2>
                            <p class="mt-1.5 text-sm leading-snug text-slate-500">Voor bedrijven met meerdere locaties en grotere teams</p>
                        </div>
                        <div class="relative mt-5">
                            <p class="text-4xl font-extrabold tabular-nums text-slate-900 sm:text-[2.35rem]">€{{ $displayPrice((float) $plans['business']['billing_amount']) }}<span class="ml-1 text-lg font-semibold text-slate-500">/{{ $billingSuffix($plans['business']) }}</span></p>
                            <p class="mt-1 text-xs font-medium text-slate-400">excl. 21% btw</p>
                        </div>
                        <p class="relative mt-4 min-h-[3.5rem] text-sm leading-relaxed text-slate-600">Volledige controle over meerdere locaties, met diep inzicht in prestaties en kwaliteit per vestiging.</p>
                        <ul class="relative mt-5 flex flex-1 flex-col gap-2.5 text-sm text-slate-600">
                            @foreach (\App\Models\Organisation\Company::planMarketingFeatures('business') as $item)
                                <li class="flex gap-2.5 transition-[transform] duration-200 hover:translate-x-0.5">
                                    <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-md bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100/80">
                                        <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="relative mt-7">
                            @auth
                                <a href="{{ route('subscription.choose-plan') }}" class="pricing-btn-dark inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 py-3.5 text-sm font-semibold text-white">Start 14 dagen gratis</a>
                            @else
                                <a href="{{ route('register') }}" class="pricing-btn-dark inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 py-3.5 text-sm font-semibold text-white">Start 14 dagen gratis</a>
                            @endauth
                        </div>
                    </article>

                    {{-- Enterprise --}}
                    <article class="pricing-reveal pricing-reveal-d4 group pricing-card flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-b from-slate-50/95 to-white p-6 shadow-sm ring-1 ring-slate-100/80 sm:p-7">
                        <div class="pointer-events-none absolute inset-x-0 -top-px h-px bg-gradient-to-r from-transparent via-indigo-300/50 to-transparent opacity-70" aria-hidden="true"></div>
                        <div class="relative min-h-[5.25rem]">
                            <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">Enterprise</h2>
                            <p class="mt-1.5 text-sm leading-snug text-slate-500">Voor grotere organisaties en ketens</p>
                        </div>
                        <div class="relative mt-5">
                            <p class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Op aanvraag</p>
                            <p class="mt-1 text-xs font-medium text-slate-400">Maatwerk & contract</p>
                        </div>
                        <p class="relative mt-4 min-h-[3.5rem] text-sm leading-relaxed text-slate-600">Volledig op maat ingericht voor jouw organisatie, met maximale flexibiliteit en ondersteuning.</p>
                        <ul class="relative mt-5 flex flex-1 flex-col gap-2.5 text-sm text-slate-600">
                            @foreach (['Onbeperkte admins & medewerkers', 'Dedicated accountmanager', 'SLA met uptime-garantie', 'Persoonlijke onboarding', 'Maatwerk integraties'] as $item)
                                <li class="flex gap-2.5 transition-[transform] duration-200 hover:translate-x-0.5">
                                    <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-md bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100/90">
                                        <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('contact') }}" class="pricing-glass-btn relative mt-7 inline-flex w-full items-center justify-center rounded-2xl border border-slate-200/95 bg-white/90 py-3.5 text-sm font-semibold text-slate-800 shadow-sm backdrop-blur-sm">Vraag een offerte aan</a>
                    </article>
                </div>

                <p class="pricing-reveal mx-auto mt-10 max-w-2xl text-center text-sm leading-relaxed text-slate-500">
                    Bij het afrekenen wordt <strong class="font-semibold text-slate-700">21% btw</strong> toegepast (standaardtarief Nederland). Betaling verloopt veilig via Mollie.
                </p>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="border-t border-slate-100 bg-slate-50/60 pb-20 pt-2 sm:pb-24">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <h2 class="pricing-reveal text-center text-2xl font-extrabold text-slate-900 sm:text-3xl">Veelgestelde vragen</h2>
                <p class="pricing-reveal pricing-reveal-d1 mx-auto mt-2 max-w-lg text-center text-sm text-slate-500 sm:text-base">Alles wat je wilt weten vóór je een plan kiest.</p>
                <div class="mt-10 grid gap-4 sm:grid-cols-2 sm:gap-5">
                    <div class="pricing-reveal pricing-reveal-d1 pricing-faq-card rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex gap-3">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500/15 to-indigo-500/10 text-blue-600" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3 3.75h13.5M3.375 7.5h17.25"/></svg>
                            </span>
                            <div>
                                <h3 class="font-semibold text-slate-900">Hoe werkt betalen?</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">Na je plankeuze ga je naar een beveiligde Mollie-checkout. Je abonnement wordt direct geactiveerd na betaling.</p>
                            </div>
                        </div>
                    </div>
                    <div class="pricing-reveal pricing-reveal-d2 pricing-faq-card rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex gap-3">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500/15 to-indigo-500/10 text-blue-600" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m9 0v4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </span>
                            <div>
                                <h3 class="font-semibold text-slate-900">Kan ik tussentijds wisselen?</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">Ja, op- en afschalen kan vanuit je abonnementspagina. Je betaalt naar wat je gebruikt.</p>
                            </div>
                        </div>
                    </div>
                    <div class="pricing-reveal pricing-reveal-d3 pricing-faq-card rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex gap-3">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500/15 to-teal-500/10 text-emerald-700" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div>
                                <h3 class="font-semibold text-slate-900">Wat na 14 dagen gratis?</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">Je kiest pas daarna een plan. Geen automatische incasso zonder jouw akkoord.</p>
                            </div>
                        </div>
                    </div>
                    <div class="pricing-reveal pricing-reveal-d4 pricing-faq-card rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex gap-3">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500/15 to-fuchsia-500/10 text-violet-700" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                            </span>
                            <div>
                                <h3 class="font-semibold text-slate-900">Korting op jaarbetaling?</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">Neem contact op — voor jaarabonnementen maken we graag maatwerk.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pricing-reveal pricing-cta-bottom relative mt-12 rounded-3xl border border-slate-200/90 p-8 text-center shadow-md sm:p-10">
                    <p class="relative text-lg font-bold text-slate-900">Nog twijfels?</p>
                    <p class="relative mx-auto mt-2 max-w-md text-sm text-slate-600">We laten je graag in een kort gesprek zien hoe TaskCheck in jouw processen past.</p>
                    <div class="relative mt-6 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('contact') }}" class="cta-btn inline-flex items-center gap-2 rounded-2xl px-7 py-3.5 text-sm font-bold text-white">Plan een demo</a>
                        <a href="{{ route('welcome') }}" class="pricing-glass-btn inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white/95 px-7 py-3.5 text-sm font-bold text-slate-700 shadow-sm backdrop-blur-sm">Terug naar homepage</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('components.footer')

    <script>
        (function () {
            var nodes = document.querySelectorAll('.pricing-reveal');
            if (!nodes.length) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                nodes.forEach(function (el) { el.classList.add('visible'); });
                return;
            }
            if (!('IntersectionObserver' in window)) {
                nodes.forEach(function (el) { el.classList.add('visible'); });
                return;
            }
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (!e.isIntersecting) return;
                    e.target.classList.add('visible');
                    io.unobserve(e.target);
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -32px 0px' });
            nodes.forEach(function (el) { io.observe(el); });
        })();
    </script>
</body>
</html>
