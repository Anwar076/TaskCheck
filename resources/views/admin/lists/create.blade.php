@extends('layouts.admin')

@section('page-title', 'Nieuwe lijst')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <a href="{{ route('admin.lists.index') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Takenlijsten</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">Nieuwe lijst</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8">

        {{-- Hero --}}
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Nieuwe takenlijst</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Maak een takenlijst of checklist voor je team</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.lists.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18.75"/>
                            </svg>
                            Terug naar overzicht
                        </a>
                        @if((auth()->user()->company?->subscription_plan ?? 'starter') !== 'starter')
                            <a href="{{ route('admin.lists.ai-import') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                AI Importer
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.lists.store') }}" data-onboarding-target="list-form">
            @csrf

            {{-- Basisgegevens + AI lijstbouwer --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6 scroll-mt-28" data-onboarding-target="list-basics">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Basisgegevens</h2>
                        <p class="text-slate-600 text-sm mt-0.5">Titel en beschrijving van de takenlijst</p>
                    </div>
                    <!-- <div class="inline-flex items-center gap-2 rounded-full bg-slate-50 border border-slate-200 px-3 py-1">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-semibold">AI</span>
                        <span class="text-xs text-slate-700 font-medium">Lijst laten bedenken met AI</span>
                    </div> -->
                </div>
                <div class="p-4 sm:p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 space-y-4">
                            <div data-onboarding-target="list-title">
                                <x-form-label for="title" help="Een duidelijke naam voor de checklist, bijv. Dagelijkse keukencontrole.">Titel <span class="text-red-500">*</span></x-form-label>
                                <input type="text" name="title" id="title" required
                                       class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('title', request('title')) }}"
                                       placeholder="Bijv. Dagelijkse keukencontrole">
                                @error('title')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-form-label for="description" help="Optioneel: leg uit wanneer en door wie deze lijst wordt gebruikt.">Beschrijving</x-form-label>
                                <textarea name="description" id="description" rows="3"
                                          class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Beschrijf waarvoor deze takenlijst dient...">{{ old('description', request('description')) }}</textarea>
                                @error('description')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-form-label for="category" help="Groepeer lijsten, bijv. Schoonmaak, HACCP of Veiligheid.">Categorie</x-form-label>
                                <input type="text" name="category" id="category"
                                       class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('category') }}"
                                       placeholder="Bijv. Schoonmaak, Veiligheid">
                                @error('category')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">AI lijstbouwer</label>
                                <p class="text-xs text-slate-500 mb-2">
                                    Typ kort wat voor lijst je nodig hebt of upload een foto van een papieren checklist. De AI stelt een lijst en taken voor.
                                </p>
                                <textarea id="ai-list-prompt" rows="3"
                                          class="block w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Bijv. Dagelijkse schoonmaaklijst voor de restaurantkeuken, inclusief ramen, vloeren en werkbladen."></textarea>
                            </div>
                            <div class="space-y-1">
                                <label for="ai-source-file" class="block text-xs font-medium text-slate-700">Foto van checklist (optioneel)</label>
                                <input type="file"
                                       id="ai-source-file"
                                       accept="image/jpeg,image/png,image/webp,application/pdf"
                                       class="block w-full text-xs text-slate-600 file:text-xs file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-[11px] text-slate-400 mt-0.5">Ondersteund: foto (jpg, png, webp). PDF/Word volgt later.</p>
                            </div>
                            <button type="button"
                                    id="ai-generate-list-button"
                                    class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                <span>AI lijstvoorstel maken</span>
                            </button>
                            <div id="ai-tasks-preview" class="hidden mt-2 border border-dashed border-slate-200 rounded-xl p-2.5 bg-slate-50/60 max-h-48 overflow-auto">
                                <p class="text-[11px] font-semibold text-slate-700 mb-1.5">Voorgestelde taken (alleen ter inspiratie, worden niet automatisch aangemaakt):</p>
                                <ul id="ai-tasks-preview-list" class="space-y-1 text-[11px] text-slate-700"></ul>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>

            {{-- Hidden field to carry AI-taken mee naar backend --}}
            <input type="hidden" name="ai_tasks" id="ai-tasks-json" value="">

            {{-- Template --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6 scroll-mt-28" data-onboarding-target="list-template-info">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Template <span class="font-normal text-slate-600">(optioneel)</span></h2>
                    <p class="text-slate-600 text-sm mt-0.5">Start met een bestaand template of maak een lege lijst</p>
                </div>
                <div class="p-4 sm:p-6">
                    <x-form-label for="template_id" help="Start met een kant-en-klaar template of begin met een lege lijst.">Template kiezen</x-form-label>
                    <select name="template_id" id="template_id"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Lege lijst aanmaken</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}" {{ old('template_id', request('template_id')) == $template->id ? 'selected' : '' }}>
                                {{ $template->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('template_id')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Instellingen --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6 scroll-mt-28" data-onboarding-target="list-settings">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Instellingen</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Categorie, prioriteit en planning</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-form-label for="priority" help="Hoe belangrijk taken uit deze lijst zijn voor je team.">Prioriteit <span class="text-red-500">*</span></x-form-label>
                            <select name="priority" id="priority" required
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Laag</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Normaal</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Hoog</option>
                                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <x-form-label for="location_id" help="Koppel de lijst aan één locatie of laat leeg voor alle locaties.">Locatie</x-form-label>
                            <select name="location_id" id="location_id"
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Alle locaties / algemeen</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (string) old('location_id') === (string) $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @include('admin.lists.partials.list-form-schedule')

                    <div class="scroll-mt-28 rounded-xl" data-onboarding-target="list-time-slots">
                        @include('admin.lists.partials.list-form-time-slots')
                    </div>
                </div>
            </div>

            {{-- Extra opties --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6 scroll-mt-28" data-onboarding-target="list-extra-options">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Extra opties</h2>
                </div>
                <div class="p-4 sm:p-6 space-y-4">
                    <label class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 p-4 cursor-pointer">
                        <span>
                            <span class="block text-sm font-medium text-slate-800">Moet deze takenlijst gecontroleerd worden?</span>
                            <span class="block mt-0.5 text-sm text-slate-500">Na invullen verschijnt de inzending in Werkcontroles.</span>
                        </span>
                        <span class="relative inline-flex flex-shrink-0">
                            <input type="hidden" name="requires_review" value="0">
                            <input type="checkbox" name="requires_review" value="1" {{ old('requires_review', true) ? 'checked' : '' }} class="peer sr-only">
                            <span class="h-6 w-11 rounded-full bg-slate-300 transition-colors peer-checked:bg-blue-600 peer-focus-visible:ring-2 peer-focus-visible:ring-blue-500 peer-focus-visible:ring-offset-2"></span>
                            <span class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="requires_signature" value="1" {{ old('requires_signature') ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="flex items-center gap-1.5 text-sm text-slate-700">
                                <span>Digitale handtekening vereist bij afronding</span>
                                <x-field-help>De medewerker moet tekenen wanneer de lijst is afgerond.</x-field-help>
                            </span>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="flex items-center gap-1.5 text-sm text-slate-700">
                                <span>Actief — medewerkers kunnen deze lijst zien en uitvoeren</span>
                                <x-field-help>Alleen actieve lijsten zijn zichtbaar voor medewerkers.</x-field-help>
                            </span>
                        </label>
                </div>
            </div>

            {{-- Acties --}}
            <div class="scroll-mt-28 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('admin.lists.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Annuleren
                </a>
                <button type="submit" data-onboarding-target="list-save"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Lijst aanmaken
                </button>
            </div>
        </form>

        {{-- Info --}}
        <div class="mt-8 p-4 sm:p-6 bg-blue-50 border border-blue-100 rounded-xl">
            <h3 class="text-sm font-semibold text-blue-900 mb-2">Wat gebeurt er daarna?</h3>
            <p class="text-sm text-blue-800">
                Na het aanmaken kun je taken toevoegen, aan medewerkers toewijzen en de lijst bewerken. Bij een template worden de taken automatisch overgenomen.
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const aiButton = document.getElementById('ai-generate-list-button');
    const aiPrompt = document.getElementById('ai-list-prompt');
    const aiFileInput = document.getElementById('ai-source-file');
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const categoryInput = document.getElementById('category');
    const tasksPreview = document.getElementById('ai-tasks-preview');
    const tasksPreviewList = document.getElementById('ai-tasks-preview-list');
    const aiTasksJsonInput = document.getElementById('ai-tasks-json');

    if (aiButton) {
        aiButton.addEventListener('click', async function () {
            const prompt = aiPrompt ? aiPrompt.value.trim() : '';
            const file = aiFileInput && aiFileInput.files.length > 0 ? aiFileInput.files[0] : null;

            if (!prompt && !file) {
                alert('Typ een korte beschrijving of kies een bestand voor de AI.');
                if (aiPrompt) aiPrompt.focus();
                return;
            }

            const formData = new FormData();
            if (prompt) formData.append('prompt', prompt);
            if (file) formData.append('source_file', file);

            aiButton.disabled = true;
            aiButton.classList.add('opacity-70', 'cursor-wait');
            const span = aiButton.querySelector('span');
            const originalLabel = span ? span.textContent : '';
            if (span) span.textContent = 'AI is bezig...';

            try {
                const response = await fetch('{{ route('admin.lists.ai-generate') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData,
                });

                let result = null;
                try {
                    result = await response.json();
                } catch (parseError) {
                    console.error('AI lijst parse error', parseError);
                }

                if (!response.ok) {
                    console.error('AI lijst response', response, result);

                    // Toon server-boodschap als die er is
                    if (result && typeof result.message === 'string') {
                        alert(result.message);
                        return;
                    }

                    // Laravel validation errors (422) hebben vaak errors-object
                    if (result && result.errors) {
                        const firstField = Object.keys(result.errors)[0];
                        const firstMsg = result.errors[firstField][0] || null;
                        if (firstMsg) {
                            alert(firstMsg);
                            return;
                        }
                    }

                    alert('AI kon geen lijstvoorstel maken. Probeer het later opnieuw.');
                    return;
                }

                if (!result || !result.success) {
                    alert((result && result.message) || 'AI kon geen lijstvoorstel maken.');
                    return;
                }

                const data = result.data || {};

                if (data.title && !titleInput.value) {
                    titleInput.value = data.title;
                }
                if (data.description && !descriptionInput.value) {
                    descriptionInput.value = data.description;
                }
                if (data.category && !categoryInput.value) {
                    categoryInput.value = data.category;
                }

                if (Array.isArray(data.tasks) && data.tasks.length > 0 && tasksPreview && tasksPreviewList) {
                    // Sla de ruwe taken op in verborgen veld zodat backend ze kan aanmaken
                    if (aiTasksJsonInput) {
                        aiTasksJsonInput.value = JSON.stringify(data.tasks);
                    }

                    tasksPreviewList.innerHTML = '';
                    data.tasks.forEach((task, index) => {
                        const li = document.createElement('li');
                        const title = typeof task.title === 'string' ? task.title : '';
                        const desc = typeof task.description === 'string' ? task.description : '';
                        li.textContent = `${index + 1}. ${title}${desc ? ' — ' + desc : ''}`;
                        tasksPreviewList.appendChild(li);
                    });
                    tasksPreview.classList.remove('hidden');
                }

            } catch (e) {
                console.error('AI list generate exception', e);
                alert('Er ging iets mis bij het aanroepen van de AI.');
            } finally {
                aiButton.disabled = false;
                aiButton.classList.remove('opacity-70', 'cursor-wait');
                if (span) span.textContent = originalLabel || 'AI lijstvoorstel maken';
            }
        });
    }
});
</script>
@endsection
