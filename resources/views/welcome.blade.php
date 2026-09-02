<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle       = 'Checklist app voor bedrijven, horeca en schoonmaak | TaskCheck';
        $seoDescription = 'TaskCheck is de checklist app voor bedrijven: takenlijst personeel beheren, werkcontrole uitvoeren en bewijs verzamelen met foto en video. Start 14 dagen gratis.';
        $seoUrl         = route('welcome');
        $seoImage       = asset('images/taskcheck-platform-overview.webp');
        $headerDark     = false;
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">
    <meta property="og:type"        content="website">
    <meta property="og:locale"      content="nl_NL">
    <meta property="og:site_name"   content="TaskCheck">
    <meta property="og:title"       content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url"         content="{{ $seoUrl }}">
    <meta property="og:image"       content="{{ $seoImage }}">
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image"       content="{{ $seoImage }}">
    <meta name="twitter:image:alt"   content="TaskCheck checklist app">
    <script type="application/ld+json">{"@@context":"https://schema.org","@@type":"SoftwareApplication","name":"TaskCheck","applicationCategory":"BusinessApplication","operatingSystem":"Web","url":"{{ $seoUrl }}","description":"{{ $seoDescription }}","offers":{"@@type":"Offer","price":"29","priceCurrency":"EUR"}}</script>
    <script type="application/ld+json">{"@@context":"https://schema.org","@@type":"Organization","name":"TaskCheck","url":"{{ $seoUrl }}","logo":"{{ asset('logos/taskcheck-favicon.png') }}","sameAs":[]}</script>
    <script type="application/ld+json">{"@@context":"https://schema.org","@@type":"FAQPage","mainEntity":[{"@@type":"Question","name":"Voor welke bedrijven is TaskCheck geschikt?","acceptedAnswer":{"@@type":"Answer","text":"TaskCheck is geschikt voor horeca, schoonmaakbedrijven en andere operationele teams die met checklists, takenlijsten en werkcontrole werken."}},{"@@type":"Question","name":"Kan ik bewijs per taak vastleggen?","acceptedAnswer":{"@@type":"Answer","text":"Ja, per taak kun je bewijs verzamelen met foto, video, tekst of handtekening."}},{"@@type":"Question","name":"Hoe start ik met TaskCheck?","acceptedAnswer":{"@@type":"Answer","text":"Je start met een proefperiode van 14 dagen. Geen creditcard nodig."}},{"@@type":"Question","name":"Werkt TaskCheck ook voor meerdere locaties?","acceptedAnswer":{"@@type":"Answer","text":"Ja, TaskCheck ondersteunt meerdere locaties met een centraal dashboard."}},{"@@type":"Question","name":"Kan ik TaskCheck gebruiken op mobiel?","acceptedAnswer":{"@@type":"Answer","text":"Ja, TaskCheck werkt volledig op mobiel, tablet en desktop."}}]}</script>
    <style>
        .fade-up { opacity:0; transform:translateY(18px); transition:opacity .55s ease,transform .55s ease; }
        .fade-up.visible { opacity:1; transform:translateY(0); }
        .delay-1.visible { transition-delay:.1s }
        .delay-2.visible { transition-delay:.2s }
        .delay-3.visible { transition-delay:.3s }
        .stagger .s-item { opacity:0; transform:translateY(14px); transition:opacity .5s ease,transform .5s ease; }
        .stagger.visible .s-item:nth-child(1){opacity:1;transform:translateY(0);transition-delay:.05s}
        .stagger.visible .s-item:nth-child(2){opacity:1;transform:translateY(0);transition-delay:.15s}
        .stagger.visible .s-item:nth-child(3){opacity:1;transform:translateY(0);transition-delay:.25s}
        .stagger.visible .s-item:nth-child(4){opacity:1;transform:translateY(0);transition-delay:.35s}
        .img-zoom img { transition:transform .6s ease; }
        .img-zoom:hover img { transform:scale(1.04); }
        .faq-body { display:none; }
        .faq-body.open { display:block; }
        .faq-icon { transition:transform .2s; }
        .faq-icon.open { transform:rotate(45deg); }
        .bar-track { background:#e2e8f0; }
        .cta-btn { background:linear-gradient(135deg,#2563eb,#4f46e5); }
        .cta-btn:hover { background:linear-gradient(135deg,#1d4ed8,#4338ca); }
        /* Hero rechts — pure CSS/SVG animatie */
        .hero-v-blob {
            position:absolute;border-radius:50%;filter:blur(48px);opacity:.55;
            animation:hero-v-float 16s ease-in-out infinite;
        }
        .hero-v-blob--2 { animation-delay:-8s; animation-duration:20s; opacity:.38; filter:blur(56px); }
        @keyframes hero-v-float {
            0%,100% { transform:translate(0,0) scale(1); }
            50% { transform:translate(-14px,18px) scale(1.06); }
        }
        .hero-v-orbit {
            position:absolute;left:50%;top:50%;width:min(100%,380px);aspect-ratio:1;
            transform:translate(-50%,-50%) rotate(0deg);
            border:1px dashed rgba(99,102,241,.22);border-radius:50%;
            animation:hero-v-spin 56s linear infinite;
        }
        .hero-v-orbit::after {
            content:'';position:absolute;width:9px;height:9px;left:50%;top:-4px;margin-left:-4px;
            border-radius:50%;
            background:linear-gradient(135deg,#2563eb,#6366f1);
            box-shadow:0 0 18px rgba(37,99,235,.45);
        }
        @keyframes hero-v-spin { to { transform:translate(-50%,-50%) rotate(360deg); } }
        .hero-v-card {
            position:relative;width:100%;max-width:380px;
                 animation:hero-v-bob 7s ease-in-out infinite;
        }
        @keyframes hero-v-bob {
            0%,100% { transform:translateY(0); }
            50% { transform:translateY(-6px); }
        }
        .hero-v-row { opacity:0; transform:translateX(14px); animation:hero-v-row-in .65s cubic-bezier(.2,.8,.2,1) forwards; }
        .hero-v-row:nth-child(1){ animation-delay:.15s; }
        .hero-v-row:nth-child(2){ animation-delay:.38s; }
        .hero-v-row:nth-child(3){ animation-delay:.6s; }
        @keyframes hero-v-row-in { to { opacity:1; transform:translateX(0); } }
        .hero-v-pop { transform:scale(.35); opacity:0; animation:hero-v-pop .5s cubic-bezier(.34,1.4,.64,1) forwards; }
        .hero-v-row:nth-child(1) .hero-v-pop{ animation-delay:.28s; }
        .hero-v-row:nth-child(2) .hero-v-pop{ animation-delay:.5s; }
        .hero-v-row:nth-child(3) .hero-v-pop{ animation-delay:.72s; }
        @keyframes hero-v-pop { to { transform:scale(1); opacity:1; } }
        .hero-v-fill {
            width:0; height:100%; border-radius:9999px;
            background:linear-gradient(90deg,#2563eb,#6366f1);
            animation:hero-v-bar 1.35s cubic-bezier(.2,.8,.2,1) forwards;
        }
        .hero-v-row:nth-child(1) .hero-v-fill{ animation-delay:.4s; --v-bar:100%; }
        .hero-v-row:nth-child(2) .hero-v-fill{ animation-delay:.62s; --v-bar:68%; }
        .hero-v-row:nth-child(3) .hero-v-fill{ animation-delay:.84s; --v-bar:0%; }
        @keyframes hero-v-bar { to { width:var(--v-bar,100%); } }
        .hero-v-shimmer {
            position:relative; overflow:hidden; background:linear-gradient(90deg,#f1f5f9 0%,#e2e8f0 50%,#f1f5f9 100%);
            background-size:200% 100%;
            animation:hero-v-shimmer 2.2s ease-in-out infinite;
        }
        @keyframes hero-v-shimmer { 0% { background-position:100% 0; } 100% { background-position:-100% 0; } }
        .hero-color-reveal {
            position:relative;
            display:inline-block;
            padding-bottom:.14em;
            margin-bottom:-.14em;
            color:#0f172a;
            animation:hero-base-hide 0s 1.45s forwards;
        }
        .hero-color-reveal::after {
            content:attr(data-text);
            position:absolute;
            inset:0;
            color:transparent;
            background:linear-gradient(90deg,#2563eb 0%,#315bea 52%,#4f46e5 100%);
            -webkit-background-clip:text;
            background-clip:text;
            clip-path:inset(0 100% 0 0);
            will-change:clip-path;
            animation:hero-color-reveal 1.15s .3s cubic-bezier(.22,.75,.2,1) forwards;
        }
        @keyframes hero-color-reveal { to { clip-path:inset(0 0 0 0); } }
        @keyframes hero-base-hide { to { color:transparent; } }
        .hero-drawn-line { stroke-dasharray:310; stroke-dashoffset:310; animation:hero-draw-line .9s 1.15s cubic-bezier(.25,.8,.25,1) forwards; }
        @keyframes hero-draw-line { to { stroke-dashoffset:0; } }
        .task-stream { --task-step:76px;--task-start:39px;position:relative;height:440px;perspective:1000px;overflow:hidden;mask-image:linear-gradient(to bottom,transparent 0,#000 5%,#000 95%,transparent 100%); }
        .task-stream::before { content:"";position:absolute;inset:7% 12%;background:repeating-linear-gradient(90deg,rgba(99,102,241,.12) 0 2px,transparent 2px 9px);mask-image:linear-gradient(to bottom,transparent,#000 12%,#000 88%,transparent); }
        .task-stream-track { position:absolute;left:4%;right:4%;top:0;display:flex;flex-direction:column;gap:18px;will-change:transform;transition:transform .82s cubic-bezier(.45,0,.25,1); }
        .task-stream-track.is-resetting { transition:none; }
        .task-stream-track.is-resetting .task-stream-item { transition:none; }
        .task-stream-item { display:flex;height:58px;flex:none;align-items:center;gap:11px;padding:8px 15px;border:1px solid rgba(226,232,240,.9);border-radius:14px;background:rgba(255,255,255,.94);box-shadow:0 9px 25px -22px rgba(15,23,42,.3);opacity:0;transform:scaleX(.96);transition:opacity .5s ease,transform .5s ease,border-color .5s ease,box-shadow .5s ease;backdrop-filter:blur(14px); }
        .task-stream-item.is-edge { opacity:.46;transform:scaleX(.97); }
        .task-stream-item.is-adjacent { opacity:.7;transform:scaleX(.985); }
        .task-stream-item.is-active { opacity:1;transform:scaleX(1.045);border-color:rgba(96,165,250,.72);box-shadow:0 24px 52px -28px rgba(37,99,235,.42); }
        .task-stream-check { display:grid;height:34px;width:34px;flex:none;place-items:center;border:1.5px solid #cbd5e1;border-radius:10px;color:transparent;background:white;transform:scale(.92);transition:all .35s ease; }
        .task-stream-item.is-done .task-stream-check { color:#fff;background:#10b981;border-color:transparent;transform:scale(.94); }
        .task-stream-item.is-active.is-checked .task-stream-check { color:#fff;background:linear-gradient(135deg,#2563eb,#4f46e5);border-color:transparent;transform:scale(1); }
        @media (max-width:639px) {
            .task-stream{--task-step:66px;--task-start:14px;height:340px}
            .task-stream::before{inset:7% 5%}
            .task-stream-track{left:0;right:0;gap:18px}
            .task-stream-item{height:48px;gap:9px;padding:6px 11px;border-radius:13px}
            .task-stream-check{height:31px;width:31px;border-radius:9px}
            .task-stream-item.is-active{transform:scaleX(1.01)}
        }
        @media (prefers-reduced-motion:reduce) {
            .hero-v-blob,.hero-v-blob--2,.hero-v-orbit,.hero-v-card,.hero-v-row,.hero-v-pop,.hero-v-fill,.hero-v-shimmer{ animation:none !important; }
            .hero-v-row,.hero-v-pop{ opacity:1; transform:none; }
            .hero-v-fill{ width:var(--v-bar,100%) !important; }
            .hero-v-card{ transform:none; }
            .hero-v-orbit::after{ display:none; }
            .hero-color-reveal{animation:none;color:transparent}
            .hero-color-reveal::after{animation:none;clip-path:inset(0)}
            .hero-drawn-line{animation:none;stroke-dashoffset:0}
            .task-stream{height:auto;padding-top:3rem;mask-image:none}
            .task-stream::before{display:none}
            .task-stream-track{position:relative;inset:auto;transform:none!important;transition:none}
            .task-stream-item{display:none;opacity:1;transform:none;transition:none}
            .task-stream-item.is-edge,.task-stream-item.is-adjacent,.task-stream-item.is-active{display:flex}
        }
        /* Mobiel: vloeiende horizontale scroll voor brede tabellen */
        .welcome-table-scroll {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }
        .welcome-table-scroll::-webkit-scrollbar {
            height: 6px;
        }
        .welcome-table-scroll::-webkit-scrollbar-thumb {
            border-radius: 9999px;
            background: rgb(203 213 225);
        }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased overflow-x-hidden">

@include('components.header')

{{-- ══════════════════════════════════════
     HERO — 2 kolommen: tekst links, screenshot rechts
══════════════════════════════════════ --}}
<section class="relative min-h-[760px] overflow-hidden bg-white pb-16 pt-28 sm:pt-32 lg:flex lg:min-h-[860px] lg:items-center lg:py-28">
    {{-- Achtergrond --}}
    <div class="absolute inset-0 pointer-events-none">
        <svg class="absolute inset-0 w-full h-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs><pattern id="dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse"><circle cx="1" cy="1" r="1.2" fill="#334155"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(16,185,129,.07)_0%,transparent_65%)]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_.95fr] lg:gap-20">

            {{-- LINKS: tekst --}}
            <div class="min-w-0">
                <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 sm:mb-7 sm:px-4 sm:text-xs">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-left leading-snug">Werkcontrole voor operationele teams</span>
                </div>

                <h1 class="text-4xl font-extrabold leading-[1.02] tracking-[-.045em] text-slate-900 sm:text-6xl xl:text-[4.3rem]">
                    Nooit meer discussie over
                    <span class="relative mt-1 inline-block whitespace-nowrap">
                        <span class="hero-color-reveal" data-text="uitgevoerd werk">uitgevoerd werk</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path class="hero-drawn-line" d="M1 6 C75 1, 225 1, 299 6" stroke="url(#ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs><linearGradient id="ul" x1="0" y1="0" x2="300" y2="0"><stop offset="0%" stop-color="#2563eb"/><stop offset="100%" stop-color="#6366f1"/></linearGradient></defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-5 max-w-lg text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Leg taken vast, verzamel bewijs en houd realtime controle over je team. Voor horeca, schoonmaak en andere operationele bedrijven.
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:flex-wrap">
                    @auth
                        <a href="{{ auth()->user()->homeDashboardUrl() }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Naar dashboard
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Start gratis trial
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endauth
                    <a href="{{ route('contact') }}" class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 sm:w-auto sm:min-h-0 touch-manipulation">
                        Plan een demo
                    </a>
                </div>

                <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 sm:mt-6 sm:gap-x-6">
                    @foreach(['14 dagen gratis','Geen creditcard','Binnen 10 min live','AVG-proof'] as $b)
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $b }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- RECHTS: taken schuiven door en worden op het actieve moment afgevinkt --}}
            <div class="relative min-h-[360px] sm:min-h-[420px] lg:min-h-[520px]" aria-label="Live voorbeeld van taken die worden uitgevoerd">
                <div class="pointer-events-none absolute inset-x-[8%] inset-y-[3%] rounded-[3rem] bg-gradient-to-b from-blue-50/80 via-indigo-50/45 to-transparent blur-2xl"></div>
                <div class="absolute right-4 top-3 z-10 flex items-center gap-2 text-xs font-semibold text-slate-500 sm:right-8">
                    <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span><span class="relative h-2 w-2 rounded-full bg-emerald-500"></span></span>
                    Live · Rotterdam Centrum
                </div>

                @php
                    $heroTasks = [
                        ['Opening keuken', 'Werkbanken gereinigd', 'Zojuist'],
                        ['HACCP-controle', 'Koelcel gemeten: 4,2 °C', 'Bewijs toegevoegd'],
                        ['Sluitingsronde', 'Afvalbakken geleegd', 'Rotterdam Centrum'],
                        ['Leveringscontrole', 'THT en verpakking gecontroleerd', 'Goedgekeurd'],
                        ['Temperatuurregistratie', 'Vriezer gemeten: -18,6 °C', 'Binnen norm'],
                        ['Schoonmaakcontrole', 'Foto van afzuigkap toegevoegd', 'Bewijs opgeslagen'],
                        ['Frituurcontrole', 'Oliekwaliteit gecontroleerd', 'Binnen norm'],
                    ];
                @endphp
                <div class="task-stream mx-auto max-w-[520px]" data-task-stream>
                    <div class="task-stream-track" data-task-stream-track>
                        @foreach (range(1, 3) as $copy)
                            @foreach ($heroTasks as [$title, $description, $meta])
                                <div class="task-stream-item" data-task-stream-item>
                                    <span class="task-stream-check" aria-hidden="true">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-bold text-slate-900 sm:text-base">{{ $title }}</span>
                                        <span class="mt-0.5 block truncate text-xs text-slate-500 sm:text-sm">{{ $description }}</span>
                                    </span>
                                    <span class="hidden shrink-0 text-[11px] font-semibold text-slate-400 sm:block">{{ $meta }}</span>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     TRUST BAR
══════════════════════════════════════ --}}
<section class="border-y border-slate-100 bg-slate-50 py-7">
    <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-5">Vertrouwd door operationele teams in Nederland</p>
        <div class="flex flex-wrap justify-center gap-2.5">
            @foreach(['Horeca','Restaurants','Schoonmaak','Facilitair','Logistiek','Retail','Technisch beheer'] as $s)
            <span class="px-4 py-1.5 rounded-full bg-white border border-slate-200 text-slate-600 text-sm font-medium shadow-sm">{{ $s }}</span>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     PROBLEEM
══════════════════════════════════════ --}}
<section class="py-14 sm:py-20 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="fade-up">
                <p class="text-sm font-bold text-red-500 uppercase tracking-wider mb-3">Herkenbaar?</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">Werk dat niet aantoonbaar is, bestaat niet</h2>
                <p class="mt-4 text-slate-500 text-lg leading-relaxed">Zonder controle en bewijs loopt kwaliteit weg — en je weet het pas als er een klacht is.</p>
                <ul class="mt-9 space-y-5">
                    @foreach([
                        ['Medewerkers vergeten taken','Zonder checklists worden stappen overgeslagen. Niemand weet achteraf wat er gedaan is.','red'],
                        ['Klanten klagen zonder bewijs','Je weet dat het werk gedaan is, maar kunt het niet aantonen. Dat kost vertrouwen.','orange'],
                        ['Geen overzicht bij meerdere locaties','Elke locatie doet het anders. Jij hebt geen centraal beeld.','amber'],
                        ['Je weet niet wat er écht speelt','Problemen bereiken je pas als ze al groot zijn.','violet'],
                    ] as [$t,$d,$c])
                    <li class="flex items-start gap-4">
                        <span class="mt-1.5 w-2 h-2 rounded-full shrink-0"
                              style="background:{{ $c==='red'?'#ef4444':($c==='orange'?'#f97316':($c==='amber'?'#f59e0b':'#a855f7')) }}"></span>
                        <div>
                            <p class="font-semibold text-slate-900 text-sm">{{ $t }}</p>
                            <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">{{ $d }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="fade-up delay-2">
                <img src="{{ asset('images/herkenbaar-operatie-chaos.png') }}"
                     alt="Manager met tablet in drukke bedrijfsomgeving — herkenbare stress door chaos en gebrek aan overzicht op de werkvloer"
                     loading="lazy" decoding="async" width="1200" height="800"
                     class="w-full rounded-2xl border border-slate-200/80 object-cover shadow-xl aspect-[4/3] sm:aspect-[3/2]">
            </div>
        </div>
    </div>
</section>
<section class="py-14 sm:py-20 lg:py-24 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mb-10 sm:mb-14 fade-up">
            <p class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3">Branches</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Ontworpen voor operationele teams</h2>
            <p class="mt-4 text-slate-500 text-lg">Voor elk operationeel team een passende aanpak.</p>
        </div>
        <div class="stagger grid md:grid-cols-3 gap-7">
            @foreach([
                ['Horeca','branch-horeca.png','Keuken met tablet — TaskCheck voor horeca','Opening & sluiting checklists','HACCP controles vastleggen','Minder fouten tijdens drukte','Bewijs bij inspecties','seo.horeca-checklist-app','Meer over horeca','#2563eb'],
                ['Schoonmaak','branch-schoonmaak.png','Schoonmaakteam met tablet — TaskCheck op locatie','Werkbonnen per locatie','Foto bewijs van uitgevoerd werk','Minder klachten van opdrachtgevers','Hogere klanttevredenheid','seo.schoonmaak-checklist-app','Meer over schoonmaak','#0891b2'],
                ['Overige teams','branch-overige.png','Magazijn met tablet — werkcontrole TaskCheck','Logistiek, facility en retail','Werkprocessen onder controle','Volledige werkregistratie','Eén platform, meerdere locaties','seo.werkcontrole-app','Meer over werkcontrole','#6366f1'],
            ] as [$name,$img,$alt,$b1,$b2,$b3,$b4,$route,$link,$col])
            <div class="s-item bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-shadow img-zoom">
                <div class="aspect-[16/9] overflow-hidden">
                    <img src="{{ asset('images/'.$img) }}" alt="{{ $alt }}"
                         loading="lazy" decoding="async"
                         class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="font-extrabold text-slate-900 text-lg mb-3">{{ $name }}</h3>
                    <ul class="space-y-1.5 mb-5">
                        @foreach([$b1,$b2,$b3,$b4] as $bullet)
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="h-3.5 w-3.5 shrink-0" style="color:{{ $col }}" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            {{ $bullet }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route($route) }}" class="inline-flex items-center gap-1.5 text-sm font-bold transition-colors" style="color:{{ $col }}">
                        {{ $link }}
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
{{-- ══════════════════════════════════════
     POPULAIRE OPLOSSINGEN
══════════════════════════════════════ --}}
<section class="py-14 sm:py-20 lg:py-24 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mb-10 sm:mb-14 fade-up">
            <p class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3">Populaire oplossingen</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Kies de oplossing voor jouw team</h2>
            <p class="mt-4 text-slate-500 text-lg">Direct naar onze meest bezochte pagina&rsquo;s voor horeca en schoonmaak.</p>
        </div>
        <div class="stagger grid sm:grid-cols-2 lg:grid-cols-6 gap-5">
            @foreach([
                ['Horeca App', 'Personeel, checklists en werkcontrole voor restaurants en cafés.', 'seo.horeca-app', '#2563eb'],
                ['Restaurant Checklist App', 'Opening, sluiting en HACCP digitaal afvinken.', 'seo.restaurant-checklist-app', '#4f46e5'],
                ['HACCP Formulieren', 'Stop met papier — registreer controles digitaal.', 'seo.haccp-formulieren', '#059669'],
                ['Temperatuurregistratie App', 'Koeling, vriezer en producten met foto bewijs.', 'seo.temperatuurregistratie-app', '#0891b2'],
                ['App Schoonmaakbedrijf', 'Werkcontrole en rapportages voor schoonmaakteams.', 'seo.app-schoonmaakbedrijf', '#0d9488'],
            ] as $index => [$title, $desc, $route, $col])
            <div class="s-item flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:border-blue-200 hover:shadow-md sm:col-span-1 lg:col-span-2 {{ $index === 3 ? 'lg:col-start-2' : '' }}">
                <h3 class="font-extrabold text-slate-900 text-base leading-snug">{{ $title }}</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed flex-1">{{ $desc }}</p>
                <a href="{{ route($route) }}"
                   class="cta-btn mt-5 inline-flex min-h-[2.75rem] w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-200/60 transition-all touch-manipulation">
                    Bekijk oplossing
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
{{-- ══════════════════════════════════════
     VERGELIJKING
══════════════════════════════════════ --}}
<section class="py-14 sm:py-20 lg:py-24 bg-slate-50 border-y border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-16 fade-up">
            <p class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3">Vergelijk</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Waarom teams kiezen voor TaskCheck</h2>
            <p class="mt-4 text-slate-500 text-lg">Excel, WhatsApp en papier zijn niet gebouwd voor werkcontrole. TaskCheck wel.</p>
        </div>

        {{-- Stat row --}}
        <div class="stagger grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10 sm:mb-16">
            @foreach([
                ['-90%','Vergeten taken','Checklists zorgen dat geen stap wordt gemist.','#2563eb'],
                ['-87%','Klachten bewijs','Elk stuk werk is altijd aantoonbaar.','#059669'],
                ['-75%','Controletijd','Managers zien realtime wat er speelt.','#7c3aed'],
                ['3×','Sneller klaar audit','Alle bewijzen staan direct klaar.','#d97706'],
            ] as [$num,$title,$desc,$col])
            <div class="s-item bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm">
                <div class="text-4xl font-black mb-1.5 leading-none" style="color:{{ $col }}">{{ $num }}</div>
                <p class="font-bold text-slate-900 text-sm mb-1">{{ $title }}</p>
                <p class="text-slate-500 text-xs leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>

        <div class="grid lg:grid-cols-2 gap-10 lg:gap-12 items-start">

            {{-- Comparison table --}}
            <div class="fade-up min-w-0 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="welcome-table-scroll overflow-x-auto">
                    <table class="w-full min-w-[32rem] text-sm" style="border-collapse:collapse">
                    <thead>
                        <tr style="border-bottom:2px solid #e2e8f0">
                            <th class="text-left py-4 pl-5 pr-3 text-slate-500 font-medium text-xs uppercase tracking-wider">Functie</th>
                            <th class="py-4 px-4 text-center text-xs font-extrabold uppercase tracking-wider text-blue-700" style="background:#eff6ff">TaskCheck</th>
                            <th class="py-4 px-3 text-center text-xs text-slate-400 font-medium">Excel</th>
                            <th class="py-4 px-3 text-center text-xs text-slate-400 font-medium">WhatsApp</th>
                            <th class="py-4 px-3 pr-5 text-center text-xs text-slate-400 font-medium">Papier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $rows = [
                            ['Realtime inzicht',         true,  false,  false,  false],
                            ['Foto & video bewijs',      true,  false,  'half', false],
                            ['Meerdere locaties',        true,  'half', false,  false],
                            ['Automatische rapportages', true,  'half', false,  false],
                            ['AI checklistgenerator',   true,  false,  false,  false],
                            ['Mobiele webapp',           true,  'half', true,   false],
                            ['Rollen & rechten',         true,  false,  false,  false],
                            ['Klaar voor audits',        true,  'half', false,  'half'],
                        ];
                        $check = '<svg class="h-5 w-5 mx-auto text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
                        $half  = '<svg class="h-4 w-4 mx-auto text-amber-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>';
                        $cross = '<svg class="h-4 w-4 mx-auto text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
                        @endphp
                        @foreach($rows as $i => [$label,$tc,$xl,$wa,$pa])
                        @php $isLast = $i === count($rows)-1; @endphp
                        <tr class="hover:bg-slate-50 transition-colors" style="{{ !$isLast ? 'border-bottom:1px solid #f1f5f9' : '' }}">
                            <td class="py-3.5 pl-5 pr-3 text-slate-700 text-sm font-medium">{{ $label }}</td>
                            @foreach([$tc,$xl,$wa,$pa] as $j => $val)
                            <td class="py-3.5 px-3{{ $j===3?' pr-5':'' }} text-center" style="{{ $j===0 ? 'background:#f0f9ff' : '' }}">
                                {!! $val===true ? $check : ($val==='half' ? $half : $cross) !!}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-400 bg-slate-50">— = beperkt of handmatig beschikbaar</div>
            </div>

            {{-- Before/After bars --}}
            <div class="fade-up delay-2 min-w-0 bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-sm space-y-6 sm:space-y-7">
                <div>
                    <p class="font-bold text-slate-900 mb-1">Effect na implementatie</p>
                    <p class="text-xs text-slate-400">Illustratief — veelgehoorde resultaten bij operationele teams.</p>
                </div>
                @php
                $metrics = [
                    ['Vergeten taken',         80, 8,  '#ef4444'],
                    ['Klachten zonder bewijs', 65, 7,  '#f97316'],
                    ['Tijd kwijt aan controle',70, 18, '#f59e0b'],
                    ['Fouten bij inspecties',  55, 6,  '#a855f7'],
                ];
                @endphp
                @foreach($metrics as [$label,$before,$after,$col])
                <div class="metric-bar min-w-0">
                    <div class="flex justify-between items-baseline mb-2.5 gap-2 min-w-0">
                        <span class="text-sm font-semibold text-slate-800 break-words min-w-0">{{ $label }}</span>
                        <span class="text-sm font-extrabold text-emerald-600">-{{ $before-$after }}%</span>
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                            <span class="text-[10px] sm:text-xs text-slate-400 w-11 sm:w-16 shrink-0 text-right leading-tight">Zonder</span>
                            <div class="flex-1 h-3 bar-track rounded-full overflow-hidden">
                                <div class="bar-before h-full rounded-full transition-all duration-[1100ms] ease-out" style="background:{{ $col }};width:0%;opacity:.75" data-w="{{ $before }}%"></div>
                            </div>
                            <span class="text-xs tabular-nums text-slate-500 w-8">{{ $before }}%</span>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                            <span class="text-[10px] sm:text-xs font-bold text-emerald-600 w-11 sm:w-16 shrink-0 text-right leading-tight">TaskCheck</span>
                            <div class="flex-1 h-3 bar-track rounded-full overflow-hidden">
                                <div class="bar-after h-full rounded-full transition-all duration-[1100ms] ease-out" style="background:#10b981;width:0%" data-w="{{ $after }}%"></div>
                            </div>
                            <span class="text-xs tabular-nums font-bold text-emerald-600 w-8">{{ $after }}%</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     OPLOSSING
══════════════════════════════════════ --}}
<section class="py-14 sm:py-20 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="fade-up order-2 lg:order-1">
                <img src="{{ asset('images/oplossing-taskcheck-platform.png') }}"
                     alt="TaskCheck oplossing: platform op laptop en mobiel in een professionele keukenomgeving, met voordelen zoals checklists per locatie, bewijs met foto en video, live dashboard en audit-klaar rapportage"
                     loading="lazy" decoding="async" width="1600" height="900"
                     class="w-full rounded-2xl border border-slate-200/90 bg-slate-900 shadow-xl shadow-slate-900/10">
            </div>
            <div class="fade-up delay-1 order-1 lg:order-2">
                <p class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3">De oplossing</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">Met TaskCheck heb je alles onder controle</h2>
                <p class="mt-4 text-slate-500 text-lg leading-relaxed">Eén platform voor manager en medewerker — van taakaanmaak tot bewijs en rapportage.</p>
                <ul class="mt-9 space-y-5">
                    @foreach([
                        ['Taken per locatie en team','Stel checklists in per locatie of ploeg. Iedereen ziet precies wat er van hem verwacht wordt.'],
                        ['Foto en video bewijs','Medewerkers voegen direct bewijs toe bij elke taak. Altijd aantoonbaar.'],
                        ['Live dashboard','Realtime inzicht in wat gedaan is en wat achterloopt.'],
                        ['Klaar voor audits','Exporteer overzichten en toon bewijs. Geen stress bij inspecties.'],
                    ] as [$t,$d])
                    <li class="flex items-start gap-3.5">
                        <span class="mt-1 w-5 h-5 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                            <svg class="h-3 w-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-slate-900 text-sm">{{ $t }}</p>
                            <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">{{ $d }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="mt-9">
                    @guest
                    <a href="{{ route('register') }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white shadow-md shadow-blue-200 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                        Probeer 14 dagen gratis
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     ZO WERKT TASKCHECK — premium SaaS
══════════════════════════════════════ --}}
<section class="py-16 sm:py-20 border-t border-slate-100/90 bg-gradient-to-b from-slate-50 via-white to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-16 fade-up">
            <p class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3">Zo werkt TaskCheck</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">
                In 3 stappen
                <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">live</span>
                met je team
            </h2>
            <p class="mt-4 text-slate-500 text-lg">Maak checklists, laat je team taken uitvoeren en houd realtime controle over kwaliteit en bewijs.</p>
        </div>

        {{-- items-start: geen gedwongen gelijke kaarthoogte (voorkomt holle onderkanten) --}}
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-center gap-10 lg:gap-2">
            {{-- Card 1 — blauw --}}
            <article class="group w-full max-w-md mx-auto lg:mx-0 lg:max-w-none lg:flex-1 lg:basis-0 lg:min-w-0 rounded-3xl border border-slate-200/90 bg-white p-5 sm:p-6 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_12px_40px_-18px_rgba(15,23,42,0.12)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_2px_4px_rgba(15,23,42,0.06),0_20px_50px_-20px_rgba(15,23,42,0.18)] relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-blue-500/0 via-blue-500 to-blue-500/0 opacity-80" aria-hidden="true"></div>
                <div class="mb-4">
                    <div class="flex flex-wrap items-center gap-2.5 gap-y-1">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-[11px] font-bold text-blue-700 ring-1 ring-blue-100">01</span>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 tracking-tight">Maak checklists</h3>
                    </div>
                    <p class="mt-2 text-sm text-slate-600 leading-relaxed">Bouw checklists in minuten of importeer bestaande Excel, PDF of Word bestanden met AI.</p>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/80 p-3.5 sm:p-4">
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Checklist · Keuken</span>
                        <span class="inline-flex items-center rounded-full bg-blue-600/10 px-2.5 py-0.5 text-[10px] font-semibold text-blue-700 ring-1 ring-blue-600/15">AI-import</span>
                    </div>
                    <ul class="space-y-1.5">
                        <li class="flex items-center gap-2.5 rounded-xl bg-white px-2.5 py-2 border border-slate-200/70 shadow-sm">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                            <span class="text-sm font-medium text-slate-800">Koeling controleren</span>
                        </li>
                        <li class="flex items-center gap-2.5 rounded-xl bg-white px-2.5 py-2 border border-slate-200/70 shadow-sm">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                            <span class="text-sm font-medium text-slate-800">HACCP logboek</span>
                        </li>
                        <li class="flex items-center gap-2.5 rounded-xl bg-white px-2.5 py-2 border border-slate-200/70 shadow-sm">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                            <span class="text-sm font-medium text-slate-800">Werkblad schoon</span>
                        </li>
                    </ul>
                    <div class="mt-2.5 flex items-center gap-2 rounded-xl border border-dashed border-slate-300/80 bg-white/60 px-3 py-2 text-sm text-slate-500">
                        <span class="text-lg leading-none text-slate-400">+</span>
                        <span>Nieuwe taak toevoegen</span>
                    </div>
                </div>
            </article>

            <div class="hidden lg:flex flex-col justify-center items-center shrink-0 w-8 pt-44 text-slate-300" aria-hidden="true">
                <div class="h-px w-full max-w-[2rem] bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                <svg class="w-5 h-5 my-1 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                <div class="h-px w-full max-w-[2rem] bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
            </div>

            {{-- Card 2 — paars --}}
            <article class="group w-full max-w-md mx-auto lg:mx-0 lg:max-w-none lg:flex-1 lg:basis-0 lg:min-w-0 rounded-3xl border border-slate-200/90 bg-white p-5 sm:p-6 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_12px_40px_-18px_rgba(15,23,42,0.12)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_2px_4px_rgba(15,23,42,0.06),0_20px_50px_-20px_rgba(15,23,42,0.18)] relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-violet-500/0 via-violet-500 to-violet-500/0 opacity-80" aria-hidden="true"></div>
                <div class="mb-4">
                    <div class="flex flex-wrap items-center gap-2.5 gap-y-1">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-violet-50 text-[11px] font-bold text-violet-700 ring-1 ring-violet-100">02</span>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 tracking-tight">Team voert uit</h3>
                    </div>
                    <p class="mt-2 text-sm text-slate-600 leading-relaxed">Medewerkers voeren taken uit via mobiel of tablet en voegen direct foto- of videobewijs toe.</p>
                </div>

                <div class="w-full">
                    <div class="mx-auto w-full max-w-[280px] lg:max-w-none rounded-[1.65rem] border border-slate-200 bg-slate-100/90 p-1.5 shadow-inner">
                        <div class="rounded-[1.28rem] overflow-hidden bg-white border border-slate-200/90 shadow-sm">
                            <div class="flex items-center justify-between px-4 pt-3 pb-2 border-b border-slate-100">
                                <span class="flex gap-1" aria-hidden="true"><span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span><span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span><span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span></span>
                                <span class="text-[10px] font-medium text-slate-400">9:41</span>
                                <span class="w-6"></span>
                            </div>
                            <div class="px-4 py-3 space-y-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Taak</p>
                                        <p class="text-sm font-semibold text-slate-900">#2841 · Koeling controleren</p>
                                    </div>
                                    <span class="shrink-0 inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 ring-1 ring-emerald-600/15">Afgerond</span>
                                </div>
                                <div class="rounded-xl border border-dashed border-violet-200 bg-violet-50/50 p-3">
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <span class="text-[11px] font-medium text-slate-600">Foto toevoegen</span>
                                        <span class="inline-flex rounded-md bg-white/80 px-1.5 py-0.5 text-[9px] font-semibold text-violet-700 ring-1 ring-violet-200">Foto bewijs</span>
                                    </div>
                                    <div class="flex h-24 items-center justify-center rounded-lg bg-white border border-slate-200/80">
                                        <svg class="h-8 w-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                    </div>
                                </div>
                                <button type="button" class="w-full rounded-lg bg-violet-600 py-2 text-xs font-semibold text-white shadow-sm">Taak indienen</button>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <div class="hidden lg:flex flex-col justify-center items-center shrink-0 w-8 pt-44 text-slate-300" aria-hidden="true">
                <div class="h-px w-full max-w-[2rem] bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                <svg class="w-5 h-5 my-1 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                <div class="h-px w-full max-w-[2rem] bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
            </div>

            {{-- Card 3 — groen --}}
            <article class="group w-full max-w-md mx-auto lg:mx-0 lg:max-w-none lg:flex-1 lg:basis-0 lg:min-w-0 rounded-3xl border border-slate-200/90 bg-white p-5 sm:p-6 shadow-[0_1px_2px_rgba(15,23,42,0.04),0_12px_40px_-18px_rgba(15,23,42,0.12)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_2px_4px_rgba(15,23,42,0.06),0_20px_50px_-20px_rgba(15,23,42,0.18)] relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-emerald-500/0 via-emerald-500 to-emerald-500/0 opacity-80" aria-hidden="true"></div>
                <div class="mb-4">
                    <div class="flex flex-wrap items-center gap-2.5 gap-y-1">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-[11px] font-bold text-emerald-700 ring-1 ring-emerald-100">03</span>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 tracking-tight">Jij houdt controle</h3>
                    </div>
                    <p class="mt-2 text-sm text-slate-600 leading-relaxed">Bekijk realtime voortgang, stuur bij en exporteer rapportages per locatie of team.</p>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/80 p-3.5 sm:p-4">
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Dashboard · Overzicht</span>
                        <span class="text-[10px] font-medium text-slate-400">Vandaag</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <div class="rounded-xl bg-white border border-slate-200/70 px-2.5 py-2 shadow-sm">
                            <p class="text-[10px] font-medium text-slate-500">Voltooid</p>
                            <p class="text-base font-semibold text-slate-900">94%</p>
                        </div>
                        <div class="rounded-xl bg-white border border-slate-200/70 px-2.5 py-2 shadow-sm">
                            <p class="text-[10px] font-medium text-slate-500">Open</p>
                            <p class="text-base font-semibold text-slate-900">12</p>
                        </div>
                        <div class="rounded-xl bg-white border border-slate-200/70 px-2.5 py-2 shadow-sm">
                            <p class="text-[10px] font-medium text-slate-500">Teams</p>
                            <p class="text-base font-semibold text-slate-900">4</p>
                        </div>
                    </div>
                    <div class="rounded-xl bg-white border border-slate-200/70 p-3 mb-3 shadow-sm">
                        <p class="text-[10px] font-semibold text-slate-500 mb-2">Voortgang per locatie</p>
                        <div class="flex items-end gap-1.5 h-16 px-1">
                            <div class="flex-1 rounded-t bg-emerald-200/80 h-[45%] min-h-[12px]"></div>
                            <div class="flex-1 rounded-t bg-emerald-300 h-[72%] min-h-[12px]"></div>
                            <div class="flex-1 rounded-t bg-emerald-400/90 h-[88%] min-h-[12px]"></div>
                            <div class="flex-1 rounded-t bg-emerald-500 h-[62%] min-h-[12px]"></div>
                            <div class="flex-1 rounded-t bg-emerald-200 h-[38%] min-h-[12px]"></div>
                            <div class="flex-1 rounded-t bg-emerald-300/90 h-[95%] min-h-[12px]"></div>
                            <div class="flex-1 rounded-t bg-emerald-400 h-[55%] min-h-[12px]"></div>
                        </div>
                    </div>
                    <div class="space-y-2 mb-3">
                        <div>
                            <div class="flex justify-between text-[10px] text-slate-500 mb-1"><span>HACCP · wk 19</span><span>78%</span></div>
                            <div class="h-1.5 rounded-full bg-slate-200 overflow-hidden"><div class="h-full w-[78%] rounded-full bg-emerald-500"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-[10px] text-slate-500 mb-1"><span>Schoonmaak · barcode</span><span>92%</span></div>
                            <div class="h-1.5 rounded-full bg-slate-200 overflow-hidden"><div class="h-full w-[92%] rounded-full bg-blue-500"></div></div>
                        </div>
                    </div>
                    <div class="rounded-xl bg-white border border-slate-200/70 divide-y divide-slate-100 shadow-sm overflow-hidden">
                        <p class="text-[10px] font-semibold text-slate-500 px-3 py-2 bg-slate-50/80">Activiteit</p>
                        <div class="px-3 py-2 flex items-center gap-2">
                            <span class="h-7 w-7 rounded-full bg-emerald-100 text-[10px] font-bold text-emerald-800 flex items-center justify-center shrink-0">MV</span>
                            <div class="min-w-0 flex-1 text-[11px] leading-snug">
                                <p class="font-medium text-slate-900 truncate">Taak afgerond · Koeling</p>
                                <p class="text-slate-500">Locatie Centrum · zojuist</p>
                            </div>
                        </div>
                        <div class="px-3 py-2 flex items-center gap-2">
                            <span class="h-7 w-7 rounded-full bg-violet-100 text-[10px] font-bold text-violet-800 flex items-center justify-center shrink-0">JK</span>
                            <div class="min-w-0 flex-1 text-[11px] leading-snug">
                                <p class="font-medium text-slate-900 truncate">Bewijs toegevoegd</p>
                                <p class="text-slate-500">Team ochtend · 4 min</p>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     FEATURES
══════════════════════════════════════ --}}
<section class="py-14 sm:py-20 lg:py-24 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-start">
            <div class="fade-up lg:sticky lg:top-28">
                <p class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3">Functies</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">Alles wat je nodig hebt om werk onder controle te houden</h2>
                <p class="mt-4 text-slate-500 text-lg leading-relaxed">Van bewijs per taak tot AI-checklists en rapportages — gebouwd voor teams die resultaat willen aantonen.</p>
                <div class="mt-8">
                    @guest
                    <a href="{{ route('register') }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white shadow-md shadow-blue-200 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                        Start gratis trial
                    </a>
                    @endguest
                </div>
            </div>
            <div class="fade-up delay-1 bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm divide-y divide-slate-100">
                @foreach([
                    ['Bewijs per taak','Foto, video, tekst of handtekening — per taak gestructureerd opgeslagen.'],
                    ['Realtime inzicht','Live dashboard — zie direct wat gedaan is en wat wacht.'],
                    ['Minder fouten','Vaste checklists zorgen dat stappen niet worden overgeslagen.'],
                    ['Meerdere locaties','Per locatie aparte checklists, één centraal dashboard.'],
                    ['AI checklistgenerator','Upload een document en laat AI automatisch een checklist voorstellen.'],
                    ['Rapportages','Exporteer weekoverzichten voor klanten, managers of auditors.'],
                    ['Mobiele webapp','Werkt op telefoon, tablet en desktop — ook installeerbaar.'],
                    ['Rollen en rechten','Admin, manager en medewerker elk met de juiste toegang.'],
                ] as [$title,$desc])
                <div class="flex items-start gap-4 px-4 py-4 sm:px-6 hover:bg-slate-50 transition-colors">
                    <svg class="h-5 w-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-semibold text-slate-900 text-sm">{{ $title }}</p>
                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     SEO TEKST
══════════════════════════════════════ --}}
<section class="py-14 sm:py-20 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-10 lg:gap-16 items-start fade-up">

            {{-- Tekst --}}
            <div class="lg:col-span-2">
                <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">Over TaskCheck</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight mb-5">
                    Checklist app voor bedrijven die werkcontrole serieus nemen
                </h2>
                <div class="text-slate-500 leading-relaxed space-y-4 text-[15px]">
                    <p>Veel bedrijven worstelen dagelijks met dezelfde vraag: hoe weet je zeker dat het werk goed is gedaan? TaskCheck geeft operationele teams een eenvoudig antwoord. Met duidelijke checklists, verplicht bewijs per taak en een realtime dashboard hoef je niet meer op goed geloof te vertrouwen.</p>
                    <p>Voor de horeca biedt TaskCheck een complete <a href="{{ route('seo.horeca-checklist-app') }}" class="text-blue-600 font-semibold underline-offset-2 hover:underline">horeca checklist app</a> waarmee opening, HACCP-controles en sluitrondes gestandaardiseerd worden. Schoonmaakbedrijven profiteren van een <a href="{{ route('seo.schoonmaak-checklist-app') }}" class="text-blue-600 font-semibold underline-offset-2 hover:underline">checklist app voor schoonmaak</a> met bewijs per locatie en rapportages richting opdrachtgevers.</p>
                    <p>Ook buiten horeca en schoonmaak is de <a href="{{ route('seo.werkcontrole-app') }}" class="text-blue-600 font-semibold underline-offset-2 hover:underline">werkcontrole app</a> van TaskCheck breed inzetbaar. Met de <a href="{{ route('seo.takenlijst-personeel') }}" class="text-blue-600 font-semibold underline-offset-2 hover:underline">takenlijst voor personeel</a> weet iedereen precies wat er verwacht wordt.</p>
                </div>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('blog') }}"
                       class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-700 border border-slate-200 bg-white rounded-lg px-4 py-2 hover:bg-slate-50 transition-colors">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.966 8.966 0 00-6 2.292m0-14.25v14.25"/></svg>
                        Lees het blog
                    </a>
                    <a href="{{ route('pricing') }}"
                       class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-700 border border-slate-200 bg-white rounded-lg px-4 py-2 hover:bg-slate-50 transition-colors">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        Bekijk abonnementen
                    </a>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="lg:pt-8">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Specifiek voor jouw branche</p>
                <div class="space-y-2">
                    @foreach([
                        ['Horeca checklist app',    route('seo.horeca-checklist-app'),    '#2563eb'],
                        ['Checklist schoonmaak',    route('seo.schoonmaak-checklist-app'), '#7c3aed'],
                        ['Werkcontrole app',        route('seo.werkcontrole-app'),         '#0891b2'],
                        ['Takenlijst personeel',    route('seo.takenlijst-personeel'),     '#059669'],
                    ] as [$label,$url,$col])
                    <a href="{{ $url }}"
                       class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl bg-white border border-slate-100 hover:border-slate-200 hover:shadow-sm transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full shrink-0" style="background:{{ $col }}"></span>
                            <span class="text-sm font-semibold text-slate-800">{{ $label }}</span>
                        </div>
                        <svg class="h-3.5 w-3.5 text-slate-300 group-hover:text-slate-500 transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     FAQ
