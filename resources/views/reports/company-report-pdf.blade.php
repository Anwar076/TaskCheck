<!doctype html><html lang="nl"><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;color:#0f172a;font-size:11px}h1{font-size:22px;margin:0 0 4px}.muted{color:#64748b}.cards{width:100%;margin:22px 0;border-collapse:collapse}.cards td{width:25%;padding:12px;border:1px solid #e2e8f0}.value{font-size:20px;font-weight:bold;margin-top:5px}table.data{width:100%;border-collapse:collapse;margin-top:10px}table.data th,table.data td{padding:8px;border-bottom:1px solid #e2e8f0;text-align:left}table.data th{background:#f8fafc;color:#475569}.right{text-align:right!important}</style></head><body>
@php
    $summary = $report['summary'] ?? [];
    $sections = \App\Models\Organisation\CompanyReportRecipient::normalizeSections($report['sections'] ?? null);
@endphp
<h1>{{ $report['title'] }} — {{ $company->name }}</h1><p class="muted">{{ $report['period_start']->format('d-m-Y') }} t/m {{ $report['period_end']->format('d-m-Y') }}</p>
@if($sections['summary'])
<table class="cards"><tr>@foreach([['Ingediend',$summary['total_lists'] ?? 0],['Afgerond',$summary['finished'] ?? 0],['Nog bezig',$summary['in_progress'] ?? 0],['Voltooiing',($summary['completion_rate'] ?? 0).'%']] as [$label,$value])<td><span class="muted">{{ $label }}</span><div class="value">{{ $value }}</div></td>@endforeach</tr></table>
<h2>Samenvatting</h2><table class="data"><tbody><tr><td>Voltooid</td><td class="right">{{ $summary['completed'] ?? 0 }}</td><td>Beoordeeld</td><td class="right">{{ $summary['reviewed'] ?? 0 }}</td></tr><tr><td>Afgekeurd</td><td class="right">{{ $summary['rejected'] ?? 0 }}</td><td>Actieve medewerkers</td><td class="right">{{ $summary['active_employees'] ?? 0 }} / {{ $summary['total_employees'] ?? 0 }}</td></tr><tr><td>Gemiddeld per medewerker</td><td class="right">{{ $summary['avg_lists_per_employee'] ?? 0 }}</td><td>Productiviteit</td><td class="right">{{ $summary['productivity_score'] ?? '-' }}</td></tr></tbody></table>
@endif
@if($sections['employee_performance'])
<h2>Prestaties medewerkers</h2><table class="data"><thead><tr><th>Medewerker</th><th class="right">Lijsten</th><th class="right">Afgerond</th><th class="right">Bezig</th><th class="right">Afgekeurd</th><th class="right">Voltooiing</th></tr></thead><tbody>@forelse($report['employee_overview'] ?? [] as $employee)<tr><td>{{ $employee['name'] }}</td><td class="right">{{ $employee['total_submissions'] }}</td><td class="right">{{ $employee['finished'] }}</td><td class="right">{{ $employee['in_progress'] }}</td><td class="right">{{ $employee['rejected'] }}</td><td class="right">{{ $employee['completion_rate'] }}%</td></tr>@empty<tr><td colspan="6">Geen gegevens in deze periode.</td></tr>@endforelse</tbody></table>
@endif
@if($sections['top_lists'])
<h2>Meest gebruikte lijsten</h2><table class="data"><thead><tr><th>Lijst</th><th class="right">Inzendingen</th></tr></thead><tbody>@forelse($report['top_lists'] ?? [] as $item)<tr><td>{{ $item['title'] }}</td><td class="right">{{ $item['submissions_count'] }}</td></tr>@empty<tr><td colspan="2">Geen gegevens in deze periode.</td></tr>@endforelse</tbody></table>
@endif
@if($sections['attention_points'])
<h2>Opmerkingen &amp; afwijkingen</h2>
@forelse($report['attention_points'] ?? [] as $list)
<h3 style="margin-bottom:3px">{{ $list['list_title'] }}@if(!empty($list['employee_name'])) <span class="muted">· {{ $list['employee_name'] }}</span>@endif</h3>
<table class="data"><thead><tr><th>Punt</th><th>Opmerking / afwijking</th></tr></thead><tbody>@foreach($list['items'] as $item)<tr><td>{{ $item['task_title'] }}</td><td>{{ implode(' · ', $item['messages']) }}</td></tr>@endforeach</tbody></table>
@empty
<p class="muted">Geen opmerkingen of afwijkingen in deze periode.</p>
@endforelse
@endif
@if($sections['task_overview'])
<h2>Individuele taken</h2>
@forelse($report['task_overview'] ?? [] as $list)
<h3 style="margin-bottom:3px">{{ $list['list_title'] }}@if(!empty($list['employee_name'])) <span class="muted">· {{ $list['employee_name'] }}</span>@endif</h3>
<table class="data"><thead><tr><th>Taak</th><th class="right">Status</th></tr></thead><tbody>@forelse($list['tasks'] as $task)<tr><td>{{ $task['title'] }}</td><td class="right">{{ $task['status_label'] }}</td></tr>@empty<tr><td colspan="2">Geen taken gevonden.</td></tr>@endforelse</tbody></table>
@empty
<p class="muted">Geen taken in deze periode.</p>
@endforelse
@endif
<p class="muted" style="margin-top:24px">Gegenereerd door TaskCheck op {{ $report['generated_at']->format('d-m-Y H:i') }}</p></body></html>
