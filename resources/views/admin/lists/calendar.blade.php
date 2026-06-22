@extends('layouts.admin')

@section('page-title', 'Agenda')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Agenda</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Agenda</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Bekijk welke lijsten gepland staan en pas taken direct aan</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('admin.lists.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                                Alle lijsten
                            </a>
                            <a href="{{ route('admin.lists.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors shadow-sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Nieuwe lijst
                            </a>
                        </div>
                    </div>
                </div>
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
            'unscheduledLists' => $unscheduledLists,
        ])
    </div>
</div>
@endsection
