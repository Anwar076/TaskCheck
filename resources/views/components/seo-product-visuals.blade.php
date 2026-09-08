@props([
    'theme' => 'generic',
    'placement' => 'all',
])

@php
    $copy = [
        'horeca' => [
            'showcaseKicker' => 'Live in de zaak',
            'showcaseTitle' => 'Zie per dienst wat er klaar is — zonder na te bellen.',
            'showcaseLead' => 'Opening, HACCP en sluiting in één dashboard. Je ziet welke locatie op schema ligt en waar bewijs nog ontbreekt.',
            'proofKicker' => 'Bewijs per taak',
            'proofTitle' => 'Niet alleen gedaan. Ook bewezen.',
            'proofLead' => 'Temperatuur, hygiëne en openingstaken worden gekoppeld aan medewerker, tijdstip en locatie. Klaar voor inspectie.',
            'proofPoints' => ['Foto of meting verplicht per kritieke taak', 'Tijdstip en medewerker automatisch vastgelegd', 'Direct terug te vinden bij NVWA of audit'],
            'locKicker' => 'Meerdere vestigingen',
            'locTitle' => 'Eén standaard. Iedere locatie.',
            'locLead' => 'Rol dezelfde horeca-checklists uit naar elke vestiging en vergelijk voortgang in één overzicht.',
            'locSteps' => [
                ['Centraal inrichten', 'Bouw opening, sluiting en HACCP één keer.'],
                ['Lokaal uitvoeren', 'Elke zaak werkt volgens dezelfde shift-standaard.'],
                ['Globaal bijsturen', 'Zie welke vestiging achterloopt — nog voor de service.'],
            ],
            'reportKicker' => 'Rapportage & inspecties',
            'reportTitle' => 'Als iemand bewijs vraagt, heb je het al.',
            'photo' => 'branch-horeca.png',
            'photoAlt' => 'Horecateam met tablet — TaskCheck in de keuken',
        ],
        'haccp' => [
            'showcaseKicker' => 'Digitale HACCP',
            'showcaseTitle' => 'Registraties die je later nog kunt aantonen.',
            'showcaseLead' => 'Temperaturen, hygiëne en keukencontroles in één overzicht. Geen losse lijsten, wel een audit trail.',
            'proofKicker' => 'Bewijs bij elke meting',
            'proofTitle' => 'Elke controle gekoppeld aan bewijs.',
            'proofLead' => 'Van koelcel tot vriezer: medewerkers leggen de meting vast, jij ziet of het binnen de norm blijft.',
            'proofPoints' => ['Temperatuur en foto per controle', 'Afwijkingen meteen zichtbaar', 'Export klaar voor inspectie of audit'],
            'locKicker' => 'Per keuken en locatie',
            'locTitle' => 'Zelfde HACCP-standaard, overal.',
            'locLead' => 'Eén set registraties voor elke keuken — met lokaal overzicht en centrale rapportage.',
            'locSteps' => [
                ['Checklists vastleggen', 'Koeling, vriezer, schoonmaak en ingangscontrole.'],
                ['Op de vloer uitvoeren', 'Medewerkers registreren op telefoon of tablet.'],
                ['Centrale controle', 'Managers zien openstaande metingen realtime.'],
            ],
            'reportKicker' => 'Audit-klaar',
            'reportTitle' => 'HACCP-bewijs zonder papierwerk.',
            'photo' => 'branch-horeca.png',
            'photoAlt' => 'Keukencontrole met TaskCheck op tablet',
        ],
        'schoonmaak' => [
            'showcaseKicker' => 'Op locatie',
            'showcaseTitle' => 'Weten wat er schoongemaakt is — met bewijs.',
            'showcaseLead' => 'Per object, ronde of opdrachtgever. Medewerkers vinken af op mobiel, jij ziet de status zonder na te bellen.',
            'proofKicker' => 'Foto als bewijs',
            'proofTitle' => 'Klaar is klaar — en aantoonbaar.',
            'proofLead' => 'Verplicht foto- of videobewijs per taak. Minder discussie met opdrachtgevers, minder herhaalbezoeken.',
            'proofPoints' => ['Foto per ruimte of object', 'Tijdstip en medewerker automatisch erbij', 'Overzicht per locatie voor de klant'],
            'locKicker' => 'Meerdere objecten',
            'locTitle' => 'Alle locaties in één planning.',
            'locLead' => 'Van kantoorpand tot zorginstelling: dezelfde werkwijze, eigen checklists per object.',
            'locSteps' => [
                ['Objecten inrichten', 'Checklists per locatie of contract.'],
                ['Team voert uit', 'Op mobiel, ook zonder vast bureau.'],
                ['Kwaliteit controleren', 'Beoordeel inzendingen voordat de klant belt.'],
            ],
            'reportKicker' => 'Voor opdrachtgevers',
            'reportTitle' => 'Rapportage die je kunt delen.',
            'photo' => 'branch-schoonmaak.png',
            'photoAlt' => 'Schoonmaakteam met tablet — TaskCheck op locatie',
        ],
        'generic' => [
            'showcaseKicker' => 'Realtime overzicht',
            'showcaseTitle' => 'Je hoeft niet meer te vragen of iets gedaan is.',
            'showcaseLead' => 'Eén live dashboard voor teams en locaties. Je ziet wat klaar is, wat loopt en wat aandacht nodig heeft.',
            'proofKicker' => 'Bewijs per taak',
            'proofTitle' => 'Niet alleen gedaan. Ook bewezen.',
            'proofLead' => 'Foto, video, temperatuur of handtekening — gekoppeld aan de juiste taak, medewerker en locatie.',
            'proofPoints' => ['Bewijs verplicht instelbaar per taak', 'Tijdstip, medewerker en locatie vastgelegd', 'Beschikbaar bij klachten of audits'],
            'locKicker' => 'Multi-locatie',
            'locTitle' => 'Eén standaard. Iedere locatie.',
            'locLead' => 'Gebouwd voor teams met meerdere vestigingen — van 2 tot 200 locaties.',
            'locSteps' => [
                ['Centraal aanmaken', 'Bouw processen één keer en rol ze uit.'],
                ['Lokaal uitvoeren', 'Elke locatie werkt volgens dezelfde standaard.'],
                ['Globaal monitoren', 'Vergelijk locaties en stuur bij waar nodig.'],
            ],
            'reportKicker' => 'Rapportage & audits',
            'reportTitle' => 'Als iemand bewijs vraagt, heb je het al.',
            'photo' => 'branch-overige.png',
            'photoAlt' => 'Operationeel team met tablet — werkcontrole in TaskCheck',
        ],
    ][$theme] ?? null;

    $copy = $copy ?? [
        'showcaseKicker' => 'Realtime overzicht',
        'showcaseTitle' => 'Je hoeft niet meer te vragen of iets gedaan is.',
        'showcaseLead' => 'Eén live dashboard voor teams en locaties.',
        'proofKicker' => 'Bewijs per taak',
        'proofTitle' => 'Niet alleen gedaan. Ook bewezen.',
        'proofLead' => 'Bewijs gekoppeld aan taak, medewerker en locatie.',
        'proofPoints' => ['Bewijs per taak', 'Realtime inzicht', 'Rapportages klaar'],
        'locKicker' => 'Multi-locatie',
        'locTitle' => 'Eén standaard. Iedere locatie.',
        'locLead' => 'Zelfde werkwijze op elke vestiging.',
        'locSteps' => [
            ['Centraal aanmaken', 'Bouw processen één keer.'],
            ['Lokaal uitvoeren', 'Elke locatie volgt dezelfde standaard.'],
            ['Globaal monitoren', 'Stuur bij waar nodig.'],
        ],
        'reportKicker' => 'Rapportage',
        'reportTitle' => 'Bewijs klaar wanneer je het nodig hebt.',
        'photo' => 'branch-overige.png',
        'photoAlt' => 'TaskCheck werkcontrole',
    ];
