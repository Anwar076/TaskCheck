<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Factuur {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
            margin: 0;
            padding: 28px 28px 170px;
            background: #ffffff;
        }
        .top-accent {
            height: 10px;
            background: #0f5bd3;
            margin: -28px -28px 24px;
        }
        .header-table,
        .meta-table,
        .line-table,
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-left .title {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            margin: 6px 0 2px;
        }
        .header-left .subtitle {
            color: #3b82f6;
            font-size: 12px;
            font-weight: bold;
        }
        .header-right {
            text-align: right;
        }
        .logo {
            height: 52px;
            width: auto;
            display: block;
        }
        .pill {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            border-radius: 999px;
            padding: 5px 12px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .spacer-20 { height: 20px; }
        .spacer-14 { height: 14px; }
        .box {
            border: 1px solid #dbe2ea;
            border-radius: 8px;
            padding: 12px;
            vertical-align: top;
            width: 48%;
        }
        .box-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
            font-weight: bold;
        }
        .muted { color: #64748b; }
        .meta-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .meta-key {
            color: #64748b;
            width: 120px;
        }
        .line-table {
            border: 1px solid #dbe2ea;
            border-radius: 8px;
            overflow: hidden;
        }
        .line-table th {
            background: #eaf2ff;
            color: #0f5bd3;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.4px;
            padding: 10px 12px;
            border-bottom: 1px solid #d7e6ff;
        }
        .line-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eef2f7;
        }
        .line-table tr:last-child td {
            border-bottom: none;
        }
        .right { text-align: right; }
        .totals-table {
            margin-top: 14px;
            width: 48%;
            margin-left: auto;
            border: 1px solid #dbe2ea;
            border-radius: 8px;
            overflow: hidden;
        }
        .totals-table td {
            padding: 9px 12px;
            border-bottom: 1px solid #eef2f7;
        }
        .totals-table tr:last-child td {
            border-bottom: none;
            background: #0f5bd3;
            color: #ffffff;
            font-weight: bold;
            font-size: 14px;
        }
        .thanks-note {
            margin-top: 10px;
            width: 48%;
            margin-left: auto;
            font-size: 11px;
            color: #64748b;
            line-height: 1.45;
        }
        .footer-note {
            position: fixed;
            left: 28px;
            right: 28px;
            bottom: 24px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            color: #64748b;
            font-size: 11px;
            line-height: 1.35;
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .footer-table td {
            vertical-align: top;
            width: 50%;
            padding: 0;
        }
        .footer-title {
            color: #0f5bd3;
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 13px;
        }
        .footer-legal {
            text-align: right;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('logos/taskcheck-logo.png');
    @endphp
    <div class="top-accent"></div>

    <table class="header-table">
        <tr>
            <td class="header-left">
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="TaskCheck" class="logo">
                @endif
                <div class="title">TaskCheck Factuur</div>
                <div class="subtitle">TaskCheck - Slimme kwaliteitscontrole</div>
            </td>
            <td class="header-right">
                <span class="pill">Betaald</span>
            </td>
        </tr>
    </table>

    <div class="spacer-20"></div>

    <table class="meta-table">
        <tr>
            <td class="box">
                <div class="box-title">Factuurgegevens</div>
                <table class="meta-table">
                    <tr>
                        <td class="meta-key">Factuurnummer</td>
                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                    </tr>
                    <tr>
                        <td class="meta-key">Factuurdatum</td>
                        <td>{{ optional($invoice->paid_at)->timezone('Europe/Amsterdam')->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <td class="meta-key">Betaald op</td>
                        <td>{{ optional($invoice->paid_at)->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="meta-key">Payment ID</td>
                        <td>{{ $invoice->payment_id }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 4%"></td>
            <td class="box">
                <div class="box-title">Klant</div>
                <table class="meta-table">
                    <tr>
                        <td><strong>{{ $company->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="muted">{{ $company->email ?: 'Geen e-mailadres bekend' }}</td>
                    </tr>
                    <tr>
                        <td class="muted">{{ $company->address ?: 'Geen adres opgegeven' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="spacer-14"></div>

    <table class="line-table">
        <thead>
            <tr>
                <th>Omschrijving</th>
                <th class="right">Bedrag</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice->description ?: 'TaskCheck abonnement' }}</td>
                <td class="right">{{ $invoice->currency }} {{ number_format((float) $invoice->amount_ex_vat, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>BTW ({{ number_format((float) $invoice->vat_rate, 2, ',', '.') }}%)</td>
                <td class="right">{{ $invoice->currency }} {{ number_format((float) $invoice->vat_amount, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotaal excl. BTW</td>
            <td class="right">{{ $invoice->currency }} {{ number_format((float) $invoice->amount_ex_vat, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>BTW ({{ number_format((float) $invoice->vat_rate, 2, ',', '.') }}%)</td>
            <td class="right">{{ $invoice->currency }} {{ number_format((float) $invoice->vat_amount, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Totaal incl. BTW</td>
            <td class="right">{{ $invoice->currency }} {{ number_format((float) $invoice->amount, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="thanks-note">
        Bedankt voor je vertrouwen in TaskCheck. Deze factuur is automatisch gegenereerd en geldt als betalingsbevestiging.
    </div>

    <div class="footer-note">
        <table class="footer-table">
            <tr>
                <td>
                    <div class="footer-title">Taskcheck</div>
                    <div>Deventerseweg 73</div>
                    <div>2994 LE Barendrecht</div>
                    <div>The Netherlands</div>
                    <br>
                    <div>E info@taskcheck.nl</div>
                    <div>W www.taskcheck.nl</div>
                    <div>T +31 (0)88 1900 999</div>
                </td>
                <td class="footer-legal">
                    <div>BTW NL850541268B01</div>
                    <div>KVK 52661830</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
