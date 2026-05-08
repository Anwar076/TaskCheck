<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Horeca checklist app voor restaurants en keukens | TaskCheck';
        $seoDescription = 'Horeca checklist app voor restaurants: taken beheren, personeel controleren en bewijs verzamelen met foto en video. Start 14 dagen gratis.';
        $seoUrl = route('seo.horeca-checklist-app');
        $seoImage = asset('images/taskcheck-horeca-seo-hero.webp');
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
<body class="bg-white min-h-screen font-sans text-slate-900 antialiased">
@include('components.header')

{{-- HERO --}}
<section class="relative bg-gradient-to-br from-orange-600 via-red-600 to-rose-800 text-white overflow-hidden pt-28 pb-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-80 h-80 bg-orange-300 rounded-full translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-rose-300 rounded-full -translate-x-1/3 translate-y-1/3"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Horeca checklist app</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Horeca checklist app voor restaurants en keukens</h1>
                <p class="mt-5 text-lg text-orange-100 leading-relaxed max-w-xl">Opening, mise-en-place, service en sluiting altijd consistent. Met TaskCheck weet iedereen in je team wat te doen en wanneer, met bewijs per taak.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-orange-700 font-bold px-6 py-3 hover:bg-orange-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-white/30 bg-white/10 text-white font-semibold px-6 py-3 hover:bg-white/20 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-orange-200">Geen creditcard nodig · Gratis proefperiode</p>
            </div>
            <div class="mt-12 lg:mt-0">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
                    <img src="{{ asset('images/taskcheck-horeca-seo-hero.webp') }}"
                         alt="Horeca checklist app van TaskCheck – dashboard en mobiele taakcontrole voor restaurants"
                         class="w-full object-cover" loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div><p class="text-3xl font-extrabold text-orange-600">Per shift</p><p class="text-sm text-slate-500 mt-1">taken per dienst ingesteld</p></div>
        <div><p class="text-3xl font-extrabold text-orange-600">HACCP</p><p class="text-sm text-slate-500 mt-1">controles met bewijs</p></div>
        <div><p class="text-3xl font-extrabold text-orange-600">Realtime</p><p class="text-sm text-slate-500 mt-1">voortgang per team</p></div>
        <div><p class="text-3xl font-extrabold text-orange-600">Mobiel</p><p class="text-sm text-slate-500 mt-1">op de vloer, geen laptop</p></div>
    </div>
</section>

