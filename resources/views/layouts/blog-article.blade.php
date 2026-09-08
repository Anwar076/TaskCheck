<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $headerDark = false;
        $ctaHeading = $ctaHeading ?? 'Wil je dit direct toepassen in jouw team?';
        $ctaLead = $ctaLead ?? 'Start met TaskCheck en zet je eerste digitale checklist live in minuten. Inclusief bewijs, voortgang en realtime inzicht.';
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    @isset($seoKeywords)
        <meta name="keywords" content="{{ $seoKeywords }}">
    @endisset
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">
    <meta property="og:type" content="article">
    <meta property="og:locale" content="nl_NL">
    <meta property="og:site_name" content="TaskCheck">
    @isset($publishedAt)
        <meta property="article:published_time" content="{{ $publishedAt }}">
    @endisset
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    @stack('head')
    <style>
        .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .55s ease, transform .55s ease; }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
        .delay-1.visible { transition-delay: .1s; }
        .delay-2.visible { transition-delay: .2s; }
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
        .blog-figure {
            overflow: hidden; border: 1px solid #e6e8ec; border-radius: 18px; background: #fff;
            box-shadow: 0 4px 12px rgba(10,12,18,.04);
        }
        .blog-figure img { display: block; width: 100%; height: auto; }
        .blog-figure figcaption {
            padding: 12px 16px; border-top: 1px solid #eef0f3; background: #f7f8fa;
            text-align: center; font-size: 12px; line-height: 1.5; color: #64748b;
        }
        .blog-aside {
            margin-top: 1.5rem; padding: 16px 18px; border: 1px solid #e6e8ec; border-radius: 16px;
            background: #f7f8fa; color: #475569; font-size: 14px; line-height: 1.7;
        }
        .blog-readmore {
            display: flex; gap: 16px; padding: 16px; border: 1px solid #e6e8ec; border-radius: 16px;
            background: #fff; transition: transform .3s ease, box-shadow .3s ease, border-color .25s ease;
        }
        .blog-readmore:hover {
            transform: translateY(-2px); border-color: #d7e2f7;
            box-shadow: 0 12px 32px -12px rgba(23,43,99,.15);
        }
        .prose-article h2 {
            font-size: 1.5rem; font-weight: 800; color: #0f172a;
            margin-top: 2.5rem; margin-bottom: .75rem; letter-spacing: -.02em; line-height: 1.25;
        }
        .prose-article h3 {
            font-size: 1.125rem; font-weight: 700; color: #1e293b;
            margin-top: 1.75rem; margin-bottom: .5rem;
        }
        .prose-article p { font-size: 1rem; line-height: 1.8; color: #475569; margin-bottom: 1rem; }
        .prose-article ul, .prose-article ol {
            padding: 0; margin: 1rem 0 1.25rem; display: flex; flex-direction: column; gap: .5rem;
        }
        .prose-article ul { list-style: none; }
        .prose-article ul li {
            display: flex; align-items: flex-start; gap: .625rem;
            font-size: .9375rem; color: #475569; line-height: 1.6;
        }
        .prose-article ul li::before {
            content: ''; display: inline-block; width: .375rem; height: .375rem;
            background: #2563eb; border-radius: 50%; margin-top: .6rem; flex-shrink: 0;
        }
        .prose-article ol { list-style: none; counter-reset: article-ol; }
        .prose-article ol li {
            counter-increment: article-ol; display: flex; align-items: flex-start; gap: .75rem;
            font-size: .9375rem; color: #475569; line-height: 1.6;
        }
        .prose-article ol li::before {
            content: counter(article-ol); display: inline-flex; align-items: center; justify-content: center;
            min-width: 1.5rem; height: 1.5rem; border-radius: 9999px; background: #eff6ff; color: #1d4ed8;
            font-size: .75rem; font-weight: 700; flex-shrink: 0; margin-top: .05rem;
        }
        .prose-article a { color: #2563eb; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
        .prose-article a:hover { color: #1d4ed8; }
        .prose-article strong { color: #1e293b; font-weight: 600; }
        .prose-article em { color: #334155; }
        .prose-article .callout {
            background: #eff6ff; border: 1px solid #dbeafe; border-left: 3px solid #2563eb;
            border-radius: 16px; padding: 1rem 1.25rem; margin: 1.5rem 0;
            color: #475569; font-size: .9375rem; line-height: 1.7;
        }
        details summary::-webkit-details-marker { display: none; }
        @media (prefers-reduced-motion: reduce) {
            .fade-up { opacity: 1; transform: none; transition: none; }
            .blog-readmore:hover { transform: none; }
        }
    </style>
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased overflow-x-hidden">
    <div class="welcome-page-glow" aria-hidden="true"></div>

    @include('components.header')

    <main>
        <section class="relative overflow-hidden bg-white pt-28 pb-10 sm:pt-32 sm:pb-12 lg:pt-36">
            <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="blog-article-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                            <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#blog-article-dots)"/>
                </svg>
                <div class="absolute -right-[200px] -top-[280px] h-[min(420px,100vw)] w-[min(420px,100vw)] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)] md:h-[560px] md:w-[560px]"></div>
            </div>
            <div class="relative mx-auto max-w-3xl px-4 sm:px-6">
                @yield('hero')
            </div>
        </section>

        <section class="relative mx-auto max-w-3xl px-4 pb-16 sm:px-6 sm:pb-20">
            @yield('content')
        </section>

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