══════════════════════════════════════ --}}
<section class="py-14 sm:py-20 lg:py-24 bg-slate-50 border-t border-slate-100">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12 fade-up">
            <p class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3">FAQ</p>
            <h2 class="text-3xl font-extrabold text-slate-900">Veelgestelde vragen</h2>
        </div>
        <div class="fade-up bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm divide-y divide-slate-100">
            @foreach([
                ['Voor welke bedrijven is TaskCheck geschikt?','TaskCheck is geschikt voor horeca, schoonmaakbedrijven en andere operationele teams die met checklists, takenlijsten en werkcontrole werken.'],
                ['Kan ik bewijs per taak vastleggen?','Ja, per taak kun je bewijs verzamelen met foto, video, tekst of handtekening. Zo maak je uitvoering aantoonbaar — ook bij klachten of inspecties.'],
                ['Hoe start ik met TaskCheck?','Je start met een proefperiode van 14 dagen. Geen creditcard nodig. Binnen 10 minuten ben je live.'],
                ['Werkt TaskCheck ook voor meerdere locaties?','Ja. Per locatie stel je eigen checklists in. Vanuit één dashboard zie je de voortgang van alle locaties.'],
                ['Kan ik TaskCheck gebruiken op mobiel?','Ja, TaskCheck werkt volledig op mobiel, tablet en desktop. Er is ook een installeerbare webapp voor iOS en Android.'],
            ] as [$q,$a])
            <div class="faq-item cursor-pointer transition-colors hover:bg-slate-50" data-faq-item>
                <button type="button" class="faq-trigger flex min-h-[3rem] w-full touch-manipulation items-center justify-between gap-4 px-4 py-4 text-left transition-colors hover:bg-slate-50 sm:min-h-0 sm:px-6 sm:py-5" aria-expanded="false">
                    <span class="break-words text-sm font-semibold text-slate-900 pr-2">{{ $q }}</span>
                    <svg class="faq-icon h-5 w-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                </button>
                <div class="faq-body px-4 pb-4 text-sm leading-relaxed text-slate-600 sm:px-6 sm:pb-5">{{ $a }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     FINAL CTA — enige donkere sectie
══════════════════════════════════════ --}}
<section class="relative overflow-hidden py-20 sm:py-28 lg:py-32" style="background:#030712">
    {{-- Glow blobs --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-32 -left-32 w-[600px] h-[600px] rounded-full opacity-25" style="background:radial-gradient(circle,#2563eb,transparent 70%)"></div>
        <div class="absolute -bottom-40 -right-24 w-[500px] h-[500px] rounded-full opacity-20" style="background:radial-gradient(circle,#6366f1,transparent 70%)"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[300px] opacity-10" style="background:radial-gradient(ellipse,#3b82f6,transparent 65%)"></div>
        {{-- subtle dot grid --}}
        <div class="absolute inset-0 opacity-[.04]" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:28px 28px"></div>
    </div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-up">

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold text-blue-300 mb-8"
             style="background:rgba(37,99,235,.18);border:1px solid rgba(96,165,250,.2)">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            14 dagen gratis proberen
        </div>

        <h2 class="text-3xl font-extrabold leading-[1.06] tracking-tight text-white sm:text-4xl sm:leading-[1.04] lg:text-5xl xl:text-6xl">
            Voorkom fouten.<br>
            <span style="background:linear-gradient(135deg,#60a5fa 0%,#a78bfa 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Begin vandaag.</span>
        </h2>

        <p class="mx-auto mt-5 max-w-lg text-base leading-relaxed text-slate-400 sm:mt-6 sm:text-lg">
            Geen lange implementatie. Geen creditcard. Binnen 10 minuten live met je eerste checklist.
        </p>

        <div class="mt-8 flex flex-col items-stretch justify-center gap-3 sm:mt-10 sm:flex-row sm:flex-wrap sm:items-center sm:gap-4">
            @auth
                <a href="{{ auth()->user()->homeDashboardUrl() }}"
                   class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2.5 rounded-2xl px-8 py-4 text-base font-extrabold text-slate-900 transition-all touch-manipulation sm:w-auto sm:min-h-0"
                   style="background:#fff;box-shadow:0 0 0 1px rgba(255,255,255,.12),0 16px 40px rgba(37,99,235,.3)">
                    Naar dashboard
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2.5 rounded-2xl px-8 py-4 text-base font-extrabold text-white transition-all hover:scale-[1.02] touch-manipulation sm:w-auto sm:min-h-0"
                   style="background:linear-gradient(135deg,#2563eb,#6366f1);box-shadow:0 0 0 1px rgba(255,255,255,.08),0 16px 40px rgba(37,99,235,.4)">
                    Start gratis trial
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            @endauth
            <a href="{{ route('contact') }}"
               class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl px-8 py-4 text-base font-bold text-white transition-all hover:bg-white/10 touch-manipulation sm:w-auto sm:min-h-0"
               style="border:1.5px solid rgba(255,255,255,.18)">
                Plan een demo
            </a>
        </div>

        {{-- Trust strip --}}
        <div class="mt-10 flex flex-wrap justify-center gap-x-8 gap-y-3">
            @foreach(['14 dagen gratis','Geen creditcard','NL support','Altijd opzegbaar'] as $b)
            <span class="flex items-center gap-2 text-sm text-slate-500">
                <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                {{ $b }}
            </span>
            @endforeach
        </div>

    </div>
</section>

@include('components.footer')

<script>
(function () {
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (!e.isIntersecting) return;
            e.target.classList.add('visible');
            e.target.querySelectorAll('.bar-before,.bar-after').forEach(function (bar) {
                setTimeout(function () { bar.style.width = bar.getAttribute('data-w'); }, 150);
            });
            io.unobserve(e.target);
        });
    }, { threshold: 0.08 });
    document.querySelectorAll('.fade-up,.stagger').forEach(function (el) { io.observe(el); });
    document.querySelectorAll('.metric-bar').forEach(function (el) {
        var mo = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                e.target.querySelectorAll('.bar-before,.bar-after').forEach(function (b) {
                    setTimeout(function () { b.style.width = b.getAttribute('data-w'); }, 150);
                });
                mo.unobserve(e.target);
            });
        }, { threshold: 0.3 });
        mo.observe(el);
    });
})();

