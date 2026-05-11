@extends('layouts.super-admin')

@section('page-title', 'AI Template Import')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="aiTemplateImport()">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('super-admin.templates.index') }}" class="text-slate-400 hover:text-slate-600">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900">AI Template Import</h1>
            <p class="text-sm text-slate-500 mt-0.5">Upload een afbeelding, PDF of Word-bestand — de AI zet het om naar een sjabloon.</p>
        </div>
    </div>

    {{-- Input card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">

        {{-- File upload --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Bestand uploaden <span class="text-slate-400 font-normal">(afbeelding, PDF of DOCX — max 12 MB)</span></label>
            <label
                class="flex flex-col items-center justify-center gap-2 border-2 border-dashed rounded-xl p-8 cursor-pointer transition"
                :class="fileName ? 'border-violet-400 bg-violet-50' : 'border-slate-200 bg-slate-50 hover:border-violet-300 hover:bg-violet-50/50'"
                @dragover.prevent
                @drop.prevent="handleDrop($event)"
            >
                <input type="file" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp" @change="handleFile($event)">
                <template x-if="!fileName">
                    <div class="flex flex-col items-center gap-1 text-slate-400">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        <span class="text-sm">Klik om bestand te kiezen of sleep het hier naartoe</span>
                    </div>
                </template>
                <template x-if="fileName">
                    <div class="flex items-center gap-2 text-violet-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium" x-text="fileName"></span>
                        <button type="button" @click.stop="clearFile()" class="ml-2 text-slate-400 hover:text-red-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </label>
        </div>

        {{-- Doelgroep --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Voor wie is dit sjabloon?</label>
            <div class="grid grid-cols-3 gap-3">
                <template x-for="opt in [
                    { value: 'horeca',   label: 'Horeca',     color: 'orange' },
                    { value: 'cleaning', label: 'Schoonmaak', color: 'teal'   },
                    { value: 'other',    label: 'Anders',     color: 'violet' },
                ]" :key="opt.value">
                    <button
                        type="button"
                        @click="companyType = opt.value"
                        class="flex items-center justify-center rounded-xl border-2 px-3 py-3 text-sm font-medium transition"
                        :class="companyType === opt.value
                            ? (opt.color === 'orange' ? 'border-orange-400 bg-orange-50 text-orange-700' : opt.color === 'teal' ? 'border-teal-400 bg-teal-50 text-teal-700' : 'border-violet-400 bg-violet-50 text-violet-700')
                            : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300'"
                    >
                        <span x-text="opt.label"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Extra prompt --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Extra context <span class="text-slate-400 font-normal">(optioneel)</span></label>
            <textarea
                x-model="prompt"
                rows="3"
                class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent"
                placeholder="Bijv. 'Dit is een sluitings checklist voor een restaurant' of specifieke instructies voor de AI..."
            ></textarea>
        </div>

        {{-- Error message --}}
        <div x-show="errorMsg" x-cloak class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700" x-text="errorMsg"></div>

        {{-- Generate button --}}
        <div class="flex justify-end">
            <button
                type="button"
                @click="generate()"
                :disabled="loading || (!fileName && prompt.trim() === '')"
                class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold text-white transition"
                :class="loading || (!fileName && prompt.trim() === '') ? 'bg-slate-400 cursor-not-allowed' : 'bg-violet-700 hover:bg-violet-800'"
            >
                <template x-if="loading">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                </template>
                <template x-if="!loading">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                </template>
                <span x-text="loading ? 'AI verwerkt...' : 'Sjabloon genereren'"></span>
            </button>
        </div>
    </div>

    {{-- Results --}}
    <template x-if="results.length > 0">
        <form method="POST" action="{{ route('super-admin.templates.ai-import.store') }}" @submit.prevent="submitImport($event)">
            @csrf
            <input type="hidden" name="import_payload" :value="JSON.stringify({ templates: results })">

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">
                        <span x-text="results.length"></span> sjabloon(en) gegenereerd
                    </h2>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="toggleAll(true)" class="text-xs text-violet-700 hover:underline">Alles selecteren</button>
                        <button type="button" @click="toggleAll(false)" class="text-xs text-slate-500 hover:underline">Deselecteer</button>
                    </div>
                </div>

                <template x-for="(tpl, idx) in results" :key="idx">
                    <div class="bg-white rounded-xl border shadow-sm overflow-hidden"
                         :class="selected.includes(idx) ? 'border-violet-400 ring-1 ring-violet-300' : 'border-slate-200'">
                        <div class="flex items-start gap-3 p-4 cursor-pointer" @click="toggleSelect(idx)">
                            <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded border-2 transition"
                                 :class="selected.includes(idx) ? 'border-violet-600 bg-violet-600' : 'border-slate-300 bg-white'">
                                <svg x-show="selected.includes(idx)" class="h-3 w-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-semibold text-slate-900" x-text="tpl.name"></p>
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                          :class="{
                                              'bg-teal-100 text-teal-700': tpl.target_company_type === 'cleaning',
                                              'bg-orange-100 text-orange-700': tpl.target_company_type === 'horeca',
                                              'bg-slate-100 text-slate-600': tpl.target_company_type === 'other'
                                          }"
                                          x-text="tpl.target_company_type === 'cleaning' ? 'Schoonmaak' : tpl.target_company_type === 'horeca' ? 'Horeca' : 'Anders'">
                                    </span>
                                    <span class="text-xs text-slate-400" x-text="tpl.tasks.length + ' taken'"></span>
                                </div>
                                <p class="text-sm text-slate-500 mt-0.5 truncate" x-text="tpl.description || '—'"></p>
                            </div>
                        </div>

                        {{-- Task list --}}
                        <div class="border-t border-slate-100 divide-y divide-slate-50">
                            <template x-for="(task, ti) in tpl.tasks" :key="ti">
                                <div class="px-4 py-2.5 flex items-start gap-3">
                                    <div class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-xs font-semibold bg-slate-100 text-slate-500" x-text="ti + 1"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-800" x-text="task.title"></p>
                                        <p class="text-xs text-slate-400 mt-0.5" x-text="task.description || ''"></p>
                                        <template x-if="task.checklist_items && task.checklist_items.length">
                                            <ul class="mt-1 space-y-0.5">
                                                <template x-for="item in task.checklist_items" :key="item">
                                                    <li class="flex items-center gap-1.5 text-xs text-slate-500">
                                                        <svg class="h-3 w-3 text-slate-300 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                        <span x-text="item"></span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </template>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <template x-if="task.required_proof_type && task.required_proof_type !== 'none'">
                                            <span class="text-xs bg-blue-50 text-blue-600 border border-blue-100 px-1.5 py-0.5 rounded" x-text="task.required_proof_type"></span>
                                        </template>
                                        <template x-if="task.start_time">
                                            <span class="text-xs text-slate-400" x-text="task.start_time + (task.end_time ? '–' + task.end_time : '')"></span>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Hidden inputs for selected indices --}}
                <template x-for="idx in selected" :key="idx">
                    <input type="hidden" name="selected_indices[]" :value="idx">
                </template>

                <div class="flex items-center justify-between pt-2">
                    <p class="text-sm text-slate-500"><span x-text="selected.length"></span> van <span x-text="results.length"></span> geselecteerd</p>
                    <button
                        type="submit"
                        :disabled="selected.length === 0 || saving"
                        class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold text-white transition"
                        :class="selected.length === 0 || saving ? 'bg-slate-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700'"
                    >
                        <template x-if="saving">
                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                        </template>
                        <span x-text="saving ? 'Opslaan...' : 'Geselecteerde sjablonen opslaan als concept'"></span>
                    </button>
                </div>
            </div>
        </form>
    </template>
</div>

<script>
// File stored outside Alpine to avoid Proxy wrapping corrupting the File object
let _aiImportFile = null;

function aiTemplateImport() {
    return {
        prompt: '',
        fileName: '',
        companyType: 'other',
        loading: false,
        saving: false,
        errorMsg: '',
        results: [],
        selected: [],

        handleFile(event) {
            const file = event.target.files[0];
            if (file) {
                _aiImportFile = file;
                this.fileName = file.name;
            }
        },

        handleDrop(event) {
            const file = event.dataTransfer.files[0];
            if (file) {
                _aiImportFile = file;
                this.fileName = file.name;
            }
        },

        clearFile() {
            _aiImportFile = null;
            this.fileName = '';
        },

        toggleSelect(idx) {
            if (this.selected.includes(idx)) {
                this.selected = this.selected.filter(i => i !== idx);
            } else {
                this.selected.push(idx);
            }
        },

        toggleAll(val) {
            if (val) {
                this.selected = this.results.map((_, i) => i);
            } else {
                this.selected = [];
            }
        },

        async generate() {
            if (!this.fileName && this.prompt.trim() === '') return;

            this.errorMsg = '';
            this.results = [];
            this.selected = [];
            this.loading = true;

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('company_type', this.companyType);
            if (this.prompt.trim()) {
                formData.append('prompt', this.prompt.trim());
            }
            if (_aiImportFile) {
                formData.append('source_file', _aiImportFile);
            }

            try {
                const resp = await fetch('{{ route('super-admin.templates.ai-import.generate') }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData,
                });
                const json = await resp.json();
                if (!json.success) {
                    this.errorMsg = json.message || 'Onbekende fout.';
                } else {
                    this.results = json.data.templates || [];
                    this.selected = this.results.map((_, i) => i);
                }
            } catch (e) {
                this.errorMsg = 'Netwerkfout: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        submitImport(event) {
            this.saving = true;
            event.target.submit();
        },
    };
}
</script>
@endsection
