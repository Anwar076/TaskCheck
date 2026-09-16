@extends('layouts.admin')

@section('page-title', 'Dossier')

@section('content')
@php
    $statusLabels = [
        'filled' => 'Ingevuld',
        'in_progress' => 'Bezig',
        'missing' => 'Niet ingevuld',
    ];
    $queryBase = array_filter([
        'location_id' => $selectedLocationId,
        'date' => $date->toDateString(),
        'start_date' => $startDate,
        'end_date' => $endDate,
        'list_id' => $listId,
        'task_id' => $taskId,
    ]);
    $input = 'w-full h-11 px-3.5 border border-slate-200 rounded-xl text-sm bg-white text-slate-800 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400';
    $primaryBtn = 'h-11 px-5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 shadow-sm shadow-blue-600/20 transition-colors inline-flex items-center justify-center';
    $secondaryBtn = 'h-11 px-5 rounded-xl border border-blue-100 bg-white text-blue-700 text-sm font-semibold hover:bg-blue-50 transition-colors inline-flex items-center justify-center';
@endphp
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-10 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
        @include('admin.reports.partials.tabs', ['reportsTab' => 'dossier'])

        <div class="relative bg-white rounded-2xl sm:rounded-3xl shadow-[0_1px_2px_rgba(15,23,42,.04),0_12px_40px_rgba(15,23,42,.08)] border border-slate-100/80 overflow-hidden">
            <div class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-7 sm:py-9">
                <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
                    <div class="absolute -top-24 -right-16 w-72 h-72 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute -bottom-32 -left-20 w-80 h-80 rounded-full bg-indigo-400/20 blur-3xl"></div>
                </div>
                <div class="relative flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-white/15 backdrop-blur-md rounded-2xl flex items-center justify-center ring-1 ring-white/20 shrink-0">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75h16.5m-16.5 4.5h16.5M4.5 19.5h15a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5h-15A1.5 1.5 0 003 6v12a1.5 1.5 0 001.5 1.5z"/></svg>
                        </div>
                        <div>
                            <p class="text-blue-200/80 text-xs sm:text-sm font-medium uppercase tracking-[0.14em] mb-1.5">Bewijsarchief</p>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Dossier</h1>
                            <p class="text-blue-100/85 text-sm sm:text-base mt-2 max-w-xl">Terugvinden wat er is ingevuld. Alle taken van de lijst, ook zonder foto.</p>
                        </div>
                    </div>
                    <div class="relative flex rounded-2xl bg-white/10 p-1 ring-1 ring-white/15">
                        <a href="{{ route('admin.reports.dossier', array_merge($queryBase, ['mode' => 'day'])) }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold {{ $mode === 'day' ? 'bg-white text-blue-700 shadow-sm' : 'text-white/85 hover:text-white' }}">Dagoverzicht</a>
                        <a href="{{ route('admin.reports.dossier', array_merge($queryBase, ['mode' => 'task'])) }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold {{ $mode === 'task' ? 'bg-white text-blue-700 shadow-sm' : 'text-white/85 hover:text-white' }}">Per lijst of taak</a>
                    </div>
                </div>
            </div>

            @if($mode === 'day')
                <form method="GET" action="{{ route('admin.reports.dossier') }}" class="px-4 sm:px-6 lg:px-8 py-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[1fr_1fr_auto_auto] gap-3 items-end bg-slate-50/70 border-t border-slate-100">
                    <input type="hidden" name="mode" value="day">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Datum</label>
                        <input type="date" name="date" value="{{ $date->toDateString() }}" class="{{ $input }}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Locatie</label>
                        <select name="location_id" class="{{ $input }}">
                            <option value="">Alle locaties</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" @selected((string) $selectedLocationId === (string) $location->id)>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="{{ $primaryBtn }}">Tonen</button>
                    <a href="{{ route('admin.reports.dossier.pdf', array_merge($queryBase, ['mode' => 'day'])) }}" class="js-dossier-pdf {{ $secondaryBtn }}">PDF uitdraaien</a>
                </form>
            @else
                <form method="GET" action="{{ route('admin.reports.dossier') }}" class="px-4 sm:px-6 lg:px-8 py-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-3 items-end bg-slate-50/70 border-t border-slate-100">
                    <input type="hidden" name="mode" value="task">
                    <div class="xl:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Lijst</label>
                        <select id="dossier-list-id" name="list_id" required class="{{ $input }}">
                            <option value="">Kies een lijst…</option>
                            @foreach($pickerLists as $list)
                                <option value="{{ $list->id }}" @selected((int) $listId === (int) $list->id)>{{ $list->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="xl:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Taak</label>
                        <select id="dossier-task-id" name="task_id" class="{{ $input }}">
                            <option value="">Alle taken van deze lijst</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Van</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="{{ $input }}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Tot</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="{{ $input }}">
                    </div>
                    <div class="xl:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Locatie</label>
                        <select name="location_id" class="{{ $input }}">
                            <option value="">Alle locaties</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" @selected((string) $selectedLocationId === (string) $location->id)>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="xl:col-span-4 flex flex-wrap gap-2">
                        <button type="submit" class="{{ $primaryBtn }}">Tonen</button>
                    </div>
                </form>
            @endif
        </div>

        @if($mode === 'day')
            <div class="grid grid-cols-3 gap-3 sm:gap-4">
                <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Gepland</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-bold text-slate-900 tabular-nums">{{ $dayOverview['total_count'] }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-blue-100 p-4 sm:p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Ingevuld</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-bold text-blue-700 tabular-nums">{{ $dayOverview['filled_count'] }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Niet ingevuld</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-bold text-slate-700 tabular-nums">{{ $dayOverview['missing_count'] }}</p>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($dayOverview['rows'] as $row)
                    <article class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 bg-gradient-to-r from-blue-50/80 to-white">
                            <div class="min-w-0">
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">{{ $row['list']->title }}</h2>
                                <p class="text-sm text-slate-500 mt-0.5">{{ $row['list']->location?->name ?? 'Geen locatie' }} · {{ $row['list']->tasks->count() }} taken</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $row['status'] === 'filled' ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-100' : ($row['status'] === 'in_progress' ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-100' : 'bg-slate-100 text-slate-600 ring-1 ring-slate-200') }}">{{ $statusLabels[$row['status']] }}</span>
                                @if($row['filled'])
                                    <a href="{{ route('admin.reports.dossier.pdf', ['mode' => 'task', 'list_id' => $row['list']->id, 'start_date' => $date->toDateString(), 'end_date' => $date->toDateString(), 'location_id' => $selectedLocationId]) }}" class="js-dossier-pdf text-xs font-semibold text-blue-700 hover:text-blue-900">PDF van deze lijst</a>
                                @endif
                            </div>
                        </div>
                        @if($row['submissions'] === [])
                            <p class="px-4 sm:px-5 py-6 text-sm text-slate-500">Deze lijst stond gepland, maar is niet ingevuld.</p>
                        @else
                            <div class="p-4 sm:p-5 grid gap-3 sm:grid-cols-2">
                                @foreach($row['submissions'] as $submission)
                                    @foreach($submission['tasks'] as $taskRow)
                                        @include('admin.reports.partials.evidence-card', [
                                            'title' => $taskRow['title'],
                                            'meta' => ($submission['employee'] ?? '').' · '.optional($submission['submitted_at'])->timezone('Europe/Amsterdam')->format('H:i'),
                                            'result' => $taskRow['result'],
                                            'comment' => $taskRow['comment'] ?? null,
                                            'files' => $taskRow['files'],
                                            'proofType' => $taskRow['proof_type'] ?? 'none',
                                            'approvalKey' => $taskRow['approval_key'],
                                            'approvalLabel' => $taskRow['approval_label'],
                                        ])
                                    @endforeach
                                @endforeach
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-100 p-10 text-center">
                        <p class="text-sm font-medium text-slate-700">Geen geplande of ingevulde lijsten op deze dag.</p>
                        <p class="text-sm text-slate-500 mt-1">Kies een andere datum of locatie.</p>
                    </div>
                @endforelse
            </div>
        @elseif(!$history)
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100 p-10 text-center shadow-sm">
                <div class="mx-auto mb-3 w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.007 5.25H3.75v.008h.007v-.008zm-.007 5.25h.007v.008H3.75v-.008z"/></svg>
                </div>
                <p class="text-sm font-medium text-slate-800">Kies eerst een lijst</p>
                <p class="text-sm text-slate-500 mt-1">Daarna kun je één taak kiezen, of de hele lijst bekijken.</p>
            </div>
        @else
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 bg-gradient-to-r from-blue-50/80 to-white">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $history['task']->title ?? $history['list']->title }}</h2>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $history['list']->title }} · {{ \Carbon\Carbon::parse($startDate)->locale('nl')->translatedFormat('d M Y') }} t/m {{ \Carbon\Carbon::parse($endDate)->locale('nl')->translatedFormat('d M Y') }}</p>
                        <p class="text-sm text-blue-700 mt-1 font-medium">{{ $history['filled_count'] }} ingevuld · {{ $history['missing_count'] }} niet ingevuld</p>
                    </div>
                    <a href="{{ route('admin.reports.dossier.pdf', array_merge($queryBase, ['mode' => 'task'])) }}" class="js-dossier-pdf {{ $primaryBtn }}">PDF uitdraaien</a>
                </div>
                <div class="p-4 sm:p-6 grid gap-3 sm:grid-cols-2">
                    @foreach($history['entries'] as $entry)
                        @include('admin.reports.partials.evidence-card', [
                            'title' => $entry['task_title'],
                            'meta' => $entry['date']->locale('nl')->translatedFormat('d M Y').($entry['employee'] ? ' · '.$entry['employee'] : ''),
                            'result' => $entry['result'],
                            'comment' => $entry['comment'] ?? null,
                            'files' => $entry['files'],
                            'proofType' => $entry['proof_type'] ?? 'none',
                            'approvalKey' => $entry['approval_key'],
                            'approvalLabel' => $entry['approval_label'],
                        ])
                    @endforeach
                </div>
            </div>
        @endif
        <p id="dossier-pdf-status" class="hidden text-sm text-blue-700 text-center font-medium"></p>
    </div>
