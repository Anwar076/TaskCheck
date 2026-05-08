@extends('layouts.super-admin')

@section('page-title', 'Global template toevoegen')

@section('content')
<div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-6">
    <h1 class="text-lg font-semibold text-slate-900 mb-4">Nieuwe global template</h1>
    @include('super-admin.templates._form', [
        'formAction' => route('super-admin.templates.store'),
        'method' => 'POST',
        'submitLabel' => 'Template opslaan',
    ])
</div>
@endsection

