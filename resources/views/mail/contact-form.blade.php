@php
    $initials = collect(explode(' ', trim($fromName)))
        ->filter()
        ->take(2)
        ->map(fn ($p) => strtoupper(substr($p, 0, 1)))
        ->join('');
    $replyUrl = 'mailto:' . $fromEmail . '?subject=' . rawurlencode('Re: ' . $subjectLabel);
@endphp
@extends('emails.layouts.taskcheck', [
    'pageTitle' => 'Nieuw contactbericht — ' . $subjectLabel,
    'headerTitle' => 'TaskCheck',
    'headerSubtitle' => 'Nieuw contactbericht',
    'headerBadge' => $subjectLabel,
    'metaText' => 'Via het contactformulier op ' . (parse_url(config('app.url'), PHP_URL_HOST) ?: 'taskcheck.nl'),
])

@section('email-body')
    <p style="margin:0 0 14px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:#94a3b8;">Afzender</p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:22px;border:1px solid #e2e8f0;border-radius:12px;background-color:#f8fafc;">
        <tr>
            <td align="center" style="padding:20px 20px 12px;">
                <table role="presentation" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="48" height="48" align="center" valign="middle" style="background-color:#4f46e5;border-radius:12px;font-size:18px;font-weight:700;color:#ffffff;">
                            {{ $initials ?: '?' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding:0 20px 8px;">
                <p style="margin:0;font-size:17px;font-weight:700;color:#0f172a;">{{ $fromName }}</p>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding:0 20px {{ $company ? '10px' : '20px' }};">
                <a href="mailto:{{ $fromEmail }}" style="font-size:14px;color:#4f46e5;text-decoration:none;font-weight:600;">{{ $fromEmail }}</a>
            </td>
        </tr>
        @if($company)
            <tr>
                <td align="center" style="padding:0 20px 20px;">
                    <span style="display:inline-block;background-color:#eef2ff;border-radius:8px;padding:6px 14px;font-size:13px;font-weight:600;color:#4338ca;">{{ $company }}</span>
                </td>
            </tr>
        @endif
    </table>

    <p style="margin:0 0 12px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:#94a3b8;">Bericht</p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:22px;border:1px solid #e2e8f0;border-left:4px solid #4f46e5;border-radius:12px;">
        <tr>
            <td style="padding:18px 20px;">
                <p style="margin:0;font-size:15px;color:#334155;line-height:1.7;white-space:pre-wrap;">{{ $messageBody }}</p>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="border-radius:10px;background-color:#4f46e5;">
                <a href="{{ $replyUrl }}" class="tc-btn" style="display:inline-block;padding:14px 24px;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:10px;">
                    Beantwoord {{ $fromName }}
                </a>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding-top:12px;">
                <p style="margin:0;font-size:13px;color:#94a3b8;">Je kunt ook rechtstreeks op deze e-mail antwoorden.</p>
            </td>
        </tr>
    </table>
@endsection
