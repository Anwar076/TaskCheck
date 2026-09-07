@extends('layouts.super-admin')
@section('page-title', $user->name)
@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <a href="{{ route('super-admin.dashboard', ['tab' => 'users']) }}" class="font-medium text-slate-500 hover:text-blue-700">Gebruikers</a>
    <span class="text-slate-400">/</span>
    <span class="truncate font-semibold text-slate-900">{{ $user->name }}</span>
@endsection
@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div><h1 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h1><p class="mt-1 text-sm text-slate-500">Gebruikersgegevens en accountbeheer</p></div>
            @include('super-admin.partials.user-actions')
        </div>
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <h2 class="border-b border-slate-100 px-5 py-4 font-semibold text-slate-900">Accountgegevens</h2>
            <dl class="grid gap-6 p-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    'Naam' => $user->name,
                    'E-mailadres' => $user->email,
                    'Telefoon' => $user->phone ?: '—',
                    'Rol' => $user->role === 'employee' ? 'Medewerker' : 'Beheerder',
                    'Locatie' => $user->location?->name ?? 'Alle locaties',
                    'Status' => $user->is_active ? 'Actief' : 'Inactief',
                    'Aangemaakt' => $user->created_at?->format('d-m-Y H:i'),
                ] as $label => $value)
                    <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $label }}</dt><dd class="mt-1 break-words text-sm font-semibold text-slate-900">{{ $value }}</dd></div>
                @endforeach
                <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Bedrijf</dt><dd class="mt-1 text-sm font-semibold">@if($company)<a href="{{ route('super-admin.companies.show', $company) }}" class="text-blue-700 hover:underline">{{ $company->name }}</a>@else — @endif</dd></div>
            </dl>
        </section>
        <a href="{{ route('super-admin.dashboard', ['tab' => 'users']) }}" class="inline-flex text-sm font-semibold text-blue-700 hover:underline">← Terug naar gebruikers</a>
    </div>
    @if($company)
        @include('super-admin.companies._user-dialog', ['dialogUser' => $user, 'returnToUserDetail' => true])
    @endif
@endsection
@push('scripts')
<script>
    document.querySelectorAll('[data-close-dialog]').forEach((button) => button.addEventListener('click', () => button.closest('dialog').close()));
    @if(request()->boolean('edit') && $company)
        document.getElementById(@json('edit-company-user-'.$user->id))?.showModal();
    @endif
</script>
@endpush
