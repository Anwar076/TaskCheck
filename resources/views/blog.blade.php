<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Blog TaskCheck – Checklist app tips voor horeca, schoonmaak en teams';
        $seoDescription = 'Lees praktische artikelen over takenlijst personeel, werkcontrole app workflows en checklist app voor bedrijven in horeca en schoonmaak.';
        $seoUrl = route('blog');
        $seoImage = asset('images/taskcheck-horeca-blog-hero.webp');
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
        .blog-scene { isolation: isolate; }

        .blog-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }
        .blog-bg__mesh {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 78% 52% at 12% -8%, rgb(79 107 255 / 0.11), transparent 52%),
                radial-gradient(ellipse 60% 48% at 92% 4%, rgb(123 97 255 / 0.09), transparent 48%),
                radial-gradient(ellipse 50% 40% at 50% 100%, rgb(99 102 241 / 0.05), transparent 52%),
                linear-gradient(180deg, rgb(248 250 252) 0%, rgb(255 255 255) 38%, rgb(248 250 252 / 0.96) 100%);
        }
        .blog-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(64px);
            opacity: 0.78;
            animation: blog-orb-drift 22s ease-in-out infinite;
        }
        .blog-orb--1 {
            width: min(28rem, 85vw);
            height: min(28rem, 85vw);
            right: -18%;
            top: -6%;
            background: radial-gradient(circle at 38% 38%, rgb(79 107 255 / 0.22), rgb(123 97 255 / 0.08) 48%, transparent 72%);
            animation-duration: 26s;
        }
        .blog-orb--2 {
            width: min(22rem, 70vw);
            height: min(22rem, 70vw);
            left: -14%;
            bottom: 18%;
            background: radial-gradient(circle at center, rgb(16 185 129 / 0.12), transparent 70%);
            animation-duration: 19s;
            animation-delay: -7s;
        }

        @keyframes blog-orb-drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            45% { transform: translate(12px, -14px) scale(1.02); }
            72% { transform: translate(-10px, 10px) scale(0.99); }
        }

        .blog-reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .blog-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .blog-reveal-d1.visible { transition-delay: 0.06s; }
        .blog-reveal-d2.visible { transition-delay: 0.12s; }
        .blog-reveal-d3.visible { transition-delay: 0.18s; }
        .blog-reveal-d4.visible { transition-delay: 0.24s; }

        .blog-chip {
            border-radius: 9999px;
            border: 1px solid rgb(226 232 240 / 0.95);
            background: linear-gradient(180deg, rgb(255 255 255 / 0.95), rgb(248 250 252 / 0.9));
            padding: 0.35rem 0.95rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: rgb(71 85 105);
            box-shadow: 0 1px 0 rgb(255 255 255 / 0.8) inset;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }
        .blog-chip:hover {
            border-color: rgb(79 107 255 / 0.22);
            box-shadow: 0 0 0 1px rgb(79 107 255 / 0.06), 0 4px 20px -8px rgb(15 23 42 / 0.08);
            transform: translateY(-1px);
        }

        .blog-card {
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 1.25rem;
            border: 1px solid rgb(226 232 240 / 0.95);
            background: linear-gradient(165deg, rgb(255 255 255 / 0.98) 0%, rgb(248 250 252 / 0.92) 100%);
            box-shadow:
                0 0 0 1px rgb(255 255 255 / 0.7) inset,
                0 1px 2px rgb(15 23 42 / 0.04),
                0 16px 40px -24px rgb(15 23 42 / 0.1);
            transition: transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.3s ease, border-color 0.25s ease;
        }
        .blog-card:hover {
            transform: translateY(-4px);
            border-color: rgb(79 107 255 / 0.2);
            box-shadow:
                0 0 0 1px rgb(255 255 255 / 0.85) inset,
                0 4px 6px rgb(15 23 42 / 0.05),
                0 24px 48px -20px rgb(79 107 255 / 0.12);
        }
        .blog-card__media {
            position: relative;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            background: rgb(241 245 249);
        }
        .blog-card__media img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: transform 0.55s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .blog-card:hover .blog-card__media img {
            transform: scale(1.04);
        }
        .blog-link-arrow {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: rgb(29 78 216);
            transition: color 0.2s ease, gap 0.2s ease;
        }
        .blog-link-arrow:hover {
            color: rgb(30 64 175);
            gap: 0.5rem;
        }

        .blog-cta {
            position: relative;
            overflow: hidden;
            border-radius: 1.25rem;
            background: linear-gradient(155deg, rgb(15 23 42) 0%, rgb(15 23 42) 40%, rgb(30 41 59) 100%);
            border: 1px solid rgb(51 65 85 / 0.5);
            box-shadow:
                0 0 0 1px rgb(255 255 255 / 0.06) inset,
                0 20px 50px -20px rgb(37 99 235 / 0.25);
        }
        .blog-cta::before {
            content: '';
            position: absolute;
            width: 24rem;
            height: 24rem;
            right: -20%;
            top: -60%;
            border-radius: 50%;
            background: radial-gradient(circle, rgb(37 99 235 / 0.22), transparent 65%);
            pointer-events: none;
        }
        .blog-cta::after {
            content: '';
            position: absolute;
            width: 18rem;
            height: 18rem;
            left: -10%;
            bottom: -50%;
            border-radius: 50%;
            background: radial-gradient(circle, rgb(99 102 241 / 0.12), transparent 68%);
            pointer-events: none;
        }

        @media (prefers-reduced-motion: reduce) {
            .blog-orb { animation: none !important; }
            .blog-reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }
            .blog-card:hover { transform: none; }
            .blog-card:hover .blog-card__media img { transform: none; }
            .blog-chip:hover { transform: none; }
        }
    </style>
