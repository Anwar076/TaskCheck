<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $headerDark = false;
        $showCta = $showCta ?? true;
        $ctaHeading = $ctaHeading ?? 'Wil je dit direct toepassen in jouw team?';
        $ctaLead = $ctaLead ?? 'Start met TaskCheck en zet je eerste digitale checklist live in minuten. Inclusief bewijs, voortgang en realtime inzicht.';
        $robots = $robots ?? 'index,follow,max-image-preview:large';
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    @isset($seoKeywords)
        <meta name="keywords" content="{{ $seoKeywords }}">
    @endisset
    <meta name="robots" content="{{ $robots }}">
    <link rel="canonical" href="{{ $seoUrl }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="nl_NL">
    <meta property="og:site_name" content="TaskCheck">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    @isset($seoImage)
        <meta property="og:image" content="{{ $seoImage }}">
        <meta name="twitter:image" content="{{ $seoImage }}">
    @endisset
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    @stack('head')
    <style>
        .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .55s ease, transform .55s ease; }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
        .delay-1.visible { transition-delay: .1s; }
        .delay-2.visible { transition-delay: .2s; }
        .delay-3.visible { transition-delay: .3s; }
        .cta-btn { background: linear-gradient(135deg, #2563eb, #4f46e5); }
        .cta-btn:hover { background: linear-gradient(135deg, #1d4ed8, #4338ca); }
        .stagger .s-item { opacity: 0; transform: translateY(14px); transition: opacity .5s ease, transform .5s ease; }
        .stagger.visible .s-item:nth-child(1) { opacity: 1; transform: translateY(0); transition-delay: .05s; }
        .stagger.visible .s-item:nth-child(2) { opacity: 1; transform: translateY(0); transition-delay: .15s; }
        .stagger.visible .s-item:nth-child(3) { opacity: 1; transform: translateY(0); transition-delay: .25s; }
        .img-zoom img { transition: transform .6s ease; }
        .img-zoom:hover img { transform: scale(1.04); }
        .product-showcase { position: relative; max-width: 1080px; margin: 0 auto; }
        .product-browser {
            overflow: hidden; border: 1px solid #e6e8ec; border-radius: 18px; background: #fff;
            box-shadow: 0 4px 12px rgba(10,12,18,.06), 0 32px 80px -16px rgba(23,43,99,.18);
        }
        .product-browser-bar {
            display: flex; align-items: center; gap: 8px; min-height: 46px; padding: 10px 14px;
            border-bottom: 1px solid #eef0f3; background: #fbfcfd;
        }
        .product-browser-dot { width: 9px; height: 9px; flex: none; border-radius: 999px; background: #e8eaee; }
        .product-browser-url {
            display: flex; align-items: center; gap: 6px; margin: 0 auto; padding: 4px 12px;
            border: 1px solid #eef0f3; border-radius: 6px; background: #fff;
            color: #9a9ea6; font: 500 10.5px/1.4 ui-monospace, SFMono-Regular, Menlo, monospace;
        }
        .product-chip {
            position: absolute; z-index: 6; display: flex; align-items: center; gap: 10px; padding: 10px 14px;
            border: 1px solid #eef0f3; border-radius: 14px; background: rgba(255,255,255,.94);
            box-shadow: 0 2px 6px rgba(10,12,18,.05), 0 12px 32px -8px rgba(10,12,18,.14);
            backdrop-filter: blur(8px); animation: product-chip-float 6s ease-in-out infinite;
        }
        .product-chip:nth-of-type(even) { animation-delay: -3s; }
        .product-chip-icon { display: grid; width: 30px; height: 30px; flex: none; place-items: center; border-radius: 9px; }
        .product-chip-title { display: block; white-space: nowrap; color: #0f172a; font-size: 12.5px; font-weight: 700; }
        .product-chip-meta { display: block; color: #9a9ea6; font: 500 9.5px/1.4 ui-monospace, SFMono-Regular, Menlo, monospace; letter-spacing: .02em; }
        .product-review-card {
            position: absolute; right: -40px; bottom: -56px; z-index: 8; width: min(520px, 52%); overflow: hidden;
            transform: rotate(1.2deg); border: 1px solid #e6e8ec; border-radius: 16px; background: #fff;
            box-shadow: 0 2px 6px rgba(10,12,18,.06), 0 32px 90px -18px rgba(10,14,30,.4);
        }
        .product-showcase-fade {
            position: absolute; z-index: 9; right: -6%; bottom: -70px; left: -6%; height: 210px; pointer-events: none;
            background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(255,255,255,.08) 22%, rgba(255,255,255,.38) 48%, rgba(255,255,255,.78) 73%, #fff 100%);
        }
        @keyframes product-chip-float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
        .new-feature-section { padding: 88px 0; }
        .new-feature-section.is-soft { background: #f7f8fa; }
        .new-feature-wrap { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        .new-feature-head { max-width: 720px; }
        .new-feature-head.is-center { margin: 0 auto; text-align: center; }
        .new-feature-kicker {
            display: inline-flex; align-items: center; gap: 10px; color: #2563eb;
            font: 600 11px/1.4 ui-monospace, SFMono-Regular, Menlo, monospace;
            letter-spacing: .14em; text-transform: uppercase;
        }
        .new-feature-kicker::before { content: ""; width: 22px; height: 1px; background: #93b4ff; }
        .new-feature-title { margin: 16px 0 0; color: #0a0a0b; font-size: clamp(32px, 4vw, 52px); font-weight: 800; letter-spacing: -.04em; line-height: 1.04; }
        .new-feature-grid .new-feature-title { font-size: clamp(32px, 3.1vw, 46px); line-height: 1.12; }
        .new-feature-title-line { display: block; }
        .new-feature-lead { margin: 17px 0 0; color: #62666d; font-size: 17px; line-height: 1.65; }
        .new-feature-grid { display: grid; grid-template-columns: minmax(300px, 5fr) minmax(0, 7fr); align-items: center; gap: clamp(36px, 6vw, 80px); }
        .new-feature-grid.is-visual-left { grid-template-columns: minmax(0, 7fr) minmax(300px, 5fr); }
        .new-browser {
            overflow: hidden; border: 1px solid #e6e8ec; border-radius: 16px; background: #fff;
            box-shadow: 0 2px 6px rgba(10,12,18,.04), 0 24px 64px -16px rgba(23,43,99,.16);
        }
        .new-browser-bar { display: flex; align-items: center; gap: 7px; padding: 9px 12px; border-bottom: 1px solid #eef0f3; background: #fbfcfd; }
        .new-browser-bar i { width: 8px; height: 8px; border-radius: 50%; background: #e8eaee; }
        .new-browser-bar span { margin: 0 auto; color: #9a9ea6; font: 500 9.5px/1.4 ui-monospace, SFMono-Regular, Menlo, monospace; }
        .new-browser img { display: block; width: 100%; height: auto; }
        .new-points { display: grid; gap: 12px; margin-top: 26px; }
        .new-point { display: flex; align-items: flex-start; gap: 10px; color: #18191b; font-size: 14px; font-weight: 600; }
        .new-check { display: grid; width: 19px; height: 19px; flex: none; place-items: center; border-radius: 6px; background: #e7f6f0; color: #0f9f6e; font-size: 12px; }
        .new-shot { position: relative; }
        .new-float-chip {
            position: absolute; z-index: 3; display: flex; align-items: center; gap: 10px; padding: 11px 14px;
            border: 1px solid #e6e8ec; border-radius: 13px; background: #fff;
            box-shadow: 0 2px 6px rgba(10,12,18,.05), 0 24px 60px -18px rgba(10,14,30,.3);
        }
        .new-float-chip strong { display: block; font-size: 13px; }
        .new-float-chip small { color: #9a9ea6; font: 500 8.5px/1.4 ui-monospace, SFMono-Regular, Menlo, monospace; }
        .new-number-list { margin-top: 26px; }
        .new-number-row { display: flex; gap: 16px; padding: 15px 0; border-top: 1px solid #e6e8ec; }
        .new-number { color: #2563eb; font: 600 11px/1.5 ui-monospace, SFMono-Regular, Menlo, monospace; }
        .new-number-row strong { display: block; font-size: 15px; }
        .new-number-row p { margin-top: 3px; color: #62666d; font-size: 13px; line-height: 1.55; }
        .seo-hero-frame {
            overflow: hidden; border: 1px solid #e6e8ec; border-radius: 18px; background: #fff;
            box-shadow: 0 4px 12px rgba(10,12,18,.06), 0 32px 80px -16px rgba(23,43,99,.18);
        }
        .seo-hero-frame img { display: block; width: 100%; height: auto; }
        .seo-story-breakout {
            position: relative; width: 100vw; max-width: 100vw; left: 50%; right: 50%;
            margin-left: -50vw; margin-right: -50vw;
        }
        @media (max-width: 1160px) {
            .product-chip { display: none; }
            .product-review-card { right: 8px; bottom: -24px; }
        }
        @media (max-width: 900px) {
            .new-feature-grid, .new-feature-grid.is-visual-left { grid-template-columns: 1fr; }
        }
        @media (max-width: 720px) {
            .product-browser { border-radius: 12px; }
            .product-browser-bar { min-height: 38px; padding: 7px 9px; }
            .product-browser-url { font-size: 9px; }
            .product-review-card { position: relative; right: auto; bottom: auto; width: 100%; margin-top: 14px; transform: none; }
            .product-showcase-fade { right: -4%; bottom: -20px; left: -4%; height: 110px; }
        }
        @media (max-width: 600px) {
            .new-feature-section { padding: 56px 0; }
            .new-feature-wrap { padding: 0 16px; }
            .new-float-chip { position: relative !important; inset: auto !important; margin-top: 10px; }
            .new-feature-lead { font-size: 16px; line-height: 1.6; }
        }
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
        .seo-page .feature-card {
            border-color: #e6e8ec;
            transition: transform .3s ease, box-shadow .3s ease, border-color .25s ease;
        }
        .seo-page .feature-card:hover {
            transform: translateY(-2px);
            border-color: #d7e2f7;
            box-shadow: 0 12px 32px -12px rgba(23,43,99,.15);
        }
        .seo-page details { border-color: #e6e8ec; }
        .seo-page details:hover { border-color: #d7e2f7; }
        details summary::-webkit-details-marker { display: none; }
        @media (prefers-reduced-motion: reduce) {
            .fade-up { opacity: 1; transform: none; transition: none; }
            .seo-page .feature-card:hover { transform: none; }
            .img-zoom:hover img { transform: none; }
            .product-chip { animation: none; }
            .stagger .s-item { opacity: 1; transform: none; }
        }
    </style>
</head>
<body class="seo-page min-h-screen overflow-x-hidden bg-white text-slate-900 antialiased">
    <div class="welcome-page-glow" aria-hidden="true"></div>

    @include('components.header')

    <main>
        @yield('content')

        @if($showCta)
        <section class="relative overflow-hidden py-20 sm:py-28" style="background:#030712">
            <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                <div class="absolute -top-32 -left-32 h-[600px] w-[600px] rounded-full opacity-25" style="background:radial-gradient(circle,#2563eb,transparent 70%)"></div>
                <div class="absolute -bottom-40 -right-24 h-[500px] w-[500px] rounded-full opacity-20" style="background:radial-gradient(circle,#6366f1,transparent 70%)"></div>
                <div class="absolute inset-0 opacity-[.04]" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:28px 28px"></div>
            </div>
            <div class="relative mx-auto max-w-3xl px-4 text-center sm:px-6 fade-up">
                <div class="mb-8 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold text-blue-300"
                     style="background:rgba(37,99,235,.18);border:1px solid rgba(96,165,250,.2)">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    14 dagen gratis proberen
                </div>
                <h2 class="text-3xl font-extrabold leading-[1.06] tracking-tight text-white sm:text-4xl">
                    {{ $ctaHeading }}
                </h2>
                <p class="mx-auto mt-5 max-w-lg text-base leading-relaxed text-slate-400 sm:mt-6 sm:text-lg">
                    {{ $ctaLead }}
                </p>
                <div class="mt-8 flex flex-col items-stretch justify-center gap-3 sm:mt-10 sm:flex-row sm:items-center sm:gap-4">
                    @auth
                        <a href="{{ auth()->user()->homeDashboardUrl() }}"
                           class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2.5 rounded-2xl px-8 py-4 text-base font-extrabold text-slate-900 sm:w-auto"
                           style="background:#fff;box-shadow:0 0 0 1px rgba(255,255,255,.12),0 16px 40px rgba(37,99,235,.3)">
                            Naar dashboard
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2.5 rounded-2xl px-8 py-4 text-base font-extrabold text-white hover:scale-[1.02] sm:w-auto"
                           style="background:linear-gradient(135deg,#2563eb,#6366f1);box-shadow:0 0 0 1px rgba(255,255,255,.08),0 16px 40px rgba(37,99,235,.4)">
                            Start gratis proefperiode
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endauth
                    <a href="{{ route('contact') }}"
                       class="inline-flex min-h-[3rem] w-full items-center justify-center rounded-2xl px-8 py-4 text-base font-bold text-white hover:bg-white/10 sm:w-auto"
                       style="border:1.5px solid rgba(255,255,255,.18)">
                        Plan een demo
                    </a>
                </div>
            </div>
        </section>
        @endif
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
