@php
    $pageTitle = $pageTitle ?? 'TaskCheck';
    $metaText = $metaText ?? 'Dit is een automatisch bericht van TaskCheck.';
@endphp
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $pageTitle }}</title>
    @include('emails.partials.styles')
</head>
<body style="margin:0;padding:0;width:100%;background-color:#eef2f7;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" class="tc-shell" style="background-color:#eef2f7;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" class="tc-card" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;">
                    @include('emails.partials.header', [
                        'headerTitle' => $headerTitle ?? 'TaskCheck',
                        'headerSubtitle' => $headerSubtitle ?? 'Checklist & kwaliteitscontrole',
                        'headerBadge' => $headerBadge ?? null,
                    ])
                    <tr>
                        <td class="tc-pad" style="padding:28px;">
                            @yield('email-body')
                        </td>
                    </tr>
                    @include('emails.partials.footer', ['metaText' => $metaText])
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
