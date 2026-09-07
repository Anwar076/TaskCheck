@if($unscheduledLists->isNotEmpty())
    <div class="border-b border-slate-200 bg-amber-50/40 px-4 py-3" data-unscheduled-list-panel>
        <h3 class="mb-2 text-xs font-semibold text-slate-700">Ongepland · {{ $unscheduledLists->count() }}</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($unscheduledLists as $unscheduledList)
                <a href="{{ route('admin.lists.show', $unscheduledList) }}" draggable="true" data-unscheduled-list
                   data-list-id="{{ $unscheduledList->id }}" data-list-title="{{ $unscheduledList->title }}"
                   data-store-url="{{ route('admin.lists.schedule-slot', $unscheduledList) }}" data-day-store-url="{{ route('admin.lists.schedule-day', $unscheduledList) }}"
                   class="max-w-full break-words rounded-lg border border-amber-200 bg-white px-3 py-2 text-xs font-medium text-amber-900 hover:bg-amber-50">{{ $unscheduledList->title }}</a>
            @endforeach
        </div>
        <p class="mt-2 text-xs text-slate-500">Sleep naar een dag of tijdslot om te plannen.</p>
    </div>
@endif
