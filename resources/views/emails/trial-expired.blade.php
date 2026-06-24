@extends('emails.layouts.taskcheck', [
    'pageTitle' => 'Proefperiode verlopen',
    'headerTitle' => 'Proefperiode verlopen',
    'headerSubtitle' => $company->name,
    'metaText' => 'Dit is een automatisch bericht van TaskCheck.',
])

@section('email-body')
    <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#0f172a;">
        Je proefperiode is afgelopen
    </h1>

    <p style="margin:0 0 16px;font-size:15px;line-height:1.65;color:#334155;">
        De gratis proefperiode van <strong>{{ $company->name }}</strong> is verlopen.
        Kies een abonnement om TaskCheck te blijven gebruiken voor je team, locaties en werkcontroles.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;border:1px solid #e2e8f0;border-radius:12px;background-color:#f8fafc;">
        <tr>
            <td style="padding:16px 18px;">
                <p style="margin:0 0 8px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:#64748b;">Wat nu?</p>
                <p style="margin:0;font-size:14px;line-height:1.65;color:#334155;">
                    Log in en kies het abonnement dat bij je organisatie past. Daarna kun je direct verder waar je was gebleven.
                </p>
            </td>
        </tr>
    </table>

    <p style="margin:0;">
        <a href="{{ $choosePlanUrl }}" style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:600;">
            Kies een abonnement
        </a>
    </p>
@endsection