</div>
<script>
document.querySelectorAll('.js-dossier-pdf').forEach((link) => {
    link.addEventListener('click', async (event) => {
        event.preventDefault();
        if (link.dataset.busy === '1') return;
        const status = document.getElementById('dossier-pdf-status');
        const original = link.textContent;
        link.dataset.busy = '1';
        link.textContent = 'PDF maken…';
        link.classList.add('opacity-60', 'pointer-events-none');
        if (status) {
            status.classList.remove('hidden');
            status.textContent = 'De PDF wordt klaargezet. Je kunt op deze pagina blijven.';
        }
        try {
            const response = await fetch(link.href, { credentials: 'same-origin' });
            if (!response.ok) throw new Error('Download mislukt');
            const blob = await response.blob();
            const match = (response.headers.get('Content-Disposition') || '').match(/filename="?([^"]+)"?/i);
            const name = match ? match[1] : 'TaskCheck-uitdraai.pdf';
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = name;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
            if (status) status.textContent = 'PDF is gedownload.';
        } catch (error) {
            if (status) status.textContent = 'PDF maken is mislukt. Probeer het opnieuw.';
            window.open(link.href, '_blank', 'noopener');
        } finally {
            link.dataset.busy = '0';
            link.textContent = original;
            link.classList.remove('opacity-60', 'pointer-events-none');
        }
    });
});
</script>
@if($mode === 'task')
<script>
    const tasksByList = @json($tasksByList);
    const selectedTaskId = @json($taskId);
    const listSelect = document.getElementById('dossier-list-id');
    const taskSelect = document.getElementById('dossier-task-id');

    function fillTasks() {
        const listId = listSelect.value;
        const tasks = tasksByList[listId] || [];
        taskSelect.innerHTML = '<option value="">Alle taken van deze lijst</option>';
        tasks.forEach((task) => {
            const option = document.createElement('option');
            option.value = task.id;
            option.textContent = task.title;
            if (String(selectedTaskId) === String(task.id)) option.selected = true;
            taskSelect.appendChild(option);
        });
        taskSelect.disabled = !listId;
    }

    listSelect.addEventListener('change', () => {
        fillTasks();
        taskSelect.value = '';
    });
    fillTasks();
</script>
@endif
@endsection
