<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- PWA Meta Tags -->
@if ($includeDefaultMetaDescription ?? true)
<meta name="description" content="Professioneel taakbeheer en teamsamenwerking platform">
@endif
<meta name="theme-color" content="#2563eb">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="TaskCheck">
<meta name="msapplication-TileColor" content="#2563eb">
<meta name="msapplication-tap-highlight" content="no">

<!-- PWA Manifest -->
<link rel="manifest" href="/manifest.json">

<!-- ✅ FIXED FAVICON (BELANGRIJK) -->
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="/logos/taskcheck-favicon.png">
<link rel="icon" type="image/png" sizes="16x16" href="/logos/taskcheck-favicon.png">

<!-- Apple Touch Icons -->
<link rel="apple-touch-icon" href="/logos/taskcheck-favicon.png">
<link rel="apple-touch-icon" sizes="152x152" href="/logos/taskcheck-favicon.png">
<link rel="apple-touch-icon" sizes="180x180" href="/logos/taskcheck-favicon.png">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">

<!-- Local compiled Tailwind -->
@vite('resources/css/app.css')

<!-- Google Analytics (GA4) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-MV0B108DRY"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-MV0B108DRY');
</script>

<!-- Contentsquare / Hotjar analytics -->
<script src="https://t.contentsquare.net/uxa/560a99166a851.js" async></script>

@if(filled(config('services.crisp.website_id')))
<script>
    window.$crisp = [];
    window.CRISP_WEBSITE_ID = @json(config('services.crisp.website_id'));
    (function () {
        var d = document;
        var s = d.createElement('script');
        s.src = 'https://client.crisp.chat/l.js';
        s.async = 1;
        d.getElementsByTagName('head')[0].appendChild(s);
    })();
</script>
@endif

<style> 
    body,
    body.font-sans { font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif; }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Outfit', 'DM Sans', ui-sans-serif, system-ui, sans-serif;
    }

    h1, h2 { letter-spacing: -0.018em !important; }
    h3, h4, h5, h6 { letter-spacing: -0.008em !important; }

    #siteHeader { font-family: 'Archivo', ui-sans-serif, system-ui, sans-serif; }

    .tc-text-reveal {
        position: relative;
        display: inline-block;
        white-space: nowrap;
    }
    .tc-text-reveal-base,
    .tc-text-reveal-color {
        display: block;
        font: inherit;
        letter-spacing: inherit;
    }
    .tc-text-reveal-color {
        position: absolute;
        inset: 0 0 -.35em;
        background: linear-gradient(105deg, #2563eb 0%, #4f46e5 58%, #6366f1 100%);
        background-clip: text;
        -webkit-background-clip: text;
        color: transparent;
        -webkit-text-fill-color: transparent;
        clip-path: inset(0 100% 0 0);
        transition: clip-path .85s cubic-bezier(.22,.75,.2,1);
        pointer-events: none;
    }
    .tc-text-reveal-curve {
        position: absolute;
        right: 0;
        bottom: -.28em;
        left: 0;
        width: 100%;
        height: .14em;
        min-height: 4px;
        overflow: visible;
        pointer-events: none;
    }
    .tc-text-reveal-curve path {
        stroke-dasharray: 310;
        stroke-dashoffset: 310;
        transition: stroke-dashoffset .72s .52s cubic-bezier(.22,.75,.2,1);
    }
    .tc-text-reveal.is-visible .tc-text-reveal-color { clip-path: inset(0); }
    .tc-text-reveal.is-visible .tc-text-reveal-curve path { stroke-dashoffset: 0; }

    @media (prefers-reduced-motion: reduce) {
        .tc-text-reveal-color { clip-path: inset(0); transition: none; }
        .tc-text-reveal-curve path { stroke-dashoffset: 0; transition: none; }
    }

    @keyframes fadeUp { from { opacity:0; transform:translateY(30px);} to {opacity:1; transform:translateY(0);} }
    @keyframes fadeIn { from { opacity:0;} to {opacity:1;} }
    @keyframes float { 0%,100% {transform:translateY(0);} 50% {transform:translateY(-8px);} }

    .fade-up { animation: fadeUp 0.9s ease-out forwards; }
    .fade-in { animation: fadeIn 1s ease-out forwards; }
    .float { animation: float 6s ease-in-out infinite; }

    .card-hover { transition: all 0.35s ease; }
    .card-hover:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

    .btn-gradient {
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(139,92,246,0.35);
    }
</style>