</head>
<body class="min-h-screen overflow-x-hidden bg-white font-sans text-slate-900 antialiased">
    <div class="blog-bg" aria-hidden="true">
        <div class="blog-bg__mesh"></div>
        <div class="blog-orb blog-orb--1"></div>
        <div class="blog-orb blog-orb--2"></div>
    </div>

    @include('components.header')

    {{-- Hero --}}
    <section class="relative border-b border-slate-200/80 bg-white/55 pt-24 pb-12 backdrop-blur-[2px] sm:pt-28 sm:pb-14 lg:pt-32">
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="blog-reveal inline-flex items-center gap-2 rounded-full border border-blue-200/80 bg-blue-50/90 px-3 py-1.5 text-[11px] font-semibold text-blue-800 shadow-sm ring-1 ring-white/60 sm:px-4 sm:text-xs">
                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500 shadow-[0_0_10px_rgb(52,211,153,0.6)]"></span>
                Tips &amp; nieuws voor operationele teams
            </div>
            <h1 class="blog-reveal blog-reveal-d1 mt-6 max-w-3xl text-3xl font-extrabold leading-[1.1] tracking-tight text-slate-900 sm:mt-7 sm:text-4xl lg:text-5xl lg:leading-[1.08]">
                Praktische gidsen voor
                <span class="mt-1 block bg-gradient-to-r from-[#4F6BFF] via-[#5f6af8] to-[#7B61FF] bg-clip-text text-transparent sm:mt-2">teams en managers</span>
            </h1>
            <p class="blog-reveal blog-reveal-d2 mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:mt-5 sm:text-lg">
                Artikelen over taakbeheer, werkcontrole en hoe bedrijven in horeca en schoonmaak dagelijks beter werken.
            </p>
            <div class="blog-reveal blog-reveal-d3 mt-6 flex flex-wrap gap-2 sm:mt-8">
                @foreach(['Alle artikelen', 'Horeca', 'Schoonmaak', 'Werkcontrole'] as $tag)
                    <span class="blog-chip cursor-default">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <main class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-20">

        {{-- Uitgelicht --}}
        <article class="blog-reveal blog-reveal-d4 group mb-12 border-b border-slate-100 pb-12 sm:mb-14 sm:pb-14 lg:grid lg:grid-cols-2 lg:items-center lg:gap-12">
            <a href="{{ route('blog.nvwa-spoedsluitingen-plaagdieren-2026') }}" class="blog-card block overflow-hidden !shadow-lg ring-1 ring-slate-200/60 lg:ring-slate-200/80">
                <div class="relative aspect-[4/3] overflow-hidden sm:aspect-[16/10] lg:min-h-[280px]">
                    <img src="{{ asset('images/blog-nvwa-plaagdier-situatie.png') }}"
                         alt="Verwaarloosde ruimte met plaagdierkeutels — illustratie NVWA"
                         class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                         width="1200"
                         height="800"
                         loading="eager"
                         decoding="async">
                    <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/85 via-black/35 to-transparent px-4 pb-3 pt-20">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-white/95 sm:text-xs">Afbeelding van NVWA</p>
                    </div>
                    <div class="pointer-events-none absolute left-3 top-3 sm:left-4 sm:top-4">
                        <span class="rounded-full bg-white/95 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-amber-800 shadow-sm ring-1 ring-amber-200/80 backdrop-blur sm:text-xs">Uitgelicht</span>
                    </div>
                </div>
            </a>
            <div class="mt-8 min-w-0 lg:mt-0">
                <div class="mb-4 flex flex-wrap items-center gap-2 sm:gap-3">
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900 ring-1 ring-amber-200/60">Nieuws</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200/80">Bron: NVWA</span>
                    <span class="text-xs font-medium text-slate-400">4 min lezen</span>
                </div>
                <h2 class="text-2xl font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-3xl">
                    <a href="{{ route('blog.nvwa-spoedsluitingen-plaagdieren-2026') }}">NVWA: begin 2026 recordaantal spoedsluitingen door plaagdieren</a>
                </h2>
                <p class="mt-3 text-slate-600 leading-relaxed">22 locaties tijdelijk gesloten in zeven weken — vooral muizen en ratten. Wat inspecteurs verwachten en hoe je met routines en hygiëne risico’s beperkt.</p>
                <a href="{{ route('blog.nvwa-spoedsluitingen-plaagdieren-2026') }}" class="blog-link-arrow mt-5">
                    Lees artikel
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </article>

        {{-- Raster --}}
        <div class="grid gap-6 sm:gap-8 md:grid-cols-2">

            <article class="blog-reveal group blog-card">
                <a href="{{ route('blog.nvwa-controle-horeca-2026') }}" class="blog-card__media block">
                    <img src="{{ asset('images/blog-nvwa-controle-horeca-2026.jpg') }}?v=2"
                         alt="NVWA-inspectie in de horeca: inspecteur controleert voedselcontainers"
                         loading="lazy"
                         decoding="async"
                         width="1024"
                         height="576">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900 ring-1 ring-amber-200/60">Nieuws</span>
                        <span class="text-xs text-slate-400">25 aug 2026 · 9 min lezen</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{ route('blog.nvwa-controle-horeca-2026') }}">NVWA-controle horeca in 2026: waar wordt op gecontroleerd?</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">Wat controleert de NVWA bij horeca in 2026? HACCP, hygiëne, temperatuur, allergenen en hoe je dagelijkse controles organiseert.</p>
                    <a href="{{ route('blog.nvwa-controle-horeca-2026') }}" class="blog-link-arrow mt-4">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-reveal group blog-card">
                <a href="{{ route('blog.nvwa-update-horeca-inspecties-juni-2026') }}" class="blog-card__media block">
                    <img src="{{ asset('images/blog-nvwa-update-horeca-inspecties-juni-2026.jpg') }}"
                         alt="NVWA Horeca inspectiekaart met beoordelingen van horecazaken"
                         loading="lazy"
                         decoding="async"
                         width="1024"
                         height="537">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900 ring-1 ring-amber-200/60">Nieuws</span>
                        <span class="text-xs text-slate-400">29 jun 2026 · 6 min lezen</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{ route('blog.nvwa-update-horeca-inspecties-juni-2026') }}">NVWA update horeca-inspecties juni 2026: wat betekent dit voor jouw zaak?</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">De NVWA voert vanaf juni 2026 striktere controles uit in de horeca. Wat verandert er en hoe kun je je voorbereiden?</p>
                    <a href="{{ route('blog.nvwa-update-horeca-inspecties-juni-2026') }}" class="blog-link-arrow mt-4">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-reveal group blog-card">
                <a href="{{ route('blog.haccp-richtlijnen-checklist') }}" class="blog-card__media block">
                    <img src="{{ asset('images/blog-haccp-richtlijnen-checklist.jpg') }}"
                         alt="HACCP richtlijnen checklist: hygiëne en schoonmaak in de horecakeuken"
                         loading="lazy"
                         decoding="async"
                         width="1024"
                         height="682">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-900 ring-1 ring-orange-200/70">Horeca</span>
                        <span class="text-xs text-slate-400">29 jun 2026 · 6 min lezen</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{ route('blog.haccp-richtlijnen-checklist') }}">HACCP richtlijnen checklist: praktisch toepassen in jouw horecazaak</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">Met de juiste HACCP-checks borg je voedselveiligheid, voldoe je aan NVWA-eisen en werk je efficiënter in restaurant, lunchroom of hotel.</p>
                    <a href="{{ route('blog.haccp-richtlijnen-checklist') }}" class="blog-link-arrow mt-4">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-reveal group blog-card">
                <a href="{{ route('blog.haccp-lijsten') }}" class="blog-card__media block">
                    <img src="{{ asset('images/blog-haccp-lijsten.jpg') }}"
                         alt="HACCP lijsten voor horeca: digitale checklist op tablet in de keuken"
                         loading="lazy"
                         decoding="async"
                         width="1024"
                         height="682">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-900 ring-1 ring-orange-200/70">Horeca</span>
                        <span class="text-xs text-slate-400">29 jun 2026 · 6 min lezen</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{ route('blog.haccp-lijsten') }}">HACCP lijsten voor horeca: grip op voedselveiligheid en controle</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">HACCP lijsten zijn essentieel voor restaurants en horecabedrijven. Ze helpen je aan de NVWA-eisen te voldoen en houden voedselveiligheid op orde.</p>
                    <a href="{{ route('blog.haccp-lijsten') }}" class="blog-link-arrow mt-4">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-reveal group blog-card">
                <a href="{{ route('blog.logboek-horeca') }}" class="blog-card__media block">
                    <img src="{{ asset('images/blog-logboek-horeca.jpg') }}"
                         alt="Logboek horeca: kok vult HACCP-registratie in aan de keukenwerkbank"
                         loading="lazy"
                         decoding="async"
                         width="1024"
                         height="682">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-900 ring-1 ring-orange-200/70">Horeca</span>
                        <span class="text-xs text-slate-400">29 jun 2026 · 6 min lezen</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{ route('blog.logboek-horeca') }}">Logboek horeca: waarom en hoe je grip houdt op voedselveiligheid</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">Een goed logboek is de ruggengraat van elke horecazaak. Van HACCP tot NVWA-inspecties: zo helpt een digitaal logboek bij dagelijkse controles en hygiëne.</p>
                    <a href="{{ route('blog.logboek-horeca') }}" class="blog-link-arrow mt-4">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-reveal group blog-card">
                <a href="{{ route('blog.haccp-temperatuur-lijsten') }}" class="blog-card__media block">
                    <img src="{{ asset('images/blog-haccp-temperatuur-lijsten.jpg') }}"
                         alt="HACCP temperatuur lijsten: temperatuurcontrole met digitale thermometer"
                         loading="lazy"
                         decoding="async"
                         width="830"
                         height="553">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-900 ring-1 ring-orange-200/70">Horeca</span>
                        <span class="text-xs text-slate-400">29 jun 2026 · 6 min lezen</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{ route('blog.haccp-temperatuur-lijsten') }}">HACCP temperatuur lijsten: essentieel voor elke horecazaak</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">Temperatuurregistratie is een van de belangrijkste onderdelen van HACCP in horeca en foodservice. Met actuele temperatuur lijsten voorkom je risico’s en voldoe je aan de eisen v...</p>
                    <a href="{{ route('blog.haccp-temperatuur-lijsten') }}" class="blog-link-arrow mt-4">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>
