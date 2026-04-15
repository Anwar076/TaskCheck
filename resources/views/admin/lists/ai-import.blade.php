@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-6 text-white">
                <h1 class="text-2xl font-bold">AI Lijst Importer (MVP)</h1>
                <p class="text-indigo-100 text-sm mt-1">Upload PDF, DOCX, XLSX of een foto en laat AI hier automatisch lijsten + taken van maken.</p>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1.5">Extra uitleg voor de AI (optioneel)</label>
                    <textarea id="ai-import-prompt" rows="4" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Bijv. Dit is voor dagelijks restaurant schoonmaakwerk, gebruik duidelijke korte taken."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1.5">Bestand uploaden</label>
                    <input type="file" id="ai-import-file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" class="block w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-slate-500 mt-1">Ondersteund in MVP: PDF, DOCX, XLSX, JPG/PNG/WEBP. Oud DOC/XLS kan beperkt werken.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" id="ai-import-generate-btn" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                        AI voorstel genereren
                    </button>
                    <a href="{{ route('admin.lists.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
                        Terug naar lijst maken
                    </a>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.lists.ai-import.store') }}" id="ai-import-store-form" class="hidden bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
            @csrf
            <input type="hidden" name="import_payload" id="ai-import-payload">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Voorgestelde lijsten</h2>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                    Geselecteerde lijsten aanmaken
                </button>
            </div>
            <p class="text-sm text-slate-600">Vink aan welke lijsten je wilt importeren. Je ziet hieronder per taak ook bewijs type, verplichting, handtekening en checklist.</p>
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

    generateBtn.addEventListener('click', async function () {
        const prompt = (promptInput.value || '').trim();
        const file = fileInput.files.length > 0 ? fileInput.files[0] : null;

        if (!prompt && !file) {
            alert('Vul context in of upload een bestand.');
            return;
        }

        const fd = new FormData();
        if (prompt) fd.append('prompt', prompt);
        if (file) fd.append('source_file', file);

        generateBtn.disabled = true;
        const originalText = generateBtn.textContent;
        generateBtn.textContent = 'AI is bezig...';

        try {
            const response = await fetch('{{ route('admin.lists.ai-import.generate') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: fd
            });

            const result = await response.json().catch(() => null);
            if (!response.ok) {
                alert((result && result.message) ? result.message : 'AI import voorstel mislukt.');
                return;
            }
            if (!result || !result.success || !result.data || !Array.isArray(result.data.lists)) {
                alert('AI gaf geen bruikbaar antwoord terug.');
                return;
            }

            const lists = result.data.lists;
            if (lists.length === 0) {
                alert('Geen lijsten gevonden in dit bestand.');
                return;
            }

            payloadInput.value = JSON.stringify({ lists: lists });
            preview.innerHTML = '';

            lists.forEach((list, idx) => {
                const title = (list.title || '').toString().trim() || `AI lijst ${idx + 1}`;
                const description = (list.description || '').toString().trim();
                const tasks = Array.isArray(list.tasks) ? list.tasks : [];
                const taskItemsHtml = tasks.slice(0, 12).map((task, taskIndex) => {
                    const tTitle = (task.title || '').toString().trim() || `Taak ${taskIndex + 1}`;
                    const tDesc = (task.description || '').toString().trim();
                    const proof = (task.required_proof_type || 'none').toString();
                    const isRequired = !!task.is_required;
                    const needsSignature = !!task.requires_signature;
                    const checklist = Array.isArray(task.checklist_items) ? task.checklist_items.filter(i => (i || '').toString().trim() !== '') : [];

                    const proofBadgeClass = {
                        none: 'bg-slate-100 text-slate-700',
                        photo: 'bg-blue-100 text-blue-700',
                        video: 'bg-indigo-100 text-indigo-700',
                        text: 'bg-amber-100 text-amber-700',
                        file: 'bg-cyan-100 text-cyan-700',
                        any: 'bg-purple-100 text-purple-700',
                    }[proof] || 'bg-slate-100 text-slate-700';

                    const checklistHtml = checklist.length
                        ? `<ul class="mt-1 text-xs text-slate-600 list-disc pl-4">${checklist.slice(0, 5).map(item => `<li>${item}</li>`).join('')}</ul>`
                        : `<p class="mt-1 text-xs text-slate-400">Geen checklist-items</p>`;

                    return `
                        <div class="mt-2 p-2.5 border border-slate-200 rounded-lg bg-white">
                            <p class="text-sm font-medium text-slate-800">${taskIndex + 1}. ${tTitle}</p>
                            ${tDesc ? `<p class="text-xs text-slate-600 mt-1">${tDesc}</p>` : ''}
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                <span class="px-2 py-0.5 rounded-full text-[11px] ${proofBadgeClass}">Bewijs: ${proof}</span>
                                <span class="px-2 py-0.5 rounded-full text-[11px] ${isRequired ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}">${isRequired ? 'Verplicht' : 'Optioneel'}</span>
                                <span class="px-2 py-0.5 rounded-full text-[11px] ${needsSignature ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600'}">${needsSignature ? 'Handtekening vereist' : 'Geen handtekening'}</span>
                            </div>
                            ${checklistHtml}
                        </div>
                    `;
                }).join('');
                const moreCount = tasks.length > 12 ? tasks.length - 12 : 0;

                const wrapper = document.createElement('label');
                wrapper.className = 'block border border-slate-200 rounded-xl p-4 hover:bg-slate-50 cursor-pointer';
                wrapper.innerHTML = `
                    <div class="flex items-start gap-3">
                        <input type="checkbox" name="selected_indices[]" value="${idx}" class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600" checked>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900">${title}</p>
                            ${description ? `<p class="text-sm text-slate-600 mt-1">${description}</p>` : ''}
                            <p class="text-xs text-slate-500 mt-2">${tasks.length} taak/taken</p>
                            <div class="mt-3">
                                ${taskItemsHtml || '<p class="text-xs text-slate-500">Geen taken ontvangen.</p>'}
                                ${moreCount > 0 ? `<p class="text-xs text-slate-500 mt-2">... en nog ${moreCount} taak/taken</p>` : ''}
                            </div>
                        </div>
                    </div>
                `;
                preview.appendChild(wrapper);
            });

            storeForm.classList.remove('hidden');
            storeForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (e) {
            console.error('ai-import-generate exception', e);
            alert('Er ging iets mis bij het genereren van het voorstel.');
        } finally {
            generateBtn.disabled = false;
            generateBtn.textContent = originalText;
        }
    });
});
</script>
@endsection