// Doorlopende werklijst: stop, vink af en schuif daarna de volledige lijst één taak door.
(function initHeroTaskStream() {
    var stream = document.querySelector('[data-task-stream]');
    if (!stream) return;

    var track = stream.querySelector('[data-task-stream-track]');
    var items = Array.from(stream.querySelectorAll('[data-task-stream-item]'));
    var baseCount = items.length / 3;
    var activeIndex = baseCount + 2;
    var checked = false;
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function metrics() {
        var styles = getComputedStyle(stream);
        return {
            step: parseFloat(styles.getPropertyValue('--task-step')),
            start: parseFloat(styles.getPropertyValue('--task-start')),
        };
    }

    function render() {
        var layout = metrics();
        track.style.transform = 'translateY(' + (layout.start - (activeIndex - 2) * layout.step) + 'px)';

        items.forEach(function (item, index) {
            var distance = index - activeIndex;
            item.classList.toggle('is-active', distance === 0);
            item.classList.toggle('is-adjacent', Math.abs(distance) === 1);
            item.classList.toggle('is-edge', Math.abs(distance) === 2);
            item.classList.toggle('is-done', distance < 0);
            item.classList.toggle('is-checked', distance === 0 && checked);
        });
    }

    function advance() {
        checked = false;
        activeIndex += 1;
        render();

        if (activeIndex >= (baseCount * 2) + 2) {
            track.addEventListener('transitionend', function resetContinuousTrack(event) {
                if (event.target !== track || event.propertyName !== 'transform') return;
                track.removeEventListener('transitionend', resetContinuousTrack);
                track.classList.add('is-resetting');
                activeIndex -= baseCount;
                render();
                track.getBoundingClientRect();
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        track.classList.remove('is-resetting');
                    });
                });
            });
        }
    }

    render();
    window.addEventListener('resize', render, { passive: true });

    if (!reducedMotion) {
        window.setInterval(function () {
            checked = true;
            render();
            window.setTimeout(advance, 1150);
        }, 3000);
    }
})();

