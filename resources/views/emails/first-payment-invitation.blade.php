@php
    $plan = $company->getPlanDetails();
    $net = (float) ($plan['billing_amount'] ?? $plan['price_monthly'] ?? 0);
    $period = \App\Models\Organisation\Company::billingPeriod($company->billing_period ?: ($plan['billing_period'] ?? 'monthly'));
@endphp
@extends('emails.layouts.taskcheck', [
    'pageTitle' => 'Eerste betaling',
    'headerTitle' => 'Je abonnement staat klaar',
    'headerSubtitle' => $company->name,
    'metaText' => 'Dit is een automatisch bericht van TaskCheck.',
])

@section('email-body')
    <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#0f172a;">Rond je betaling af</h1>
    <p style="margin:0 0 16px;font-size:15px;line-height:1.65;color:#334155;">
        Voor <strong>{{ $company->name }}</strong> staat het abonnement <strong>{{ $company->getPlanDisplayName() }}</strong> klaar.
        Klik op de knop hieronder om direct te betalen via Mollie. Daarmee geef je ook het mandaat voor de volgende incasso’s.
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;border:1px solid #e2e8f0;border-radius:12px;background-color:#f8fafc;"><tr><td style="padding:16px 18px;">
        <p style="margin:0 0 8px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:#64748b;">Te betalen</p>
        <p style="margin:0;font-size:15px;color:#0f172a;"><strong>{{ $company->getPlanDisplayName() }}</strong></p>
        @if($net > 0)
            <p style="margin:8px 0 0;font-size:14px;color:#334155;">€{{ number_format($net, 2, ',', '.') }} excl. btw / {{ $period['suffix'] }}</p>
            <p style="margin:4px 0 0;font-size:13px;color:#64748b;">€{{ number_format($net * 1.21, 2, ',', '.') }} incl. 21% btw bij afrekenen</p>
        @endif
        <p style="margin:8px 0 0;font-size:13px;color:#64748b;">Betaalfrequentie: {{ $period['label'] }}</p>
    </td></tr></table>
    <p style="margin:0;"><a href="{{ $paymentUrl }}" style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:600;">Betaal je abonnement nu</a></p>
@endsection
