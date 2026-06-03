@php
    $footerText = $metaText ?? 'Dit is een automatisch bericht van TaskCheck.';
    $siteHost = parse_url(config('app.url'), PHP_URL_HOST) ?: 'taskcheck.nl';
@endphp
<tr>
    <td class="tc-pad" style="padding:16px 28px;background-color:#f8fafc;border-top:1px solid #e2e8f0;">
        <p style="margin:0 0 8px;font-size:12px;line-height:1.55;color:#64748b;text-align:center;">{{ $footerText }}</p>
        <p style="margin:0;font-size:11px;line-height:1.5;color:#94a3b8;text-align:center;">
            <a href="{{ config('app.url') }}" style="color:#4f46e5;text-decoration:none;font-weight:600;">{{ $siteHost }}</a>
            · © {{ date('Y') }} TaskCheck
        </p>
    </td>
</tr>