@endphp

@if($placement === 'showcase' || $placement === 'all')
<section class="relative z-10 overflow-hidden bg-white pb-16 pt-6 sm:pb-20 lg:pb-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto mb-10 max-w-2xl text-center fade-up sm:mb-12">
            <p class="new-feature-kicker">{{ $copy['showcaseKicker'] }}</p>
            <h2 class="new-feature-title">{{ $copy['showcaseTitle'] }}</h2>
            <p class="new-feature-lead">{{ $copy['showcaseLead'] }}</p>
        </div>
        <div class="product-showcase fade-up">
            <div class="product-chip" style="top:8%;left:-34px;transform:rotate(-2deg)">
                <span class="product-chip-icon bg-amber-50 text-amber-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.85 23.85 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.078 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                </span>
                <span><span class="product-chip-title">Wacht op beoordeling</span><span class="product-chip-meta">2 INZENDINGEN</span></span>
            </div>
            <div class="product-chip" style="top:42%;left:-70px;transform:rotate(1.5deg)">
                <span class="product-chip-icon bg-emerald-50 text-emerald-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                </span>
                <span><span class="product-chip-title">3/3 taken · 100%</span><span class="product-chip-meta">OPENING · AFGEROND</span></span>
            </div>
            <div class="product-chip" style="top:12%;right:-6px;transform:rotate(2deg)">
                <span class="product-chip-icon bg-blue-50 text-blue-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l6-6 4 4L21 3.5M21 3.5h-6m6 0v6"/></svg>
                </span>
                <span><span class="product-chip-title">Voltooiing 75%</span><span class="product-chip-meta">3 VAN 4 LIJSTEN</span></span>
            </div>
            <div class="product-browser">
                <div class="product-browser-bar" aria-hidden="true">
                    <span class="product-browser-dot"></span><span class="product-browser-dot"></span><span class="product-browser-dot"></span>
                    <span class="product-browser-url">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><rect x="5" y="10.5" width="14" height="9.5" rx="2"/><path d="M8 10.5V7.8a4 4 0 018 0v2.7"/></svg>
                        app.taskcheck.nl/dashboard
                    </span>
                    <span class="product-browser-dot opacity-0"></span>
                </div>
                <img src="{{ asset('images/dashboard-product-showcase.jpg') }}"
                     alt="TaskCheck-dashboard met overzicht van medewerkers, takenlijsten en inzendingen"
                     loading="lazy" decoding="async" width="1440" height="1000" class="block h-auto w-full">
            </div>
            <div class="product-review-card">
                <img src="{{ asset('images/dashboard-review-strip.jpg') }}"
                     alt="Inzending beoordelen in TaskCheck"
                     loading="lazy" decoding="async" width="1140" height="305" class="block h-auto w-full">
            </div>
            <div class="product-showcase-fade" aria-hidden="true"></div>
        </div>
    </div>
