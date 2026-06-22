@extends('emails.layouts.taskcheck', [
    'pageTitle' => 'Welkom bij TaskCheck',
    'headerTitle' => 'Welkom bij TaskCheck',
    'headerSubtitle' => $companyName ?? 'Je nieuwe account',
    'metaText' => 'Deze link is persoonlijk. Deel hem niet met anderen.',
])

@section('email-body')
    <p style="margin:0 0 14px;font-size:15px;line-height:1.65;color:#334155;">
        Hallo <strong>{{ $user->name }}</strong>,
        @if($invitedByName)
            {{ $invitedByName }} heeft een account voor je aangemaakt
            @if($companyName)
                bij <strong>{{ $companyName }}</strong>
            @endif
            .
        @else
            er is een account voor je aangemaakt
            @if($companyName)
                bij <strong>{{ $companyName }}</strong>
            @endif
            .
        @endif
    </p>

    <p style="margin:0 0 20px;font-size:15px;line-height:1.65;color:#334155;">
        Klik op de knop hieronder om je wachtwoord in te stellen. Daarna kun je meteen inloggen.
    </p>

    <table role="presentation" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
        <tr>
            <td style="border-radius:10px;background-color:#4f46e5;">
                <a href="{{ $resetUrl }}" class="tc-btn" style="display:inline-block;padding:14px 24px;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:10px;">
                    Wachtwoord instellen
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:0 0 14px;font-size:14px;line-height:1.6;color:#64748b;">
        Deze link is {{ $expireMinutes }} minuten geldig. Werkt de knop niet? Kopieer dan deze URL in je browser:
    </p>
    <p style="margin:0;font-size:13px;line-height:1.5;color:#64748b;word-break:break-all;">
        {{ $resetUrl }}
    </p>
@endsection