function toggleFaq(trigger) {
    if (!trigger) return;

    var body = trigger.nextElementSibling, icon = trigger.querySelector('.faq-icon'), open = body.classList.contains('open');
    document.querySelectorAll('.faq-body').forEach(function (b) { b.classList.remove('open'); });
    document.querySelectorAll('.faq-icon').forEach(function (i) { i.classList.remove('open'); });
    document.querySelectorAll('.faq-trigger').forEach(function (b) { b.setAttribute('aria-expanded','false'); });
    if (!open) { body.classList.add('open'); icon.classList.add('open'); trigger.setAttribute('aria-expanded','true'); }
}

document.querySelectorAll('.faq-trigger').forEach(function (btn) {
    btn.addEventListener('click', function (event) {
        event.stopPropagation();
        toggleFaq(this);
    });
});

document.querySelectorAll('[data-faq-item]').forEach(function (item) {
    item.addEventListener('click', function () {
        toggleFaq(this.querySelector('.faq-trigger'));
    });
});

if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
    window.location.href = '/login?source=pwa';
}
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js').then(function (reg) {
            var swRefresh = false;
            navigator.serviceWorker.addEventListener('controllerchange', function () {
                if (swRefresh) return; swRefresh = true; window.location.reload();
            });
            function showUpdate(w) {
                var t = document.createElement('div');
                t.style.cssText = 'position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;padding:1rem';
                t.innerHTML = '<div style="background:#fff;border-radius:16px;padding:24px;max-width:320px;width:100%;box-shadow:0 25px 50px rgba(0,0,0,.25)"><p style="font-weight:700;color:#0f172a;margin:0 0 6px">Update beschikbaar</p><p style="color:#64748b;font-size:14px;margin:0 0 16px">Er is een nieuwe versie van TaskCheck.</p><button style="width:100%;padding:10px;background:#2563eb;color:#fff;border:none;border-radius:10px;font-weight:700;cursor:pointer;font-size:14px">Nu updaten</button></div>';
                t.querySelector('button').addEventListener('click', function () { w.postMessage({ type: 'SKIP_WAITING' }); });
                document.body.appendChild(t);
            }
            if (reg.waiting && navigator.serviceWorker.controller) showUpdate(reg.waiting);
            reg.addEventListener('updatefound', function () {
                var nw = reg.installing; if (!nw) return;
                nw.addEventListener('statechange', function () {
                    if (nw.state === 'installed' && navigator.serviceWorker.controller) showUpdate(nw);
                });
            });
        }).catch(function () {});
    });
}
</script>
</body>
</html>
