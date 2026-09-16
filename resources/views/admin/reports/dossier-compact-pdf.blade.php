<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 16px 16px 26px; }
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 9px; }
        .hero { background: #2563eb; color: #fff; padding: 16px 18px 14px; }
        .brand { font-size: 8px; letter-spacing: 2px; text-transform: uppercase; color: #bfdbfe; margin: 0 0 4px; }
        .hero h1 { font-size: 20px; margin: 0 0 3px; }
        .hero p { margin: 0; color: #dbeafe; font-size: 9px; }
        .meta { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .meta td { width: 25%; background: #eff6ff; padding: 8px 10px; border: 1px solid #dbeafe; vertical-align: top; }
        .label { display: block; font-size: 7px; letter-spacing: .08em; text-transform: uppercase; color: #2563eb; margin-bottom: 2px; }
        .value { font-weight: bold; font-size: 9px; }
        .pair { width: 100%; border-collapse: separate; border-spacing: 8px 8px; }
        .cell { width: 50%; background: #fff; border: 1px solid #dbeafe; vertical-align: top; }
        .inner { width: 100%; border-collapse: collapse; }
        .inner td { padding: 8px; vertical-align: top; }
        .thumb { width: 84px; height: 64px; }
        .ph { width: 84px; height: 64px; background: #eff6ff; color: #93c5fd; font-size: 7px; text-align: center; line-height: 64px; }
        .t { font-size: 10px; font-weight: bold; margin: 0 0 3px; color: #0f172a; }
        .n { margin: 0; color: #64748b; font-size: 8px; }
        .ok { background: #dbeafe; color: #1d4ed8; }
        .wait { background: #fff7ed; color: #c2410c; }
        .no { background: #fee2e2; color: #b91c1c; }
        .badge { display: inline-block; margin-top: 5px; padding: 2px 7px; font-size: 8px; font-weight: bold; }
        .footer { position: fixed; bottom: -14px; left: 0; right: 0; font-size: 7px; color: #64748b; border-top: 1px solid #dbeafe; padding-top: 3px; }
    </style>
</head>
<body>
<div class="footer">TaskCheck uitdraai · {{ $company->name }} · {{ $printMeta['generated'] }}</div>
<div class="hero">
    <p class="brand">TaskCheck</p>
    <h1>Uitdraai bewijs</h1>
    <p>{{ $printMeta['kind'] }} · {{ $company->name }}</p>
</div>
<table class="meta">
    <tr>
        <td><span class="label">Periode</span><span class="value">{{ $printMeta['period'] }}</span></td>
        <td><span class="label">Lijst</span><span class="value">{{ $printMeta['list'] }}</span></td>
        <td><span class="label">Taak</span><span class="value">{{ $printMeta['task'] }}</span></td>
        <td><span class="label">Locatie</span><span class="value">{{ $printMeta['location'] }}</span></td>
    </tr>
</table>
<table class="pair">
    @foreach(array_chunk($printEntries, 2) as $pair)
        <tr>
            @foreach($pair as $entry)
                @php
                    $dateLabel = $entry['date'] instanceof \Carbon\Carbon
                        ? $entry['date']->timezone('Europe/Amsterdam')->format('d-m-Y H:i')
                        : (is_object($entry['date']) && method_exists($entry['date'], 'format') ? $entry['date']->format('d-m-Y H:i') : (string) $entry['date']);
                    $badge = $entry['approval_key'] === 'approved' ? 'ok' : ($entry['approval_key'] === 'rejected' || $entry['approval_key'] === 'missing' ? 'no' : 'wait');
                @endphp
                <td class="cell">
                    <table class="inner">
                        <tr>
                            <td style="width:92px">
                                @if(!empty($entry['image_path']))
                                    <img class="thumb" src="{{ $entry['image_path'] }}" alt="">
                                @else
                                    <div class="ph">geen foto</div>
                                @endif
                            </td>
                            <td>
                                <p class="t">{{ $entry['task_title'] }}</p>
                                <p class="n">{{ $dateLabel }}@if(!empty($entry['employee'])) · {{ $entry['employee'] }}@endif</p>
                                @if(!empty($entry['result']))<p class="n">{{ $entry['result'] }}</p>@endif
                                <span class="badge {{ $badge }}">{{ $entry['approval_label'] }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            @endforeach
            @if(count($pair) === 1)
                <td class="cell" style="border:0;background:transparent"></td>
            @endif
        </tr>
    @endforeach
</table>
</body>
</html>
