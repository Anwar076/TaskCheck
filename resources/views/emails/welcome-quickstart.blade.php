<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welkom bij TaskCheck</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#1d4ed8,#4f46e5);padding:24px;">
                            <h1 style="margin:0;font-size:24px;line-height:1.2;color:#ffffff;">Welkom bij TaskCheck, {{ $user->name }}!</h1>
                            <p style="margin:10px 0 0;font-size:14px;color:#dbeafe;">
                                Je account voor <strong>{{ $company->name }}</strong> staat klaar.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 14px;font-size:15px;line-height:1.6;">
                                Super dat je gestart bent. In de bijlage vind je de <strong>TaskCheck Quickstart PDF</strong> met een korte stap-voor-stap uitleg.
                            </p>
                            <ul style="margin:0 0 18px;padding-left:20px;color:#334155;font-size:14px;line-height:1.7;">
                                <li>Bedrijf en instellingen controleren</li>
                                <li>Eerste takenlijst maken</li>
                                <li>Medewerkers uitnodigen</li>
                                <li>Checklist uitvoeren en beoordelen</li>
                            </ul>
                            <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#334155;">
                                Je proefperiode is direct actief. Je kunt vandaag al live starten met je team.
                            </p>
                            <p style="margin:0;font-size:14px;line-height:1.6;color:#334155;">
                                Veel succes,<br>
                                <strong>Team TaskCheck</strong>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;font-size:12px;color:#64748b;">
                            Vragen? Reageer op deze e-mail, dan helpen we je snel verder.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
