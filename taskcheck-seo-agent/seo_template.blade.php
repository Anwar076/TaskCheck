{{--
  Legacy placeholder. Nieuwe SEO-pagina's worden gegenereerd via PageWriter
  en moeten altijd @extends('layouts.seo-page') gebruiken.
  Dit bestand mag niet meer als standalone HTML-template worden gebruikt.
--}}
@php
    $seoTitle = '{{SEO_TITLE}}';
    $seoDescription = '{{SEO_DESCRIPTION}}';
    $seoKeywords = '{{SEO_KEYWORDS}}';
    $seoUrl = route('{{ROUTE_NAME}}');
    $seoImage = asset('{{SEO_IMAGE}}');
    $faqItems = [];
    $ctaHeading = '{{CTA_HEADING}}';
    $ctaLead = '{{CTA_LEAD}}';
    $seoTheme = '{{SEO_THEME}}';
@endphp

@extends('layouts.seo-page')

@section('content')
<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="blog-kicker mb-6">{{BADGE}}</p>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">{{H1}}</h1>
        <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:text-lg">{{INTRO}}</p>
    </div>
</section>
@endsection