<main class="pb-20">
    <div class="max-w-6xl mx-auto px-6">

        {{-- GRIP OP TAKEN --}}
        <section class="mt-16 lg:grid lg:grid-cols-2 lg:gap-16 lg:items-start">
            <div>
                <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">Dagelijkse operatie</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Grip op dagelijkse horeca taken</h2>
                <p class="mt-4 text-slate-600 leading-relaxed">In horeca draait kwaliteit op ritme. Als de opening niet strak loopt of de sluiting half gedaan wordt, voel je dat direct in service, hygiëne en klantbeleving.</p>
                <p class="mt-3 text-slate-600 leading-relaxed">Met TaskCheck werk je met vaste takenlijsten per shift. Daardoor weet iedereen wat er moet gebeuren en wanneer.</p>
                <div class="mt-5 grid grid-cols-2 gap-3">
                    @php $shifts = [['☀️','Opening','Alles klaar voor service.'],['🍽️','Service','Kwaliteit op de vloer.'],['🌙','Sluiting','Hygiëne en veiligheid.'],['🔍','Controle','HACCP en bewijs.']]; @endphp
                    @foreach($shifts as $sh)
                    <div class="bg-orange-50 rounded-xl border border-orange-100 p-3 text-center">
                        <span class="text-xl">{{ $sh[0] }}</span>
                        <p class="font-bold text-slate-900 text-sm mt-1">{{ $sh[1] }}</p>
                        <p class="text-xs text-slate-500">{{ $sh[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-10 lg:mt-0 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <p class="font-semibold text-slate-900 mb-3 text-sm">Personeel controleren zonder discussie</p>
                    <p class="text-sm text-slate-600 leading-relaxed">Voor kritieke taken kun je foto- of videobewijs verplicht maken. Denk aan temperatuurcontrole, schoonmaak van werkstations of voorraadchecks. Managers krijgen realtime inzicht per locatie of team.</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <p class="font-semibold text-slate-900 mb-3 text-sm">Waarom horeca teams kiezen voor TaskCheck</p>
                    <ul class="space-y-1.5">
                        @foreach(['Mobiel werken op de vloer, geen laptop','Snelle afhandeling per taak','Duidelijk dashboard voor leidinggevenden','Sneller inwerken nieuw personeel','Consistent, ook bij personeelswisselingen'] as $item)
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <span class="w-4 h-4 bg-emerald-100 rounded flex items-center justify-center flex-shrink-0"><svg class="w-2.5 h-2.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        {{-- REVIEWS --}}
        <section class="mt-20 bg-gradient-to-br from-orange-50 to-red-50 rounded-3xl p-8 sm:p-12">
            <div class="text-center max-w-2xl mx-auto mb-8">
                <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">Ervaringen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Wat zeggen horeca teams?</h2>
            </div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <p class="text-slate-700 italic leading-relaxed">"Onze opening en sluiting gaan nu altijd goed. Iedereen weet precies wat er moet gebeuren, ook nieuwe medewerkers."</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-orange-600 flex items-center justify-center text-white font-bold text-sm">R</div>
                        <div><p class="text-sm font-semibold text-slate-900">Roel, horecamanager</p><p class="text-xs text-slate-500">Restaurant, 2 locaties</p></div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <p class="text-slate-700 italic leading-relaxed">"HACCP-controles zijn nu altijd gedocumenteerd. Dat scheelt ons veel tijd bij de audit."</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-red-600 flex items-center justify-center text-white font-bold text-sm">L</div>
                        <div><p class="text-sm font-semibold text-slate-900">Lisa, keukenmanager</p><p class="text-xs text-slate-500">Horecabedrijf, 8 medewerkers</p></div>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-20 text-center">
            <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-3xl p-10 sm:p-14 text-white shadow-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Start vandaag gratis</h2>
                <p class="mt-3 text-lg text-orange-100 max-w-xl mx-auto">Probeer TaskCheck 14 dagen gratis. Geen creditcard nodig.</p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-orange-700 font-bold px-8 py-4 text-lg hover:bg-orange-50 transition shadow-lg">Start 14 dagen gratis</a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center rounded-xl border-2 border-white/40 text-white font-semibold px-8 py-4 text-lg hover:bg-white/10 transition">Bekijk prijzen</a>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mt-8 max-w-3xl mx-auto space-y-3">
                @php $faqs = [['Werkt dit ook voor kleine horecazaken?','Ja, ook met 3-4 medewerkers helpt een horeca checklist app om structuur te houden. Je begint met je belangrijkste lijst en groeit daarna.'],['Kan ik HACCP-controles vastleggen?','Ja. Je kunt bewijs zoals foto, video of tekst verplicht maken voor kritieke controles. Dat geeft aantoonbaar overzicht bij audits.'],['Kan ik taken per rol instellen?','Zeker. Je kunt taken opdelen per rol: keuken, bar, bediening of teamleider. Elk team ziet alleen zijn eigen taken.'],['Werkt de app op mobiel?','Ja, TaskCheck werkt op elke smartphone. Medewerkers hebben geen laptop nodig.']]; @endphp
                @foreach($faqs as $faq)
                <details class="group bg-white border border-slate-200 rounded-2xl px-6 py-4 cursor-pointer hover:border-orange-200 transition">
                    <summary class="flex justify-between items-center font-semibold text-slate-900 list-none">
                        {{ $faq[0] }}
                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0 group-open:rotate-45 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </summary>
                    <p class="mt-3 text-slate-600 leading-relaxed text-sm">{{ $faq[1] }}</p>
                </details>
                @endforeach
            </div>
        </section>

        {{-- INTERNE LINKS --}}
        <section class="mt-16 mb-4">
            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6">
                <p class="font-semibold text-slate-900 mb-3">Gerelateerde pagina's</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('seo.horeca-app-personeel') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Horeca app personeel</a>
                    <a href="{{ route('seo.werkcontrole-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Werkcontrole app</a>
                    <a href="{{ route('seo.checklist-app-schoonmaak') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Checklist app schoonmaak</a>
                    <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Blog: horeca personeel controleren</a>
                </div>
            </div>
        </section>
    </div>
</main>

@include('components.footer')
</body>
</html>
