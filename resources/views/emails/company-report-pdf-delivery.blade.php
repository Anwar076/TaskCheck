@extends('emails.layouts.taskcheck', ['pageTitle' => ($report['title'] ?? 'Rapportage').' - '.$company->name, 'headerTitle' => $report['title'] ?? 'Rapportage', 'headerSubtitle' => $company->name, 'metaText' => 'Dit is een automatisch rapport van TaskCheck.'])
@section('email-body')
<h1 style="margin:0 0 12px;font-size:22px;color:#0f172a;">{{ $report['title'] ?? 'Rapportage' }}</h1>
<p style="margin:0;font-size:14px;line-height:1.6;color:#334155;">De gevraagde PDF-rapportage voor {{ $company->name }} is als bijlage toegevoegd.</p>
@endsection
