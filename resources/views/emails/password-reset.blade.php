@extends('emails.layouts.taskcheck', [
    'pageTitle' => 'Wachtwoord opnieuw instellen',
    'headerTitle' => 'TaskCheck',
    'headerSubtitle' => 'Wachtwoord resetten',
    'headerBadge' => 'Beveiliging',
    'metaText' => 'Deze link is persoonlijk. Deel hem niet met anderen.',
])

@section('email-body')
    <p style="margin:0 0 14px;font-size:15px;line-height:1.65;color:#334155;">
        Hallo <strong>{{ $user->name }}</strong>,
    </p>

    <p style="margin:0 0 20px;font-size:15px;line-height:1.65;color:#334155;">
        We hebben een verzoek ontvangen om het wachtwoord van je TaskCheck-account opnieuw in te stellen.
        Klik op de knop hieronder om een nieuw wachtwoord te kiezen.
    </p>

    <table role="presentation" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
        <tr>
            <td style="border-radius:10px;background-color:#4f46e5;">
                <a href="{{ $resetUrl }}" class="tc-btn" style="display:inline-block;padding:14px 24px;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:10px;">
                    Wachtwoord opnieuw instellen
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:0 0 14px;font-size:14px;line-height:1.6;color:#64748b;">
        Deze link is {{ $expireMinutes }} minuten geldig. Heb jij dit niet aangevraagd? Dan kun je deze e-mail negeren — je wachtwoord blijft ongewijzigd.
    </p>

    <p style="margin:0 0 14px;font-size:14px;line-height:1.6;color:#64748b;">
        Werkt de knop niet? Kopieer dan deze URL in je browser:
    </p>
    <p style="margin:0 0 24px;font-size:13px;line-height:1.5;color:#64748b;word-break:break-all;">
        {{ $resetUrl }}
    </p>

    <p style="margin:0;font-size:14px;line-height:1.6;color:#334155;">
        Met vriendelijke groet,<br>
        <strong style="color:#0f172a;">Team TaskCheck</strong>
    </p>
@endsection
