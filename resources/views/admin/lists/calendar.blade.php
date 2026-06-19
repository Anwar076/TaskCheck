@extends('layouts.admin')

@section('page-title', 'Agenda')

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Agenda</h1>
                <p class="mt-1 text-sm text-slate-600">Bekijk welke lijsten gepland staan. Klik op een lijst om taken toe te voegen of aan te passen.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.lists.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Alle lijsten
                </a>
                <a href="{{ route('admin.lists.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Nieuwe lijst
                </a>
            </div>
        </div>

        @if($locations->isNotEmpty())
            <form method="GET" class="mb-4">
                @foreach(request()->except('location_id') as $key => $value)
                    @if(is_string($value) || is_numeric($value))
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2">
                    <label for="location_id" class="text-sm text-slate-600">Locatie</label>
                    <select id="location_id" name="location_id" onchange="this.form.submit()" class="rounded-lg border-0 bg-transparent text-sm font-medium text-slate-900 focus:ring-0">
                        <option value="">Alle locaties</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" @selected($locationId === $location->id)>{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        @endif

        @include('admin.lists.partials.lists-calendar', [
            'calendar' => $calendar,
            'calendarView' => $calendarView,
            'selectedDay' => $selectedDay,
            'miniMonth' => $miniMonth,
            'weekStart' => $weekStart,
            'locationId' => $locationId,
            'lists' => $lists,
        ])
    </div>
</div>
@endsection
