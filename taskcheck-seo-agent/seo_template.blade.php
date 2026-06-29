<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = '{{SEO_TITLE}}';
        $seoDescription = '{{SEO_DESCRIPTION}}';
        $seoUrl = route('{{ROUTE_NAME}}');
        $seoImage = asset('{{SEO_IMAGE}}');
    @endphp

    <title>{{ $seoTitle }}</title>

    @include('components.head')

    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:image" content="{{ $seoImage }}">
</head>

<body class="bg-white text-slate-900 antialiased overflow-x-hidden">

@include('components.header')

<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">

            <div>

                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-xs font-semibold text-blue-700">
                    {{BADGE}}
                </div>

                <h1 class="text-4xl font-extrabold text-slate-900">
                    {{H1}}
                </h1>

                <p class="mt-6 text-lg text-slate-600">
                    {{INTRO}}
                </p>

                <div class="mt-8 flex gap-4">
                    <a href="/register"
                       class="rounded-xl bg-blue-600 px-6 py-3 text-white font-semibold">
                        Start gratis
                    </a>

                    <a href="/demo"
                       class="rounded-xl border px-6 py-3 font-semibold">
                        Plan demo
                    </a>
                </div>

            </div>

            <div>
                <img
                    src="{{HERO_IMAGE}}"
                    alt="{{H1}}"
                    class="rounded-3xl shadow-xl"
                >
            </div>

        </div>

    </div>

</section>

<section class="py-20">

    <div class="mx-auto max-w-5xl px-4">

        <h2 class="text-3xl font-bold mb-6">
            Waarom dit belangrijk is
        </h2>

        {{WHY_IMPORTANT}}

    </div>

</section>

<section class="py-20 bg-slate-50">

    <div class="mx-auto max-w-5xl px-4">

        <h2 class="text-3xl font-bold mb-6">
            Hoe TaskCheck helpt
        </h2>

        {{HOW_TASKCHECK_HELPS}}

    </div>

</section>

<section class="py-20">

    <div class="mx-auto max-w-5xl px-4">

        <h2 class="text-3xl font-bold mb-6">
            Belangrijkste functies
        </h2>

        {{FEATURES}}

    </div>

</section>

<section class="py-20 bg-slate-50">

    <div class="mx-auto max-w-5xl px-4">

        <h2 class="text-3xl font-bold mb-6">
            Voordelen
        </h2>

        {{BENEFITS}}

    </div>

</section>

<section class="py-20">

    <div class="mx-auto max-w-5xl px-4">

        <h2 class="text-3xl font-bold mb-10">
            Veelgestelde vragen
        </h2>

        {{FAQ}}

    </div>

</section>

<section class="py-20 bg-blue-600 text-white">

    <div class="mx-auto max-w-4xl text-center px-4">

        <h2 class="text-4xl font-bold mb-6">
            Klaar om te starten?
        </h2>

        <p class="text-lg mb-8">
            {{CTA}}
        </p>

        <a href="/register"
           class="rounded-xl bg-white text-blue-600 px-8 py-4 font-bold">
            Start gratis
        </a>

    </div>

</section>

@include('components.footer')

</body>
</html>