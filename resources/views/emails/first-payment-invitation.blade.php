@extends('emails.layouts.taskcheck', [
    'pageTitle' => 'Eerste betaling',
    'headerTitle' => 'Je abonnement staat klaar',
    'headerSubtitle' => $company->name,
    'metaText' => 'Dit is een automatisch bericht van TaskCheck.',
])

@section('email-body')
    <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#0f172a;">Rond je eerste betaling af</h1>
    <p style="margin:0 0 16px;font-size:15px;line-height:1.65;color:#334155;">
        Je proefperiode is afgelopen en het abonnement <strong>{{ $company->getPlanDetails()['name'] }}</strong> staat al voor je klaar.
        Rond eenmalig de eerste betaling via Mollie af. Daarmee geef je ook het mandaat voor de volgende incasso's.
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;border:1px solid #e2e8f0;border-radius:12px;background-color:#f8fafc;"><tr><td style="padding:16px 18px;">
        <strong>Betaalfrequentie:</strong> {{ \App\Models\Organisation\Company::billingPeriod($company->billing_period ?: 'monthly')['label'] }}<br>
        <span style="color:#64748b;">De volgende incasso vindt één betaalperiode na de eerste geslaagde betaling plaats.</span>
    </td></tr></table>
    <p style="margin:0;"><a href="{{ $paymentUrl }}" style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:600;">Naar eerste betaling</a></p>
@endsection
