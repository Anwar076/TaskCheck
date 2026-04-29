@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Locaties</h1>
            @php $locationLimit = $company?->getLocationLimit(); @endphp
            <p class="text-sm text-slate-600">
                @if($company && $locationLimit === -1)
                    Onbeperkt locaties op jouw plan.
                @elseif($company)
                    {{ $company->getLocationCount() }} van {{ $locationLimit }} locaties in gebruik.
                @endif
            </p>
        </div>
        <a href="{{ route('admin.locations.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Locatie toevoegen
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Naam</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Adres</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Acties</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($locations as $location)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $location->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $location->address ?: '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-lg px-2 py-1 text-xs font-medium {{ $location->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $location->is_active ? 'Actief' : 'Gearchiveerd' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-2">
                                <a href="{{ route('admin.locations.edit', $location) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">Bewerken</a>
                                @if($location->is_active)
                                    <form method="POST" action="{{ route('admin.locations.destroy', $location) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50" onclick="return confirm('Locatie archiveren?')">Archiveren</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">Nog geen locaties toegevoegd.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
