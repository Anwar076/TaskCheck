<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Contact over checklist app voor bedrijven | TaskCheck';
        $seoDescription = 'Neem contact op met TaskCheck. Vraag een demo aan of stel je vraag over digitale checklists, HACCP en werkcontrole. We reageren meestal binnen één werkdag.';
        $seoUrl = route('contact');
        $seoImage = asset('images/taskcheck-platform-overview.webp');
        $headerDark = false;
    @endphp
    <title>{{ $seoTitle }}</title>
    @include('components.head')
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{ $seoUrl }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="nl_NL">
    <meta property="og:site_name" content="TaskCheck">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    @if(filled(config('services.recaptcha.site_key')))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}&hl=nl" async defer></script>
    @endif
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "ContactPage",
        "name": "Contact TaskCheck",
        "url": "{{ $seoUrl }}",
        "description": "{{ $seoDescription }}",
        "mainEntity": {
            "@@type": "Organization",
            "name": "TaskCheck",
            "telephone": "+31881900999",
            "openingHoursSpecification": [{
                "@@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                "opens": "09:00",
                "closes": "17:00"
            }],
            "contactPoint": [
                {
                    "@@type": "ContactPoint",
                    "contactType": "customer support",
                    "email": "support@taskcheck.nl",
                    "telephone": "+31881900999",
                    "availableLanguage": ["nl", "en"]
                },
                {
                    "@@type": "ContactPoint",
                    "contactType": "sales",
                    "email": "info@taskcheck.nl",
                    "telephone": "+31881900999",
                    "availableLanguage": ["nl", "en"]
                }
            ]
        }
    }
    </script>
    <style>
        .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .55s ease, transform .55s ease; }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
        .delay-1.visible { transition-delay: .1s; }
        .delay-2.visible { transition-delay: .2s; }
        .delay-3.visible { transition-delay: .3s; }
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
        .contact-field {
            width: 100%; border-radius: 14px; border: 1px solid #e6e8ec; background: #fff;
            padding: 0.85rem 1rem; font-size: 0.9375rem; color: #0f172a;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .contact-field:hover { border-color: #d7e2f7; }
        .contact-field:focus {
            outline: none; border-color: #93b4ff;
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }
        .contact-label {
            display: block; margin-bottom: 0.4rem;
            font-size: 0.8125rem; font-weight: 600; color: #334155;
        }
        .contact-select {
            appearance: none; -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 0.85rem center; background-size: 1.1rem;
            padding-right: 2.5rem; cursor: pointer;
        }
        .grecaptcha-badge { z-index: 40; bottom: 100px !important; }
        @media (prefers-reduced-motion: reduce) {
            .fade-up { opacity: 1; transform: none; transition: none; }
        }
    </style>
</head>
<body class="min-h-screen overflow-x-hidden bg-white text-slate-900 antialiased">
    <div class="welcome-page-glow" aria-hidden="true"></div>

    @include('components.header')

    <main>
        <section class="relative overflow-hidden bg-white pt-28 pb-10 sm:pt-32 sm:pb-12 lg:pt-36">
            <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="contact-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                            <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#contact-dots)"/>
                </svg>
                <div class="absolute -right-[200px] -top-[280px] h-[min(480px,110vw)] w-[min(480px,110vw)] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)] md:h-[720px] md:w-[720px]"></div>
                <div class="absolute -left-[140px] bottom-[-60px] h-[240px] w-[240px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.07)_0%,transparent_65%)] md:h-[380px] md:w-[380px]"></div>
            </div>

            <div class="relative mx-auto max-w-4xl px-4 text-center sm:px-6">
                <h1 class="fade-up text-4xl font-extrabold leading-[1.02] tracking-[-.045em] text-slate-900 sm:text-6xl xl:text-[4.1rem]">
                    <span class="block">Neem contact op met</span>
                    <x-text-reveal text="TaskCheck" trigger="load" class="mt-2 block sm:mt-3" />
                </h1>
                <p class="fade-up delay-1 mx-auto mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    Demo, verkoop of support — we denken graag mee over jouw checklists en werkprocessen. Meestal binnen één werkdag antwoord.
                </p>
                <div class="fade-up delay-2 mx-auto mt-6 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 sm:mt-8">
                    @foreach(['Binnen 1 werkdag antwoord', 'Demo op maat', '088 190 0999'] as $b)
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 sm:text-sm">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        @if($b === '088 190 0999')
                            <a href="tel:+31881900999" class="transition hover:text-blue-700">{{ $b }}</a>
                        @else
                            {{ $b }}
                        @endif
                    </span>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="relative pb-20 sm:pb-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-start gap-10 lg:grid-cols-12 lg:gap-14">
                    <div class="fade-up lg:col-span-7">
                        <div class="rounded-[18px] border border-[#e6e8ec] bg-white p-6 shadow-[0_4px_12px_rgba(10,12,18,.04)] sm:p-8 lg:p-10">
                            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Stuur ons een bericht</h2>
                            <p class="mt-2 text-sm leading-relaxed text-slate-500 sm:text-base">Vertel kort wat je zoekt — we lezen alles zelf en reageren persoonlijk.</p>

                            @if(session('success'))
                                <div class="mt-6 flex gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5" role="alert">
                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <p class="text-sm font-medium text-emerald-900">{{ session('success') }}</p>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="mt-6 flex gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5" role="alert">
                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                    <p class="text-sm font-medium text-red-900">{{ session('error') }}</p>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5" role="alert">
                                    <p class="text-sm font-semibold text-red-900">Er ontbrak nog iets</p>
                                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-800">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form
                                method="POST"
                                action="{{ route('contact.send') }}"
                                class="mt-8 space-y-5"
                                data-contact-form
                                @if(filled(config('services.recaptcha.site_key')))
                                    data-recaptcha-sitekey="{{ config('services.recaptcha.site_key') }}"
                                @endif
                            >
                                @csrf

                                <div class="grid gap-5 sm:grid-cols-2">
                                    <div>
                                        <label for="firstName" class="contact-label">Voornaam</label>
                                        <input type="text" id="firstName" name="firstName" value="{{ old('firstName') }}" autocomplete="given-name" class="contact-field" required>
                                    </div>
                                    <div>
                                        <label for="lastName" class="contact-label">Achternaam</label>
                                        <input type="text" id="lastName" name="lastName" value="{{ old('lastName') }}" autocomplete="family-name" class="contact-field" required>
                                    </div>
                                </div>

                                <div class="grid gap-5 sm:grid-cols-2">
                                    <div>
                                        <label for="email" class="contact-label">E-mail</label>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" class="contact-field" required>
                                    </div>
                                    <div>
                                        <label for="company" class="contact-label">Bedrijf <span class="font-normal text-slate-400">(optioneel)</span></label>
                                        <input type="text" id="company" name="company" value="{{ old('company') }}" autocomplete="organization" class="contact-field">
                                    </div>
                                </div>

                                <div>
                                    <label for="subject" class="contact-label">Onderwerp</label>
                                    <select id="subject" name="subject" class="contact-field contact-select" autocomplete="off">
                                        <option value="" @selected(old('subject', request('subject')) === '')>Kies een onderwerp</option>
                                        <option value="demo" @selected(old('subject', request('subject')) === 'demo')>Demo aanvragen</option>
                                        <option value="sales" @selected(old('subject', request('subject')) === 'sales')>Verkoopvraag</option>
                                        <option value="support" @selected(old('subject', request('subject')) === 'support')>Technische ondersteuning</option>
                                        <option value="billing" @selected(old('subject', request('subject')) === 'billing')>Facturatie</option>
                                        <option value="other" @selected(old('subject', request('subject')) === 'other')>Overig</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="message" class="contact-label">Bericht</label>
                                    <textarea id="message" name="message" rows="5" class="contact-field min-h-[9rem] resize-y" required>{{ old('message') }}</textarea>
                                </div>

                                @if(filled(config('services.recaptcha.site_key')))
                                    <input type="hidden" name="g-recaptcha-response" value="" autocomplete="off">
                                    <p class="hidden text-sm font-medium text-red-700" data-recaptcha-error>De beveiligingscontrole is mislukt. Probeer het opnieuw.</p>
                                @endif

                                <div class="flex flex-col gap-4 pt-1 sm:flex-row sm:items-center sm:justify-between">
                                    <button type="submit" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto" data-contact-submit>
                                        Verstuur bericht
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                    </button>
                                    <p class="text-center text-xs leading-relaxed text-slate-400 sm:max-w-xs sm:text-left">
                                        Alleen gebruikt om je vraag te beantwoorden.
                                        @if(filled(config('services.recaptcha.site_key')))
                                            Beschermd door reCAPTCHA —
                                            <a href="https://policies.google.com/privacy" class="underline underline-offset-2 hover:text-slate-600" rel="noopener noreferrer" target="_blank">privacy</a>
                                            &amp;
                                            <a href="https://policies.google.com/terms" class="underline underline-offset-2 hover:text-slate-600" rel="noopener noreferrer" target="_blank">voorwaarden</a>.
                                        @endif
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>

                    <aside class="fade-up delay-1 space-y-8 lg:col-span-5 lg:pt-2">
                        <div>
                            <p class="blog-kicker mb-3">Direct bereikbaar</p>
                            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Andere manieren</h2>
                            <p class="mt-2 text-sm leading-relaxed text-slate-500">Liever meteen mailen of bellen? Dat kan natuurlijk ook.</p>
                        </div>

                        <div class="space-y-5">
                            <div class="rounded-2xl border border-[#e6e8ec] bg-[#f7f8fa] p-5">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">E-mail</p>
                                <div class="mt-3 space-y-3">
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Algemeen &amp; verkoop</p>
                                        <a href="mailto:info@taskcheck.nl" class="mt-0.5 block text-base font-bold text-blue-600 transition hover:text-blue-700">info@taskcheck.nl</a>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Technische support</p>
                                        <a href="mailto:support@taskcheck.nl" class="mt-0.5 block text-base font-bold text-blue-600 transition hover:text-blue-700">support@taskcheck.nl</a>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-[#e6e8ec] bg-white p-5 shadow-sm">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Telefoon</p>
                                <a href="tel:+31881900999" class="mt-2 block text-xl font-extrabold tracking-tight text-slate-900 transition hover:text-blue-700">088 190 0999</a>
                                <p class="mt-1 text-sm text-slate-500">Ma–vr · 09:00–17:00</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Snelle links</p>
                                <nav class="mt-3 space-y-1" aria-label="Snelle links">
                                    @foreach([
                                        ['Bekijk prijzen', route('pricing')],
                                        ['Start 14 dagen gratis', route('register')],
                                        ['Werkcontrole app', route('seo.werkcontrole-app')],
                                        ['Blog', route('blog')],
                                    ] as [$label, $href])
                                    <a href="{{ $href }}" class="group flex items-center justify-between gap-3 rounded-xl px-1 py-2.5 text-sm font-semibold text-slate-700 transition hover:text-blue-700">
                                        <span>{{ $label }}</span>
                                        <svg class="h-4 w-4 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                    </a>
                                    @endforeach
                                </nav>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
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
                    Liever meteen zelf starten?
                </h2>
                <p class="mx-auto mt-5 max-w-lg text-base leading-relaxed text-slate-400 sm:mt-6 sm:text-lg">
                    Zet je eerste digitale checklist live in minuten. Geen creditcard nodig.
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
                    <a href="{{ route('pricing') }}"
                       class="inline-flex min-h-[3rem] w-full items-center justify-center rounded-2xl px-8 py-4 text-base font-bold text-white hover:bg-white/10 sm:w-auto"
                       style="border:1.5px solid rgba(255,255,255,.18)">
                        Bekijk prijzen
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

        (function () {
            var form = document.querySelector('[data-contact-form]');
            if (!form) return;
            var siteKey = form.getAttribute('data-recaptcha-sitekey');
            if (!siteKey) return;
            var errorEl = form.querySelector('[data-recaptcha-error]');
            var submitBtn = form.querySelector('[data-contact-submit]');

            form.addEventListener('submit', function (e) {
                if (form.getAttribute('data-recaptcha-ready') === '1') return;
                e.preventDefault();
                if (errorEl) errorEl.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = true;

                function fail() {
                    if (submitBtn) submitBtn.disabled = false;
                    if (errorEl) errorEl.classList.remove('hidden');
                }

                function setToken(token) {
                    var fields = form.querySelectorAll('[name="g-recaptcha-response"]');
                    if (!fields.length) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'g-recaptcha-response';
                        form.appendChild(input);
                        fields = form.querySelectorAll('[name="g-recaptcha-response"]');
                    }
                    fields.forEach(function (el) { el.value = token; });
                }

                if (!window.grecaptcha || typeof window.grecaptcha.ready !== 'function') {
                    fail();
                    return;
                }

                window.grecaptcha.ready(function () {
                    window.grecaptcha.execute(siteKey, { action: 'contact' }).then(function (token) {
                        if (!token) { fail(); return; }
                        setToken(token);
                        form.setAttribute('data-recaptcha-ready', '1');
                        if (typeof form.requestSubmit === 'function') form.requestSubmit();
                        else form.submit();
                    }).catch(fail);
                });
            });
        })();
    </script>
</body>
</html>
