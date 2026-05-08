@php
    $editing = isset($template);
    $tasks = old('tasks');
    if (!is_array($tasks)) {
        $tasks = $editing
            ? $template->templateTasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'instructions' => $task->instructions,
                    'required_proof_type' => $task->required_proof_type,
                    'is_required' => (bool) $task->is_required,
                    'checklist_items' => is_array($task->checklist_items) ? implode("\n", $task->checklist_items) : '',
                ];
            })->toArray()
            : [[
                'title' => '',
                'description' => '',
                'instructions' => '',
                'required_proof_type' => 'photo',
                'is_required' => true,
                'checklist_items' => '',
            ]];
    }
@endphp

<form method="POST" action="{{ $formAction }}" class="space-y-6">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-1">Template naam</label>
            <input name="name" value="{{ old('name', $template->name ?? '') }}" required class="w-full rounded-lg border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Doelgroep</label>
            <select name="target_company_type" class="w-full rounded-lg border-slate-300 text-sm">
                <option value="">Alle bedrijven</option>
                <option value="cleaning" @selected(old('target_company_type', $template->target_company_type ?? '') === 'cleaning')>Schoonmaak</option>
                <option value="horeca" @selected(old('target_company_type', $template->target_company_type ?? '') === 'horeca')>Horeca</option>
                <option value="other" @selected(old('target_company_type', $template->target_company_type ?? '') === 'other')>Anders</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Beschrijving</label>
        <textarea name="description" rows="3" class="w-full rounded-lg border-slate-300 text-sm">{{ old('description', $template->description ?? '') }}</textarea>
    </div>

    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-slate-900">Taken</h3>
            <button type="button" id="add-task-btn" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200">Taak toevoegen</button>
        </div>
        <div id="tasks-container" class="space-y-4">
            @foreach($tasks as $index => $task)
                <div class="rounded-xl border border-slate-200 p-4 task-item">
                    @if(!empty($task['id']))
                        <input type="hidden" name="tasks[{{ $index }}][id]" value="{{ $task['id'] }}">
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Titel</label>
                            <input name="tasks[{{ $index }}][title]" value="{{ $task['title'] ?? '' }}" required class="w-full rounded-lg border-slate-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Bewijs</label>
                            <select name="tasks[{{ $index }}][required_proof_type]" class="w-full rounded-lg border-slate-300 text-sm">
                                @foreach(['none','photo','video','text','file','any'] as $proofType)
                                    <option value="{{ $proofType }}" @selected(($task['required_proof_type'] ?? 'photo') === $proofType)>{{ $proofType }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Beschrijving</label>
                        <textarea name="tasks[{{ $index }}][description]" rows="2" class="w-full rounded-lg border-slate-300 text-sm">{{ $task['description'] ?? '' }}</textarea>
                    </div>
                    <div class="mt-3">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Instructies</label>
                        <textarea name="tasks[{{ $index }}][instructions]" rows="2" class="w-full rounded-lg border-slate-300 text-sm">{{ $task['instructions'] ?? '' }}</textarea>
                    </div>
                    <div class="mt-3">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Checklist items (1 per regel)</label>
                        <textarea name="tasks[{{ $index }}][checklist_items_text]" rows="3" class="w-full rounded-lg border-slate-300 text-sm">{{ $task['checklist_items'] ?? '' }}</textarea>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 text-xs text-slate-700">
                            <input type="checkbox" name="tasks[{{ $index }}][is_required]" value="1" @checked(($task['is_required'] ?? true))>
                            Verplicht
                        </label>
                        <button type="button" class="remove-task-btn rounded-lg border border-red-200 px-2 py-1 text-xs text-red-600 hover:bg-red-50">Verwijderen</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center gap-2">
        <button class="rounded-lg bg-violet-700 text-white px-4 py-2 text-sm font-semibold hover:bg-violet-800">{{ $submitLabel }}</button>
        <a href="{{ route('super-admin.templates.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Annuleren</a>
    </div>
</form>

@push('scripts')
<script>
(() => {
    const container = document.getElementById('tasks-container');
    const addBtn = document.getElementById('add-task-btn');
    if (!container || !addBtn) return;

    const reindex = () => {
        [...container.querySelectorAll('.task-item')].forEach((card, index) => {
            card.querySelectorAll('input, textarea, select').forEach((el) => {
                if (!el.name) return;
                el.name = el.name.replace(/tasks\[\d+\]/g, `tasks[${index}]`);
            });
        });
    };

    addBtn.addEventListener('click', () => {
        const first = container.querySelector('.task-item');
        if (!first) return;
        const clone = first.cloneNode(true);
        clone.querySelectorAll('input, textarea').forEach((el) => {
            if (el.type === 'checkbox') {
                el.checked = true;
            } else if (el.type !== 'hidden') {
                el.value = '';
            }
        });
        clone.querySelectorAll('select').forEach((el) => { el.value = 'photo'; });
        clone.querySelectorAll('input[type="hidden"][name*="[id]"]').forEach((el) => el.remove());
        container.appendChild(clone);
        reindex();
    });

    container.addEventListener('click', (e) => {
        const btn = e.target.closest('.remove-task-btn');
        if (!btn) return;
        if (container.querySelectorAll('.task-item').length <= 1) return;
        btn.closest('.task-item')?.remove();
        reindex();
    });
})();
</script>
@endpush

