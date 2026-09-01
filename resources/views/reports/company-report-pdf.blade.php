<!doctype html><html lang="nl"><head><meta charset="utf-8"><style>
@page{margin:28px 32px}body{font-family:DejaVu Sans,sans-serif;color:#172033;font-size:10.5px;line-height:1.45}.brand{background:#4f46e5;color:#fff;padding:18px 20px;margin:-28px -32px 24px}.brand h1{font-size:22px;margin:0 0 3px}.brand p{margin:0;color:#dbeafe}.muted{color:#64748b}h2{font-size:14px;margin:24px 0 8px;padding:7px 10px;border-left:4px solid #4f46e5;background:#eef2ff;color:#312e81}h3{font-size:11px;color:#172033}.cards{width:100%;margin:18px 0;border-collapse:separate;border-spacing:6px}.cards td{width:25%;padding:12px;border:1px solid #dbeafe;background:#f8faff;border-radius:7px}.value{font-size:20px;font-weight:bold;margin-top:5px;color:#4f46e5}table.data{width:100%;border-collapse:collapse;margin:6px 0 14px;border:1px solid #e2e8f0}table.data th,table.data td{padding:8px;border-bottom:1px solid #e2e8f0;text-align:left}table.data th{background:#f1f5f9;color:#475569;font-size:9px;text-transform:uppercase;letter-spacing:.3px}.right{text-align:right!important}.ok{color:#059669;font-weight:bold}.open{color:#dc2626;font-weight:bold}.attention h2{border-color:#f59e0b;background:#fffbeb;color:#92400e}.footer{margin-top:26px;padding-top:10px;border-top:1px solid #e2e8f0;color:#94a3b8;font-size:9px}
</style></head><body>
@php
    $summary = $report['summary'] ?? [];
    $sections = \App\Models\Organisation\CompanyReportRecipient::normalizeSections($report['sections'] ?? null);
@endphp
<div class="brand"><h1>TaskCheck · {{ $report['title'] }}</h1><p>{{ $company->name }} &nbsp;|&nbsp; {{ $report['period_start']->format('d-m-Y') }} t/m {{ $report['period_end']->format('d-m-Y') }}</p></div>
@if($sections['summary'])
<table class="cards"><tr>@foreach([['Ingediend',$summary['total_lists'] ?? 0],['Afgerond',$summary['finished'] ?? 0],['Nog bezig',$summary['in_progress'] ?? 0],['Voltooiing',($summary['completion_rate'] ?? 0).'%']] as [$label,$value])<td><span class="muted">{{ $label }}</span><div class="value">{{ $value }}</div></td>@endforeach</tr></table>
<h2>Samenvatting</h2><table class="data"><tbody><tr><td>Wacht op beoordeling</td><td class="right">{{ $summary['completed'] ?? 0 }}</td><td>Geaccepteerde lijsten</td><td class="right">{{ $summary['reviewed'] ?? 0 }}</td></tr><tr><td>Afgerond zonder controle</td><td class="right">{{ $summary['finalized_without_review'] ?? 0 }}</td><td>Afgekeurde lijsten</td><td class="right">{{ $summary['rejected'] ?? 0 }}</td></tr><tr><td>Taken afgerond</td><td class="right ok">{{ $summary['finished_tasks'] ?? 0 }} / {{ $summary['total_tasks'] ?? 0 }}</td><td>Taken niet afgerond</td><td class="right open">{{ $summary['unfinished_tasks'] ?? 0 }}</td></tr><tr><td>Gemiddeld per medewerker</td><td class="right">{{ $summary['avg_lists_per_employee'] ?? 0 }}</td><td>Productiviteit</td><td class="right">{{ $summary['productivity_score'] ?? '-' }}</td></tr></tbody></table>
@endif
@if($sections['employee_performance'])
<h2>Prestaties medewerkers</h2><table class="data"><thead><tr><th>Medewerker</th><th class="right">Lijsten</th><th class="right">Afgerond</th><th class="right">Bezig</th><th class="right">Afgekeurd</th><th class="right">Voltooiing</th></tr></thead><tbody>@forelse($report['employee_overview'] ?? [] as $employee)<tr><td>{{ $employee['name'] }}</td><td class="right">{{ $employee['total_submissions'] }}</td><td class="right">{{ $employee['finished'] }}</td><td class="right">{{ $employee['in_progress'] }}</td><td class="right">{{ $employee['rejected'] }}</td><td class="right">{{ $employee['completion_rate'] }}%</td></tr>@empty<tr><td colspan="6">Geen gegevens in deze periode.</td></tr>@endforelse</tbody></table>
@endif
@if($sections['top_lists'])
<h2>Meest gebruikte lijsten</h2><table class="data"><thead><tr><th>Lijst</th><th class="right">Inzendingen</th></tr></thead><tbody>@forelse($report['top_lists'] ?? [] as $item)<tr><td>{{ $item['title'] }}</td><td class="right">{{ $item['submissions_count'] }}</td></tr>@empty<tr><td colspan="2">Geen gegevens in deze periode.</td></tr>@endforelse</tbody></table>
@endif
@if($sections['attention_points'])
<div class="attention">
<h2>Opmerkingen &amp; afwijkingen</h2>
@forelse($report['attention_points'] ?? [] as $list)
<h3 style="margin-bottom:3px">{{ $list['list_title'] }}@if(!empty($list['employee_name'])) <span class="muted">· {{ $list['employee_name'] }}</span>@endif</h3>
<table class="data"><thead><tr><th>Punt</th><th>Opmerking / afwijking</th></tr></thead><tbody>@foreach($list['items'] as $item)<tr><td>{{ $item['task_title'] }}</td><td>{{ implode(' · ', $item['messages']) }}</td></tr>@endforeach</tbody></table>
@empty
<p class="muted">Geen opmerkingen of afwijkingen in deze periode.</p>
@endforelse
</div>
@endif
<p class="footer">Gegenereerd door TaskCheck op {{ $report['generated_at']->format('d-m-Y H:i') }}</p></body></html>