<article class="blog-reveal group blog-card">
                <a href="{{ route('blog.waarom-restaurants-steeds-vaker-werken-met-digitale-checklists') }}" class="blog-card__media block">
                    <img src="{{ asset('images/blog-waarom-restaurants-steeds-vaker-werken-met-digitale-checklists.jpg') }}"
                         alt="Restaurantkeuken: waarom horeca steeds vaker digitale checklists gebruikt"
                         loading="lazy"
                         decoding="async"
                         width="1024"
                         height="682">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-900 ring-1 ring-orange-200/70">Horeca</span>
                        <span class="text-xs text-slate-400">Juni 2026 · 7 min</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{ route('blog.waarom-restaurants-steeds-vaker-werken-met-digitale-checklists') }}">Waarom restaurants steeds vaker werken met digitale checklists</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">Van HACCP tot opening en sluiting: waarom horecaondernemers papier achter zich laten.</p>
                    <a href="{{ route('blog.waarom-restaurants-steeds-vaker-werken-met-digitale-checklists') }}" class="blog-link-arrow mt-4">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-reveal group blog-card">
                <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}" class="blog-card__media block">
                    <img src="{{ asset('images/blog-horeca-personeel-controleren-checklist-app.jpg') }}"
                         alt="NVWA-inspecteur in uniform: horecapersoneel controleren met checklists"
                         loading="lazy"
                         decoding="async"
                         width="1024"
                         height="576">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 ring-1 ring-blue-200/60">Horeca</span>
                        <span class="text-xs text-slate-400">8 min lezen</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}">Hoe horeca personeel beter te controleren met een checklist app</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">Van openingscheck tot HACCP-rondes: zo richt je een takenlijst personeel in die écht wordt uitgevoerd.</p>
                    <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}" class="blog-link-arrow mt-4">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-reveal group blog-card">
                <a href="{{ route('blog.waarom-horeca-stopt-met-papieren-checklists') }}" class="blog-card__media block">
                    <img src="{{ asset('images/blog-waarom-horeca-stopt-met-papieren-checklists.jpg') }}"
                         alt="Horeca stopt met papieren checklists: tablet naast papieren lijsten in de keuken"
                         loading="lazy"
                         decoding="async"
                         width="1024"
                         height="576">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-900 ring-1 ring-orange-200/70">Horeca</span>
                        <span class="text-xs text-slate-400">5 min lezen</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{ route('blog.waarom-horeca-stopt-met-papieren-checklists') }}">Waarom horeca bedrijven stoppen met papieren checklists</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">Het velletje aan de muur werkt niet meer. Waarom steeds meer horecazaken overstappen naar een digitale checklist.</p>
                    <a href="{{ route('blog.waarom-horeca-stopt-met-papieren-checklists') }}" class="blog-link-arrow mt-4">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-reveal group blog-card">
                <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}" class="blog-card__media block">
                    <img src="{{ asset('images/blog-beste-checklist-app-voor-schoonmaakbedrijven.jpg') }}"
                         alt="Schoonmakers gebruiken een digitale checklist-app op locatie"
                         loading="lazy"
                         decoding="async"
                         width="1024"
                         height="576">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-900 ring-1 ring-emerald-200/70">Schoonmaak</span>
                        <span class="text-xs text-slate-400">6 min lezen</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}">Beste checklist app voor schoonmaakbedrijven</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">Kwaliteitscontrole per locatie met bewijs per taak en realtime inzicht voor planners en leidinggevenden.</p>
                    <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}" class="blog-link-arrow mt-4">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-reveal group blog-card">
                <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}" class="blog-card__media block">
                    <img src="{{ asset('images/blog-waarom-bedrijven-stoppen-met-excel-checklists.jpg') }}"
                         alt="Waarom bedrijven stoppen met Excel: spreadsheet versus digitale checklist"
                         loading="lazy"
                         decoding="async"
                         width="1024"
                         height="576">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200/90">Algemeen</span>
                        <span class="text-xs text-slate-400">7 min lezen</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}">Waarom bedrijven stoppen met Excel en overstappen op checklist apps</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">Waarom losse spreadsheets zorgen voor fouten en hoe een werkcontrole app processen schaalbaar maakt.</p>
                    <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}" class="blog-link-arrow mt-4">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>

        </div>

        <div class="blog-reveal my-14 border-t border-slate-100 sm:my-16"></div>

        {{-- Onderwerpen --}}
        <div class="blog-reveal mb-14 sm:mb-16">
            <h2 class="text-lg font-extrabold text-slate-900 sm:text-xl">Meer lezen per onderwerp</h2>
            <p class="mt-1 max-w-2xl text-sm text-slate-600">Diepgaande pagina’s over hoe TaskCheck in jouw sector helpt.</p>
            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                <a href="{{ route('seo.horeca-checklist-app') }}"
                   class="group flex flex-col gap-1 rounded-2xl border border-slate-200/95 bg-white/90 p-5 shadow-sm ring-1 ring-white/60 transition hover:-translate-y-0.5 hover:border-blue-300/80 hover:bg-blue-50/40 hover:shadow-md">
                    <span class="text-xs font-semibold uppercase tracking-wide text-blue-600">Horeca</span>
                    <h3 class="font-bold text-slate-900 transition group-hover:text-blue-800">Horeca checklist app</h3>
                    <p class="text-sm leading-relaxed text-slate-600">Dagelijkse controle voor restaurants, keukens en teams.</p>
                </a>
                <a href="{{ route('seo.horeca-app-personeel') }}"
                   class="group flex flex-col gap-1 rounded-2xl border border-slate-200/95 bg-white/90 p-5 shadow-sm ring-1 ring-white/60 transition hover:-translate-y-0.5 hover:border-blue-300/80 hover:bg-blue-50/40 hover:shadow-md">
                    <span class="text-xs font-semibold uppercase tracking-wide text-blue-600">Horeca</span>
                    <h3 class="font-bold text-slate-900 transition group-hover:text-blue-800">Horeca app personeel</h3>
                    <p class="text-sm leading-relaxed text-slate-600">Taken per shift aansturen met realtime werkcontrole.</p>
                </a>
                <a href="{{ route('seo.checklist-app-schoonmaak') }}"
                   class="group flex flex-col gap-1 rounded-2xl border border-slate-200/95 bg-white/90 p-5 shadow-sm ring-1 ring-white/60 transition hover:-translate-y-0.5 hover:border-emerald-300/80 hover:bg-emerald-50/35 hover:shadow-md">
                    <span class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Schoonmaak</span>
                    <h3 class="font-bold text-slate-900 transition group-hover:text-emerald-800">Checklist app schoonmaak</h3>
                    <p class="text-sm leading-relaxed text-slate-600">Rondes, bewijs en rapportage per gebouw of opdrachtgever.</p>
                </a>
            </div>
        </div>

        {{-- CTA --}}
        <div class="blog-reveal blog-cta relative z-0 flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-9 lg:p-10">
            <div class="relative z-[1]">
                <h2 class="text-xl font-bold text-white sm:text-2xl">Wil je dit direct toepassen in jouw team?</h2>
                <p class="mt-2 max-w-lg text-sm leading-relaxed text-slate-300">Start met TaskCheck en zet je eerste digitale checklist live in minuten. Inclusief bewijs, voortgang en realtime inzicht.</p>
            </div>
            <div class="relative z-[1] flex flex-shrink-0 flex-col gap-3 sm:flex-row">
                <a href="{{ route('pricing') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-center text-sm font-semibold text-white shadow-lg shadow-blue-900/30 transition hover:bg-blue-500 sm:min-h-10">
                    Bekijk prijzen
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-xl border border-white/25 bg-white/10 px-5 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-white/15 sm:min-h-10">
                    Plan een demo
                </a>
            </div>
        </div>

    </main>

    @include('components.footer')

    <script>
        (function () {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                document.querySelectorAll('.blog-reveal').forEach(function (el) { el.classList.add('visible'); });
                return;
            }
            if (!('IntersectionObserver' in window)) {
                document.querySelectorAll('.blog-reveal').forEach(function (el) { el.classList.add('visible'); });
                return;
            }
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (!e.isIntersecting) return;
                    e.target.classList.add('visible');
                    io.unobserve(e.target);
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -32px 0px' });
            document.querySelectorAll('.blog-reveal').forEach(function (el) { io.observe(el); });
        })();
    </script>
</body>
</html>
