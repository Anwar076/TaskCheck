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
        /* Productoverzicht onder de hero */
        .welcome-page-glow {
            position:fixed; z-index:40; top:-300px; right:-300px; width:820px; height:820px;
            border-radius:9999px; pointer-events:none;
            background:radial-gradient(circle,rgba(59,130,246,.075) 0%,rgba(99,102,241,.035) 36%,rgba(255,255,255,0) 70%);
        }
        .product-showcase { position:relative; max-width:1080px; margin:0 auto; }
        .product-browser {
            overflow:hidden; border:1px solid #e6e8ec; border-radius:18px; background:#fff;
            box-shadow:0 4px 12px rgba(10,12,18,.06),0 32px 80px -16px rgba(23,43,99,.18);
        }
        .product-browser-bar {
            display:flex; align-items:center; gap:8px; min-height:46px; padding:10px 14px;
            border-bottom:1px solid #eef0f3; background:#fbfcfd;
        }
        .product-browser-dot { width:9px; height:9px; flex:none; border-radius:999px; background:#e8eaee; }
        .product-browser-url {
            display:flex; align-items:center; gap:6px; margin:0 auto; padding:4px 12px;
            border:1px solid #eef0f3; border-radius:6px; background:#fff;
            color:#9a9ea6; font:500 10.5px/1.4 ui-monospace,SFMono-Regular,Menlo,monospace;
        }
        .product-chip {
            position:absolute; z-index:6; display:flex; align-items:center; gap:10px; padding:10px 14px;
            border:1px solid #eef0f3; border-radius:14px; background:rgba(255,255,255,.94);
            box-shadow:0 2px 6px rgba(10,12,18,.05),0 12px 32px -8px rgba(10,12,18,.14);
            backdrop-filter:blur(8px); animation:product-chip-float 6s ease-in-out infinite;
        }
        .product-chip:nth-of-type(even) { animation-delay:-3s; }
        .product-chip-icon { display:grid; width:30px; height:30px; flex:none; place-items:center; border-radius:9px; }
        .product-chip-title { display:block; white-space:nowrap; color:#0f172a; font-size:12.5px; font-weight:700; }
        .product-chip-meta { display:block; color:#9a9ea6; font:500 9.5px/1.4 ui-monospace,SFMono-Regular,Menlo,monospace; letter-spacing:.02em; }
        .product-review-card {
            position:absolute; right:-40px; bottom:-56px; z-index:8; width:min(520px,52%); overflow:hidden;
            transform:rotate(1.2deg); border:1px solid #e6e8ec; border-radius:16px; background:#fff;
            box-shadow:0 2px 6px rgba(10,12,18,.06),0 32px 90px -18px rgba(10,14,30,.4);
        }
        .product-showcase-fade {
            position:absolute; z-index:9; right:-6%; bottom:-70px; left:-6%; height:210px; pointer-events:none;
            background:linear-gradient(180deg,rgba(255,255,255,0) 0%,rgba(255,255,255,.08) 22%,rgba(255,255,255,.38) 48%,rgba(255,255,255,.78) 73%,#fff 100%);
        }
        @keyframes product-chip-float { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-7px); } }
        @media (max-width:1160px) {
            .product-chip { display:none; }
            .product-review-card { right:8px; bottom:-24px; }
        }
        @media (max-width:720px) {
            .product-browser { border-radius:12px; }
            .product-browser-bar { min-height:38px; padding:7px 9px; }
            .product-browser-url { font-size:9px; }
            .product-review-card { position:relative; right:auto; bottom:auto; width:100%; margin-top:14px; transform:none; }
            .product-showcase-fade { right:-4%; bottom:-20px; left:-4%; height:110px; }
        }
        @media (prefers-reduced-motion:reduce) { .product-chip { animation:none; } }
        .problem-compact { padding:64px 0 68px; background:#030712; }
        .problem-compact .problem-kicker { color:#7da7ff; }
        .problem-compact .problem-kicker span { background:#4979ea; }
        .problem-compact .problem-heading { color:#fff; }
        .problem-compact .tc-text-reveal-base { color:#fff; }
        .problem-compact .problem-lead { color:#aab4c5; }
        .problem-chaos { position:relative; display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); align-items:start; gap:22px; max-width:1000px; margin:34px auto 0; padding:16px 0 36px; }
        .problem-card { position:relative; z-index:2; width:auto; min-width:0; overflow:hidden; border:1px solid rgba(125,167,255,.22); background:#fff; box-shadow:0 4px 12px rgba(0,0,0,.18),0 24px 60px -18px rgba(37,99,235,.38); }
        .problem-card--chat { top:8px; left:4%; width:215px; transform:rotate(-2deg); border-radius:14px; }
        .problem-card--sheet { top:2px; right:5%; width:205px; transform:rotate(2deg); border-radius:14px; }
        .problem-card--paper { bottom:6px; left:11%; width:190px; transform:rotate(1.5deg); border-radius:6px; padding:12px 14px; background:#fffefa; }
        .problem-card--manager { right:12%; bottom:22px; width:205px; transform:rotate(-1.5deg); border-radius:14px; padding:12px 14px; }
        .problem-card--chat,.problem-card--sheet,.problem-card--paper,.problem-card--manager { inset:auto; width:auto; }
        .problem-card--chat { transform:translateY(13px) rotate(-1.6deg); }
        .problem-card--sheet { transform:translateY(-5px) rotate(1.5deg); }
        .problem-card--paper { transform:translateY(30px) rotate(1deg); }
        .problem-card--manager { transform:translateY(17px) rotate(-1.2deg); }
        .problem-mono { color:#9a9ea6; font:500 8.5px/1.45 ui-monospace,SFMono-Regular,Menlo,monospace; }
        .problem-grid-cell { height:16px; border:1px solid #eef0f3; border-radius:3px; background:#f7f8fa; }
        .problem-app-head { display:flex; align-items:center; gap:8px; padding:9px 12px; border-bottom:1px solid #eef0f3; font-size:11px; font-weight:600; color:#3f4248; }
        .problem-app-icon { display:grid; width:18px; height:18px; flex:none; place-items:center; border-radius:6px; color:#fff; }
        .problem-app-icon svg { width:10px; height:10px; }
        .problem-chat-head { background:#f0f2f5; }
        .problem-chat-icon { background:#25d366; }
        .problem-chat-body { display:grid; gap:6px; padding:10px 12px; color:#18191b; }
        .problem-bubble { width:max-content; max-width:94%; padding:7px 11px; font-size:11.5px; }
        .problem-bubble--in { justify-self:start; border-radius:4px 12px 12px 12px; background:#f0f2f5; }
        .problem-bubble--out { justify-self:end; border-radius:12px 4px 12px 12px; background:#d9fdd3; }
        .problem-card-note { color:#9a9ea6; font:500 8.5px/1.4 ui-monospace,SFMono-Regular,Menlo,monospace; }
        .problem-chat-note { justify-self:end; }
        .problem-sheet-icon { background:#107c41; }
        .problem-sheet-name { min-width:0; overflow:hidden; color:#18191b; font:500 10px/1.3 ui-monospace,SFMono-Regular,Menlo,monospace; text-overflow:ellipsis; white-space:nowrap; }
        .problem-sheet-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:4px; padding:10px; }
        .problem-sheet-error { padding:0 12px 10px; color:#dc2626; }
        .problem-paper-title { padding-bottom:6px; border-bottom:1px dashed #d8dce2; color:#18191b; font-size:11.5px; font-weight:650; }
        .problem-paper-row { display:flex; align-items:center; gap:7px; margin-top:7px; color:#3f4248; font-size:11px; }
        .problem-paper-row.is-faint { color:#a1a7b0; }
        .problem-paper-box { display:grid; width:12px; height:12px; flex:none; place-items:center; border:1.5px solid #c4cad2; border-radius:3px; color:#18191b; font-size:9px; line-height:1; }
        .problem-paper-note { margin-top:8px; }
        .problem-manager-head { display:flex; align-items:center; gap:8px; }
        .problem-manager-avatar { display:grid; width:26px; height:26px; flex:none; place-items:center; border-radius:8px; background:#f3f6fa; color:#1d4ed8; font:600 9px/1 ui-monospace,SFMono-Regular,Menlo,monospace; }
        .problem-manager-name { display:block; color:#18191b; font-size:11.5px; font-weight:600; }
        .problem-manager-quote { margin-top:8px; color:#0a0a0b; font-size:12.5px; font-weight:550; font-style:italic; }
        .problem-manager-note { margin-top:6px; color:#dc2626; }
        .problem-chaos > svg { position:absolute; z-index:0; inset:0; display:block; width:100%; height:100%; pointer-events:none; }
        .problem-chaos > svg path { stroke:#2a3851; }
        @media (max-width:900px) {
            .problem-chaos { grid-template-columns:repeat(2,minmax(0,1fr)); max-width:620px; }
            .problem-card--chat,.problem-card--sheet,.problem-card--paper,.problem-card--manager { transform:none; }
            .problem-chaos > svg { display:none; }
        }
        @media (max-width:760px) {
            .problem-compact { padding:50px 0 54px; }
            .problem-chaos { grid-template-columns:repeat(2,minmax(0,1fr)); max-width:460px; gap:12px; margin-top:28px; }
        }
        @media (max-width:400px) { .problem-chaos { grid-template-columns:1fr; max-width:360px; } }
        .how-accordion { padding:76px 0 84px; background:#fff; }
        .how-accordion .how-kicker { color:#2563eb; }
        .how-accordion .how-kicker span { background:#93b4ff; }
        .how-accordion .how-heading { color:#020617; }
        .how-accordion .how-lead { color:#64748b; }
        .how-accordion-grid { display:grid; grid-template-columns:minmax(300px,.82fr) minmax(0,1.35fr); align-items:stretch; gap:clamp(42px,6vw,88px); margin-top:52px; }
        .how-steps { display:flex; min-height:100%; flex-direction:column; }
        .how-step { position:relative; flex:0 0 auto; border-top:1px solid #e6e8ec; transition:flex-grow .45s cubic-bezier(.22,.61,.21,1); }
        .how-step:last-child { border-bottom:1px solid #e6e8ec; }
        .how-step-button { display:grid; grid-template-columns:38px 1fr 24px; width:100%; align-items:center; gap:12px; padding:22px 0; border:0; background:transparent; color:#0a0a0b; text-align:left; cursor:pointer; }
        .how-step-number { color:#9a9ea6; font:500 11px/1 ui-monospace,SFMono-Regular,Menlo,monospace; letter-spacing:.08em; transition:color .3s ease; }
        .how-step-title { font-size:20px; font-weight:750; letter-spacing:-.025em; }
        .how-step-plus { position:relative; width:20px; height:20px; color:#9a9ea6; }
        .how-step-plus::before,.how-step-plus::after { content:""; position:absolute; top:9px; left:4px; width:12px; height:1.5px; border-radius:2px; background:currentColor; transition:transform .3s ease,color .3s ease; }
        .how-step-plus::after { transform:rotate(90deg); }
        .how-step-panel { display:grid; grid-template-rows:0fr; opacity:0; transition:grid-template-rows .45s cubic-bezier(.22,.61,.21,1),opacity .3s ease; }
        .how-step-panel > div { overflow:hidden; }
        .how-step-copy { padding:0 0 23px 50px; color:#62666d; font-size:14px; line-height:1.65; }
        .how-step-tags { display:flex; flex-wrap:wrap; gap:7px; margin-top:16px; }
        .how-step-tag { padding:4px 9px; border:1px solid #e6e8ec; border-radius:999px; background:#f7f8fa; color:#62666d; font:500 9px/1.4 ui-monospace,SFMono-Regular,Menlo,monospace; letter-spacing:.04em; text-transform:uppercase; }
        .how-step.is-active { flex:1 1 auto; }
        .how-step.is-active::before { content:""; position:absolute; top:-1px; left:0; width:100%; height:2px; background:linear-gradient(90deg,#2563eb,#6366f1 55%,transparent); }
        .how-step.is-active .how-step-number { color:#2563eb; }
        .how-step.is-active .how-step-plus { color:#2563eb; }
        .how-step.is-active .how-step-plus::after { transform:rotate(0); }
        .how-step.is-active .how-step-panel { grid-template-rows:1fr; opacity:1; }
        .how-visual { position:sticky; top:110px; min-width:0; padding:18px 0 28px; }
        .how-visual-stage { position:relative; }
        .how-visual-panel { position:absolute; top:0; left:0; width:100%; opacity:0; transform:translateY(14px) scale(.985); pointer-events:none; transition:opacity .42s ease,transform .5s cubic-bezier(.22,.61,.21,1); }
        .how-visual-panel.is-active { position:relative; z-index:2; opacity:1; transform:none; pointer-events:auto; }
        .how-browser { overflow:hidden; border:1px solid #e6e8ec; border-radius:16px; background:#fff; box-shadow:0 2px 6px rgba(10,12,18,.04),0 24px 64px -16px rgba(23,43,99,.16); }
        .how-browser-bar { display:flex; align-items:center; gap:7px; padding:9px 12px; border-bottom:1px solid #eef0f3; background:#fbfcfd; }
        .how-browser-dot { width:8px; height:8px; border-radius:50%; background:#e8eaee; }
        .how-browser-url { display:flex; align-items:center; gap:5px; margin:0 auto; padding:3px 10px; border:1px solid #eef0f3; border-radius:5px; color:#9a9ea6; font:500 9px/1.4 ui-monospace,SFMono-Regular,Menlo,monospace; }
        .how-screen-scroll img { display:block; width:100%; height:auto; }
        .how-screen-scroll--vertical { aspect-ratio:1.895/1; overflow-y:auto; overscroll-behavior:contain; background:#f7f8fa; scrollbar-width:thin; scrollbar-color:#cbd5e1 transparent; }
        .how-screen-scroll--vertical::-webkit-scrollbar { width:7px; }
        .how-screen-scroll--vertical::-webkit-scrollbar-track { background:transparent; }
        .how-screen-scroll--vertical::-webkit-scrollbar-thumb { border:2px solid transparent; border-radius:999px; background:#cbd5e1; background-clip:padding-box; }
        .how-trial-link { display:inline-flex; align-items:center; gap:8px; color:#2563eb; font-size:15px; font-weight:700; text-decoration:none; }
        .how-trial-link svg { transition:transform .25s ease; }
        .how-trial-link:hover svg { transform:translateX(4px); }
        .new-feature-section { padding:88px 0; }
        .new-feature-section.is-soft { background:#f7f8fa; }
        .new-ai-section { overflow:hidden; background:#030712; }
        .new-ai-section .new-feature-kicker { color:#7da7ff; }
        .new-ai-section .new-feature-kicker::before { background:#4979ea; }
        .new-ai-section .new-feature-title { color:#fff; }
        .new-ai-section .tc-text-reveal-base { color:#fff; }
        .new-ai-section .new-feature-lead { color:#aab4c5; }
        .new-ai-section .new-doc { border-color:#263247; background:#111827; box-shadow:inset 0 1px 0 rgba(255,255,255,.04),0 12px 28px -20px rgba(0,0,0,.8); }
        .new-ai-section .new-doc-icon { background:#1c2535; }
        .new-ai-section .new-doc strong { color:#f8fafc; }
        .new-ai-section .new-doc small { color:#8f9aab; }
        .new-ai-section .new-doc svg { color:#71819a; }
        .new-ai-section .new-ai-flow::before { background:rgba(96,165,250,.35); }
        .new-ai-section .new-ai-beam span { background:#2563eb; box-shadow:0 0 28px rgba(37,99,235,.45); }
        .new-ai-section .new-ai-caption { color:#71819a; }
        .new-feature-wrap { width:100%; max-width:1200px; margin:0 auto; padding:0 24px; }
        .new-feature-head { max-width:720px; }
        .new-feature-head.is-center { margin:0 auto; text-align:center; }
        .new-feature-kicker { display:inline-flex; align-items:center; gap:10px; color:#2563eb; font:600 11px/1.4 ui-monospace,SFMono-Regular,Menlo,monospace; letter-spacing:.14em; text-transform:uppercase; }
        .new-feature-kicker::before { content:""; width:22px; height:1px; background:#93b4ff; }
        .new-feature-title { margin:16px 0 0; color:#0a0a0b; font-size:clamp(32px,4vw,52px); font-weight:800; letter-spacing:-.04em; line-height:1.04; }
        .new-feature-grid .new-feature-title { font-size:clamp(32px,3.1vw,46px); line-height:1.12; }
        .new-feature-title-line { display:block; }
        .new-feature-lead { margin:17px 0 0; color:#62666d; font-size:17px; line-height:1.65; }
        .new-feature-grid { display:grid; grid-template-columns:minmax(300px,5fr) minmax(0,7fr); align-items:center; gap:clamp(36px,6vw,80px); }
        .new-feature-grid.is-visual-left { grid-template-columns:minmax(0,7fr) minmax(300px,5fr); }
        .new-browser { overflow:hidden; border:1px solid #e6e8ec; border-radius:16px; background:#fff; box-shadow:0 2px 6px rgba(10,12,18,.04),0 24px 64px -16px rgba(23,43,99,.16); }
        .new-browser-bar { display:flex; align-items:center; gap:7px; padding:9px 12px; border-bottom:1px solid #eef0f3; background:#fbfcfd; }
        .new-browser-bar i { width:8px; height:8px; border-radius:50%; background:#e8eaee; }
        .new-browser-bar span { margin:0 auto; color:#9a9ea6; font:500 9.5px/1.4 ui-monospace,SFMono-Regular,Menlo,monospace; }
        .new-browser img { display:block; width:100%; height:auto; }
        .new-points { display:grid; gap:12px; margin-top:26px; }
        .new-point { display:flex; align-items:flex-start; gap:10px; color:#18191b; font-size:14px; font-weight:600; }
        .new-check { display:grid; width:19px; height:19px; flex:none; place-items:center; border-radius:6px; background:#e7f6f0; color:#0f9f6e; font-size:12px; }
        .new-tags { display:flex; flex-wrap:wrap; gap:8px; margin-top:24px; }
        .new-tag { padding:6px 11px; border:1px solid #e6e8ec; border-radius:999px; background:#fff; color:#3f4248; font-size:12px; font-weight:600; }
        .evidence-types .new-tag { display:inline-flex; align-items:center; gap:8px; padding:8px 14px; color:#18191b; font-size:13px; font-weight:600; }
        .evidence-types .new-tag svg { width:15px; height:15px; flex:none; color:#2563eb; }
        .new-number-list { margin-top:26px; }
        .new-number-row { display:flex; gap:16px; padding:15px 0; border-top:1px solid #e6e8ec; }
        .new-number { color:#2563eb; font:600 11px/1.5 ui-monospace,SFMono-Regular,Menlo,monospace; }
        .new-number-row strong { display:block; font-size:15px; }
        .new-number-row p { margin-top:3px; color:#62666d; font-size:13px; line-height:1.55; }
        .new-shot { position:relative; }
        .new-float-chip { position:absolute; z-index:3; display:flex; align-items:center; gap:10px; padding:11px 14px; border:1px solid #e6e8ec; border-radius:13px; background:#fff; box-shadow:0 2px 6px rgba(10,12,18,.05),0 24px 60px -18px rgba(10,14,30,.3); }
        .new-float-chip strong { display:block; font-size:13px; }
        .new-float-chip small { color:#9a9ea6; font:500 8.5px/1.4 ui-monospace,SFMono-Regular,Menlo,monospace; }
        .new-ai-flow { position:relative; display:grid; grid-template-columns:minmax(0,1fr) 132px minmax(0,1fr); align-items:center; width:100%; max-width:1152px; gap:0; margin:52px auto 0; }
        .new-ai-flow::before { content:""; position:absolute; z-index:0; top:50%; left:42%; right:38%; height:2px; transform:translateY(-1px); background:#dfe9ff; }
        .new-ai-flow::after { content:""; position:absolute; z-index:1; top:calc(50% - 3px); left:42%; width:7px; height:7px; border-radius:50%; background:#2563eb; box-shadow:0 0 0 5px rgba(37,99,235,.1); animation:new-ai-pulse 2.8s ease-in-out infinite; }
        @keyframes new-ai-pulse { 0%{left:42%;opacity:0} 12%{opacity:1} 88%{opacity:1} 100%{left:62%;opacity:0} }
        .new-docs { display:grid; gap:11px; }
        .new-doc { display:flex; align-items:center; gap:10px; padding:11px 13px; border:1px solid #e6e8ec; border-radius:12px; background:#fff; }
        .new-doc-icon { display:grid; width:32px; height:32px; place-items:center; border-radius:9px; background:#f7f8fa; font-weight:800; }
        .new-doc strong { display:block; color:#18191b; font:600 10.5px/1.4 ui-monospace,SFMono-Regular,Menlo,monospace; }
        .new-doc small { color:#9a9ea6; font:500 9px/1.4 ui-monospace,SFMono-Regular,Menlo,monospace; }
        .new-ai-beam { position:relative; z-index:2; display:grid; place-items:center; }
        .new-ai-beam span { white-space:nowrap; padding:6px 11px; border-radius:999px; background:#0a0a0b; color:#fff; font-size:10px; font-weight:700; }
        .new-docs,.new-ai-result { position:relative; z-index:2; }
        .new-ai-result { position:relative; width:100%; min-width:0; margin:0; overflow:hidden; transform:rotate(1.4deg); border:1px solid #dfe9ff; border-radius:16px; background:#fff; box-shadow:0 4px 12px rgba(10,12,18,.05),0 28px 70px -22px rgba(37,99,235,.28); }
        .new-ai-result img { display:block; width:100%; height:auto; }
        .new-industry-grid { display:grid; grid-template-columns:repeat(6,1fr); gap:14px; margin-top:48px; }
        .new-industry { grid-column:span 2; display:flex; flex-direction:column; padding:21px; border:1px solid #e6e8ec; border-radius:16px; background:#fff; transition:.3s ease; }
        .new-industry:nth-child(-n+2) { grid-column:span 3; }
        .new-industry:hover { transform:translateY(-3px); border-color:#d7e2f7; box-shadow:0 12px 32px -12px rgba(23,43,99,.15); }
        .new-industry h3 { font-size:17px; font-weight:750; }
        .new-industry p { margin-top:9px; color:#62666d; font-size:13.5px; line-height:1.6; }
        .new-industry .new-tags { margin-top:auto; padding-top:15px; }
        .new-industry .new-tag { padding:4px 8px; border-color:#eef0f3; border-radius:7px; background:#f7f8fa; font-size:9px; }
        .new-report-points { display:grid; gap:19px; margin-top:27px; }
        .new-report-point { display:flex; gap:13px; }
        .new-report-icon { display:grid; width:36px; height:36px; flex:none; place-items:center; border:1px solid #e6e8ec; border-radius:10px; color:#2563eb; }
        .new-report-point strong { font-size:14.5px; }
        .new-report-point p { margin-top:2px; color:#62666d; font-size:13px; }
        .new-compare { display:grid; grid-template-columns:1fr 1fr; gap:16px; max-width:980px; margin:60px auto 0; }
        .new-compare-card { height:100%; padding:26px 24px; border:1px dashed #c9ced6; border-radius:16px; }
        .new-compare-card.is-new { position:relative; overflow:hidden; border:1px solid #dfe9ff; background:#fff; box-shadow:0 4px 12px rgba(10,12,18,.05),0 28px 70px -22px rgba(37,99,235,.25); }
        .new-compare-card.is-new::before { content:""; position:absolute; top:0; right:0; left:0; height:3px; background:linear-gradient(90deg,#2563eb,#4d79ff); }
        .new-compare-label { color:#9a9ea6; font:600 10px/1.4 ui-monospace,SFMono-Regular,Menlo,monospace; letter-spacing:.12em; text-transform:uppercase; }
        .new-compare-label-row { display:flex; align-items:center; gap:8px; }
        .new-compare-logo { display:grid; width:22px; height:22px; place-items:center; border-radius:7px; background:#2563eb; color:#fff; }
        .new-compare-logo svg { width:12px; height:12px; }
        .new-compare-card.is-new .new-compare-label { color:#1d4ed8; }
        .new-compare-list { display:grid; gap:4px; margin-top:18px; }
        .new-compare-item { display:flex; align-items:center; gap:10px; padding:9px 2px; color:#62666d; font-size:14.5px; }
        .new-compare-x { width:19px; flex:none; color:#ef4444; text-align:center; }
        .new-compare-card:not(.is-new) .new-compare-item { text-decoration-line:line-through; text-decoration-color:rgba(239,68,68,.62); text-decoration-thickness:1.5px; }
        .new-compare-card.is-new .new-compare-item { color:#0a0a0b; font-weight:600; }
        .new-value-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-top:72px; }
        .new-value { padding-top:16px; border-top:2px solid #2563eb; }
        .new-value strong { display:block; margin-top:9px; font-size:16px; }
        .new-value p { margin-top:5px; color:#62666d; font-size:13px; line-height:1.55; }
        @media(max-width:900px){.new-feature-grid,.new-feature-grid.is-visual-left{grid-template-columns:1fr}.new-ai-flow{grid-template-columns:1fr;gap:22px}.new-ai-flow::before,.new-ai-flow::after{display:none}.new-ai-beam{width:2px;height:48px;margin:auto;background:#dfe9ff}.new-ai-result{transform:none}.new-industry-grid{grid-template-columns:1fr 1fr}.new-industry,.new-industry:nth-child(-n+2){grid-column:span 1}.new-value-grid{grid-template-columns:1fr 1fr}}
        @media(max-width:600px){.new-feature-section{padding:56px 0}.new-feature-wrap{padding:0 16px}.new-industry-grid,.new-compare{grid-template-columns:1fr}.new-value-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:22px 14px;margin-top:48px}.new-float-chip{position:relative!important;inset:auto!important;margin-top:10px}.new-feature-lead{font-size:16px;line-height:1.6}.new-compare{margin-top:40px}.new-compare-card{padding:22px 18px}}
        @media(max-width:400px){.new-value-grid{grid-template-columns:1fr}}
        @media (max-width:900px) {
            .how-accordion-grid { grid-template-columns:1fr; gap:34px; }
            .how-steps { display:block; }
            .how-step.is-active { flex:none; }
            .how-visual { position:relative; top:auto; }
        }
        @media (max-width:640px) {
            .how-accordion { padding:58px 0 64px; }
            .how-accordion-grid { margin-top:36px; }
            .how-step-button { grid-template-columns:30px 1fr 22px; padding:18px 0; }
            .how-step-title { font-size:18px; }
            .how-step-copy { padding-left:42px; }
        }
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
        .task-stream { --task-step:76px;--task-start:39px;position:relative;height:440px;perspective:1000px;overflow:hidden;isolation:isolate;mask-image:linear-gradient(to bottom,transparent 0,#000 5%,#000 95%,transparent 100%); }
        .task-stream::before { content:"";position:absolute;z-index:0;inset:10% 3%;background:radial-gradient(ellipse at 50% 48%,rgba(79,70,229,.13),rgba(37,99,235,.06) 38%,transparent 70%);filter:blur(18px); }
        .task-stream::after { content:"";position:absolute;z-index:0;inset:7% 9%;background:repeating-linear-gradient(90deg,rgba(59,130,246,.1) 0 1px,transparent 1px 8px);opacity:.65;mask-image:linear-gradient(to bottom,transparent,#000 18%,#000 82%,transparent); }
        .task-stream-track { position:absolute;z-index:1;left:4%;right:4%;top:0;display:flex;flex-direction:column;gap:18px;will-change:transform;transition:transform .82s cubic-bezier(.45,0,.25,1); }
        .task-stream-track.is-resetting { transition:none; }
        .task-stream-track.is-resetting .task-stream-item { transition:none; }
        .task-stream-list { display:flex;flex-direction:column;gap:18px; }
        .task-stream-item { display:flex;height:58px;flex:none;align-items:center;gap:11px;padding:8px 15px;border:1px solid rgba(226,232,240,.9);border-radius:14px;background:rgba(255,255,255,.94);box-shadow:0 9px 25px -22px rgba(15,23,42,.3);opacity:0;transform:scaleX(.96);transition:opacity .5s ease,transform .5s ease,border-color .5s ease,box-shadow .5s ease;backdrop-filter:blur(14px); }
        .task-stream-item.is-edge { opacity:.46;transform:scaleX(.97); }
        .task-stream-item.is-adjacent { opacity:.7;transform:scaleX(.985); }
        .task-stream-item.is-active { opacity:1;transform:scaleX(1.045);border-color:rgba(96,165,250,.72);box-shadow:0 24px 52px -28px rgba(37,99,235,.42); }
        .task-stream-check { display:grid;height:34px;width:34px;flex:none;place-items:center;border:1.5px solid #cbd5e1;border-radius:10px;color:transparent;background:white;transform:scale(.92);transition:all .35s ease; }
        .task-stream-check path { stroke-dasharray:24;stroke-dashoffset:24;transition:stroke-dashoffset .32s .08s ease; }
        .task-stream-item.is-done .task-stream-check { color:#fff;background:#10b981;border-color:transparent;transform:scale(.94); }
        .task-stream-item.is-done .task-stream-check path,.task-stream-item.is-active.is-checked .task-stream-check path { stroke-dashoffset:0; }
        .task-stream-item.is-active.is-checked .task-stream-check { color:#fff;background:linear-gradient(135deg,#2563eb,#4f46e5);border-color:transparent;transform:scale(1);animation:task-check-pop .58s cubic-bezier(.2,.85,.25,1); }
        @keyframes task-check-pop { 0%{transform:scale(.78) rotate(-7deg);box-shadow:0 0 0 0 rgba(37,99,235,.32)} 48%{transform:scale(1.18) rotate(2deg);box-shadow:0 0 0 8px rgba(37,99,235,.12)} 100%{transform:scale(1) rotate(0);box-shadow:0 0 0 0 rgba(37,99,235,0)} }
        .task-stream-meta { border:1px solid #e2e8f0;border-radius:999px;background:#f8fafc;padding:3px 8px;color:#64748b;transition:all .35s ease; }
        .task-stream-item.is-done .task-stream-meta,.task-stream-item.is-active.is-checked .task-stream-meta { border-color:#a7f3d0;background:#ecfdf5;color:#047857;box-shadow:0 4px 12px -8px rgba(5,150,105,.45); }
        @media (max-width:639px) {
            .task-stream{--task-step:66px;--task-start:14px;height:340px}
            .task-stream::before{inset:8% 0}
            .task-stream::after{inset:7% 5%}
            .task-stream-track{left:0;right:0;gap:18px}
            .task-stream-list{gap:18px}
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
            .task-stream{height:auto;padding-top:3rem;mask-image:none}
            .task-stream::before{display:none}
            .task-stream::after{display:none}
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

<div class="welcome-page-glow" aria-hidden="true"></div>

{{-- ══════════════════════════════════════
     HERO — 2 kolommen: tekst links, screenshot rechts
══════════════════════════════════════ --}}
<section class="relative min-h-[720px] overflow-hidden bg-white pb-12 pt-28 sm:pt-32 lg:flex lg:min-h-[740px] lg:items-center lg:pb-16 lg:pt-32">
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
        <div class="grid items-center gap-12 lg:translate-y-16 lg:grid-cols-[1.05fr_.95fr] lg:gap-20">

            {{-- LINKS: tekst --}}
            <div class="min-w-0">
                <h1 class="text-4xl font-extrabold leading-[1.02] tracking-[-.045em] text-slate-900 sm:text-6xl xl:text-[4.3rem]">
                    Nooit meer discussie over
                    <x-text-reveal text="uitgevoerd werk" trigger="load" class="mt-1" />
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
                            Start gratis proefperiode
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
            <div class="relative min-h-[360px] sm:min-h-[420px] lg:flex lg:min-h-[520px] lg:items-center" aria-label="Live voorbeeld van taken die worden uitgevoerd">
                <div class="pointer-events-none absolute inset-x-[8%] inset-y-[3%] rounded-[3rem] bg-gradient-to-b from-blue-50/80 via-indigo-50/45 to-transparent blur-2xl"></div>
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
                <div class="task-stream mx-auto w-full max-w-[520px]" data-task-stream>
                    <div class="task-stream-track" data-task-stream-track>
                        @foreach (range(1, 3) as $copy)
                            <div class="task-stream-list" data-task-stream-list>
                                @foreach ($heroTasks as [$title, $description, $meta])
                                    <div class="task-stream-item" data-task-stream-item>
                                        <span class="task-stream-check" aria-hidden="true">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate text-sm font-bold text-slate-900 sm:text-base">{{ $title }}</span>
                                            <span class="mt-0.5 block truncate text-xs text-slate-500 sm:text-sm">{{ $description }}</span>
                                        </span>
                                        <span class="task-stream-meta hidden shrink-0 text-[11px] font-semibold sm:block">{{ $meta }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     PRODUCTOVERZICHT
══════════════════════════════════════ --}}
<section class="relative z-10 overflow-hidden pb-12 pt-8 sm:pb-16 sm:pt-10 lg:pb-24 lg:pt-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="product-showcase fade-up">
            <div class="product-chip" style="top:8%;left:-34px;transform:rotate(-2deg)">
                <span class="product-chip-icon bg-amber-50 text-amber-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.85 23.85 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.078 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg></span>
                <span><span class="product-chip-title">Wacht op beoordeling</span><span class="product-chip-meta">2 INZENDINGEN · OPENING KEUKEN</span></span>
            </div>
            <div class="product-chip" style="top:42%;left:-70px;transform:rotate(1.5deg)">
                <span class="product-chip-icon bg-emerald-50 text-emerald-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span>
                <span><span class="product-chip-title">3/3 taken · 100%</span><span class="product-chip-meta">OPENING KEUKEN · MILAN J.</span></span>
            </div>
            <div class="product-chip" style="top:12%;right:-6px;transform:rotate(2deg)">
                <span class="product-chip-icon bg-blue-50 text-blue-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l6-6 4 4L21 3.5M21 3.5h-6m6 0v6"/></svg></span>
                <span><span class="product-chip-title">Voltooiing 75%</span><span class="product-chip-meta">3 VAN 4 LIJSTEN AFGEROND</span></span>
            </div>
            <div class="product-chip" style="bottom:14%;left:-44px;transform:rotate(-1.5deg)">
                <span class="product-chip-icon bg-blue-50 text-blue-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 10.5a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                <span><span class="product-chip-title">Rotterdam Centrum</span><span class="product-chip-meta">LOCATIE ACTIEF · 4 LIJSTEN</span></span>
            </div>

            <div class="product-browser">
                <div class="product-browser-bar" aria-hidden="true">
                    <span class="product-browser-dot"></span><span class="product-browser-dot"></span><span class="product-browser-dot"></span>
                    <span class="product-browser-url"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><rect x="5" y="10.5" width="14" height="9.5" rx="2"/><path d="M8 10.5V7.8a4 4 0 018 0v2.7"/></svg>app.taskcheck.nl/dashboard</span>
                    <span class="product-browser-dot opacity-0"></span>
                </div>
                <img src="{{ asset('images/dashboard-product-showcase.jpg') }}" alt="TaskCheck-dashboard met overzicht van medewerkers, takenlijsten en inzendingen" loading="lazy" decoding="async" width="1440" height="1000" class="block h-auto w-full">
            </div>
            <div class="product-review-card">
                <img src="{{ asset('images/dashboard-review-strip.jpg') }}" alt="Inzending beoordelen in TaskCheck" loading="lazy" decoding="async" width="1140" height="305" class="block h-auto w-full">
            </div>
            <div class="product-showcase-fade" aria-hidden="true"></div>
        </div>
    </div>
</section>

{{-- Compact overzicht van versnipperde werkprocessen --}}
<section class="problem-compact">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="fade-up text-center">
            <p class="problem-kicker inline-flex items-center gap-3 text-[11px] font-bold uppercase tracking-[.2em]"><span class="h-px w-6"></span>Het probleem</p>
            <h2 class="problem-heading mx-auto mt-4 max-w-2xl text-3xl font-extrabold leading-[1.05] tracking-[-.035em] sm:text-4xl lg:text-5xl">Je kunt niet <x-text-reveal text="managen" /><br>wat je niet kunt zien.</h2>
            <p class="problem-lead mx-auto mt-4 max-w-2xl text-base leading-relaxed sm:text-lg">Zonder controle en bewijs loopt kwaliteit weg — en je weet het pas als er een klacht is.</p>
        </div>

        <div class="problem-chaos fade-up delay-1">
            <div class="problem-card problem-card--chat">
                <div class="problem-app-head problem-chat-head"><span class="problem-app-icon problem-chat-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a9 9 0 00-7.8 13.5L3 21l4.7-1.2A9 9 0 1012 3z"/></svg></span>WhatsApp · Team keuken</div>
                <div class="problem-chat-body"><span class="problem-bubble problem-bubble--in">Koeling al gecontroleerd?</span><span class="problem-bubble problem-bubble--out">Ja denk het wel 👍</span><span class="problem-card-note problem-chat-note">GEEN BEWIJS · 3 UNREAD THREADS</span></div>
            </div>

            <div class="problem-card problem-card--sheet">
                <div class="problem-app-head"><span class="problem-app-icon problem-sheet-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4.5" y="4" width="15" height="16" rx="1.5"/><path d="M4.5 9.5h15M10 9.5V20"/></svg></span><span class="problem-sheet-name">Checklist_v7_FINAL.xlsx</span></div>
                <div class="problem-sheet-grid">@foreach(range(1,9) as $cell)<span class="problem-grid-cell" @if($cell === 5) style="background:#fdeeee" @endif></span>@endforeach</div>
                <p class="problem-card-note problem-sheet-error">VERSIE-CONFLICT · WIE HAD DE LAATSTE?</p>
            </div>

            <div class="problem-card problem-card--paper">
                <p class="problem-paper-title">Opening checklist — papier</p>
                @foreach([['Koeling',true],['Werkbladen',true],['Voorraad — onleesbaar',false]] as [$label,$done])
                <p class="problem-paper-row {{ $done ? '' : 'is-faint' }}"><span class="problem-paper-box">{{ $done ? '×' : '' }}</span><span class="{{ $label === 'Werkbladen' ? 'line-through' : '' }}">{{ $label }}</span></p>
                @endforeach
                <p class="problem-card-note problem-paper-note">NIET DOORZOEKBAAR · NIET DEELBAAR</p>
            </div>

            <div class="problem-card problem-card--manager">
                <div class="problem-manager-head"><span class="problem-manager-avatar">JV</span><span><strong class="problem-manager-name">Manager</strong><span class="problem-card-note">LOCATIE ONBEKEND</span></span></div>
                <p class="problem-manager-quote">“Wie heeft dit gedaan?”</p><p class="problem-card-note problem-manager-note">NIEMAND WEET HET ZEKER</p>
            </div>

            <svg viewBox="0 0 1000 240" preserveAspectRatio="none" aria-hidden="true">
                <path d="M105 92 C205 28 300 142 395 88 S585 34 680 92 S825 145 900 76" fill="none" stroke="#dfe4eb" stroke-width="1.5" stroke-dasharray="3 8"/>
                <path d="M115 162 C230 218 330 128 455 174 S690 220 885 148" fill="none" stroke="#e5e9ef" stroke-width="1.5" stroke-dasharray="3 8"/>
            </svg>
        </div>

        <div class="fade-up flex w-full justify-center text-center">
            <span class="grid w-full min-w-0 max-w-md grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)] items-center gap-3 px-4 text-sm font-bold text-white"><span class="h-px min-w-0 bg-slate-600"></span><span class="whitespace-nowrap">TaskCheck brengt alles samen</span><span class="h-px min-w-0 bg-slate-600"></span></span>
        </div>
    </div>
</section>

{{-- Interactieve uitleg: accordion gekoppeld aan productvisual --}}
<section class="how-accordion" data-how-accordion>
    <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="fade-up text-center">
            <p class="how-kicker inline-flex items-center gap-3 text-[11px] font-bold uppercase tracking-[.2em]"><span class="h-px w-6"></span>Zo werkt het</p>
            <h2 class="how-heading mx-auto mt-4 max-w-3xl text-3xl font-extrabold leading-[1.05] tracking-[-.035em] sm:text-4xl lg:text-5xl">Van taak naar bewijs.<br>Van bewijs naar controle.</h2>
            <p class="how-lead mx-auto mt-4 max-w-2xl text-base leading-relaxed sm:text-lg">Maak checklists, laat je team taken uitvoeren en houd realtime controle over kwaliteit en bewijs.</p>
        </div>

        <div class="how-accordion-grid">
            <div class="how-steps fade-up" role="presentation">
                @foreach([
                    ['01','Maak checklists','Maak processen één keer goed en laat iedere locatie volgens dezelfde standaard werken. Bouw zelf, of importeer bestaande documenten met AI.',['AI-import','Bewijs verplicht','Terugkerend']],
                    ['02','Team voert uit','Medewerkers zien precies wat ze moeten doen — op telefoon of tablet — en voegen direct bewijs toe: foto, video, temperatuur of handtekening.',['Mobiel','Foto & video','Temperatuur']],
                    ['03','Jij houdt controle','Zie realtime welke locatie op schema ligt en waar actie nodig is. Beoordeel bewijs, stuur bij en exporteer rapportages per locatie of team.',['Realtime','Multi-locatie','Rapportage']],
                ] as $index => [$number,$title,$copy,$tags])
                <div class="how-step {{ $index === 0 ? 'is-active' : '' }}" data-how-step="{{ $index }}">
                    <button class="how-step-button" type="button" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="how-step-panel-{{ $index }}">
                        <span class="how-step-number">{{ $number }}</span><span class="how-step-title">{{ $title }}</span><span class="how-step-plus" aria-hidden="true"></span>
                    </button>
                    <div class="how-step-panel" id="how-step-panel-{{ $index }}">
                        <div><div class="how-step-copy"><p>{{ $copy }}</p><div class="how-step-tags">@foreach($tags as $tag)<span class="how-step-tag">{{ $tag }}</span>@endforeach</div></div></div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="how-visual fade-up delay-1" aria-live="polite">
                <div class="how-visual-stage">
                    @foreach([
                        ['app.taskcheck.nl/takenlijsten','images/how-it-works-takenlijsten.jpg','TaskCheck takenlijsten met Opening keuken, Temperatuurregistratie en Schoonmaak afsluiting'],
                        ['app.taskcheck.nl/uitvoering','images/how-it-works-uitvoering.jpg','TaskCheck uitvoering waarin een medewerker een checklist invult'],
                        ['app.taskcheck.nl/werkcontroles','images/how-it-works-beoordelen.jpg','TaskCheck inzending beoordelen met volledige voortgang'],
                    ] as $index => [$url,$image,$alt])
                    <div class="how-visual-panel {{ $index === 0 ? 'is-active' : '' }}" data-how-visual="{{ $index }}" @if($index !== 0) aria-hidden="true" @endif>
                        <div class="how-browser">
                            <div class="how-browser-bar" aria-hidden="true"><span class="how-browser-dot"></span><span class="how-browser-dot"></span><span class="how-browser-dot"></span><span class="how-browser-url"><svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><rect x="5" y="10.5" width="14" height="9.5" rx="2"/><path d="M8 10.5V7.8a4 4 0 018 0v2.7"/></svg>{{ $url }}</span><span class="how-browser-dot" style="opacity:0"></span></div>
                            <div class="how-screen-scroll {{ $index === 1 ? 'how-screen-scroll--vertical' : '' }}">
                                <img src="{{ asset($image) }}" alt="{{ $alt }}" loading="lazy" decoding="async">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="fade-up mt-10 text-center sm:mt-14">
            <a href="{{ route('register') }}" class="how-trial-link">
                Probeer het 14 dagen gratis
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
            </a>
        </div>
    </div>
</section>

@if(false)
{{-- ══════════════════════════════════════
     PROBLEEM
══════════════════════════════════════ --}}
<section class="py-14 sm:py-20 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="fade-up">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">Werk dat niet <x-text-reveal text="aantoonbaar" /> is, bestaat niet</h2>
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
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Ontworpen voor <x-text-reveal text="operationele teams" /></h2>
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
        <div class="stagger grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([
                ['Horeca App', 'Personeel, checklists en werkcontrole voor restaurants en cafés.', 'seo.horeca-app', '#2563eb'],
                ['Restaurant Checklist App', 'Opening, sluiting en HACCP digitaal afvinken.', 'seo.restaurant-checklist-app', '#4f46e5'],
                ['HACCP Formulieren', 'Stop met papier — registreer controles digitaal.', 'seo.haccp-formulieren', '#059669'],
                ['Temperatuurregistratie App', 'Koeling, vriezer en producten met foto bewijs.', 'seo.temperatuurregistratie-app', '#0891b2'],
            ] as $index => [$title, $desc, $route, $col])
            <div class="s-item flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:border-blue-200 hover:shadow-md">
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
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">Met TaskCheck heb je <x-text-reveal text="alles onder controle" /></h2>
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
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">
                In 3 stappen
                <x-text-reveal text="live" />
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
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">Alles wat je nodig hebt om werk <x-text-reveal text="onder controle" /> te houden</h2>
                <p class="mt-4 text-slate-500 text-lg leading-relaxed">Van bewijs per taak tot AI-checklists en rapportages — gebouwd voor teams die resultaat willen aantonen.</p>
                <div class="mt-8">
                    @guest
                    <a href="{{ route('register') }}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white shadow-md shadow-blue-200 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                        Start gratis proefperiode
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
@endif

{{-- Nieuwe productverhaal-secties --}}
<section class="new-feature-section is-soft">
    <div class="new-feature-wrap">
        <div class="new-feature-head is-center fade-up"><span class="new-feature-kicker">Realtime overzicht</span><h2 class="new-feature-title">Je hoeft niet meer te vragen of iets <x-text-reveal text="gedaan is" />.</h2><p class="new-feature-lead">Eén live dashboard voor al je locaties. Je ziet wat klaar is, wat loopt en wat aandacht nodig heeft — zonder iemand te bellen.</p></div>
        <div class="new-shot fade-up mt-12 sm:mt-14">
            <div class="new-browser"><div class="new-browser-bar"><i></i><i></i><i></i><span>app.taskcheck.nl/dashboard</span></div><img src="{{ asset('images/dashboard-product-showcase.jpg') }}" alt="TaskCheck dashboard — live voortgang per team en locatie"></div>
            <div class="new-float-chip hidden lg:flex" style="left:18%;top:48%"><span>⌖</span><span><strong>Locatie filter</strong><small>ALLE VESTIGINGEN</small></span></div>
            <div class="new-float-chip hidden lg:flex" style="right:8%;top:70%"><span>◷</span><span><strong>Wacht op beoordeling</strong><small>2 INZENDINGEN</small></span></div>
            <div class="new-float-chip hidden lg:flex" style="left:15%;bottom:8%"><span>◉</span><span><strong>Teamoverzicht</strong><small>RECENT &amp; REALTIME</small></span></div>
        </div>
    </div>
</section>

<section class="new-feature-section">
    <div class="new-feature-wrap new-feature-grid">
        <div class="fade-up"><div class="new-feature-head"><span class="new-feature-kicker">Bewijs per taak</span><h2 class="new-feature-title"><span class="new-feature-title-line">Niet alleen gedaan.</span><x-text-reveal text="Ook bewezen." /></h2><p class="new-feature-lead">Van schoonmaakcontrole tot HACCP-temperatuur: bewijs wordt automatisch gekoppeld aan de juiste taak, medewerker en locatie.</p></div>
            <div class="new-points">@foreach(['Verplicht bewijs per taak instelbaar','Tijdstip, medewerker en locatie automatisch vastgelegd','Direct beschikbaar bij klachten of inspecties'] as $point)<div class="new-point"><span class="new-check">✓</span>{{ $point }}</div>@endforeach</div>
            <p class="mt-7 text-[10px] font-bold uppercase tracking-[.14em] text-slate-400">Bewijstypen</p>
            <div class="new-tags evidence-types">
                <span class="new-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8a2 2 0 012-2h2l1.2-2h5.6L16 6h2a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V8z"/><circle cx="12" cy="13" r="3.5"/></svg>Foto</span>
                <span class="new-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="13" height="12" rx="2"/><path d="M16 10l5-3v10l-5-3z"/></svg>Video</span>
                <span class="new-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5a3 3 0 016 0v8.2a5 5 0 11-6 0V5z"/><path d="M12 7v8"/></svg>Temperatuur</span>
                <span class="new-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 6h14M5 10h14M5 14h9M5 18h7"/></svg>Tekst</span>
                <span class="new-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20l4.5-1 10-10a2.1 2.1 0 10-3-3l-10 10L4 20zM14 7l3 3"/></svg>Handtekening</span>
            </div>
        </div>
        <div class="new-shot fade-up"><div class="new-browser"><div class="new-browser-bar"><i></i><i></i><i></i><span>app.taskcheck.nl/werkcontroles</span></div><img src="{{ asset('images/how-it-works-beoordelen.jpg') }}" alt="TaskCheck — inzending beoordelen met voortgang 3 van 3 taken en beoordelingsacties"></div><div class="new-float-chip" style="left:-20px;bottom:7%"><span class="new-check">✓</span><span><strong>Keur alles goed (3)</strong><small>MILAN JANSEN · 3/3 TAKEN</small></span></div></div>
    </div>
</section>

<section class="new-feature-section is-soft">
    <div class="new-feature-wrap new-feature-grid is-visual-left">
        <div class="new-shot fade-up"><p class="mb-3 text-[10px] font-bold uppercase tracking-[.12em] text-slate-500">● Locatiekaart · live</p><div class="new-browser"><div class="new-browser-bar"><i></i><i></i><i></i><span>app.taskcheck.nl/instellingen/locaties</span></div><img src="{{ asset('images/product-locations.jpg') }}" alt="TaskCheck locatiekaart — vestigingen in Rotterdam op de kaart"></div><div class="new-float-chip" style="left:-18px;top:48%"><span>⌖</span><span><strong>Rotterdam Centrum</strong><small>HAVENSTRAAT 12 · 3011 AA</small></span></div><div class="new-float-chip" style="right:-10px;bottom:16%"><span class="new-check">✓</span><span><strong>2 van 2 locaties in gebruik</strong><small>CENTRUM · NOORD</small></span></div></div>
        <div class="fade-up"><div class="new-feature-head"><span class="new-feature-kicker">Multi-locatie</span><h2 class="new-feature-title"><span class="new-feature-title-line"><x-text-reveal text="Eén standaard." /></span>Iedere locatie.</h2><p class="new-feature-lead">Gebouwd voor franchises, ketens en organisaties met meerdere vestigingen — van 2 tot 200 locaties.</p></div>
            <div class="new-number-list">@foreach([['Centraal aanmaken','Bouw processen één keer en rol ze uit naar elke vestiging.'],['Lokaal uitvoeren','Elke locatie werkt volgens dezelfde standaard, met eigen planning.'],['Globaal monitoren','Vergelijk locaties in één dashboard en stuur bij waar nodig.']] as $index => [$title,$copy])<div class="new-number-row"><span class="new-number">0{{ $index+1 }}</span><div><strong>{{ $title }}</strong><p>{{ $copy }}</p></div></div>@endforeach</div>
        </div>
    </div>
</section>

<section class="new-feature-section new-ai-section">
    <div class="new-feature-wrap"><div class="new-feature-head is-center fade-up"><span class="new-feature-kicker">AI checklist-generator</span><h2 class="new-feature-title">Je bestaande werkwijze. <x-text-reveal text="Binnen minuten digitaal" />.</h2><p class="new-feature-lead">Upload een PDF, Excel of Word-document. TaskCheck AI zet het om naar een werkende checklist — jij controleert en publiceert.</p></div>
        <div class="new-ai-flow fade-up"><div class="new-docs">@foreach([['PDF','HACCP-handboek.pdf','2,4 MB','#c0392b'],['XLS','Opening-checklist.xlsx','84 KB','#107c41'],['DOC','Schoonmaakprotocol.docx','312 KB','#1d4ed8']] as [$icon,$name,$meta,$color])<div class="new-doc"><span class="new-doc-icon" style="color:{{ $color }};font-size:{{ $icon === 'PDF' ? '11px' : '9px' }}">{{ $icon }}</span><span><strong>{{ $name }}</strong><small>{{ $meta }}</small></span><svg class="ml-auto h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L7 9m5-5 5 5M5 16v3h14v-3"/></svg></div>@endforeach</div><div class="new-ai-beam"><span>✦ TaskCheck AI</span></div><div class="new-ai-result"><img src="{{ asset('images/product-ai-checklist.jpg') }}" alt="TaskCheck-checklist Opening keuken, actief op locatie Rotterdam Centrum"></div></div>
        <p class="new-ai-caption mt-8 text-center text-[10px] font-bold uppercase tracking-[.12em]">AI als versneller — jij houdt de regie</p>
    </div>
</section>

<section class="py-14 sm:py-20 lg:py-24 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mb-10 sm:mb-14 fade-up">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Ontworpen voor <x-text-reveal text="operationele teams" /></h2>
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
                    <img src="{{ asset('images/'.$img) }}" alt="{{ $alt }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
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

<section class="new-feature-section">
    <div class="new-feature-wrap new-feature-grid">
        <div class="fade-up"><div class="new-feature-head"><span class="new-feature-kicker">Rapportage &amp; audits</span><h2 class="new-feature-title">Als iemand bewijs vraagt, <x-text-reveal text="heb je het al" />.</h2><p class="new-feature-lead">Dagrapporten, weekoverzichten en een complete audit trail — ideaal voor HACCP, kwaliteitsaudits, klantverantwoording en interne controle.</p></div>
            <div class="new-report-points">@foreach([['◷','Dag- en weekrapporten','Automatisch gegenereerd per locatie, team of checklist.'],['◇','Audit trail','Wie, wat, wanneer en waar — volledig terug te volgen.'],['↓','PDF-export','Deel overzichten met klanten, managers of auditors.'],['▣','Bewijs inbegrepen','Foto’s, video’s en metingen zitten direct in het rapport.']] as [$icon,$title,$copy])<div class="new-report-point"><span class="new-report-icon">{{ $icon }}</span><div><strong>{{ $title }}</strong><p>{{ $copy }}</p></div></div>@endforeach</div>
        </div>
        <div class="new-shot fade-up"><div class="new-browser"><div class="new-browser-bar"><i></i><i></i><i></i><span>app.taskcheck.nl/rapportages</span></div><img src="{{ asset('images/product-reporting.jpg') }}" alt="TaskCheck rapportages — voltooiing 75 procent, teamscore en PDF-export"></div><div class="new-float-chip" style="right:0;bottom:9%"><span class="grid h-8 w-8 place-items-center rounded-lg bg-blue-600 text-white">↓</span><span><strong>PDF-rapport of ruwe data</strong><small>OVERZICHTELIJK · PER PERIODE</small></span></div></div>
    </div>
</section>

<section class="new-feature-section is-soft">
    <div class="new-feature-wrap"><div class="new-feature-head is-center fade-up"><span class="new-feature-kicker">Voor &amp; na</span><h2 class="new-feature-title">Excel, WhatsApp en papier zijn niet gebouwd voor <x-text-reveal text="werkcontrole" />.</h2><p class="new-feature-lead">TaskCheck wel.</p></div>
        <div class="new-compare">
            <div class="new-compare-card fade-up"><span class="new-compare-label">De oude manier</span><div class="new-compare-list">@foreach(['Berichten verspreid over tientallen chats','Geen bewijs van uitvoering','Elke locatie werkt anders','Handmatig achter taken aan','Geen centraal overzicht'] as $item)<div class="new-compare-item"><span class="new-compare-x"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></span>{{ $item }}</div>@endforeach</div></div>
            <div class="new-compare-card is-new fade-up"><div class="new-compare-label-row"><span class="new-compare-logo"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4 4L19 7"/></svg></span><span class="new-compare-label">Met TaskCheck</span></div><div class="new-compare-list">@foreach(['Eén centrale workflow voor al je teams','Foto-, video- en controlebewijs per taak','Gestandaardiseerde processen per locatie','Realtime status zonder na te vragen','Automatische rapportages en audit trail'] as $item)<div class="new-compare-item"><span class="new-check">✓</span>{{ $item }}</div>@endforeach</div></div>
        </div>
        <div class="new-value-grid">@foreach([['✓','Minder vergeten taken','Vaste checklists zorgen dat geen stap wordt overgeslagen.'],['◷','Sneller controleren','Managers zien realtime wat er speelt, zonder rondje langs locaties.'],['⌖','Meer grip op locaties','Elke vestiging werkt volgens dezelfde standaard.'],['◇','Auditbewijs direct klaar','Alle bewijzen staan geordend klaar voor elke inspectie.']] as [$icon,$title,$copy])<div class="new-value fade-up"><span class="text-blue-600">{{ $icon }}</span><strong>{{ $title }}</strong><p>{{ $copy }}</p></div>@endforeach</div>
    </div>
</section>

{{-- ══════════════════════════════════════
     SEO TEKST
══════════════════════════════════════ --}}
<section class="py-10 sm:py-12 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-8 lg:gap-12 items-start fade-up">

            {{-- Tekst --}}
            <div class="lg:col-span-2">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight mb-3">
                    Checklist app voor bedrijven die werkcontrole serieus nemen
                </h2>
                <div class="text-slate-500 leading-relaxed space-y-3 text-[15px]">
                    <p>Veel bedrijven worstelen dagelijks met dezelfde vraag: hoe weet je zeker dat het werk goed is gedaan? TaskCheck geeft operationele teams een eenvoudig antwoord. Met duidelijke checklists, verplicht bewijs per taak en een realtime dashboard hoef je niet meer op goed geloof te vertrouwen.</p>
                    <p>Voor de horeca biedt TaskCheck een complete <a href="{{ route('seo.horeca-checklist-app') }}" class="text-blue-600 font-semibold underline-offset-2 hover:underline">horeca checklist app</a> waarmee opening, HACCP-controles en sluitrondes gestandaardiseerd worden. Schoonmaakbedrijven profiteren van een <a href="{{ route('seo.schoonmaak-checklist-app') }}" class="text-blue-600 font-semibold underline-offset-2 hover:underline">checklist app voor schoonmaak</a> met bewijs per locatie en rapportages richting opdrachtgevers.</p>
                    <p>Ook buiten horeca en schoonmaak is de <a href="{{ route('seo.werkcontrole-app') }}" class="text-blue-600 font-semibold underline-offset-2 hover:underline">werkcontrole app</a> van TaskCheck breed inzetbaar. Met de <a href="{{ route('seo.takenlijst-personeel') }}" class="text-blue-600 font-semibold underline-offset-2 hover:underline">takenlijst voor personeel</a> weet iedereen precies wat er verwacht wordt.</p>
                </div>
                <div class="mt-5 flex flex-wrap gap-3">
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
            <div class="lg:pt-3">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Specifiek voor jouw branche</p>
                <div class="space-y-2">
                    @foreach([
                        ['Horeca checklist app',    route('seo.horeca-checklist-app'),    '#2563eb'],
                        ['Checklist schoonmaak',    route('seo.schoonmaak-checklist-app'), '#7c3aed'],
                        ['Werkcontrole app',        route('seo.werkcontrole-app'),         '#0891b2'],
                        ['Takenlijst personeel',    route('seo.takenlijst-personeel'),     '#059669'],
                    ] as [$label,$url,$col])
                    <a href="{{ $url }}"
                       class="flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl bg-white border border-slate-100 hover:border-slate-200 hover:shadow-sm transition-all group">
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
<section class="pt-8 pb-14 sm:pt-10 sm:pb-20 bg-slate-50 border-t border-slate-100">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12 fade-up">
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
                    Start gratis proefperiode
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
    var firstList = stream.querySelector('[data-task-stream-list]');
    var baseCount = firstList.querySelectorAll('[data-task-stream-item]').length;
    var activeIndex = 2;
    var checked = false;
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var checkTimer = null;
    var advanceTimer = null;
    var recycleTimer = null;

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

        if (activeIndex >= baseCount + 2) {
            window.clearTimeout(recycleTimer);
            recycleTimer = window.setTimeout(function recycleContinuousTrack() {
                track.classList.add('is-resetting');
                track.appendChild(track.firstElementChild);
                activeIndex -= baseCount;
                items = Array.from(stream.querySelectorAll('[data-task-stream-item]'));
                render();
                track.getBoundingClientRect();
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        track.classList.remove('is-resetting');
                    });
                });
            }, 900);
        }
    }

    function clearCycle() {
        window.clearTimeout(checkTimer);
        window.clearTimeout(advanceTimer);
        window.clearTimeout(recycleTimer);
        checkTimer = advanceTimer = recycleTimer = null;
    }

    function normalizeTrack() {
        track.classList.add('is-resetting');
        while (activeIndex >= baseCount + 2) {
            track.appendChild(track.firstElementChild);
            activeIndex -= baseCount;
        }
        items = Array.from(stream.querySelectorAll('[data-task-stream-item]'));
        checked = false;
        render();
        track.getBoundingClientRect();
        requestAnimationFrame(function () { track.classList.remove('is-resetting'); });
    }

    function scheduleCycle(delay) {
        checkTimer = window.setTimeout(function () {
            if (document.hidden) return;
            checked = true;
            render();
            advanceTimer = window.setTimeout(function () {
                advance();
                // De nieuwe actieve taak wordt direct na de schuifbeweging afgevinkt.
                scheduleCycle(850);
            }, 1150);
        }, delay);
    }

    render();
    window.addEventListener('resize', render, { passive: true });

    if (!reducedMotion) {
        scheduleCycle(850);
        document.addEventListener('visibilitychange', function () {
            clearCycle();
            if (!document.hidden) {
                normalizeTrack();
                scheduleCycle(850);
            }
        });
    }
})();

// Verticale "Zo werkt het"-accordion: elke stap stuurt de productvisual rechts aan.
(function initHowAccordion() {
    document.querySelectorAll('[data-how-accordion]').forEach(function (accordion) {
        var steps = Array.from(accordion.querySelectorAll('[data-how-step]'));
        var visuals = Array.from(accordion.querySelectorAll('[data-how-visual]'));

        function activate(index) {
            steps.forEach(function (step, stepIndex) {
                var active = stepIndex === index;
                step.classList.toggle('is-active', active);
                step.querySelector('button').setAttribute('aria-expanded', active ? 'true' : 'false');
            });
            visuals.forEach(function (visual, visualIndex) {
                var active = visualIndex === index;
                visual.classList.toggle('is-active', active);
                visual.setAttribute('aria-hidden', active ? 'false' : 'true');
            });
        }

        steps.forEach(function (step, index) {
            step.querySelector('button').addEventListener('click', function () { activate(index); });
        });
    });
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
var isLocalPreview = ['127.0.0.1', 'localhost', '::1'].includes(window.location.hostname);
if ('serviceWorker' in navigator && isLocalPreview) {
    navigator.serviceWorker.getRegistrations().then(function (registrations) {
        registrations.forEach(function (registration) { registration.unregister(); });
    });
    if ('caches' in window) {
        caches.keys().then(function (keys) {
            keys.forEach(function (key) { caches.delete(key); });
        });
    }
}
if ('serviceWorker' in navigator && !isLocalPreview) {
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
