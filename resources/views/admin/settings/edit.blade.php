@extends('layouts.admin')

@section('page-title', 'Organisatie-instellingen')

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        @php
            $savedDepartments = collect(old('departments', $company->departments ?? []))
                ->filter(fn($item) => is_string($item) && trim($item) !== '')
                ->values()
                ->all();
        @endphp
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Organisatie-instellingen</h1>
                        <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Beheer de gegevens van uw bedrijf of organisatie</p>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 sm:p-8">
                @include('admin.settings.tabs', ['activeTab' => 'settings'])

                <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <strong>Belangrijk:</strong> Organisatienaam, adres, telefoon en e-mail zijn verplicht voor correcte facturen.
                </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Logo & Naam Sectie -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Organisatieprofiel
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Logo upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                    <div class="flex flex-col items-start gap-4">
                        <div class="relative w-32 h-32 rounded-xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden group">
                            <div class="text-center p-4 {{ $company->logo_path ? 'hidden' : '' }}" id="logo-placeholder">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-xs text-gray-500">Geen logo</p>
                            </div>
                            <img src="{{ $company->logo_path ? Storage::url($company->logo_path) : '' }}" alt="Logo" class="w-full h-full object-contain p-2 {{ $company->logo_path ? '' : 'hidden' }}" id="logo-preview">
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <label class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Upload
                                <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="sr-only">
                            </label>
                            @if($company->logo_path)
                            <label class="inline-flex items-center px-3 py-2 border border-red-200 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-colors cursor-pointer">
                                <input type="checkbox" name="remove_logo" value="1" id="remove_logo" class="sr-only">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Verwijderen
                            </label>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG of GIF. Max 2MB. Aanbevolen: 256×256px</p>
                        @error('logo')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <!-- Naam & Domain -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Organisatienaam <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            value="{{ old('name', $company->name) }}" placeholder="bijv. JAYAS Organisatie">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contactgegevens Sectie -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contactgegevens
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adres <span class="text-red-500">*</span></label>
                    <input type="text" name="address" id="address" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        value="{{ old('address', $company->address) }}" placeholder="Straat, nummer, postcode, plaats">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoon <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" id="phone" required
                        inputmode="numeric" pattern="[0-9]*" maxlength="15"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        value="{{ old('phone', preg_replace('/\D+/', '', (string) $company->phone)) }}" placeholder="Bijv. 0612345678">
                    <p class="mt-1 text-xs text-gray-500">Alleen cijfers, maximaal 15 tekens.</p>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        value="{{ old('email', $company->email) }}" placeholder="info@bedrijf.nl">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Afdelingen Sectie -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Afdelingen
            </h2>
            <div>
                <label for="departments_text" class="block text-sm font-medium text-gray-700 mb-1">Welke afdelingen zijn er binnen jouw bedrijf?</label>
                <div class="rounded-lg border border-gray-300 bg-white p-3">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input type="text" id="department_input"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Bijv. Operaties">
                        <button type="button" id="add_department_btn" class="inline-flex items-center justify-center w-full sm:w-auto min-w-[140px] px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors min-h-[44px] touch-manipulation whitespace-nowrap">
                            Voeg toe
                        </button>
                    </div>

                    <div id="departments_tags" class="mt-3 flex flex-wrap gap-2">
                        @foreach($savedDepartments as $department)
                            <span class="department-tag inline-flex items-center gap-2 rounded-full bg-blue-50 text-blue-800 px-3 py-1 text-sm" data-value="{{ $department }}">
                                {{ $department }}
                                <button type="button" class="remove-department text-blue-600 hover:text-blue-800" aria-label="Verwijderen">&times;</button>
                            </span>
                        @endforeach
                    </div>

                    <div id="departments_inputs">
                        @foreach($savedDepartments as $department)
                            <input type="hidden" name="departments[]" value="{{ $department }}">
                        @endforeach
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">Voeg afdelingen één voor één toe. Deze lijst wordt gebruikt bij gebruiker toevoegen/bewerken.</p>
                @error('departments_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('departments')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Opslaan -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
            <p class="text-sm text-gray-500">Wijzigingen worden direct opgeslagen voor alle gebruikers van uw organisatie.</p>
            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors min-h-[44px] touch-manipulation">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Opslaan
            </button>
        </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logo-preview');
    const logoPlaceholder = document.getElementById('logo-placeholder');

    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (logoPreview) {
                        logoPreview.src = e.target.result;
                        logoPreview.classList.remove('hidden');
                    }
                    if (logoPlaceholder) {
                        logoPlaceholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Remove logo checkbox - when checked, hide preview
    const removeCheckbox = document.querySelector('input[name="remove_logo"]');
    if (removeCheckbox && logoPreview && logoPlaceholder) {
        removeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                logoPreview.classList.add('hidden');
                logoPlaceholder.classList.remove('hidden');
            } else {
                logoPreview.classList.remove('hidden');
                logoPlaceholder.classList.add('hidden');
            }
        });
    }

    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D+/g, '').slice(0, 15);
        });
    }

    const departmentInput = document.getElementById('department_input');
    const addDepartmentBtn = document.getElementById('add_department_btn');
    const tagsContainer = document.getElementById('departments_tags');
    const inputsContainer = document.getElementById('departments_inputs');

    function getDepartments() {
        return Array.from(inputsContainer.querySelectorAll('input[name="departments[]"]'))
            .map(input => input.value.trim())
            .filter(Boolean);
    }

    function syncDepartmentHiddenInputs(departments) {
        inputsContainer.innerHTML = '';
        departments.forEach(dep => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'departments[]';
            hidden.value = dep;
            inputsContainer.appendChild(hidden);
        });
    }

    function renderDepartmentTags(departments) {
        tagsContainer.innerHTML = '';
        departments.forEach(dep => {
            const tag = document.createElement('span');
            tag.className = 'department-tag inline-flex items-center gap-2 rounded-full bg-blue-50 text-blue-800 px-3 py-1 text-sm';
            tag.dataset.value = dep;
            tag.innerHTML = `${dep}<button type="button" class="remove-department text-blue-600 hover:text-blue-800" aria-label="Verwijderen">&times;</button>`;
            tagsContainer.appendChild(tag);
        });
    }

    function addDepartment() {
        if (!departmentInput || !inputsContainer || !tagsContainer) return;
        const value = departmentInput.value.trim();
        if (!value) return;

        const current = getDepartments();
        if (current.includes(value)) {
            departmentInput.value = '';
            return;
        }

        const next = [...current, value];
        syncDepartmentHiddenInputs(next);
        renderDepartmentTags(next);
        departmentInput.value = '';
        departmentInput.focus();
    }

    if (addDepartmentBtn) {
        addDepartmentBtn.addEventListener('click', addDepartment);
    }

    if (departmentInput) {
        departmentInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addDepartment();
            }
        });
    }

    if (tagsContainer) {
        tagsContainer.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-department');
            if (!btn) return;
            const tag = btn.closest('.department-tag');
            if (!tag) return;
            const value = tag.dataset.value;
            const next = getDepartments().filter(dep => dep !== value);
            syncDepartmentHiddenInputs(next);
            renderDepartmentTags(next);
        });
    }
});
</script>
@endsection
