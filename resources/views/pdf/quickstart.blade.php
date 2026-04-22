<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>TaskCheck Quickstart</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 12px; line-height: 1.5; }
        .header { background: #1d4ed8; color: #ffffff; padding: 18px 20px; border-radius: 8px; }
        .title { font-size: 22px; margin: 0; }
        .subtitle { margin: 6px 0 0; font-size: 12px; color: #dbeafe; }
        .section { margin-top: 20px; }
        .section h2 { margin: 0 0 8px; font-size: 15px; color: #1e293b; }
        .card { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-top: 10px; }
        ul { margin: 6px 0 0 18px; padding: 0; }
        li { margin-bottom: 6px; }
        .tip { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 10px; border-radius: 6px; margin-top: 10px; }
        .footer { margin-top: 24px; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    @php
        $planKey = strtolower((string) ($company->subscription_plan ?: $company->pending_subscription_plan ?: 'starter'));
        $planNameMap = [
            'starter' => 'Starter',
            'professional' => 'Professional',
            'enterprise' => 'Enterprise',
            'custom' => 'Custom',
        ];
        $planName = $planNameMap[$planKey] ?? ucfirst($planKey);
        $hasAiFeatures = in_array($planKey, ['professional', 'enterprise', 'custom'], true);
    @endphp

    <div class="header">
        <h1 class="title">TaskCheck Quickstart</h1>
        <p class="subtitle">Voor {{ $company->name }} - welkom {{ $user->name }}</p>
        <p class="subtitle" style="margin-top:4px;">
            Abonnement: <strong>{{ $planName }}</strong>
            @if($hasAiFeatures)
                (met AI-functies)
            @else
                (zonder AI-functies)
            @endif
        </p>
    </div>

    <div class="section">
        <h2>1) Start met je basis</h2>
        <div class="card">
            <ul>
                <li>Controleer je bedrijfsinstellingen in het admin dashboard.</li>
                <li>Stel je gewenste workflow in voor dagelijkse of wekelijkse checklists.</li>
                <li>Controleer meteen of notificaties zijn toegestaan.</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>2) Maak je eerste takenlijst</h2>
        <div class="card">
            <ul>
                <li>Ga naar <strong>Takenlijsten</strong> en klik op <strong>Nieuwe lijst</strong>.</li>
                <li>Voeg duidelijke taken toe, met bewijs type (foto, tekst, bestand) waar nodig.</li>
                <li>Markeer kritieke taken als verplicht.</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>3) Nodig medewerkers uit</h2>
        <div class="card">
            <ul>
                <li>Maak gebruikers aan via <strong>Gebruikers</strong>.</li>
                <li>Koppel de juiste lijsten aan de juiste medewerkers.</li>
                <li>Laat medewerkers 1 keer inloggen en meldingen toestaan.</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>4) Uitvoeren en beoordelen</h2>
        <div class="card">
            <ul>
                <li>Medewerkers vullen taken in en leveren bewijs aan.</li>
                <li>Beheerder keurt goed, wijst af, of vraagt om opnieuw uitvoeren.</li>
                <li>Gebruik notificaties om realtime op de hoogte te blijven.</li>
            </ul>
        </div>
        <div class="tip">
            Tip: begin met 1 kleine checklist en schaal daarna op.
        </div>
    </div>

    <div class="section">
        <h2>5) Plan-specifieke functies</h2>
        <div class="card">
            @if($hasAiFeatures)
                <p style="margin:0 0 8px;"><strong>AI is beschikbaar op jouw plan.</strong></p>
                <ul>
                    <li>Gebruik AI voor hulp bij het opstellen van takenlijsten.</li>
                    <li>Gebruik AI-review om inzendingen sneller na te kijken.</li>
                    <li>Combineer AI met je eigen controle voor de beste kwaliteit.</li>
                </ul>
            @else
                <p style="margin:0 0 8px;"><strong>Je gebruikt momenteel Starter (zonder AI).</strong></p>
                <ul>
                    <li>Alle kernfuncties werken: checklists, bewijs, review en notificaties.</li>
                    <li>AI-functies zijn beschikbaar in Professional en Enterprise.</li>
                    <li>Upgraden kan op elk moment via <strong>Abonnement</strong> in het dashboard.</li>
                </ul>
            @endif
        </div>
    </div>

    <div class="footer">
        TaskCheck - Checklist & kwaliteitscontrole voor teams.
    </div>
</body>
</html>
