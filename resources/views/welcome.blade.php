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
        @media (prefers-reduced-motion:reduce) {
            .hero-v-blob,.hero-v-blob--2,.hero-v-orbit,.hero-v-card,.hero-v-row,.hero-v-pop,.hero-v-fill,.hero-v-shimmer{ animation:none !important; }
            .hero-v-row,.hero-v-pop{ opacity:1; transform:none; }
            .hero-v-fill{ width:var(--v-bar,100%) !important; }
            .hero-v-card{ transform:none; }
            .hero-v-orbit::after{ display:none; }
        }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased overflow-x-hidden">

@include('components.header')

{{-- ══════════════════════════════════════
     HERO — 2 kolommen: tekst links, screenshot rechts
══════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-white pt-28 pb-16">
    {{-- Achtergrond --}}
    <div class="absolute inset-0 pointer-events-none">
        <svg class="absolute inset-0 w-full h-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs><pattern id="dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse"><circle cx="1" cy="1" r="1.2" fill="#334155"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#dots)"/>
        </svg>
        <div style="position:absolute;width:800px;height:800px;top:-300px;right:-200px;background:radial-gradient(circle,rgba(99,102,241,.1) 0%,transparent 65%)"></div>
        <div style="position:absolute;width:400px;height:400px;bottom:0;left:-100px;background:radial-gradient(circle,rgba(16,185,129,.07) 0%,transparent 65%)"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- LINKS: tekst --}}
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-1.5 text-xs font-semibold text-blue-700 mb-7">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Werkcontrole voor operationele teams
                </div>

                <h1 class="text-4xl sm:text-5xl xl:text-[3.4rem] font-extrabold text-slate-900 leading-[1.06] tracking-tight">
                    Nooit meer discussie over
                    <span class="relative inline-block mt-1">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">uitgevoerd werk</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs><linearGradient id="ul" x1="0" y1="0" x2="300" y2="0"><stop offset="0%" stop-color="#2563eb"/><stop offset="100%" stop-color="#6366f1"/></linearGradient></defs>
                        </svg>
                    </span>
                </h1>

                <p class="mt-6 text-slate-500 text-lg leading-relaxed max-w-lg">
                    Leg taken vast, verzamel bewijs en houd realtime controle over je team. Voor horeca, schoonmaak en andere operationele bedrijven.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="cta-btn inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-white font-bold text-sm transition-all shadow-lg shadow-blue-200/60">
                            Naar dashboard
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="cta-btn inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-white font-bold text-sm transition-all shadow-lg shadow-blue-200/60">
                            Start gratis trial
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endauth
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-bold text-sm hover:bg-slate-50 transition-colors shadow-sm">
                        Plan een demo
                    </a>
                </div>

                <div class="mt-6 flex flex-wrap gap-x-6 gap-y-2">
                    @foreach(['14 dagen gratis','Geen creditcard','Binnen 10 min live','AVG-proof'] as $b)
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {{ $b }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- RECHTS: geanimeerde illustratie (CSS/SVG, geen afbeelding) --}}
            <div class="relative flex min-h-[300px] items-center justify-center lg:min-h-[400px]" aria-hidden="true">
                <div class="hero-v-blob -right-[15%] top-[8%] h-[200px] w-[240px] bg-gradient-to-br from-blue-400/50 via-indigo-400/35 to-violet-400/25"></div>
                <div class="hero-v-blob hero-v-blob--2 -left-[20%] bottom-[5%] h-[180px] w-[200px] bg-gradient-to-tr from-emerald-400/35 via-cyan-400/20 to-transparent"></div>
                <div class="hero-v-orbit hidden sm:block"></div>

                <div class="hero-v-card rounded-2xl border border-slate-200/90 bg-white/75 p-5 shadow-[0_24px_48px_-20px_rgba(37,99,235,.15),0_0_0_1px_rgba(255,255,255,.8)_inset] backdrop-blur-xl sm:p-6">
                    <div class="mb-5 flex items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-60 animate-ping"></span>
                                <span class="relative h-2 w-2 rounded-full bg-emerald-500"></span>
                            </span>
                            <span class="text-xs font-semibold tracking-wide text-slate-500">Live voortgang</span>
                        </div>
                        <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">3 locaties</span>
                    </div>
                    <div class="space-y-4">
                        <div class="hero-v-row flex gap-3">
                            <span class="hero-v-pop flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 shadow-md shadow-blue-500/25">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                            <div class="min-w-0 flex-1 pt-0.5">
                                <p class="text-sm font-semibold text-slate-800">Opening checklist</p>
                                <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="hero-v-fill"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hero-v-row flex gap-3">
                            <span class="hero-v-pop flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white shadow-sm">
                                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div class="min-w-0 flex-1 pt-0.5">
                                <p class="text-sm font-semibold text-slate-800">HACCP-ronde</p>
                                <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="hero-v-fill"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hero-v-row flex gap-3">
                            <span class="hero-v-pop flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50/80">
                                <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                            </span>
                            <div class="min-w-0 flex-1 pt-0.5">
                                <p class="text-sm font-semibold text-slate-600">Sluiting locatie</p>
                                <div class="hero-v-shimmer mt-2 h-1.5 rounded-full"></div>
                            </div>
                        </div>
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
    <div class="max-w-6xl mx-auto px-6 lg:px-8 text-center">
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
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
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
<section class="py-24 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="max-w-xl mb-14 fade-up">
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
     VERGELIJKING
