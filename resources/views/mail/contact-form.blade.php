<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuw contactbericht</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:40px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:580px;">

                    {{-- Logo / header --}}
                    <tr>
                        <td align="center" style="padding-bottom:24px;">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background-color:#4f46e5;border-radius:12px;padding:10px 20px;">
                                        <span style="color:#ffffff;font-size:18px;font-weight:700;letter-spacing:-0.3px;">TaskCheck</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Card --}}
                    <tr>
                        <td style="background-color:#ffffff;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.08);overflow:hidden;">

                            {{-- Top accent bar --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background:linear-gradient(90deg,#4f46e5,#6366f1);height:4px;"></td>
                                </tr>
                            </table>

                            {{-- Content --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="padding:32px 36px;">

                                {{-- Title --}}
                                <tr>
                                    <td style="padding-bottom:8px;">
                                        <p style="margin:0;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#6366f1;">Contactformulier</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:28px;border-bottom:1px solid #f1f5f9;">
                                        <h1 style="margin:0;font-size:22px;font-weight:700;color:#0f172a;line-height:1.3;">
                                            Nieuw bericht: {{ $subjectLabel }}
                                        </h1>
                                    </td>
                                </tr>

                                {{-- Afzender info --}}
                                <tr>
                                    <td style="padding-top:24px;padding-bottom:20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;">
                                            <tr>
                                                <td style="padding:20px 24px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td width="50%" style="padding-bottom:12px;">
                                                                <p style="margin:0 0 3px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.6px;">Naam</p>
                                                                <p style="margin:0;font-size:14px;color:#1e293b;font-weight:500;">{{ $fromName }}</p>
                                                            </td>
                                                            <td width="50%" style="padding-bottom:12px;">
                                                                <p style="margin:0 0 3px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.6px;">E-mail</p>
                                                                <p style="margin:0;font-size:14px;color:#1e293b;font-weight:500;">{{ $fromEmail }}</p>
                                                            </td>
                                                        </tr>
                                                        @if($company)
                                                        <tr>
                                                            <td colspan="2">
                                                                <p style="margin:0 0 3px;font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.6px;">Bedrijf</p>
                                                                <p style="margin:0;font-size:14px;color:#1e293b;font-weight:500;">{{ $company }}</p>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Bericht --}}
                                <tr>
                                    <td style="padding-bottom:28px;">
                                        <p style="margin:0 0 10px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.6px;color:#94a3b8;">Bericht</p>
                                        <div style="background-color:#f8fafc;border-left:3px solid #6366f1;border-radius:0 8px 8px 0;padding:16px 20px;">
                                            <p style="margin:0;font-size:15px;color:#334155;line-height:1.7;white-space:pre-wrap;">{{ $messageBody }}</p>
                                        </div>
                                    </td>
                                </tr>

                                {{-- CTA --}}
                                <tr>
                                    <td align="center" style="padding-top:4px;padding-bottom:8px;">
                                        <a href="mailto:{{ $fromEmail }}"
                                           style="display:inline-block;background-color:#4f46e5;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;padding:12px 28px;border-radius:8px;">
                                            Beantwoord {{ $fromName }}
                                        </a>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="padding-top:24px;">
                            <p style="margin:0;font-size:12px;color:#94a3b8;">
                                Dit bericht is verstuurd via het contactformulier op
                                <a href="{{ config('app.url') }}" style="color:#6366f1;text-decoration:none;">taskcheck.nl</a>
                            </p>
                            <p style="margin:6px 0 0;font-size:12px;color:#cbd5e1;">
                                Gemaakt door <a href="https://brancom.nl" style="color:#6366f1;text-decoration:none;">Brancom.nl</a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
