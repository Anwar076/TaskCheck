<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TaskCheck bericht' }}</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#1d4ed8,#4f46e5);padding:22px 24px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="font-size:24px;font-weight:700;color:#ffffff;line-height:1.2;">
                                        <img src="{{ url('/logos/taskcheck-favicon.png') }}" alt="TaskCheck" width="26" height="26" style="vertical-align:middle;border:0;outline:none;text-decoration:none;margin-right:8px;">
                                        <span style="vertical-align:middle;">TaskCheck</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top:8px;font-size:13px;color:#dbeafe;">Checklist & kwaliteitscontrole</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 10px;font-size:14px;color:#334155;">Beste {{ $greetingName }},</p>
                            <h1 style="margin:0 0 14px;font-size:22px;line-height:1.25;color:#0f172a;">{{ $title }}</h1>
                            <p style="margin:0;font-size:14px;line-height:1.7;color:#334155;">
                                {!! nl2br(e($bodyText)) !!}
                            </p>

                            @if(!empty($ctaLabel) && !empty($ctaUrl))
                                <table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:20px;">
                                    <tr>
                                        <td>
                                            <a href="{{ $ctaUrl }}"
                                               style="display:inline-block;padding:12px 18px;background:#2563eb;color:#ffffff;text-decoration:none;border-radius:10px;font-weight:600;font-size:14px;">
                                                {{ $ctaLabel }}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:22px;border:1px solid #dbeafe;border-radius:12px;background:#eff6ff;">
                                <tr>
                                    <td style="padding:14px 16px;">
                                        <p style="margin:0 0 8px;font-size:13px;font-weight:700;color:#1d4ed8;">Waarom teams kiezen voor TaskCheck</p>
                                        <p style="margin:0;font-size:13px;line-height:1.6;color:#334155;">
                                            Sneller controle rondes uitvoeren, minder fouten op locatie en realtime overzicht voor managers.
                                        </p>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:10px;">
                                            <tr>
                                                <td style="font-size:12px;color:#0f172a;">✓ Duidelijke dagtaken voor medewerkers</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:12px;color:#0f172a;padding-top:4px;">✓ Fotobewijs + handtekening waar nodig</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:12px;color:#0f172a;padding-top:4px;">✓ Minder ad-hoc appjes en losse Excel files</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:18px;">
                                <tr>
                                    <td style="padding:12px 14px;border:1px solid #e2e8f0;border-radius:10px;background:#ffffff;">
                                        <p style="margin:0;font-size:12px;line-height:1.6;color:#334155;">
                                            “Sinds we TaskCheck gebruiken, besparen we elke week uren aan controle en opvolging.”
                                            <br><span style="color:#64748b;">— Operations manager, horeca keten</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top:10px;">
                                        <p style="margin:0;font-size:12px;line-height:1.6;color:#334155;">
                                            “De app is super duidelijk voor medewerkers. We zien direct wat wel/niet gedaan is.”
                                            <br><span style="color:#64748b;">— Teamleider, schoonmaakbedrijf</span>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:18px;">
                                <tr>
                                    <td>
                                        <a href="{{ url('/') }}"
                                           style="display:inline-block;padding:10px 14px;background:#eef2ff;color:#3730a3;text-decoration:none;border-radius:10px;font-weight:600;font-size:13px;border:1px solid #c7d2fe;">
                                            Bekijk website
                                        </a>
                                    </td>
                                    <td style="width:10px;"></td>
                                    <td>
                                        <a href="{{ url('/pricing') }}"
                                           style="display:inline-block;padding:10px 14px;background:#f8fafc;color:#0f172a;text-decoration:none;border-radius:10px;font-weight:600;font-size:13px;border:1px solid #e2e8f0;">
                                            Bekijk pakketten
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:20px 0 0;font-size:14px;line-height:1.6;color:#334155;">
                                Met vriendelijke groet,<br>
                                <strong>Team TaskCheck</strong>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;font-size:12px;color:#64748b;">
                            {{ $metaText ?: 'Dit is een automatisch bericht van TaskCheck.' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
