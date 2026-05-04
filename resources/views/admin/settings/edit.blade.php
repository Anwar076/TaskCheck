@extends('layouts.admin')

@section('page-title', 'Organisatie-instellingen')

@section('header')
<div>
    <h1 class="text-2xl font-bold text-gray-900">Organisatie-instellingen</h1>
    <p class="mt-1 text-sm text-gray-500">Beheer de gegevens van uw bedrijf of organisatie</p>
</div>
@endsection

@section('content')
<div class="p-6 sm:p-8">
    <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        <strong>Belangrijk:</strong> Organisatienaam, adres, telefoon en e-mail zijn verplicht voor correcte facturen.
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Logo & Naam Sectie -->
        <div class="border-b border-gray-200 pb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Organisatieprofiel
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Logo upload -->
                <div class="lg:col-span-1">
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
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Organisatienaam <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            value="{{ old('name', $company->name) }}" placeholder="bijv. JAYAS Organisatie">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">Domein</label>
                        <input type="text" name="domain" id="domain"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            value="{{ old('domain', $company->domain) }}" placeholder="bijv. jayas.nl">
                        @error('domain')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Beschrijving</label>
                        <textarea name="description" id="description" rows="3"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Korte omschrijving van uw organisatie">{{ old('description', $company->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contactgegevens Sectie -->
        <div class="border-b border-gray-200 pb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contactgegevens
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        value="{{ old('phone', $company->phone) }}" placeholder="+31 20 123 4567">
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
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" id="website"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        value="{{ old('website', $company->website) }}" placeholder="https://www.bedrijf.nl">
                    @error('website')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
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
});
</script>
@endsection
