<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Horeca app personeel voor checklists en werkcontrole | TaskCheck';
        $seoDescription = 'Horeca app personeel: plan taken per shift, controleer uitvoering met bewijs en houd grip op kwaliteit in restaurant, keuken en bediening.';
        $seoUrl = route('seo.horeca-app-personeel');
        $seoImage = asset('images/taskcheck-horeca-personeel-seo-hero.webp');
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
<section class="relative bg-gradient-to-br from-amber-600 via-orange-600 to-red-700 text-white overflow-hidden pt-28 pb-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-72 h-72 bg-amber-300 rounded-full -translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-red-300 rounded-full translate-x-1/3 translate-y-1/3"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">Horeca app personeel</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Horeca app personeel voor dagelijkse taken en controle</h1>
                <p class="mt-5 text-lg text-amber-100 leading-relaxed max-w-xl">TaskCheck helpt horeca teams om taken per dienst duidelijk te verdelen, uit te voeren en te controleren. Per rol, per shift, met bewijs.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-white text-orange-700 font-bold px-6 py-3 hover:bg-orange-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start 14 dagen gratis
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center gap-2 rounded-xl border border-white/30 bg-white/10 text-white font-semibold px-6 py-3 hover:bg-white/20 transition">
                        Bekijk prijzen
                    </a>
                </div>
                <p class="mt-4 text-sm text-amber-200">Geen creditcard nodig · Direct aan de slag</p>
            </div>
            <div class="mt-12 lg:mt-0">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white/20">
                    <img src="{{ asset('images/taskcheck-horeca-personeel-seo-hero.webp') }}"
                         alt="Horeca app personeel – TaskCheck takenlijsten voor keuken, bar en bediening"
                         class="w-full object-cover" loading="eager">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div><p class="text-3xl font-extrabold text-orange-600">Per rol</p><p class="text-sm text-slate-500 mt-1">keuken · bar · bediening</p></div>
        <div><p class="text-3xl font-extrabold text-orange-600">Per shift</p><p class="text-sm text-slate-500 mt-1">opening · service · sluiting</p></div>
        <div><p class="text-3xl font-extrabold text-orange-600">HACCP</p><p class="text-sm text-slate-500 mt-1">aantoonbaar gedocumenteerd</p></div>
        <div><p class="text-3xl font-extrabold text-orange-600">Realtime</p><p class="text-sm text-slate-500 mt-1">bijsturen zonder te bellen</p></div>
    </div>
</section>