══════════════════════════════════════ --}}
<section class="py-24 bg-slate-50 border-y border-slate-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-16 fade-up">
            <p class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3">Vergelijk</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Waarom teams kiezen voor TaskCheck</h2>
            <p class="mt-4 text-slate-500 text-lg">Excel, WhatsApp en papier zijn niet gebouwd voor werkcontrole. TaskCheck wel.</p>
        </div>

        {{-- Stat row --}}
        <div class="stagger grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-16">
            @foreach([
                ['-90%','Vergeten taken','Checklists zorgen dat geen stap wordt gemist.','#2563eb'],
                ['-87%','Klachten bewijs','Elk stuk werk is altijd aantoonbaar.','#059669'],
                ['-75%','Controletijd','Managers zien realtime wat er speelt.','#7c3aed'],
                ['3×','Sneller klaar audit','Alle bewijzen staan direct klaar.','#d97706'],
            ] as [$num,$title,$desc,$col])
            <div class="s-item bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="text-4xl font-black mb-1.5 leading-none" style="color:{{ $col }}">{{ $num }}</div>
                <p class="font-bold text-slate-900 text-sm mb-1">{{ $title }}</p>
                <p class="text-slate-500 text-xs leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>

        <div class="grid lg:grid-cols-2 gap-12 items-start">

            {{-- Comparison table --}}
            <div class="fade-up bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <table class="w-full text-sm" style="border-collapse:collapse">
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
                <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-400 bg-slate-50">— = beperkt of handmatig beschikbaar</div>
            </div>

            {{-- Before/After bars --}}
            <div class="fade-up delay-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-7">
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
                <div class="metric-bar">
                    <div class="flex justify-between items-baseline mb-2.5">
                        <span class="text-sm font-semibold text-slate-800">{{ $label }}</span>
                        <span class="text-sm font-extrabold text-emerald-600">-{{ $before-$after }}%</span>
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-400 w-16 shrink-0 text-right">Zonder</span>
                            <div class="flex-1 h-3 bar-track rounded-full overflow-hidden">
                                <div class="bar-before h-full rounded-full transition-all duration-[1100ms] ease-out" style="background:{{ $col }};width:0%;opacity:.75" data-w="{{ $before }}%"></div>
                            </div>
                            <span class="text-xs tabular-nums text-slate-500 w-8">{{ $before }}%</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-emerald-600 w-16 shrink-0 text-right">TaskCheck</span>
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
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
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
                    <a href="{{ route('register') }}" class="cta-btn inline-flex items-center gap-2 px-5 py-3 rounded-xl text-white font-semibold text-sm shadow-md shadow-blue-200 transition-all">
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
        <div class="text-center max-w-2xl mx-auto mb-16 fade-up">
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
<section class="py-24 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-start">
            <div class="fade-up lg:sticky lg:top-28">
                <p class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-3">Functies</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">Alles wat je nodig hebt om werk onder controle te houden</h2>
                <p class="mt-4 text-slate-500 text-lg leading-relaxed">Van bewijs per taak tot AI-checklists en rapportages — gebouwd voor teams die resultaat willen aantonen.</p>
                <div class="mt-8">
                    @guest
                    <a href="{{ route('register') }}" class="cta-btn inline-flex items-center gap-2 px-5 py-3 rounded-xl text-white font-semibold text-sm shadow-md shadow-blue-200 transition-all">
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
                <div class="flex items-start gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
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
<section class="py-20 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
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
<section class="py-24 bg-slate-50 border-t border-slate-100">
    <div class="max-w-3xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-12 fade-up">
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
            <div>
                <button class="faq-trigger w-full flex items-center justify-between gap-4 px-6 py-5 text-left hover:bg-slate-50 transition-colors" aria-expanded="false">
                    <span class="font-semibold text-slate-900 text-sm">{{ $q }}</span>
                    <svg class="faq-icon h-5 w-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                </button>
                <div class="faq-body px-6 pb-5 text-sm text-slate-600 leading-relaxed">{{ $a }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     FINAL CTA — enige donkere sectie
══════════════════════════════════════ --}}
<section class="relative overflow-hidden py-32" style="background:#030712">
    {{-- Glow blobs --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-32 -left-32 w-[600px] h-[600px] rounded-full opacity-25" style="background:radial-gradient(circle,#2563eb,transparent 70%)"></div>
        <div class="absolute -bottom-40 -right-24 w-[500px] h-[500px] rounded-full opacity-20" style="background:radial-gradient(circle,#6366f1,transparent 70%)"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[300px] opacity-10" style="background:radial-gradient(ellipse,#3b82f6,transparent 65%)"></div>
        {{-- subtle dot grid --}}
        <div class="absolute inset-0 opacity-[.04]" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:28px 28px"></div>
    </div>

    <div class="relative max-w-3xl mx-auto px-6 lg:px-8 text-center fade-up">

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold text-blue-300 mb-8"
             style="background:rgba(37,99,235,.18);border:1px solid rgba(96,165,250,.2)">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            14 dagen gratis proberen
        </div>

        <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.04] tracking-tight">
            Voorkom fouten.<br>
            <span style="background:linear-gradient(135deg,#60a5fa 0%,#a78bfa 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Begin vandaag.</span>
        </h2>

        <p class="mt-6 text-lg text-slate-400 leading-relaxed max-w-lg mx-auto">
            Geen lange implementatie. Geen creditcard. Binnen 10 minuten live met je eerste checklist.
        </p>

        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="inline-flex items-center gap-2.5 px-8 py-4 rounded-2xl text-slate-900 font-extrabold text-base transition-all"
                   style="background:#fff;box-shadow:0 0 0 1px rgba(255,255,255,.12),0 16px 40px rgba(37,99,235,.3)">
                    Naar dashboard
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2.5 px-8 py-4 rounded-2xl text-white font-extrabold text-base transition-all hover:scale-[1.02]"
                   style="background:linear-gradient(135deg,#2563eb,#6366f1);box-shadow:0 0 0 1px rgba(255,255,255,.08),0 16px 40px rgba(37,99,235,.4)">
                    Start gratis trial
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            @endauth
            <a href="{{ route('contact') }}"
               class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl font-bold text-base text-white transition-all hover:bg-white/10"
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

document.querySelectorAll('.faq-trigger').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var body = this.nextElementSibling, icon = this.querySelector('.faq-icon'), open = body.classList.contains('open');
        document.querySelectorAll('.faq-body').forEach(function (b) { b.classList.remove('open'); });
        document.querySelectorAll('.faq-icon').forEach(function (i) { i.classList.remove('open'); });
        document.querySelectorAll('.faq-trigger').forEach(function (b) { b.setAttribute('aria-expanded','false'); });
        if (!open) { body.classList.add('open'); icon.classList.add('open'); this.setAttribute('aria-expanded','true'); }
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
