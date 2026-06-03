<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Factuur {{ $invoice->invoice_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.45;
            margin: 0;
            padding: 36px 40px 130px;
        }
        .brand-bar {
            height: 6px;
            background: #4f46e5;
            margin: -36px -40px 28px;
        }
        table { border-collapse: collapse; }
        .w-100 { width: 100%; }
        .logo {
            height: 44px;
            width: auto;
            margin-bottom: 8px;
        }
        .doc-label {
            font-size: 28px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .doc-sub {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }
        .status {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            padding: 6px 14px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .panel {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 14px 16px;
            vertical-align: top;
        }
        .panel-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #64748b;
            margin: 0 0 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
        }
        .panel p { margin: 0 0 4px; }
        .panel strong { color: #0f172a; }
        .muted { color: #64748b; }
        .lines {
            width: 100%;
            margin-top: 22px;
            border: 1px solid #e2e8f0;
        }
        .lines th {
            background: #4f46e5;
            color: #ffffff;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 14px;
            text-align: left;
        }
        .lines th.right,
        .lines td.right { text-align: right; }
        .lines td {
            padding: 11px 14px;
            border-bottom: 1px solid #eef2f7;
            vertical-align: top;
        }
        .lines tr:last-child td { border-bottom: none; }
        .totals-wrap {
            width: 100%;
            margin-top: 16px;
        }
        .totals {
            width: 260px;
            margin-left: auto;
            border: 1px solid #e2e8f0;
        }
        .totals td {
            padding: 9px 14px;
            border-bottom: 1px solid #eef2f7;
        }
        .totals tr.grand td {
            background: #4f46e5;
            color: #ffffff;
            font-weight: bold;
            font-size: 13px;
            border-bottom: none;
        }
        .note {
            margin-top: 14px;
            font-size: 10px;
            color: #64748b;
            line-height: 1.5;
            text-align: right;
            width: 260px;
            margin-left: auto;
        }
        .footer {
            position: fixed;
            left: 40px;
            right: 40px;
            bottom: 28px;
            border-top: 2px solid #4f46e5;
            padding-top: 12px;
        }
        .footer-table td {
            vertical-align: top;
            width: 50%;
            font-size: 10px;
            color: #475569;
            line-height: 1.5;
        }
        .footer-brand {
            font-size: 12px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 4px;
        }
        .footer-legal {
            text-align: right;
            color: #64748b;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('logos/taskcheck-logo.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('taskcheck-logo.png');
        }
        $paidAt = optional($invoice->paid_at)->timezone('Europe/Amsterdam');
    @endphp

    <div class="brand-bar"></div>

    <table class="w-100">
        <tr>
            <td width="58%" style="vertical-align: top;">
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="TaskCheck" class="logo">
                @endif
                <p class="doc-label">Factuur</p>
                <p class="doc-sub">TaskCheck · Slimme checklist &amp; kwaliteitscontrole</p>
            </td>
            <td width="42%" style="vertical-align: top; text-align: right;">
                <span class="status">Betaald</span>
            </td>
        </tr>
    </table>

    <table class="w-100" style="margin-top: 24px;">
        <tr>
            <td width="48%" class="panel">
                <p class="panel-title">Factuurgegevens</p>
                <p><strong>Factuurnummer</strong><br>{{ $invoice->invoice_number }}</p>
                <p style="margin-top:8px;"><strong>Factuurdatum</strong><br>{{ $paidAt ? $paidAt->format('d-m-Y') : '—' }}</p>
                <p style="margin-top:8px;"><strong>Betaald op</strong><br>{{ $paidAt ? $paidAt->format('d-m-Y H:i') : '—' }}</p>
                @if($invoice->payment_id)
                    <p style="margin-top:8px;" class="muted"><strong>Betaling</strong><br>{{ $invoice->payment_id }}</p>
                @endif
            </td>
            <td width="4%"></td>
            <td width="48%" class="panel">
                <p class="panel-title">Factuuradres</p>
                <p><strong>{{ $company->name }}</strong></p>
                @if($company->address)
                    <p class="muted">{{ $company->address }}</p>
                @endif
                @if($company->email)
                    <p class="muted" style="margin-top:6px;">{{ $company->email }}</p>
                @endif
            </td>
        </tr>
    </table>

    <table class="lines">
        <thead>
            <tr>
                <th style="width:70%;">Omschrijving</th>
                <th class="right" style="width:30%;">Bedrag</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice->description ?: 'TaskCheck abonnement' }}</td>
                <td class="right">{{ $invoice->currency }} {{ number_format((float) $invoice->amount_ex_vat, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>BTW ({{ number_format((float) $invoice->vat_rate, 0) }}%)</td>
                <td class="right">{{ $invoice->currency }} {{ number_format((float) $invoice->vat_amount, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals-wrap">
        <tr>
            <td>
                <table class="totals">
                    <tr>
                        <td>Subtotaal excl. BTW</td>
                        <td class="right">{{ $invoice->currency }} {{ number_format((float) $invoice->amount_ex_vat, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>BTW</td>
                        <td class="right">{{ $invoice->currency }} {{ number_format((float) $invoice->vat_amount, 2, ',', '.') }}</td>
                    </tr>
                    <tr class="grand">
                        <td>Totaal incl. BTW</td>
                        <td class="right">{{ $invoice->currency }} {{ number_format((float) $invoice->amount, 2, ',', '.') }}</td>
                    </tr>
                </table>
                <p class="note">
                    Bedankt voor je vertrouwen in TaskCheck. Deze factuur is automatisch gegenereerd en geldt als betalingsbevestiging.
                </p>
            </td>
        </tr>
    </table>

    <div class="footer">
        <table class="footer-table w-100">
            <tr>
                <td>
                    <div class="footer-brand">TaskCheck</div>
                    Deventerseweg 73<br>
                    2994 LE Barendrecht<br>
                    Nederland<br><br>
                    info@taskcheck.nl · www.taskcheck.nl<br>
                    +31 (0)88 1900 999
                </td>
                <td class="footer-legal">
                    BTW NL850541268B01<br>
                    KVK 52661830
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