<main class="pb-20">
    <div class="max-w-6xl mx-auto px-6">

        {{-- HOE HET WERKT --}}
        <section class="mt-16">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">Werkwijze</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Een horeca check app die op de vloer werkt</h2>
                <p class="mt-3 text-slate-500">In een restaurant wisselt de druk per moment. Vaste workflows houden kwaliteit stabiel.</p>
            </div>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php $hoe = [['⚙️','Workflows instellen','Opening, mise-en-place, service en sluiting als vaste workflows.'],['👤','Taken toewijzen','Medewerkers zien direct hun verantwoordelijkheden per dienst.'],['📸','Bewijs toevoegen','Foto of tekst bij kritieke taken: temperatuur, schoonmaak, voorraad.'],['👁️','Live bijsturen','Teamleiders zien direct waar taken blijven liggen.'],['📊','Trends bekijken','Zie welke taken vaak te laat of onvolledig zijn.'],['🎓','Snel inwerken','Nieuwe medewerkers volgen dezelfde workflows.']]; @endphp
                @foreach($hoe as $h)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center hover:border-orange-200 hover:shadow-md transition">
                    <span class="text-2xl">{{ $h[0] }}</span>
                    <h3 class="mt-2 font-bold text-slate-900 text-sm">{{ $h[1] }}</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $h[2] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        {{-- RESTAURANT CHECKLIST --}}
        <section class="mt-20 bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl p-8 sm:p-12">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                <div>
                    <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">Per afdeling</span>
                    <h2 class="mt-2 text-3xl font-bold text-slate-900">Restaurant checklist app voor keuken, bar en bediening</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">Per taak kun je instructies, deadlines en verplicht bewijs instellen. Temperatuurcontrole, HACCP-rondes, schoonmaak van werkstations en voorraadcontrole.</p>
                    <p class="mt-3 text-slate-600 leading-relaxed">Bewijs wordt centraal opgeslagen. Handig bij kwaliteitsgesprekken, audits en onboarding van nieuw personeel.</p>
                </div>
                <div class="mt-8 lg:mt-0 grid grid-cols-2 gap-3">
                    @foreach(['🍳 Keuken','🍸 Bar','🍽️ Bediening','🔑 Opening','🌙 Sluiting','🔍 HACCP'] as $afd)
                    <div class="bg-white rounded-xl border border-orange-100 shadow-sm px-4 py-3 text-center font-semibold text-slate-800 text-sm hover:border-orange-300 transition">{{ $afd }}</div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- REVIEW --}}
        <section class="mt-20">
            <div class="text-center max-w-2xl mx-auto mb-8">
                <span class="text-orange-600 text-sm font-semibold uppercase tracking-wide">Ervaringen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Wat zeggen horeca teams?</h2>
            </div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <p class="text-slate-700 italic leading-relaxed">"Alle medewerkers werken nu hetzelfde. Geen discussies meer over wie wat gedaan heeft."</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-amber-600 flex items-center justify-center text-white font-bold text-sm">J</div>
                        <div><p class="text-sm font-semibold text-slate-900">Jasper, restaurantmanager</p><p class="text-xs text-slate-500">3 vestigingen</p></div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <p class="text-slate-700 italic leading-relaxed">"Bij onze inspectie hadden we alles gedocumenteerd. Dat gaf ons veel vertrouwen."</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-orange-600 flex items-center justify-center text-white font-bold text-sm">A</div>
                        <div><p class="text-sm font-semibold text-slate-900">Anita, horecaondernemer</p><p class="text-xs text-slate-500">Hotel restaurant</p></div>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-20 text-center">
            <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-3xl p-10 sm:p-14 text-white shadow-xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Klaar voor meer controle?</h2>
                <p class="mt-3 text-lg text-amber-100 max-w-xl mx-auto">Probeer TaskCheck 14 dagen gratis en zie direct het verschil.</p>
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
                @php $faqs = [['Wat is een horeca app voor personeel precies?','Een digitale takenomgeving waarin medewerkers per dienst zien wat er moet gebeuren. Denk aan opening, keukencontrole, schoonmaak en sluiting met duidelijke deadlines en bewijs per taak.'],['Kan ik keuken, bar en bediening apart aansturen?','Ja. In TaskCheck kun je takenlijsten opdelen per rol, team of locatie. Elk team ziet alleen de taken die voor die dienst relevant zijn.'],['Helpt dit ook bij HACCP en kwaliteitscontroles?','Ja. Je kunt bewijs zoals foto, video en notities verplicht maken voor kritieke controles. Daardoor heb je aantoonbaar overzicht bij audits.'],['Hoe snel kan ik starten?','Direct. Na registratie maak je je eerste checklist in een paar minuten. Geen installatie of training nodig.']]; @endphp
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
                    <a href="{{ route('seo.horeca-checklist-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Horeca checklist app</a>
                    <a href="{{ route('seo.takenlijst-personeel') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Takenlijst personeel</a>
                    <a href="{{ route('seo.werkcontrole-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Werkcontrole app</a>
                    <a href="{{ route('blog.horeca-personeel-controleren-checklist-app') }}" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-blue-700 font-medium hover:border-blue-300 hover:bg-blue-50 transition">Blog: personeel controleren</a>
                </div>
            </div>
        </section>
    </div>
</main>

@include('components.footer')
</body>
</html>
