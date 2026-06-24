@php
    /** @var \App\Models\Organisation\Company $company */
    /** @var array<string, mixed> $report */
    $stats = $report['stats'] ?? [];
    $topLists = $report['top_lists'] ?? [];
    $periodStart = $report['period_start'] ?? null;
    $periodEnd = $report['period_end'] ?? null;
@endphp

@component('emails.layouts.taskcheck')
    <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#0f172a;">
        {{ $report['title'] ?? 'Rapportage' }} - {{ $company->name }}
    </h1>

    <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#334155;">
        Periode:
        <strong>
            {{ $periodStart?->format('d-m-Y H:i') }} t/m {{ $periodEnd?->format('d-m-Y H:i') }}
        </strong>
        (Nederlandse tijd)
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin:0 0 20px;">
        <tr>
            <td style="padding:8px 0;color:#475569;">Totaal inzendingen</td>
            <td style="padding:8px 0;text-align:right;font-weight:700;color:#0f172a;">{{ $stats['total_submissions'] ?? 0 }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#475569;">Wacht op beoordeling</td>
            <td style="padding:8px 0;text-align:right;font-weight:700;color:#0f172a;">{{ $stats['pending_review'] ?? 0 }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#475569;">Goedgekeurd</td>
            <td style="padding:8px 0;text-align:right;font-weight:700;color:#0f172a;">{{ $stats['reviewed'] ?? 0 }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#475569;">Afgekeurd</td>
            <td style="padding:8px 0;text-align:right;font-weight:700;color:#0f172a;">{{ $stats['rejected'] ?? 0 }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#475569;">Nog bezig</td>
            <td style="padding:8px 0;text-align:right;font-weight:700;color:#0f172a;">{{ $stats['in_progress'] ?? 0 }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#475569;">Actieve medewerkers</td>
            <td style="padding:8px 0;text-align:right;font-weight:700;color:#0f172a;">{{ $stats['active_employees'] ?? 0 }}</td>
        </tr>
    </table>

    @if(!empty($topLists))
        <h2 style="margin:0 0 10px;font-size:16px;color:#0f172a;">Top lijsten</h2>
        <ul style="margin:0 0 18px;padding-left:18px;color:#334155;">
            @foreach($topLists as $item)
                <li style="margin:0 0 6px;">
                    {{ $item['title'] }} - <strong>{{ $item['submissions_count'] }}</strong> inzendingen
                </li>
            @endforeach
        </ul>
    @endif

    <p style="margin:0;">
        <a href="{{ $report['dashboard_url'] }}" style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:10px 14px;border-radius:8px;font-weight:600;">
            Open dashboard
        </a>
    </p>
@endcomponent
