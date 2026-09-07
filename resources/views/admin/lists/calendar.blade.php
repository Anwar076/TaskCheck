@extends('layouts.admin')

@section('page-title', 'Agenda')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Agenda</span>
@endsection

@section('content')
<div class="bg-slate-50 py-4 sm:py-6">
    <div class="w-full mx-auto px-3 sm:px-6 lg:px-8">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Agenda</h1>
                <p class="mt-1 text-sm text-slate-500">Bekijk de week of open een dag om op tijd te plannen.</p>
            </div>
            <a href="{{ route('admin.lists.index') }}" class="text-sm font-semibold text-blue-700 hover:underline">Alle lijsten →</a>
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
            'unscheduledLists' => $unscheduledLists,
        ])
    </div>
</div>
@endsection
