<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Contact over checklist app voor bedrijven | TaskCheck';
        $seoDescription = 'Contacteer TaskCheck over onze takenlijst personeel en werkcontrole app voor horeca, schoonmaak en andere bedrijven.';
        $seoUrl = route('contact');
        $seoImage = asset('logos/taskcheck-logo.png');
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
    @if(filled(config('services.recaptcha.site_key')))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}&hl=nl" async defer></script>
    @endif

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "ContactPage",
            "name": "Contact TaskCheck",
            "url": "{{ $seoUrl }}",
            "description": "{{ $seoDescription }}",
            "mainEntity": {
                "@@type": "Organization",
                "name": "TaskCheck",
                "telephone": "+31881900999",
                "openingHoursSpecification": [
                    {
                        "@@type": "OpeningHoursSpecification",
                        "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                        "opens": "09:00",
                        "closes": "17:00"
                    }
                ],
                "contactPoint": [
                    {
                        "@@type": "ContactPoint",
                        "contactType": "customer support",
                        "email": "support@taskcheck.nl",
                        "telephone": "+31881900999",
                        "availableLanguage": ["nl", "en"]
                    },
                    {
                        "@@type": "ContactPoint",
                        "contactType": "sales",
                        "email": "info@taskcheck.nl",
                        "telephone": "+31881900999",
                        "availableLanguage": ["nl", "en"]
                    }
                ]
            }
        }
    </script>
    <style>
        .contact-scene { isolation: isolate; }

        .contact-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }
        .contact-bg__mesh {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 55% at 10% -5%, rgb(79 107 255 / 0.11), transparent 52%),
                radial-gradient(ellipse 65% 50% at 92% 8%, rgb(123 97 255 / 0.1), transparent 48%),
                radial-gradient(ellipse 50% 42% at 50% 100%, rgb(99 102 241 / 0.06), transparent 52%),
                linear-gradient(180deg, rgb(248 250 252) 0%, rgb(255 255 255) 42%, rgb(248 250 252) 100%);
        }
        .contact-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(68px);
            opacity: 0.82;
            animation: contact-orb-drift 24s ease-in-out infinite;
        }
        .contact-orb--a {
            width: min(34rem, 88vw);
            height: min(34rem, 88vw);
            right: -16%;
            top: 2%;
            background: radial-gradient(circle at 38% 38%, rgb(79 107 255 / 0.2), rgb(123 97 255 / 0.08) 48%, transparent 72%);
            animation-duration: 28s;
        }
        .contact-orb--b {
            width: min(26rem, 72vw);
            height: min(26rem, 72vw);
            left: -12%;
            bottom: 8%;
            background: radial-gradient(circle at center, rgb(99 102 241 / 0.14), transparent 70%);
            animation-duration: 21s;
            animation-delay: -8s;
        }
        .contact-grid-noise {
            position: absolute;
            inset: 0;
            opacity: 0.32;
            background-image:
                linear-gradient(rgb(15 23 42 / 0.028) 1px, transparent 1px),
                linear-gradient(90deg, rgb(15 23 42 / 0.028) 1px, transparent 1px);
            background-size: 72px 72px;
            mask-image: radial-gradient(ellipse 72% 58% at 50% 32%, black 18%, transparent 70%);
        }
        .contact-noise-fine {
            position: absolute;
            inset: 0;
            opacity: 0.035;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        }

        @keyframes contact-orb-drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            40% { transform: translate(14px, -20px) scale(1.03); }
            70% { transform: translate(-10px, 12px) scale(0.99); }
        }

        .contact-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1), transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .contact-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .contact-reveal-d1.visible { transition-delay: 0.07s; }
        .contact-reveal-d2.visible { transition-delay: 0.14s; }
        .contact-reveal-d3.visible { transition-delay: 0.21s; }
        .contact-reveal-d4.visible { transition-delay: 0.28s; }

        .contact-float-pill { animation: contact-float-y 6s ease-in-out infinite; }
        .contact-float-pill--2 { animation-delay: -2s; }
        .contact-float-pill--3 { animation-delay: -3.4s; }
        @keyframes contact-float-y {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }

        .contact-form-shell {
            position: relative;
            border-radius: 1.5rem;
            background: linear-gradient(165deg, rgb(255 255 255 / 0.96) 0%, rgb(255 255 255 / 0.88) 100%);
            box-shadow:
                0 0 0 1px rgb(255 255 255 / 0.8) inset,
                0 1px 2px rgb(15 23 42 / 0.04),
                0 20px 52px -28px rgb(79 107 255 / 0.18),
                0 16px 44px -28px rgb(15 23 42 / 0.09);
            border: 1px solid rgb(226 232 240 / 0.95);
            transition: transform 0.35s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.35s ease, border-color 0.3s ease;
        }
        .contact-form-shell:hover {
            border-color: rgb(79 107 255 / 0.18);
            box-shadow:
                0 0 0 1px rgb(255 255 255 / 0.85) inset,
                0 1px 2px rgb(15 23 42 / 0.04),
                0 26px 60px -24px rgb(79 107 255 / 0.22),
                0 18px 48px -26px rgb(15 23 42 / 0.1);
        }

        .contact-side-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.25rem;
            border: 1px solid rgb(226 232 240 / 0.92);
            background: linear-gradient(155deg, rgb(255 255 255 / 0.94) 0%, rgb(248 250 252 / 0.85) 100%);
            box-shadow:
                0 0 0 1px rgb(255 255 255 / 0.65) inset,
                0 12px 36px -20px rgb(15 23 42 / 0.1);
            transition: transform 0.32s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.32s ease, border-color 0.25s ease;
        }
        .contact-side-card::before {
            content: '';
            position: absolute;
            width: 9rem;
            height: 9rem;
            right: -3rem;
            top: -3rem;
            border-radius: 50%;
            opacity: 0.55;
            pointer-events: none;
            transition: opacity 0.35s ease, transform 0.35s ease;
        }
        .contact-side-card:hover {
            transform: translateY(-4px);
        }
        .contact-side-card:hover::before {
            opacity: 0.85;
            transform: scale(1.08);
        }
        .contact-side-card--mail::before {
            background: radial-gradient(circle at center, rgb(79 107 255 / 0.22), transparent 68%);
        }
        .contact-side-card--hours::before {
            background: radial-gradient(circle at center, rgb(16 185 129 / 0.2), transparent 68%);
        }
        .contact-side-card--links::before {
            background: radial-gradient(circle at center, rgb(123 97 255 / 0.2), transparent 68%);
        }
        .contact-side-card--mail:hover { border-color: rgb(79 107 255 / 0.28); box-shadow: 0 0 0 1px rgb(255 255 255 / 0.7) inset, 0 18px 42px -18px rgb(79 107 255 / 0.2); }
        .contact-side-card--hours:hover { border-color: rgb(16 185 129 / 0.28); box-shadow: 0 0 0 1px rgb(255 255 255 / 0.7) inset, 0 18px 42px -18px rgb(16 185 129 / 0.16); }
        .contact-side-card--links:hover { border-color: rgb(123 97 255 / 0.28); box-shadow: 0 0 0 1px rgb(255 255 255 / 0.7) inset, 0 18px 42px -18px rgb(123 97 255 / 0.18); }

        .contact-input {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid rgb(226 232 240 / 0.95);
            background: linear-gradient(180deg, rgb(255 255 255 / 0.95) 0%, rgb(248 250 252 / 0.75) 100%);
            padding: 1.35rem 1rem 0.45rem;
            font-size: 0.9375rem;
            color: rgb(15 23 42);
            box-shadow: 0 1px 0 rgb(255 255 255 / 0.8) inset;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        .contact-input::placeholder {
            color: transparent;
        }
        .contact-input:hover {
            border-color: rgb(203 213 225 / 0.95);
        }
        .contact-input:focus {
            outline: none;
            border-color: rgb(79 107 255 / 0.45);
            box-shadow:
                0 0 0 3px rgb(79 107 255 / 0.12),
                0 1px 0 rgb(255 255 255 / 0.8) inset;
            background: rgb(255 255 255);
        }
        .contact-input-label {
            position: absolute;
            left: 1rem;
            top: 0.95rem;
            font-size: 0.9375rem;
            color: rgb(100 116 139);
            pointer-events: none;
            transform-origin: left top;
            transition: transform 0.2s ease, color 0.2s ease, font-size 0.2s ease, top 0.2s ease;
        }
        .contact-input:focus ~ .contact-input-label,
        .contact-input:not(:placeholder-shown) ~ .contact-input-label {
            top: 0.45rem;
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: rgb(79 107 255);
        }
        .contact-input--area {
            padding-top: 1.25rem;
            padding-bottom: 0.85rem;
        }
        .contact-input--area ~ .contact-input-label {
            top: 0.85rem;
        }
        .contact-input--area:focus ~ .contact-input-label,
        .contact-input--area:not(:placeholder-shown) ~ .contact-input-label {
            top: 0.45rem;
        }

        .contact-select-wrap {
            position: relative;
        }
        .contact-select {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid rgb(226 232 240 / 0.95);
            background: linear-gradient(180deg, rgb(255 255 255 / 0.95) 0%, rgb(248 250 252 / 0.75) 100%);
            /* Ruimte boven: vaste label (klein) + regel met gekozen optie — geen “dubbele” floating state */
            padding: 1.45rem 2.75rem 0.5rem 1rem;
            min-height: 3.55rem;
            font-size: 0.9375rem;
            line-height: 1.4;
            color: rgb(100 116 139);
            box-shadow: 0 1px 0 rgb(255 255 255 / 0.8) inset;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
            touch-action: manipulation;
        }
        .contact-select:hover {
            border-color: rgb(203 213 225 / 0.95);
        }
        .contact-select:focus {
            outline: none;
            border-color: rgb(79 107 255 / 0.45);
            box-shadow:
                0 0 0 3px rgb(79 107 255 / 0.12),
                0 1px 0 rgb(255 255 255 / 0.8) inset;
            background: rgb(255 255 255);
            color: rgb(15 23 42);
        }
        .contact-select--has-value {
            color: rgb(15 23 42);
        }
        .contact-select option {
            color: rgb(15 23 42);
            background: rgb(255 255 255);
            font-weight: 500;
            padding: 0.5rem;
        }
        .contact-select option:first-of-type {
            color: rgb(100 116 139);
        }
        /* Select: label altijd compact — anders overlapt “Onderwerp” met de zichtbare eerste optie */
        .contact-select-label {
            position: absolute;
            left: 1rem;
            top: 0.45rem;
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: rgb(100 116 139);
            pointer-events: none;
        }
        .contact-select-wrap:focus-within .contact-select-label {
            color: rgb(79 107 255);
        }
        .contact-select-chevron {
            position: absolute;
            right: 1rem;
            top: 1.65rem;
            transform: translateY(-50%);
            width: 1.25rem;
            height: 1.25rem;
            color: rgb(100 116 139);
            pointer-events: none;
            transition: color 0.2s ease;
        }
        .contact-select-wrap:focus-within .contact-select-chevron {
            color: rgb(79 107 255);
        }
        .contact-cta {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #4f6bff 0%, #7b61ff 100%);
            box-shadow:
                0 0 0 1px rgb(255 255 255 / 0.16) inset,
                0 10px 28px -6px rgb(79 107 255 / 0.35),
                0 0 40px -12px rgb(123 97 255 / 0.3);
            transition: transform 0.22s cubic-bezier(0.2, 0.8, 0.2, 1), filter 0.22s ease, box-shadow 0.28s ease;
        }
        .contact-cta::after {
            content: '';
            position: absolute;
            inset: 0;
            left: -110%;
            width: 65%;
            background: linear-gradient(105deg, transparent 0%, rgb(255 255 255 / 0.28) 50%, transparent 100%);
            transform: skewX(-16deg);
            transition: left 0.55s ease;
            pointer-events: none;
        }
        .contact-cta:hover {
            transform: translateY(-2px);
            filter: brightness(1.04);
            box-shadow:
                0 0 0 1px rgb(255 255 255 / 0.22) inset,
                0 14px 36px -8px rgb(79 107 255 / 0.4),
                0 0 48px -10px rgb(123 97 255 / 0.38);
        }
        .contact-cta:hover::after {
            left: 110%;
        }
        .contact-cta:active {
            transform: translateY(0) scale(0.98);
        }
        .grecaptcha-badge {
            z-index: 40;
            bottom: 100px !important;
        }

        .sparkle-pill {
            position: relative;
            overflow: hidden;
        }
        .sparkle-pill::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 22%, rgb(255 255 255 / 0.55) 50%, transparent 78%);
            transform: translateX(-120%);
            transition: transform 0.55s ease;
            pointer-events: none;
        }
        .sparkle-pill:hover::after {
            transform: translateX(120%);
        }

        @media (prefers-reduced-motion: reduce) {
            .contact-orb,
            .contact-float-pill,
            .contact-float-pill--2,
            .contact-float-pill--3 {
                animation: none !important;
            }
            .contact-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }
            .contact-reveal-d1.visible,
            .contact-reveal-d2.visible,
            .contact-reveal-d3.visible,
            .contact-reveal-d4.visible {
                transition-delay: 0s !important;
            }
            .contact-form-shell:hover,
            .contact-side-card:hover {
                transform: none;
            }
            .contact-cta:hover {
                transform: none;
                filter: none;
            }
            .contact-cta::after {
                display: none;
            }
            .sparkle-pill::after {
                display: none;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-white font-sans text-slate-900 antialiased">
    <div class="contact-bg" aria-hidden="true">
        <div class="contact-bg__mesh"></div>
        <div class="contact-orb contact-orb--a"></div>
        <div class="contact-orb contact-orb--b"></div>
        <div class="contact-grid-noise"></div>
        <div class="contact-noise-fine"></div>
    </div>

    @include('components.header')

    <div class="contact-scene">
        <section class="relative overflow-hidden pt-28 pb-14 sm:pb-16 sm:pt-32 lg:pt-36">
            <div class="relative mx-auto max-w-6xl px-4 text-center sm:px-6 lg:px-8">
                <div class="contact-reveal inline-flex items-center gap-2 rounded-full border border-[#4F6BFF]/20 bg-white/85 px-4 py-2 text-xs font-semibold text-slate-700 shadow-[0_8px_30px_-12px_rgba(79,107,255,0.25)] ring-1 ring-white/60 backdrop-blur-md">
                    <span class="relative flex h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_12px_rgba(52,211,153,0.65)]"></span>
                We helpen je graag verder
            </div>
                <h1 class="contact-reveal contact-reveal-d1 mx-auto mt-8 max-w-4xl text-4xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl lg:text-[3.15rem] lg:leading-[1.06]">
                Neem contact op met
                    <span class="mt-3 block bg-gradient-to-r from-[#4F6BFF] via-[#5f6af8] to-[#7B61FF] bg-clip-text text-transparent sm:mt-4">het TaskCheck team</span>
            </h1>
                <p class="contact-reveal contact-reveal-d2 mx-auto mt-6 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg sm:leading-relaxed">
                    Vraag een demo aan, stel je vraag of laat ons meedenken over je operationele proces. We reageren meestal binnen één werkdag.
                </p>
                <div class="contact-reveal contact-reveal-d3 mt-8 flex flex-wrap justify-center gap-2.5 sm:mt-10 sm:gap-3">
                    <span class="contact-float-pill sparkle-pill inline-flex items-center gap-2 rounded-full border border-emerald-200/90 bg-emerald-50/90 px-3.5 py-2 text-xs font-semibold text-emerald-800 shadow-sm ring-1 ring-emerald-100/80 sm:text-sm">
                        <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                        Snelle reactie
                    </span>
                    <span class="contact-float-pill contact-float-pill--2 sparkle-pill inline-flex items-center gap-2 rounded-full border border-[#4F6BFF]/20 bg-white/90 px-3.5 py-2 text-xs font-semibold text-[#334155] shadow-sm ring-1 ring-[#4F6BFF]/10 sm:text-sm">
                        <svg class="h-4 w-4 text-[#4F6BFF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/></svg>
                        Demo op maat
                    </span>
                    <span class="contact-float-pill contact-float-pill--3 sparkle-pill inline-flex items-center gap-2 rounded-full border border-violet-200/90 bg-violet-50/90 px-3.5 py-2 text-xs font-semibold text-violet-900 shadow-sm ring-1 ring-violet-100/80 sm:text-sm">
                        <svg class="h-4 w-4 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                        Persoonlijk contact
                    </span>
            </div>
        </div>
    </section>

        <section class="relative pb-20 sm:pb-24 lg:pb-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-5 lg:gap-10 xl:gap-12">
                    <div class="contact-reveal contact-reveal-d2 lg:col-span-3">
                        <div class="contact-form-shell p-6 sm:p-8 lg:p-10">
                            <div class="relative">
                                <div class="pointer-events-none absolute -right-8 -top-16 h-36 w-36 rounded-full bg-[radial-gradient(circle_at_center,rgba(79,107,255,0.18),transparent_70%)] blur-2xl"></div>
                                <h2 class="relative text-2xl font-extrabold tracking-tight text-slate-900 sm:text-[1.65rem]">Stuur ons een bericht</h2>
                                <p class="relative mt-2 max-w-xl text-sm leading-relaxed text-slate-600 sm:text-base">Vertel kort wat je zoekt — we lezen alles zelf en reageren persoonlijk.</p>
                            </div>

                            @if(session('success'))
                                <div class="mt-6 flex gap-4 rounded-2xl border border-emerald-200/90 bg-emerald-50/90 p-4 shadow-sm backdrop-blur-sm" role="alert">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <p class="pt-2 text-sm font-medium leading-relaxed text-emerald-900">{{ session('success') }}</p>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="mt-6 flex gap-4 rounded-2xl border border-red-200/90 bg-red-50/90 p-4 shadow-sm backdrop-blur-sm" role="alert">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                    </span>
                                    <p class="pt-2 text-sm font-medium leading-relaxed text-red-900">{{ session('error') }}</p>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="mt-6 rounded-2xl border border-red-200/90 bg-red-50/90 p-4 shadow-sm backdrop-blur-sm" role="alert">
                                    <p class="text-sm font-semibold text-red-900">Er ontbrak nog iets</p>
                                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-800">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form
                                method="POST"
                                action="{{ route('contact.send') }}"
                                class="relative mt-8 space-y-5 sm:space-y-6"
                                data-contact-form
                                @if(filled(config('services.recaptcha.site_key')))
                                    data-recaptcha-sitekey="{{ config('services.recaptcha.site_key') }}"
                                @endif
                            >
                                @csrf
                                <div class="grid gap-4 sm:grid-cols-2 sm:gap-5">
                                    <div class="relative">
                                        <input type="text" id="firstName" name="firstName" value="{{ old('firstName') }}" placeholder=" " autocomplete="given-name" class="contact-input" required>
                                        <label for="firstName" class="contact-input-label">Voornaam</label>
                                    </div>
                                    <div class="relative">
                                        <input type="text" id="lastName" name="lastName" value="{{ old('lastName') }}" placeholder=" " autocomplete="family-name" class="contact-input" required>
                                        <label for="lastName" class="contact-input-label">Achternaam</label>
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2 sm:gap-5">
                                    <div class="relative">
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder=" " autocomplete="email" class="contact-input" required>
                                        <label for="email" class="contact-input-label">E-mail</label>
                                    </div>
                                    <div class="relative">
                                        <input type="text" id="company" name="company" value="{{ old('company') }}" placeholder=" " autocomplete="organization" class="contact-input">
                                        <label for="company" class="contact-input-label">Bedrijf (optioneel)</label>
                                    </div>
                                </div>

                                <div class="contact-select-wrap">
                                    <select
                                        id="subject"
                                        name="subject"
                                        @class(['contact-select', 'contact-select--has-value' => filled(old('subject'))])
                                        data-contact-select
                                        autocomplete="off"
                                    >
                                        <option value="" @selected(old('subject') === '')>Kies een onderwerp</option>
                                        <option value="demo" @selected(old('subject') === 'demo')>Demo aanvragen</option>
                                        <option value="sales" @selected(old('subject') === 'sales')>Verkoopvraag</option>
                                        <option value="support" @selected(old('subject') === 'support')>Technische ondersteuning</option>
                                        <option value="billing" @selected(old('subject') === 'billing')>Facturatie</option>
                                        <option value="other" @selected(old('subject') === 'other')>Overig</option>
                                    </select>
                                    <label for="subject" class="contact-select-label">Onderwerp</label>
                                    <span class="contact-select-chevron" aria-hidden="true">
                                        <svg class="h-full w-full" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                    </span>
                                </div>

                                <div class="relative">
                                    <textarea id="message" name="message" rows="5" placeholder=" " class="contact-input contact-input--area min-h-[8.5rem] resize-y sm:min-h-[9.5rem]" required>{{ old('message') }}</textarea>
                                    <label for="message" class="contact-input-label">Bericht</label>
                                </div>

                                @if(filled(config('services.recaptcha.site_key')))
                                    <input type="hidden" name="g-recaptcha-response" value="" autocomplete="off">
                                    <p class="hidden text-sm font-medium text-red-700" data-recaptcha-error>De beveiligingscontrole is mislukt. Probeer het opnieuw.</p>
                                @endif

                                <div class="flex flex-col gap-4 pt-2 sm:flex-row sm:items-center sm:justify-between">
                                    <button type="submit" class="contact-cta inline-flex h-12 w-full items-center justify-center gap-2 rounded-2xl px-8 text-sm font-semibold text-white sm:w-auto sm:min-w-[12rem]" data-contact-submit>
                                        <svg class="relative z-[1] h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                        <span class="relative z-[1]">Verstuur bericht</span>
                                    </button>
                                    <p class="text-center text-xs leading-relaxed text-slate-500 sm:text-left sm:max-w-xs">Je gegevens gebruiken we alleen om je vraag te beantwoorden — nooit voor spam. Deze pagina is beschermd door reCAPTCHA; het <a href="https://policies.google.com/privacy" class="underline decoration-slate-300 underline-offset-2 hover:text-slate-700" rel="noopener noreferrer" target="_blank">privacybeleid</a> en de <a href="https://policies.google.com/terms" class="underline decoration-slate-300 underline-offset-2 hover:text-slate-700" rel="noopener noreferrer" target="_blank">voorwaarden</a> van Google zijn van toepassing.</p>
                        </div>
                            </form>
                        </div>
                    </div>

                    <div class="contact-reveal contact-reveal-d3 flex flex-col gap-4 sm:gap-5 lg:col-span-2">
                        <div class="contact-side-card contact-side-card--mail p-5 sm:p-6">
                            <div class="relative flex items-start gap-4">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#4F6BFF]/15 to-[#7B61FF]/10 text-[#4F6BFF] ring-1 ring-[#4F6BFF]/15">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <h3 class="text-base font-bold text-slate-900">Direct per e-mail</h3>
                                    <div class="mt-3 space-y-3 text-sm">
                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Algemeen &amp; verkoop</p>
                                            <a href="mailto:info@taskcheck.nl" class="mt-1 block font-semibold text-[#4F6BFF] transition hover:text-[#3d56cc]">info@taskcheck.nl</a>
                        </div>
                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Technische ondersteuning</p>
                                            <a href="mailto:support@taskcheck.nl" class="mt-1 block font-semibold text-[#4F6BFF] transition hover:text-[#3d56cc]">support@taskcheck.nl</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="contact-side-card contact-side-card--hours p-5 sm:p-6">
                            <div class="relative flex items-start gap-4">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/80 text-emerald-700 ring-1 ring-emerald-200/70">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <h3 class="text-base font-bold text-slate-900">Telefoon &amp; bereikbaarheid</h3>
                                    <a href="tel:+31881900999" class="mt-2 block text-lg font-bold tracking-tight text-[#4F6BFF] transition hover:text-[#3d56cc]">088 190 0999</a>
                                    <p class="mt-2 text-sm font-medium text-slate-700">Maandag t/m vrijdag</p>
                                    <p class="mt-0.5 text-sm text-slate-600">09:00 – 17:00 (CET)</p>
                    </div>
                    </div>
                    </div>

                        <div class="contact-side-card contact-side-card--links p-5 sm:p-6">
                            <div class="relative flex items-start gap-4">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-50 to-[#7B61FF]/10 text-[#7B61FF] ring-1 ring-violet-200/70">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.629l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base font-bold text-slate-900">Snelle links</h3>
                                    <nav class="mt-3 space-y-2 text-sm" aria-label="Snelle links">
                                        <a href="{{ route('pricing') }}" class="group flex items-center justify-between gap-2 rounded-xl py-1.5 font-medium text-slate-700 transition hover:text-[#4F6BFF]">
                                            <span>Bekijk prijzen</span>
                                            <svg class="h-4 w-4 text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-[#4F6BFF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                        </a>
                                        <a href="{{ route('login') }}" class="group flex items-center justify-between gap-2 rounded-xl py-1.5 font-medium text-slate-700 transition hover:text-[#4F6BFF]">
                                            <span>Start 14 dagen gratis</span>
                                            <svg class="h-4 w-4 text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-[#4F6BFF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                        </a>
                                        <a href="{{ route('seo.werkcontrole-app') }}" class="group flex items-center justify-between gap-2 rounded-xl py-1.5 font-medium text-slate-700 transition hover:text-[#4F6BFF]">
                                            <span>Werkcontrole app</span>
                                            <svg class="h-4 w-4 text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-[#4F6BFF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                        </a>
                                        <a href="{{ route('blog') }}" class="group flex items-center justify-between gap-2 rounded-xl py-1.5 font-medium text-slate-700 transition hover:text-[#4F6BFF]">
                                            <span>Lees de blog</span>
                                            <svg class="h-4 w-4 text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-[#4F6BFF]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                        </a>
                                    </nav>
            </div>
                </div>
                </div>
                    </div>
                </div>
            </div>
        </section>
        </div>

    @include('components.footer')

    <script>
        (function () {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                document.querySelectorAll('.contact-reveal').forEach(function (el) { el.classList.add('visible'); });
                return;
            }
            if (!('IntersectionObserver' in window)) {
                document.querySelectorAll('.contact-reveal').forEach(function (el) { el.classList.add('visible'); });
                return;
            }
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (!e.isIntersecting) return;
                    e.target.classList.add('visible');
                    io.unobserve(e.target);
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -36px 0px' });
            document.querySelectorAll('.contact-reveal').forEach(function (el) { io.observe(el); });
        })();

        (function () {
            document.querySelectorAll('[data-contact-select]').forEach(function (sel) {
                function sync() {
                    sel.classList.toggle('contact-select--has-value', sel.value !== '');
                }
                sel.addEventListener('change', sync);
                sel.addEventListener('blur', sync);
                sync();
            });
        })();

        (function () {
            var form = document.querySelector('[data-contact-form]');
            if (!form) return;
            var siteKey = form.getAttribute('data-recaptcha-sitekey');
            if (!siteKey) return;
            var errorEl = form.querySelector('[data-recaptcha-error]');
            var submitBtn = form.querySelector('[data-contact-submit]');

            form.addEventListener('submit', function (e) {
                if (form.getAttribute('data-recaptcha-ready') === '1') return;
                e.preventDefault();
                if (errorEl) errorEl.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = true;

                function fail() {
                    if (submitBtn) submitBtn.disabled = false;
                    if (errorEl) errorEl.classList.remove('hidden');
                }

                function setToken(token) {
                    var fields = form.querySelectorAll('[name="g-recaptcha-response"]');
                    if (!fields.length) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'g-recaptcha-response';
                        form.appendChild(input);
                        fields = form.querySelectorAll('[name="g-recaptcha-response"]');
                    }
                    fields.forEach(function (el) { el.value = token; });
                }

                if (!window.grecaptcha || typeof window.grecaptcha.ready !== 'function') {
                    fail();
                    return;
                }

                window.grecaptcha.ready(function () {
                    window.grecaptcha.execute(siteKey, { action: 'contact' }).then(function (token) {
                        if (!token) {
                            fail();
                            return;
                        }
                        setToken(token);
                        form.setAttribute('data-recaptcha-ready', '1');
                        if (typeof form.requestSubmit === 'function') {
                            form.requestSubmit();
                        } else {
                            form.submit();
                        }
                    }).catch(fail);
                });
            });
        })();
    </script>
</body>
</html>
