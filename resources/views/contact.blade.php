<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = 'Contact over checklist app voor bedrijven | TaskCheck';
        $seoDescription = 'Contacteer TaskCheck over onze takenlijst personeel en werkcontrole app voor horeca, schoonmaak en andere bedrijven.';
        $seoUrl = route('contact');
        $seoImage = asset('logos/taskcheck-logo.png');
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
                "email": "support@taskcheck.com"
            }
        }
    </script>
    <style>
        @keyframes floatSoft {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .contact-card {
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }

        .contact-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 30px -24px rgba(37, 99, 235, .4);
            border-color: rgba(99, 102, 241, .35);
        }

        .float-soft {
            animation: floatSoft 7s ease-in-out infinite;
        }

        .sparkle-pill {
            position: relative;
            overflow: hidden;
        }

        .sparkle-pill::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 20%, rgba(255,255,255,.55) 45%, transparent 70%);
            transform: translateX(-140%);
            transition: transform .55s ease;
            pointer-events: none;
        }

        .sparkle-pill:hover::after {
            transform: translateX(140%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-sky-50 via-indigo-50 to-fuchsia-50 min-h-screen font-sans text-slate-900">
    @include('components.header')

    <section class="relative pt-28 pb-12 overflow-hidden">
        <div class="absolute -top-20 left-1/4 w-48 h-48 rounded-full bg-cyan-300/30 blur-3xl float-soft"></div>
        <div class="absolute top-16 right-10 w-52 h-52 rounded-full bg-fuchsia-300/25 blur-3xl float-soft" style="animation-delay:1.2s;"></div>

        <div class="relative max-w-6xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-blue-200 bg-white/80 text-xs text-slate-700">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                We helpen je graag verder
            </div>
            <h1 class="mt-5 text-4xl sm:text-5xl font-bold leading-[1.14] sm:leading-[1.1] tracking-tight text-slate-900">
                Neem contact op met
                <span class="block mt-1 sm:mt-2 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-fuchsia-600">het TaskCheck team</span>
            </h1>
            <p class="mt-4 text-slate-600 text-lg max-w-2xl mx-auto">
                Vraag een demo aan, stel je vraag of laat ons meedenken over je operationele proces. We reageren meestal binnen 1 werkdag.
            </p>
            <div class="mt-5 flex flex-wrap justify-center gap-2 text-xs sm:text-sm">
                <span class="sparkle-pill inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-emerald-200 bg-emerald-50 text-emerald-700">⚡ Snelle reactie</span>
                <span class="sparkle-pill inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-blue-200 bg-blue-50 text-blue-700">🎯 Demo op maat</span>
                <span class="sparkle-pill inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-fuchsia-200 bg-fuchsia-50 text-fuchsia-700">🤝 Persoonlijk contact</span>
            </div>
        </div>
    </section>

    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 contact-card rounded-2xl border border-blue-100 bg-white/90 p-6 sm:p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-slate-900">Stuur ons een bericht</h2>
                <p class="text-sm text-slate-600 mt-1">Vertel kort wat je zoekt. Dan nemen we snel contact op.</p>

                @if(session('success'))
                    <div class="mt-4 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mt-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}" class="mt-6 space-y-5">
                    @csrf
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="firstName" class="block text-sm font-medium text-slate-700 mb-1.5">Voornaam</label>
                            <input type="text" id="firstName" name="firstName" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Jan">
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-medium text-slate-700 mb-1.5">Achternaam</label>
                            <input type="text" id="lastName" name="lastName" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Janssen">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">E-mail</label>
                            <input type="email" id="email" name="email" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="jan@bedrijf.nl">
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-medium text-slate-700 mb-1.5">Bedrijf (optioneel)</label>
                            <input type="text" id="company" name="company" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Jouw bedrijf">
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-slate-700 mb-1.5">Onderwerp</label>
                        <select id="subject" name="subject" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Kies een onderwerp</option>
                            <option value="demo">Demo aanvragen</option>
                            <option value="sales">Verkoopvraag</option>
                            <option value="support">Technische ondersteuning</option>
                            <option value="billing">Facturatie</option>
                            <option value="other">Overig</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-slate-700 mb-1.5">Bericht</label>
                        <textarea id="message" name="message" rows="5" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Vertel ons hoe we je kunnen helpen..."></textarea>
                    </div>

                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-6 py-3 transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        Verstuur bericht
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 space-y-4">
                <div class="contact-card rounded-2xl border border-indigo-100 bg-white/90 p-5 shadow-sm">
                    <h3 class="font-semibold text-slate-900">Direct contact</h3>
                    <p class="text-sm text-slate-600 mt-2">📩 support@taskcheck.com</p>
                    <p class="text-sm text-slate-600">💼 admin@taskcheck.com</p>
                </div>

                <div class="contact-card rounded-2xl border border-emerald-100 bg-white/90 p-5 shadow-sm">
                    <h3 class="font-semibold text-slate-900">Beschikbaarheid</h3>
                    <p class="text-sm text-slate-600 mt-2">🗓️ Maandag t/m vrijdag</p>
                    <p class="text-sm text-slate-600">🕘 09:00 - 17:30</p>
                </div>

                <div class="contact-card rounded-2xl border border-fuchsia-100 bg-white/90 p-5 shadow-sm">
                    <h3 class="font-semibold text-slate-900">Snelle links</h3>
                    <div class="mt-2 space-y-1.5 text-sm">
                        <a href="{{ route('pricing') }}" class="block text-blue-600 hover:text-blue-700">Bekijk prijzen</a>
                        <a href="{{ route('login') }}" class="block text-blue-600 hover:text-blue-700">Start 14 dagen gratis</a>
                        <a href="{{ route('seo.werkcontrole-app') }}" class="block text-blue-600 hover:text-blue-700">Werkcontrole app informatie</a>
                        <a href="{{ route('blog') }}" class="block text-blue-600 hover:text-blue-700">Lees de blog</a>
                        {{-- <a href="{{ route('help') }}" class="block text-blue-600 hover:text-blue-700">Helpcentrum</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
