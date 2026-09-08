<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Blog TaskCheck – Checklist app tips voor horeca, schoonmaak en teams';
        $seoDescription = 'Lees praktische artikelen over takenlijst personeel, werkcontrole app workflows en checklist app voor bedrijven in horeca en schoonmaak.';
        $seoUrl = route('blog');
        $seoImage = asset('images/taskcheck-horeca-blog-hero.webp');
        $headerDark = false;
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
    <style>
        .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .55s ease, transform .55s ease; }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
        .delay-1.visible { transition-delay: .1s; }
        .delay-2.visible { transition-delay: .2s; }
        .delay-3.visible { transition-delay: .3s; }
        .cta-btn { background: linear-gradient(135deg, #2563eb, #4f46e5); }
        .cta-btn:hover { background: linear-gradient(135deg, #1d4ed8, #4338ca); }
        .welcome-page-glow {
            position: fixed; z-index: 0; top: -300px; right: -300px; width: 820px; height: 820px;
            border-radius: 9999px; pointer-events: none;
            background: radial-gradient(circle, rgba(59,130,246,.075) 0%, rgba(99,102,241,.035) 36%, rgba(255,255,255,0) 70%);
        }
        .blog-kicker {
            display: inline-flex; align-items: center; gap: 10px;
            color: #2563eb; font: 600 11px/1.4 ui-monospace, SFMono-Regular, Menlo, monospace;
            letter-spacing: .14em; text-transform: uppercase;
        }
        .blog-kicker::before { content: ""; width: 22px; height: 1px; background: #93b4ff; }
        .blog-tag {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 4px 9px; border: 1px solid #e6e8ec; border-radius: 999px;
            background: #f7f8fa; color: #62666d;
            font: 500 9px/1.4 ui-monospace, SFMono-Regular, Menlo, monospace;
            letter-spacing: .04em; text-transform: uppercase;
        }
        .blog-card {
            display: flex; flex-direction: column; overflow: hidden;
            border: 1px solid #e6e8ec; border-radius: 18px; background: #fff;
            box-shadow: 0 4px 12px rgba(10,12,18,.04);
            transition: transform .3s ease, box-shadow .3s ease, border-color .25s ease;
        }
        .blog-card:hover {
            transform: translateY(-3px);
            border-color: #d7e2f7;
            box-shadow: 0 12px 32px -12px rgba(23,43,99,.15);
        }
        .blog-card__media {
            position: relative; aspect-ratio: 16 / 9; overflow: hidden; background: #f7f8fa;
        }
        .blog-card__media img {
            height: 100%; width: 100%; object-fit: cover;
            transition: transform .55s ease;
        }
        .blog-card:hover .blog-card__media img { transform: scale(1.04); }
        .blog-link {
            display: inline-flex; align-items: center; gap: .35rem;
            font-size: .875rem; font-weight: 700; color: #2563eb;
            transition: gap .2s ease, color .2s ease;
        }
        .blog-link:hover { gap: .5rem; color: #1d4ed8; }
        .blog-topic {
            display: flex; flex-direction: column; gap: 4px; padding: 21px;
            border: 1px solid #e6e8ec; border-radius: 16px; background: #fff;
            transition: transform .3s ease, box-shadow .3s ease, border-color .25s ease;
        }
        .blog-topic:hover {
            transform: translateY(-3px);
            border-color: #d7e2f7;
            box-shadow: 0 12px 32px -12px rgba(23,43,99,.15);
        }
        @media (prefers-reduced-motion: reduce) {
            .fade-up { opacity: 1; transform: none; transition: none; }
            .blog-card:hover, .blog-topic:hover { transform: none; }
            .blog-card:hover .blog-card__media img { transform: none; }
        }
    </style>
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased overflow-x-hidden">
    <div class="welcome-page-glow" aria-hidden="true"></div>

    @include('components.header')

    <main>
        <section class="relative overflow-hidden bg-white pt-28 pb-12 sm:pt-32 sm:pb-16 lg:pt-36 lg:pb-20">
            <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="blog-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                            <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#blog-dots)"/>
                </svg>
                <div class="absolute -right-[200px] -top-[280px] h-[min(520px,120vw)] w-[min(520px,120vw)] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)] md:h-[720px] md:w-[720px]"></div>
                <div class="absolute -left-[120px] bottom-[-80px] h-[280px] w-[280px] rounded-full bg-[radial-gradient(circle,rgba(16,185,129,.07)_0%,transparent_65%)] md:h-[400px] md:w-[400px]"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="blog-kicker fade-up">Tips &amp; nieuws voor operationele teams</p>
                <h1 class="fade-up delay-1 mt-5 max-w-3xl text-4xl font-extrabold leading-[1.04] tracking-[-.045em] text-slate-900 sm:text-5xl lg:text-6xl">
                    Praktische gidsen voor
                    <span class="mt-1 block"><x-text-reveal text="teams en managers" trigger="load" /></span>
                </h1>
                <p class="fade-up delay-2 mt-5 max-w-2xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Artikelen over taakbeheer, werkcontrole en hoe bedrijven in horeca en schoonmaak dagelijks beter werken.
                </p>
                <div class="fade-up delay-3 mt-7 flex flex-wrap gap-2">
                    @foreach (['Alle artikelen', 'Horeca', 'Schoonmaak', 'Werkcontrole'] as $tag)
                        <span class="blog-tag">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="relative mx-auto max-w-7xl px-4 pb-16 sm:px-6 sm:pb-20 lg:px-8 lg:pb-24">
            <article class="fade-up group mb-14 grid items-center gap-8 border-b border-slate-100 pb-14 lg:mb-16 lg:grid-cols-2 lg:gap-14 lg:pb-16">
                <a href="{{ route('blog.nvwa-spoedsluitingen-plaagdieren-2026') }}" class="blog-card block overflow-hidden">
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
                            <span class="rounded-full border border-white/80 bg-white/95 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-slate-800 shadow-sm sm:text-xs">Uitgelicht</span>
                        </div>
                    </div>
                </a>
                <div class="min-w-0">
                    <div class="mb-4 flex flex-wrap items-center gap-2">
                        <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>Nieuws</span>
                        <span class="blog-tag">Bron: NVWA</span>
                        <span class="text-xs font-medium text-slate-400">4 min lezen</span>
                    </div>
                    <h2 class="text-2xl font-extrabold leading-snug tracking-tight text-slate-900 transition group-hover:text-blue-700 sm:text-3xl">
                        <a href="{{ route('blog.nvwa-spoedsluitingen-plaagdieren-2026') }}">NVWA: begin 2026 recordaantal spoedsluitingen door plaagdieren</a>
                    </h2>
                    <p class="mt-3 text-slate-500 leading-relaxed">22 locaties tijdelijk gesloten in zeven weken — vooral muizen en ratten. Wat inspecteurs verwachten en hoe je met routines en hygiëne risico’s beperkt.</p>
                    <a href="{{ route('blog.nvwa-spoedsluitingen-plaagdieren-2026') }}" class="blog-link mt-5">
                        Lees artikel
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
            </article>

            <div class="grid gap-6 sm:gap-8 md:grid-cols-2">
                <article class="fade-up group blog-card">
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
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>Nieuws</span>
                            <span class="text-xs text-slate-400">25 aug 2026 · 9 min lezen</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{ route('blog.nvwa-controle-horeca-2026') }}">NVWA-controle horeca in 2026: waar wordt op gecontroleerd?</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">Wat controleert de NVWA bij horeca in 2026? HACCP, hygiëne, temperatuur, allergenen en hoe je dagelijkse controles organiseert.</p>
                        <a href="{{ route('blog.nvwa-controle-horeca-2026') }}" class="blog-link mt-4">
                            Lees artikel
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </article>

                <article class="fade-up group blog-card">
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
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>Nieuws</span>
                            <span class="text-xs text-slate-400">29 jun 2026 · 6 min lezen</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{ route('blog.nvwa-update-horeca-inspecties-juni-2026') }}">NVWA update horeca-inspecties juni 2026: wat betekent dit voor jouw zaak?</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">De NVWA voert vanaf juni 2026 striktere controles uit in de horeca. Wat verandert er en hoe kun je je voorbereiden?</p>
                        <a href="{{ route('blog.nvwa-update-horeca-inspecties-juni-2026') }}" class="blog-link mt-4">
                            Lees artikel
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </article>

                <article class="fade-up group blog-card">
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
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>Horeca</span>
                            <span class="text-xs text-slate-400">29 jun 2026 · 6 min lezen</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{ route('blog.haccp-richtlijnen-checklist') }}">HACCP richtlijnen checklist: praktisch toepassen in jouw horecazaak</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">Met de juiste HACCP-checks borg je voedselveiligheid, voldoe je aan NVWA-eisen en werk je efficiënter in restaurant, lunchroom of hotel.</p>
                        <a href="{{ route('blog.haccp-richtlijnen-checklist') }}" class="blog-link mt-4">
                            Lees artikel
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </article>

                <article class="fade-up group blog-card">
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
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>Horeca</span>
                            <span class="text-xs text-slate-400">29 jun 2026 · 6 min lezen</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{ route('blog.haccp-lijsten') }}">HACCP lijsten voor horeca: grip op voedselveiligheid en controle</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">HACCP lijsten zijn essentieel voor restaurants en horecabedrijven. Ze helpen je aan de NVWA-eisen te voldoen en houden voedselveiligheid op orde.</p>
                        <a href="{{ route('blog.haccp-lijsten') }}" class="blog-link mt-4">
                            Lees artikel
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </article>

                <article class="fade-up group blog-card">
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
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>Horeca</span>
                            <span class="text-xs text-slate-400">29 jun 2026 · 6 min lezen</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{ route('blog.logboek-horeca') }}">Logboek horeca: waarom en hoe je grip houdt op voedselveiligheid</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">Een goed logboek is de ruggengraat van elke horecazaak. Van HACCP tot NVWA-inspecties: zo helpt een digitaal logboek bij dagelijkse controles en hygiëne.</p>
                        <a href="{{ route('blog.logboek-horeca') }}" class="blog-link mt-4">
                            Lees artikel
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </article>

                <article class="fade-up group blog-card">
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
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>Horeca</span>
                            <span class="text-xs text-slate-400">29 jun 2026 · 6 min lezen</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{ route('blog.haccp-temperatuur-lijsten') }}">HACCP temperatuur lijsten: essentieel voor elke horecazaak</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">Temperatuurregistratie is een van de belangrijkste onderdelen van HACCP in horeca en foodservice. Met actuele temperatuur lijsten voorkom je risico’s en voldoe je aan de eisen v...</p>
                        <a href="{{ route('blog.haccp-temperatuur-lijsten') }}" class="blog-link mt-4">
                            Lees artikel
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </article>

                <article class="fade-up group blog-card">
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
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>Horeca</span>
                            <span class="text-xs text-slate-400">Juni 2026 · 7 min</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{ route('blog.waarom-restaurants-steeds-vaker-werken-met-digitale-checklists') }}">Waarom restaurants steeds vaker werken met digitale checklists</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">Van HACCP tot opening en sluiting: waarom horecaondernemers papier achter zich laten.</p>
                        <a href="{{ route('blog.waarom-restaurants-steeds-vaker-werken-met-digitale-checklists') }}" class="blog-link mt-4">
                            Lees artikel
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </article>

                <article class="fade-up group blog-card">
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
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Horeca</span>
                            <span class="text-xs text-slate-400">8 min lezen</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}">Hoe horeca personeel beter te controleren met een checklist app</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">Van openingscheck tot HACCP-rondes: zo richt je een takenlijst personeel in die écht wordt uitgevoerd.</p>
                        <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}" class="blog-link mt-4">
                            Lees artikel
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </article>

                <article class="fade-up group blog-card">
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
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>Horeca</span>
                            <span class="text-xs text-slate-400">5 min lezen</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{ route('blog.waarom-horeca-stopt-met-papieren-checklists') }}">Waarom horeca bedrijven stoppen met papieren checklists</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">Het velletje aan de muur werkt niet meer. Waarom steeds meer horecazaken overstappen naar een digitale checklist.</p>
                        <a href="{{ route('blog.waarom-horeca-stopt-met-papieren-checklists') }}" class="blog-link mt-4">
                            Lees artikel
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </article>

                <article class="fade-up group blog-card">
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
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Schoonmaak</span>
                            <span class="text-xs text-slate-400">6 min lezen</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}">Beste checklist app voor schoonmaakbedrijven</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">Kwaliteitscontrole per locatie met bewijs per taak en realtime inzicht voor planners en leidinggevenden.</p>
                        <a href="{{ route('blog.beste-checklist-app-voor-schoonmaakbedrijven') }}" class="blog-link mt-4">
                            Lees artikel
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </article>

                <article class="fade-up group blog-card">
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
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Algemeen</span>
                            <span class="text-xs text-slate-400">7 min lezen</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}">Waarom bedrijven stoppen met Excel en overstappen op checklist apps</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">Waarom losse spreadsheets zorgen voor fouten en hoe een werkcontrole app processen schaalbaar maakt.</p>
                        <a href="{{ route('blog.waarom-bedrijven-stoppen-met-excel-checklists') }}" class="blog-link mt-4">
                            Lees artikel
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </article>
            </div>

            <div class="fade-up mt-16 sm:mt-20">
                <p class="blog-kicker">Dieper in jouw sector</p>
                <h2 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Meer lezen per onderwerp</h2>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-500 sm:text-base">Diepgaande pagina’s over hoe TaskCheck in jouw sector helpt.</p>
                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <a href="{{ route('seo.horeca-checklist-app') }}" class="blog-topic group">
                        <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Horeca</span>
                        <h3 class="mt-2 font-extrabold text-slate-900 transition group-hover:text-blue-700">Horeca checklist app</h3>
                        <p class="text-sm leading-relaxed text-slate-500">Dagelijkse controle voor restaurants, keukens en teams.</p>
                    </a>
                    <a href="{{ route('seo.horeca-app-personeel') }}" class="blog-topic group">
                        <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Horeca</span>
                        <h3 class="mt-2 font-extrabold text-slate-900 transition group-hover:text-blue-700">Horeca app personeel</h3>
                        <p class="text-sm leading-relaxed text-slate-500">Taken per shift aansturen met realtime werkcontrole.</p>
                    </a>
                    <a href="{{ route('seo.checklist-app-schoonmaak') }}" class="blog-topic group">
                        <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Schoonmaak</span>
                        <h3 class="mt-2 font-extrabold text-slate-900 transition group-hover:text-blue-700">Checklist app schoonmaak</h3>
                        <p class="text-sm leading-relaxed text-slate-500">Rondes, bewijs en rapportage per gebouw of opdrachtgever.</p>
                    </a>
                </div>
            </div>
        </section>

        <section class="relative overflow-hidden py-20 sm:py-28 lg:py-32" style="background:#030712">
            <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                <div class="absolute -top-32 -left-32 h-[600px] w-[600px] rounded-full opacity-25" style="background:radial-gradient(circle,#2563eb,transparent 70%)"></div>
                <div class="absolute -bottom-40 -right-24 h-[500px] w-[500px] rounded-full opacity-20" style="background:radial-gradient(circle,#6366f1,transparent 70%)"></div>
                <div class="absolute inset-0 opacity-[.04]" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:28px 28px"></div>
            </div>

            <div class="relative mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8 fade-up">
                <div class="mb-8 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold text-blue-300"
                     style="background:rgba(37,99,235,.18);border:1px solid rgba(96,165,250,.2)">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Direct toepassen in jouw team
                </div>
                <h2 class="text-3xl font-extrabold leading-[1.06] tracking-tight text-white sm:text-4xl lg:text-5xl">
                    Wil je dit direct toepassen<br class="hidden sm:block">
                    <span style="background:linear-gradient(135deg,#60a5fa 0%,#a78bfa 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">in jouw team?</span>
                </h2>
                <p class="mx-auto mt-5 max-w-lg text-base leading-relaxed text-slate-400 sm:mt-6 sm:text-lg">
                    Start met TaskCheck en zet je eerste digitale checklist live in minuten. Inclusief bewijs, voortgang en realtime inzicht.
                </p>
                <div class="mt-8 flex flex-col items-stretch justify-center gap-3 sm:mt-10 sm:flex-row sm:flex-wrap sm:items-center sm:gap-4">
                    <a href="{{ route('pricing') }}"
                       class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2.5 rounded-2xl px-8 py-4 text-base font-extrabold text-white transition-all hover:scale-[1.02] touch-manipulation sm:w-auto sm:min-h-0"
                       style="background:linear-gradient(135deg,#2563eb,#6366f1);box-shadow:0 0 0 1px rgba(255,255,255,.08),0 16px 40px rgba(37,99,235,.4)">
                        Bekijk prijzen
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl px-8 py-4 text-base font-bold text-white transition-all hover:bg-white/10 touch-manipulation sm:w-auto sm:min-h-0"
                       style="border:1.5px solid rgba(255,255,255,.18)">
                        Plan een demo
                    </a>
                </div>
            </div>
        </section>
    </main>

    @include('components.footer')

    <script>
        (function () {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
                document.querySelectorAll('.fade-up').forEach(function (el) { el.classList.add('visible'); });
                return;
            }
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (!e.isIntersecting) return;
                    e.target.classList.add('visible');
                    io.unobserve(e.target);
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -32px 0px' });
            document.querySelectorAll('.fade-up').forEach(function (el) { io.observe(el); });
        })();
    </script>
</body>
</html>
