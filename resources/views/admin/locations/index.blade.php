@extends('layouts.admin')

@section('page-title', 'Locaties')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Locaties</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        @php $locationLimit = $company?->getLocationLimit(); @endphp

        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.75-5.625-6.75-11.25a6.75 6.75 0 1113.5 0C18.75 15.375 12 21 12 21z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Locaties</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">
                                    @if($company && $locationLimit === -1)
                                        Onbeperkt locaties op jouw plan.
                                    @elseif($company)
                                        {{ $company->getLocationCount() }} van {{ $locationLimit }} locaties in gebruik.
                                    @else
                                        Beheer je locaties en status.
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.locations.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Locatie toevoegen
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 sm:p-8">
                @include('admin.settings.tabs', ['activeTab' => 'locations'])

                <div class="rounded-xl sm:rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
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
            </div>
        </div>
    </div>
</div>
@endsection
