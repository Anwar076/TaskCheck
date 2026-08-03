@extends($company ? 'layouts.super-admin' : 'layouts.admin')

@section('page-title', 'AI import')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    @if($company)
        <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}" class="text-slate-500 hover:text-blue-700 font-medium transition-colors">Bedrijven</a>
        <span class="text-slate-400">/</span>
        <a href="{{ route('super-admin.companies.show', $company) }}" class="text-slate-500 hover:text-blue-700 font-medium transition-colors">{{ $company->name }}</a>
    @else
        <a href="{{ route('admin.lists.index') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Takenlijsten</a>
    @endif
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">AI import</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-6 text-white">
                <h1 class="text-2xl font-bold">AI-lijsten importeren</h1>
                <p class="text-indigo-100 text-sm mt-1">Upload maximaal 5 documenten tegelijk. AI maakt van ieder document precies één lijst met bijbehorende taken.</p>
                @if($company)
                    <div class="mt-4 inline-flex items-center gap-2 rounded-xl bg-white/15 px-3 py-2 text-sm font-semibold ring-1 ring-white/20">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                        Wordt gekoppeld aan {{ $company->name }}
                    </div>
                @endif
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1.5">Extra uitleg voor de AI (optioneel)</label>
                    <textarea id="ai-import-prompt" rows="4" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Bijv. Deze lijsten zijn voor dagelijks restaurantwerk. Maak alleen fotobewijs verplicht bij de eindcontrole."></textarea>
                    <p class="text-xs text-slate-500 mt-1">AI houdt de bestandsnaam aan als lijstnaam, maakt één lijst per bestand en neemt iedere herkenbare taak één keer over.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1.5">Bestanden uploaden</label>
                    <input type="file" id="ai-import-file" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" class="block w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-slate-500 mt-1">Maximaal 5 bestanden van elk 12 MB. Ondersteund: PDF, DOCX, XLSX, JPG/PNG/WEBP. Oud DOC/XLS kan beperkt werken.</p>
                    <div id="ai-import-files" class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2"></div>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" id="ai-import-generate-btn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                        AI voorstel genereren
                    </button>
                    <a href="{{ $company ? route('super-admin.companies.show', $company) : route('admin.lists.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
                        {{ $company ? 'Terug naar klant' : 'Terug naar lijst maken' }}
                    </a>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ $company ? route('super-admin.companies.lists.ai-import.store', $company) : route('admin.lists.ai-import.store') }}" id="ai-import-store-form" class="hidden bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
            @csrf
            <input type="hidden" name="import_payload" id="ai-import-payload">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Voorgestelde lijsten</h2>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                    Geselecteerde lijsten aanmaken
                </button>
            </div>
            <p class="text-sm text-slate-600">Controleer en bewerk de lijsten vóór het opslaan. Je kunt taken hernoemen, ordenen, verwijderen en het bewijstype aanpassen.</p>
            <div id="ai-import-preview" class="space-y-3"></div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const generateBtn = document.getElementById('ai-import-generate-btn');
    const promptInput = document.getElementById('ai-import-prompt');
    const fileInput = document.getElementById('ai-import-file');
    const storeForm = document.getElementById('ai-import-store-form');
    const payloadInput = document.getElementById('ai-import-payload');
    const preview = document.getElementById('ai-import-preview');
    const filesPreview = document.getElementById('ai-import-files');
    let generatedLists = [];
    const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (char) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'
    })[char]);

    const syncPayload = () => payloadInput.value = JSON.stringify({ lists: generatedLists });
    const renderFiles = (status = 'Klaar voor verwerking') => {
        filesPreview.innerHTML = Array.from(fileInput.files || []).map((file, index) => `<div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-xs font-bold text-blue-700">${index + 1}</span><div class="min-w-0 flex-1"><p class="truncate text-sm font-medium text-slate-800">${escapeHtml(file.name)}</p><p class="text-xs text-slate-500">${(file.size / 1024 / 1024).toFixed(1)} MB · <span data-file-status>${status}</span></p></div></div>`).join('');
    };
    const setFileStatus = (index, status, state = 'normal') => {
        const element = filesPreview.querySelectorAll('[data-file-status]')[index];
        if (!element) return;
        element.textContent = status;
        element.className = state === 'error' ? 'font-semibold text-red-600' : (state === 'success' ? 'font-semibold text-emerald-600' : 'text-slate-500');
    };
    fileInput.addEventListener('change', () => renderFiles());
    const renderPreview = () => {
        const seen = new Set();
        preview.innerHTML = '';
        generatedLists.forEach((list, idx) => {
            list.tasks = Array.isArray(list.tasks) ? list.tasks : [];
            const duplicateTasks = new Set();
            list.tasks.forEach((task) => { const key = String(task.title || '').trim().toLowerCase(); if (key && seen.has(`${idx}:${key}`)) duplicateTasks.add(key); seen.add(`${idx}:${key}`); });
            const tasksHtml = list.tasks.map((task, taskIndex) => {
                const key = String(task.title || '').trim().toLowerCase();
                return `<div class="rounded-xl border ${duplicateTasks.has(key) ? 'border-amber-300 bg-amber-50' : 'border-slate-200 bg-white'} p-3" data-task-row>
                    <div class="flex items-start gap-2"><span class="mt-2 text-slate-400" title="Volgorde">↕</span><div class="min-w-0 flex-1 space-y-2">
                        <div class="flex flex-col gap-2 sm:flex-row"><input value="${escapeHtml(task.title || '')}" data-task-field="title" data-list-index="${idx}" data-task-index="${taskIndex}" class="min-w-0 flex-1 rounded-lg border-slate-300 text-sm font-medium" aria-label="Taaknaam"><select data-task-field="required_proof_type" data-list-index="${idx}" data-task-index="${taskIndex}" class="rounded-lg border-slate-300 text-xs"><option value="none" ${task.required_proof_type === 'none' ? 'selected' : ''}>Geen bewijs</option><option value="photo" ${task.required_proof_type === 'photo' ? 'selected' : ''}>Foto</option><option value="video" ${task.required_proof_type === 'video' ? 'selected' : ''}>Video</option><option value="text" ${task.required_proof_type === 'text' ? 'selected' : ''}>Tekst</option><option value="file" ${task.required_proof_type === 'file' ? 'selected' : ''}>Bestand</option><option value="any" ${task.required_proof_type === 'any' ? 'selected' : ''}>Willekeurig</option></select></div>
                        <textarea rows="2" data-task-field="description" data-list-index="${idx}" data-task-index="${taskIndex}" class="w-full rounded-lg border-slate-300 text-xs" placeholder="Uitleg bij deze taak">${escapeHtml(task.description || '')}</textarea>
                        <div class="flex flex-wrap items-center gap-2"><label class="text-xs text-slate-600"><input type="checkbox" data-task-field="is_required" data-list-index="${idx}" data-task-index="${taskIndex}" ${task.is_required ? 'checked' : ''}> Verplicht</label><label class="text-xs text-slate-600"><input type="checkbox" data-task-field="requires_signature" data-list-index="${idx}" data-task-index="${taskIndex}" ${task.requires_signature ? 'checked' : ''}> Handtekening</label>${duplicateTasks.has(key) ? '<span class="rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-800">Mogelijk duplicaat</span>' : ''}<span class="flex-1"></span><button type="button" data-task-move="up" data-list-index="${idx}" data-task-index="${taskIndex}" class="text-xs font-semibold text-slate-500">Omhoog</button><button type="button" data-task-move="down" data-list-index="${idx}" data-task-index="${taskIndex}" class="text-xs font-semibold text-slate-500">Omlaag</button><button type="button" data-task-remove data-list-index="${idx}" data-task-index="${taskIndex}" class="text-xs font-semibold text-red-600">Verwijder</button></div>
                    </div></div></div>`;
            }).join('');
            const wrapper = document.createElement('section');
            wrapper.className = 'rounded-2xl border border-slate-200 bg-slate-50 p-4';
            wrapper.innerHTML = `<div class="flex items-start gap-3"><input type="checkbox" name="selected_indices[]" value="${idx}" class="mt-3 h-4 w-4 rounded border-slate-300 text-blue-600" checked><div class="min-w-0 flex-1"><div class="flex gap-2"><input value="${escapeHtml(list.title || `AI lijst ${idx + 1}`)}" data-list-title="${idx}" class="min-w-0 flex-1 rounded-xl border-slate-300 font-semibold"><button type="button" data-list-remove="${idx}" class="rounded-xl border border-red-200 px-3 text-sm font-semibold text-red-600">Verwijder lijst</button></div><textarea rows="2" data-list-description="${idx}" class="mt-2 w-full rounded-xl border-slate-300 text-sm" placeholder="Beschrijving">${escapeHtml(list.description || '')}</textarea><p class="my-3 text-xs font-semibold text-slate-500">${list.tasks.length} taken</p><div class="space-y-2">${tasksHtml || '<p class="rounded-xl border border-dashed border-slate-300 p-5 text-center text-sm text-slate-500">Geen taken gevonden.</p>'}</div></div></div>`;
            preview.appendChild(wrapper);
        });
        syncPayload();
    };

    preview.addEventListener('input', (event) => {
        const el = event.target;
        if (el.dataset.listTitle !== undefined) generatedLists[Number(el.dataset.listTitle)].title = el.value;
        if (el.dataset.listDescription !== undefined) generatedLists[Number(el.dataset.listDescription)].description = el.value;
        if (el.dataset.taskField) { const task = generatedLists[Number(el.dataset.listIndex)].tasks[Number(el.dataset.taskIndex)]; task[el.dataset.taskField] = el.type === 'checkbox' ? el.checked : el.value; }
        syncPayload();
    });
    preview.addEventListener('change', (event) => { if (event.target.dataset.taskField) { const el = event.target; generatedLists[Number(el.dataset.listIndex)].tasks[Number(el.dataset.taskIndex)][el.dataset.taskField] = el.type === 'checkbox' ? el.checked : el.value; syncPayload(); } });
    preview.addEventListener('click', (event) => {
        const button = event.target.closest('button'); if (!button) return;
        if (button.dataset.listRemove !== undefined) generatedLists.splice(Number(button.dataset.listRemove), 1);
        if (button.dataset.taskRemove !== undefined) generatedLists[Number(button.dataset.listIndex)].tasks.splice(Number(button.dataset.taskIndex), 1);
        if (button.dataset.taskMove) { const tasks = generatedLists[Number(button.dataset.listIndex)].tasks; const from = Number(button.dataset.taskIndex); const to = button.dataset.taskMove === 'up' ? from - 1 : from + 1; if (to >= 0 && to < tasks.length) [tasks[from], tasks[to]] = [tasks[to], tasks[from]]; }
        renderPreview();
    });

    generateBtn.addEventListener('click', async function () {
        const prompt = (promptInput.value || '').trim();
        const files = Array.from(fileInput.files || []);

        if (!prompt && files.length === 0) {
            alert('Vul context in of upload maximaal 5 bestanden.');
            return;
        }
        if (files.length > 5) {
            alert('Selecteer maximaal 5 bestanden per import.');
            return;
        }

        generateBtn.disabled = true;
        renderFiles('Wacht op verwerking…');
        const originalText = generateBtn.textContent;
        generateBtn.textContent = files.length > 1 ? `0 van ${files.length} verwerkt…` : 'AI is bezig…';

        try {
            const endpoint = '{{ $company ? route('super-admin.companies.lists.ai-import.generate', $company) : route('admin.lists.ai-import.generate') }}';
            const jobs = files.length ? files.map((file, index) => ({ file, index })) : [{ file: null, index: 0 }];
            const results = new Array(jobs.length);
            let nextJob = 0;
            let completed = 0;

            const processJobs = async () => {
                while (nextJob < jobs.length) {
                    const jobIndex = nextJob++;
                    const job = jobs[jobIndex];
                    if (job.file) setFileStatus(job.index, 'AI verwerkt dit document…');
                    const fd = new FormData();
                    if (prompt) fd.append('prompt', prompt);
                    if (job.file) fd.append('source_files[]', job.file);

                    try {
                        const response = await fetch(endpoint, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: fd
                        });
                        const result = await response.json().catch(() => null);
                        if (!response.ok || !result?.success || !Array.isArray(result?.data?.lists) || result.data.lists.length === 0) {
                            throw new Error(result?.message || 'Geen bruikbaar AI-antwoord ontvangen.');
                        }
                        results[jobIndex] = { ok: true, lists: result.data.lists };
                        if (job.file) setFileStatus(job.index, 'Voorstel gereed', 'success');
                    } catch (error) {
                        console.error('ai-import file failed', job.file?.name, error);
                        results[jobIndex] = { ok: false, message: error.message, file: job.file?.name || 'Beschrijving' };
                        if (job.file) setFileStatus(job.index, 'Mislukt — probeer opnieuw', 'error');
                    } finally {
                        completed++;
                        generateBtn.textContent = `${completed} van ${jobs.length} verwerkt…`;
                    }
                }
            };

            await Promise.all(Array.from({ length: Math.min(3, jobs.length) }, () => processJobs()));
            generatedLists = results.filter(result => result?.ok).flatMap(result => result.lists);
            const failures = results.filter(result => result && !result.ok);
            if (generatedLists.length === 0) {
                alert(failures[0]?.message || 'Geen lijsten gevonden in de bestanden.');
                return;
            }
            renderPreview();
            storeForm.classList.remove('hidden');
            storeForm.scrollIntoView({ behavior: 'smooth', block: 'start' });

            if (failures.length) {
                alert(`${generatedLists.length} ${generatedLists.length === 1 ? 'lijst is' : 'lijsten zijn'} wel verwerkt. Niet gelukt: ${failures.map(item => item.file).join(', ')}. Je kunt de geslaagde lijsten opslaan of de import opnieuw proberen.`);
            }
        } catch (e) {
            console.error('ai-import-generate exception', e);
            alert('Er ging iets mis bij het verwerken van de documenten. Probeer het opnieuw.');
        } finally {
            generateBtn.disabled = false;
            generateBtn.textContent = originalText;
        }
    });
});
</script>
@endsection
