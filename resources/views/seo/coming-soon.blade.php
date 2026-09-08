@php
    $seoTitle = $seoTitle ?? 'Binnenkort beschikbaar | TaskCheck';
        $seoDescription = $seoDescription ?? 'Deze pagina wordt binnenkort gepubliceerd. Ontdek intussen TaskCheck voor digitale checklists en HACCP-registratie in de horeca.';
        $seoUrl = $seoUrl ?? url()->current();
        $pageTitle = $pageTitle ?? 'Binnenkort beschikbaar';
        $robots = 'noindex,follow';
        $showCta = false;
@endphp

@extends('layouts.seo-page')

@section('content')
<section class="relative overflow-hidden bg-white pt-24 pb-20 sm:pt-32 sm:pb-28">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.08)_0%,transparent_65%)]"></div>
    </div>
    <div class="relative mx-auto max-w-2xl px-4 text-center sm:px-6">
        <p class="blog-kicker fade-up mb-6">Binnenkort beschikbaar</p>
        <h1 class="fade-up delay-1 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">{{ $pageTitle }}</h1>
        <p class="fade-up delay-2 mt-4 text-lg leading-relaxed text-slate-500">
            Deze pagina is binnenkort beschikbaar. We werken aan uitgebreide informatie over dit onderwerp.
        </p>
        <p class="fade-up delay-2 mt-3 text-sm text-slate-400">
            In de tussentijd kun je TaskCheck gratis uitproberen voor digitale checklists en HACCP-registratie.
        </p>
        <div class="fade-up mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="{{ route('seo.haccp-app') }}" class="inline-flex min-h-[3rem] items-center justify-center rounded-2xl border border-[#e6e8ec] bg-white px-6 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition hover:border-[#d7e2f7] hover:bg-slate-50">
                HACCP app voor horeca
            </a>
            @auth
                <a href="{{ auth()->user()->homeDashboardUrl() }}" class="cta-btn inline-flex min-h-[3rem] items-center justify-center rounded-2xl px-6 py-3.5 text-sm font-bold text-white shadow-lg">Naar dashboard</a>
            @else
                <a href="{{ route('register') }}" class="cta-btn inline-flex min-h-[3rem] items-center justify-center rounded-2xl px-6 py-3.5 text-sm font-bold text-white shadow-lg">Start 14 dagen gratis</a>
            @endauth
        </div>
    </div>
</section>
@endsection
