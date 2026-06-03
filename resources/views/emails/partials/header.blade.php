@php
    $headerTitle = $headerTitle ?? 'TaskCheck';
    $headerSubtitle = $headerSubtitle ?? 'Checklist & kwaliteitscontrole';
@endphp
<tr>
    <td class="tc-header" style="background-color:#4f46e5;padding:24px 28px;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <table role="presentation" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="vertical-align:middle;padding-right:12px;">
                                <img src="{{ url('/logos/taskcheck-favicon.png') }}" alt="TaskCheck" width="32" height="32" style="border-radius:8px;">
                            </td>
                            <td style="vertical-align:middle;">
                                <p style="margin:0;font-size:20px;font-weight:700;color:#ffffff;line-height:1.2;">{{ $headerTitle }}</p>
                                @if(!empty($headerSubtitle))
                                    <p style="margin:4px 0 0;font-size:13px;color:#c7d2fe;line-height:1.35;">{{ $headerSubtitle }}</p>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @if(!empty($headerBadge))
                <tr>
                    <td style="padding-top:14px;">
                        <span style="display:inline-block;background-color:#4338ca;border-radius:8px;padding:5px 12px;font-size:11px;font-weight:700;color:#ffffff;text-transform:uppercase;letter-spacing:0.5px;">{{ $headerBadge }}</span>
                    </td>
                </tr>
            @endif
        </table>
    </td>
</tr>