</section>
@endif

@if($placement === 'story' || $placement === 'all')
<div class="seo-story-breakout">
<section class="new-feature-section">
    <div class="new-feature-wrap new-feature-grid">
        <div class="fade-up">
            <div class="new-feature-head">
                <span class="new-feature-kicker">{{ $copy['proofKicker'] }}</span>
                <h2 class="new-feature-title">{{ $copy['proofTitle'] }}</h2>
                <p class="new-feature-lead">{{ $copy['proofLead'] }}</p>
            </div>
            <div class="new-points">
                @foreach($copy['proofPoints'] as $point)
                <div class="new-point"><span class="new-check">✓</span>{{ $point }}</div>
                @endforeach
            </div>
        </div>
        <div class="new-shot fade-up">
            <x-seo-browser url="app.taskcheck.nl/werkcontroles">
                <img src="{{ asset('images/how-it-works-beoordelen.jpg') }}"
                     alt="TaskCheck — inzending beoordelen met voortgang en beoordelingsacties"
                     loading="lazy" decoding="async" width="1200" height="800">
            </x-seo-browser>
            <div class="new-float-chip hidden lg:flex" style="left:-20px;bottom:7%">
                <span class="new-check">✓</span>
                <span><strong>Keur alles goed (3)</strong><small>3/3 TAKEN AFGEROND</small></span>
            </div>
        </div>
    </div>
