@php
    $plan = $company->getPlanDetails();
    $net = (float) ($plan['billing_amount'] ?? $plan['price_monthly'] ?? 0);
    $period = \App\Models\Organisation\Company::billingPeriod($company->billing_period ?: ($plan['billing_period'] ?? 'monthly'));
    $headline = match ($daysRemaining) {
        0 => 'Je proefperiode eindigt vandaag',
        1 => 'Morgen eindigt je proefperiode',
        default => "Over {$daysRemaining} dagen eindigt je proefperiode",
    };
@endphp
@extends('emails.layouts.taskcheck', [
    'pageTitle' => 'Proefperiode',
    'headerTitle' => $headline,
    'headerSubtitle' => $company->name,
    'metaText' => 'Dit is een automatisch bericht van TaskCheck.',
])

@section('email-body')
    <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#0f172a;">{{ $headline }}</h1>
    <p style="margin:0 0 16px;font-size:15px;line-height:1.65;color:#334155;">
        Het abonnement <strong>{{ $company->getPlanDisplayName() }}</strong> staat klaar voor <strong>{{ $company->name }}</strong>.
        @if($daysRemaining === 0)
            Rond vandaag de betaling af om TaskCheck zonder onderbreking te blijven gebruiken.
        @else
            Rond de betaling op tijd af, zodat je toegang doorloopt zodra de proefperiode stopt.
        @endif
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;border:1px solid #e2e8f0;border-radius:12px;background-color:#f8fafc;"><tr><td style="padding:16px 18px;">
        <p style="margin:0 0 8px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:#64748b;">Abonnement</p>
        <p style="margin:0;font-size:15px;color:#0f172a;"><strong>{{ $company->getPlanDisplayName() }}</strong></p>
        @if($net > 0)
            <p style="margin:8px 0 0;font-size:14px;color:#334155;">€{{ number_format($net, 2, ',', '.') }} excl. btw / {{ $period['suffix'] }} (€{{ number_format($net * 1.21, 2, ',', '.') }} incl. 21% btw)</p>
        @endif
        <p style="margin:8px 0 0;font-size:13px;color:#64748b;">Proefperiode tot {{ $company->trial_ends_at?->format('d-m-Y') }}</p>
    </td></tr></table>
    <p style="margin:0;"><a href="{{ $paymentUrl }}" style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:600;">Betaal je abonnement nu</a></p>
@endsection
