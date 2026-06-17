@props(['task', 'compact' => false])

<div class="task-item flex items-center justify-between gap-2 {{ $compact ? 'p-2 bg-slate-50 rounded-lg' : 'p-3 sm:p-4 bg-white rounded-xl border border-slate-100 hover:border-slate-200' }} group cursor-grab" data-task-id="{{ $task->id }}" title="Slepen om volgorde te wijzigen">
    <div class="flex items-center gap-2 min-w-0 flex-1">
        <div class="drag-handle cursor-grab active:cursor-grabbing p-0.5 rounded text-slate-400 hover:text-slate-600 touch-manipulation flex-shrink-0" title="Slepen om te sorteren">
            <svg class="{{ $compact ? 'w-3.5 h-3.5' : 'w-4 h-4' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6h2v2H8V6zm0 4h2v2H8v-2zm0 4h2v2H8v-2zm4-8h2v2h-2V6zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2z"/></svg>
        </div>
        @unless($compact)
            <div class="w-6 h-6 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-xs">{{ $task->order_index ?? '#' }}</span>
            </div>
        @endunless
        <div class="min-w-0">
            <p class="font-medium text-slate-900 {{ $compact ? 'text-sm truncate' : '' }}">{{ $task->title }}</p>
            @if(!$compact && $task->description)
                <p class="text-sm text-slate-600 truncate">{{ Str::limit($task->description, 60) }}</p>
            @endif
        </div>
        @if($task->is_required)
            <span class="px-1.5 py-0.5 bg-red-100 text-red-700 rounded text-[10px] font-medium flex-shrink-0">Verplicht</span>
        @endif
    </div>
    <div class="flex items-center gap-1 flex-shrink-0">
        <a href="{{ route('admin.tasks.edit', $task) }}" class="p-1.5 text-slate-500 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-colors" title="Bewerken">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
        </a>
        @unless($compact)
            <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('Weet je zeker dat je deze taak wilt verwijderen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-1.5 text-slate-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Verwijderen">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </button>
            </form>
        @endunless
    </div>
</div>