</section>

<section class="new-feature-section is-soft">
    <div class="new-feature-wrap new-feature-grid is-visual-left">
        <div class="new-shot fade-up">
            <p class="mb-3 text-[10px] font-bold uppercase tracking-[.12em] text-slate-500">● Locatiekaart · live</p>
            <x-seo-browser url="app.taskcheck.nl/instellingen/locaties">
                <img src="{{ asset('images/product-locations.jpg') }}"
                     alt="TaskCheck locatiekaart — vestigingen op de kaart"
                     loading="lazy" decoding="async" width="1200" height="800">
            </x-seo-browser>
            <div class="new-float-chip hidden lg:flex" style="left:-18px;top:48%">
                <span>⌖</span>
                <span><strong>Rotterdam Centrum</strong><small>LOCATIE ACTIEF</small></span>
            </div>
        </div>
        <div class="fade-up">
            <div class="new-feature-head">
                <span class="new-feature-kicker">{{ $copy['locKicker'] }}</span>
                <h2 class="new-feature-title">{{ $copy['locTitle'] }}</h2>
                <p class="new-feature-lead">{{ $copy['locLead'] }}</p>
            </div>
            <div class="new-number-list">
                @foreach($copy['locSteps'] as $index => [$title, $text])
                <div class="new-number-row">
                    <span class="new-number">0{{ $index + 1 }}</span>
                    <div><strong>{{ $title }}</strong><p>{{ $text }}</p></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="new-feature-section">
    <div class="new-feature-wrap new-feature-grid">
        <div class="fade-up">
            <div class="new-feature-head">
                <span class="new-feature-kicker">{{ $copy['reportKicker'] }}</span>
                <h2 class="new-feature-title">{{ $copy['reportTitle'] }}</h2>
                <p class="new-feature-lead">Dagrapporten, weekoverzichten en een complete audit trail — inclusief foto’s en metingen.</p>
            </div>
            <div class="new-points">
                @foreach(['Automatische rapporten per locatie of team', 'Wie, wat, wanneer en waar — volledig te volgen', 'PDF-export voor klanten, managers of auditors'] as $point)
                <div class="new-point"><span class="new-check">✓</span>{{ $point }}</div>
                @endforeach
            </div>
        </div>
        <div class="new-shot fade-up">
            <x-seo-browser url="app.taskcheck.nl/rapportages">
                <img src="{{ asset('images/product-reporting.jpg') }}"
                     alt="TaskCheck rapportages — voltooiing, teamscore en PDF-export"
                     loading="lazy" decoding="async" width="1200" height="800">
            </x-seo-browser>
        </div>
    </div>
</section>

<section class="new-feature-section is-soft">
    <div class="new-feature-wrap">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="fade-up img-zoom overflow-hidden rounded-[18px] border border-[#e6e8ec] bg-white shadow-[0_4px_12px_rgba(10,12,18,.04),0_24px_64px_-16px_rgba(23,43,99,.16)]">
                <img src="{{ asset('images/'.$copy['photo']) }}"
                     alt="{{ $copy['photoAlt'] }}"
                     loading="lazy" decoding="async" width="1200" height="800"
                     class="h-full w-full object-cover">
            </div>
            <div class="fade-up delay-1">
                <span class="new-feature-kicker">Zo werkt het op de vloer</span>
                <h2 class="new-feature-title">Van takenlijst naar uitvoering.</h2>
                <p class="new-feature-lead">Medewerkers zien precies wat ze moeten doen — op telefoon of tablet — en ronden af met bewijs.</p>
                <div class="mt-8">
                    <x-seo-browser url="app.taskcheck.nl/uitvoering">
                        <img src="{{ asset('images/how-it-works-uitvoering.jpg') }}"
                             alt="TaskCheck uitvoering waarin een medewerker een checklist invult"
                             loading="lazy" decoding="async" width="1200" height="800">
                    </x-seo-browser>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endif
