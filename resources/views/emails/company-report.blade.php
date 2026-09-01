@extends('emails.layouts.taskcheck', [
    'pageTitle' => ($report['title'] ?? 'Rapportage') . ' - ' . $company->name,
    'headerTitle' => $report['title'] ?? 'Rapportage',
    'headerSubtitle' => $company->name,
    'metaText' => 'Dit is een automatisch rapport van TaskCheck.',
])

@section('email-body')
@php
    /** @var \App\Models\Organisation\Company $company */
    /** @var array<string, mixed> $report */
    $summary = $report['summary'] ?? [];
    $employeeOverview = $report['employee_overview'] ?? [];
    $topLists = $report['top_lists'] ?? [];
    $attentionPoints = $report['attention_points'] ?? [];
    $taskOverview = $report['task_overview'] ?? [];
    $periodStart = $report['period_start'] ?? null;
    $periodEnd = $report['period_end'] ?? null;
    $periodGrowth = $summary['period_growth'] ?? 0;
    $sections = \App\Models\Organisation\CompanyReportRecipient::normalizeSections($report['sections'] ?? null);
    $hasData = (bool) ($report['has_data'] ?? (($summary['total_lists'] ?? 0) > 0));
    $periodLabel = $periodStart?->locale('nl')->translatedFormat('d M Y');
    if ($periodStart && $periodEnd && ! $periodStart->isSameDay($periodEnd)) {
        $periodLabel .= ' t/m '.$periodEnd->locale('nl')->translatedFormat('d M Y');
    }
@endphp

    <h1 style="margin:0 0 12px;font-size:22px;line-height:1.3;color:#0f172a;">
        {{ $report['title'] ?? 'Rapportage' }}
    </h1>

    <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#334155;">
        <strong>{{ $report['period_description'] ?? 'Periode' }}</strong>:
        {{ $periodLabel }}
    </p>

    @if(! $hasData)
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;border:1px solid #e2e8f0;border-radius:10px;background-color:#f8fafc;">
            <tr>
                <td style="padding:20px 18px;text-align:center;">
                    <p style="margin:0 0 8px;font-size:15px;font-weight:600;color:#0f172a;">
                        Geen gegevens beschikbaar
                    </p>
                    <p style="margin:0;font-size:14px;line-height:1.6;color:#64748b;">
                        @if(($report['frequency'] ?? '') === \App\Models\Organisation\Company::REPORTING_FREQUENCY_WEEKLY)
                            Er zijn in vorige week geen lijsten ingediend. Zodra er activiteit is, vind je die terug in je volgende rapportage.
                        @else
                            Er zijn gisteren geen lijsten ingediend. Zodra er activiteit is, vind je die terug in je volgende rapportage.
                        @endif
                    </p>
                </td>
            </tr>
        </table>
    @else
        @if($sections['summary'])
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
        @endif

        @if($sections['employee_performance'] && !empty($employeeOverview))
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

        @if($sections['top_lists'] && !empty($topLists))
            <h2 style="margin:0 0 10px;font-size:16px;color:#0f172a;">Meest gebruikte lijsten</h2>
            <ul style="margin:0 0 18px;padding-left:18px;color:#334155;">
                @foreach($topLists as $item)
                    <li style="margin:0 0 6px;">
                        {{ $item['title'] }} - <strong>{{ $item['submissions_count'] }}</strong> inzendingen
                    </li>
                @endforeach
            </ul>
        @endif

        @if($sections['attention_points'])
            <h2 style="margin:0 0 10px;font-size:16px;color:#0f172a;">Opmerkingen &amp; afwijkingen</h2>
            @forelse($attentionPoints as $list)
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin:0 0 12px;border:1px solid #e2e8f0;border-radius:8px;">
                    <tr>
                        <td style="padding:10px 12px;background:#f8fafc;font-size:14px;font-weight:700;color:#0f172a;">
                            {{ $list['list_title'] }}
                            @if(!empty($list['employee_name']))<span style="font-weight:400;color:#64748b;"> · {{ $list['employee_name'] }}</span>@endif
                        </td>
                    </tr>
                    @foreach($list['items'] as $item)
                        <tr><td style="padding:10px 12px;border-top:1px solid #e2e8f0;color:#334155;">
                            <strong>{{ $item['task_title'] }}</strong><br>
                            <span style="color:#64748b;">{{ implode(' · ', $item['messages']) }}</span>
                        </td></tr>
                    @endforeach
                </table>
            @empty
                <p style="margin:0 0 20px;font-size:14px;color:#64748b;">Geen opmerkingen of afwijkingen in deze periode.</p>
            @endforelse
        @endif


        @if($sections['task_overview'])
            <h2 style="margin:0 0 10px;font-size:16px;color:#0f172a;">Individuele taken</h2>
            @forelse($taskOverview as $list)
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin:0 0 12px;border:1px solid #e2e8f0;border-radius:8px;">
                    <tr><td colspan="2" style="padding:10px 12px;background:#f8fafc;font-size:14px;font-weight:700;color:#0f172a;">
                        {{ $list['list_title'] }}
                        @if(!empty($list['employee_name']))<span style="font-weight:400;color:#64748b;"> · {{ $list['employee_name'] }}</span>@endif
                    </td></tr>
                    @forelse($list['tasks'] as $task)
                        <tr>
                            <td style="padding:8px 12px;border-top:1px solid #e2e8f0;color:#334155;">{{ $task['title'] }}</td>
                            <td align="right" style="padding:8px 12px;border-top:1px solid #e2e8f0;font-weight:600;color:{{ $task['is_finished'] ? '#059669' : '#dc2626' }};">{{ $task['status_label'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" style="padding:8px 12px;color:#64748b;">Geen taken gevonden.</td></tr>
                    @endforelse
                </table>
            @empty
                <p style="margin:0 0 20px;font-size:14px;color:#64748b;">Geen taken in deze periode.</p>
            @endforelse
        @endif
    @endif

    <p style="margin:0;">
        <a href="{{ $report['overview_url'] ?? route('admin.dashboard') }}" style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:10px 14px;border-radius:8px;font-weight:600;">
            Bekijk volledig overzicht
        </a>
    </p>
@endsection
