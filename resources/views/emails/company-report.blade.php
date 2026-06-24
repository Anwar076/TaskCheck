@php
    /** @var \App\Models\Organisation\Company $company */
    /** @var array<string, mixed> $report */
    $summary = $report['summary'] ?? [];
    $employeeOverview = $report['employee_overview'] ?? [];
    $topLists = $report['top_lists'] ?? [];
    $periodStart = $report['period_start'] ?? null;
    $periodEnd = $report['period_end'] ?? null;
    $periodGrowth = $summary['period_growth'] ?? 0;
@endphp

@component('emails.layouts.taskcheck')
    <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#0f172a;">
        {{ $report['title'] ?? 'Rapportage' }} - {{ $company->name }}
    </h1>

    <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#334155;">
        <strong>{{ $report['period_description'] ?? 'Periode' }}</strong>:
        {{ $periodStart?->locale('nl')->translatedFormat('d M Y') }}
        @if($periodStart && $periodEnd && ! $periodStart->isSameDay($periodEnd))
            t/m {{ $periodEnd->locale('nl')->translatedFormat('d M Y') }}
        @endif
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin:0 0 20px;background:#f8fafc;border-radius:8px;">
        <tr>
            <td style="padding:12px 14px;color:#475569;">Lijsten ingediend</td>
            <td style="padding:12px 14px;text-align:right;font-weight:700;color:#0f172a;">{{ $summary['total_lists'] ?? 0 }}</td>
        </tr>
        <tr>
            <td style="padding:12px 14px;color:#475569;">Afgerond</td>
            <td style="padding:12px 14px;text-align:right;font-weight:700;color:#0f172a;">{{ $summary['finished'] ?? 0 }}</td>
        </tr>
        <tr>
            <td style="padding:12px 14px;color:#475569;">&nbsp;&nbsp;Goedgekeurd</td>
            <td style="padding:12px 14px;text-align:right;color:#0f172a;">{{ $summary['reviewed'] ?? 0 }}</td>
        </tr>
        <tr>
            <td style="padding:12px 14px;color:#475569;">&nbsp;&nbsp;Te beoordelen</td>
            <td style="padding:12px 14px;text-align:right;color:#0f172a;">{{ $summary['completed'] ?? 0 }}</td>
        </tr>
        <tr>
            <td style="padding:12px 14px;color:#475569;">Nog bezig</td>
            <td style="padding:12px 14px;text-align:right;font-weight:700;color:#0f172a;">{{ $summary['in_progress'] ?? 0 }}</td>
        </tr>
        <tr>
            <td style="padding:12px 14px;color:#475569;">Afgekeurd</td>
            <td style="padding:12px 14px;text-align:right;font-weight:700;color:#0f172a;">{{ $summary['rejected'] ?? 0 }}</td>
        </tr>
        <tr>
            <td style="padding:12px 14px;color:#475569;">Voltooiingspercentage</td>
            <td style="padding:12px 14px;text-align:right;font-weight:700;color:#0f172a;">{{ $summary['completion_rate'] ?? 0 }}%</td>
        </tr>
        <tr>
            <td style="padding:12px 14px;color:#475569;">Teamscore</td>
            <td style="padding:12px 14px;text-align:right;font-weight:700;color:#0f172a;">{{ $summary['productivity_score'] ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding:12px 14px;color:#475569;">Actieve medewerkers</td>
            <td style="padding:12px 14px;text-align:right;font-weight:700;color:#0f172a;">{{ $summary['active_employees'] ?? 0 }} van {{ $summary['total_employees'] ?? 0 }}</td>
        </tr>
        @if($periodGrowth != 0)
        <tr>
            <td style="padding:12px 14px;color:#475569;">Verschil t.o.v. vorige periode</td>
            <td style="padding:12px 14px;text-align:right;font-weight:700;color:{{ $periodGrowth > 0 ? '#059669' : '#dc2626' }};">{{ $periodGrowth > 0 ? '+' : '' }}{{ $periodGrowth }}%</td>
        </tr>
        @endif
    </table>

    @if(!empty($employeeOverview))
        <h2 style="margin:0 0 10px;font-size:16px;color:#0f172a;">Prestaties medewerkers</h2>
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin:0 0 20px;font-size:13px;">
            <tr>
                <th align="left" style="padding:8px 0;border-bottom:1px solid #e2e8f0;color:#64748b;">Medewerker</th>
                <th align="right" style="padding:8px 0;border-bottom:1px solid #e2e8f0;color:#64748b;">Lijsten</th>
                <th align="right" style="padding:8px 0;border-bottom:1px solid #e2e8f0;color:#64748b;">Voltooid</th>
            </tr>
            @foreach(array_slice($employeeOverview, 0, 8) as $employee)
                <tr>
                    <td style="padding:8px 0;color:#334155;">
                        {{ $employee['name'] }}
                        @if(!empty($employee['department']))
                            <span style="color:#94a3b8;">({{ $employee['department'] }})</span>
                        @endif
                    </td>
                    <td align="right" style="padding:8px 0;color:#0f172a;">{{ $employee['total_submissions'] }}</td>
                    <td align="right" style="padding:8px 0;color:#0f172a;font-weight:600;">{{ $employee['completion_rate'] }}%</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if(!empty($topLists))
        <h2 style="margin:0 0 10px;font-size:16px;color:#0f172a;">Meest gebruikte lijsten</h2>
        <ul style="margin:0 0 18px;padding-left:18px;color:#334155;">
            @foreach($topLists as $item)
                <li style="margin:0 0 6px;">
                    {{ $item['title'] }} - <strong>{{ $item['submissions_count'] }}</strong> inzendingen
                </li>
            @endforeach
        </ul>
    @endif

    <p style="margin:0;">
        <a href="{{ $report['overview_url'] ?? route('admin.dashboard') }}" style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:10px 14px;border-radius:8px;font-weight:600;">
            Bekijk volledig overzicht
        </a>
    </p>
@endcomponent
