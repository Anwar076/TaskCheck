<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = $seoTitle ?? 'Binnenkort beschikbaar | TaskCheck';
        $seoDescription = $seoDescription ?? 'Deze pagina wordt binnenkort gepubliceerd. Ontdek intussen TaskCheck voor digitale checklists en HACCP-registratie in de horeca.';
        $seoUrl = $seoUrl ?? url()->current();
        $pageTitle = $pageTitle ?? 'Binnenkort beschikbaar';
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="noindex,follow">
    <link rel="canonical" href="{{ $seoUrl }}">
    <style>
        .cta-btn { background: linear-gradient(135deg, #2563eb, #4f46e5); }
        .cta-btn:hover { background: linear-gradient(135deg, #1d4ed8, #4338ca); }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased overflow-x-hidden">
@include('components.header')

<section class="relative overflow-hidden bg-white pt-24 pb-20 sm:pt-32 sm:pb-28">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.08)_0%,transparent_65%)]"></div>
    </div>
    <div class="relative mx-auto max-w-2xl px-4 text-center sm:px-6">
        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-1.5 text-xs font-semibold text-amber-800">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            Binnenkort beschikbaar
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">{{ $pageTitle }}</h1>
        <p class="mt-4 text-lg leading-relaxed text-slate-500">
            Deze pagina is binnenkort beschikbaar. We werken aan uitgebreide informatie over dit onderwerp.
        </p>
        <p class="mt-3 text-sm text-slate-400">
            In de tussentijd kun je TaskCheck gratis uitproberen voor digitale checklists en HACCP-registratie.
        </p>
        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="{{ route('seo.haccp-app') }}" class="inline-flex min-h-[3rem] items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                HACCP app voor horeca
            </a>
            @auth
                <a href="{{ url('/dashboard') }}" class="cta-btn inline-flex min-h-[3rem] items-center justify-center rounded-xl px-6 py-3.5 text-sm font-bold text-white shadow-lg">Naar dashboard</a>
            @else
                <a href="{{ route('register') }}" class="cta-btn inline-flex min-h-[3rem] items-center justify-center rounded-xl px-6 py-3.5 text-sm font-bold text-white shadow-lg">Start 14 dagen gratis</a>
            @endauth
        </div>
    </div>
</section>

@include('components.footer')
</body>
</html>
