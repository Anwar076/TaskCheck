@extends('emails.layouts.taskcheck', [
    'pageTitle' => 'Welkom bij TaskCheck',
    'headerTitle' => 'Welkom bij TaskCheck',
    'headerSubtitle' => $company->name,
    'metaText' => 'Vragen? Reageer op deze e-mail, dan helpen we je snel verder.',
])

@section('email-body')
    <p style="margin:0 0 14px;font-size:15px;line-height:1.65;color:#334155;">
        Hallo <strong>{{ $user->name }}</strong>, super dat je gestart bent. Je account voor
        <strong>{{ $company->name }}</strong> staat live — je kunt vandaag meteen aan de slag.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;border:1px solid #e2e8f0;border-radius:12px;background-color:#f8fafc;">
        <tr>
            <td style="padding:16px 18px;">
                <p style="margin:0 0 10px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;color:#64748b;">Zo begin je</p>
                <p style="margin:0;font-size:14px;line-height:1.65;color:#334155;">
                    1. Log in en bekijk je templates<br>
                    2. Maak of importeer een takenlijst<br>
                    3. Wijs medewerkers toe en start met controleren
                </p>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
        <tr>
            <td style="border-radius:10px;background-color:#4f46e5;">
                <a href="{{ route('login') }}" class="tc-btn" style="display:inline-block;padding:14px 24px;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:10px;">
                    Naar je dashboard
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:0;font-size:14px;line-height:1.6;color:#64748b;">
        Je proefperiode is direct actief. Veel succes met je eerste takenlijsten.
    </p>
@endsection
