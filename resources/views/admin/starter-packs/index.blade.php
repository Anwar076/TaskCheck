@extends('layouts.admin')

@section('page-title', 'Starterpacks')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Starterpacks</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

        {{-- Hero --}}
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Starterpacks</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5 max-w-2xl">
                                    Activeer een branche pakket met kant-en-klare controlelijsten. Alle templates komen in je templatebibliotheek  je maakt zelf takenlijsten wanneer je klaar bent.
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/15 text-white text-xs sm:text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                HACCP & NVWA
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/15 text-white text-xs sm:text-sm font-medium">
                                {{ $packs->count() }} branches
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/15 text-white text-xs sm:text-sm font-medium">
                                {{ $packs->sum('template_count') }} templates
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Onboarding highlight: disclaimer + alle packs + uitleg --}}
        <div class="scroll-mt-28 space-y-6 sm:space-y-8" data-onboarding-target="starter-packs-section">
        {{-- Disclaimer --}}
        <div class="bg-amber-50 border border-amber-200 rounded-xl sm:rounded-2xl p-4 sm:p-5">
            <div class="flex gap-3">
                <div class="shrink-0 w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-amber-900">Juridische disclaimer</h2>
                    <p class="mt-1 text-sm text-amber-800 leading-relaxed">{{ $disclaimer }}</p>
                </div>
            </div>
        </div>

        {{-- Packs grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
            @foreach($packs as $pack)
                <article class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col {{ $pack['is_active'] ? 'ring-2 ring-blue-200' : '' }}">
                    <div class="aspect-[3/2] w-full overflow-hidden bg-slate-100">
                        <img
                            src="{{ asset($pack['cover_image'] ?? 'images/starter-packs/'.$pack['slug'].'.jpg') }}"
                            alt="{{ $pack['name'] }}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                            decoding="async"
                        >
                    </div>
                    <div class="p-5 sm:p-6 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="min-w-0">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    {{ $pack['template_count'] }} controlelijsten
                                </span>
                                <h3 class="mt-2 text-lg sm:text-xl font-bold text-slate-900">{{ $pack['name'] }}</h3>
                            </div>
                            @if($pack['is_active'])
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                    Actief
                                </span>
                            @endif
                        </div>

                        <p class="text-sm text-slate-600 leading-relaxed mb-4">{{ $pack['description'] }}</p>

                        <div class="mb-5">
                            @php
                                $templateNames = collect($pack['templates'] ?? [])->pluck('name');
                                $visibleTemplates = $templateNames->take(5);
                                $hiddenTemplates = $templateNames->skip(5);
                                $hiddenTemplateCount = $hiddenTemplates->count();
                            @endphp
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-2">Inbegrepen templates</p>
                            <ul class="space-y-1.5">
                                @foreach($visibleTemplates as $templateName)
                                    <li class="flex items-start gap-2 text-sm text-slate-700">
                                        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        <span>{{ $templateName }}</span>
                                    </li>
                                @endforeach
                                @if($hiddenTemplateCount > 0)
                                    <li>
                                        <details class="group">
                                            <summary class="ml-6 inline-flex cursor-pointer select-none items-center gap-1.5 rounded-lg py-1 text-sm font-medium text-slate-600 transition-colors hover:text-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                                                <span class="group-open:hidden">Toon {{ $hiddenTemplateCount }} extra controlelijsten</span>
                                                <span class="hidden group-open:inline">Verberg extra controlelijsten</span>
                                                <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </summary>
                                            <ul class="mt-1.5 space-y-1.5">
                                                @foreach($hiddenTemplates as $templateName)
                                                    <li class="flex items-start gap-2 text-sm text-slate-700">
                                                        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                        <span>{{ $templateName }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </details>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-100">
                            @if($pack['is_active'])
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                        Bekijk templates
                                    </a>
                                    <form method="POST" action="{{ route('admin.starter-packs.deactivate', $pack['slug']) }}" onsubmit="return confirm('Starterpack &quot;{{ $pack['name'] }}&quot; deactiveren? Alle {{ $pack['template_count'] }} bijbehorende templates worden uit je bibliotheek verwijderd.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 transition-colors">
                                            Deactiveren
                                        </button>
                                    </form>
                                </div>
                            @else
                                <button type="button" data-open-starter-pack-modal="{{ $pack['slug'] }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                                    Starterpack activeren
                                </button>
                            @endif
                        </div>
                    </div>
                </article>

                @unless($pack['is_active'])
                    <div id="starter-pack-modal-{{ $pack['slug'] }}" class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6" role="dialog" aria-modal="true" aria-labelledby="starter-pack-modal-title-{{ $pack['slug'] }}">
                        <button type="button" class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm" data-close-starter-pack-modal="{{ $pack['slug'] }}" aria-label="Sluiten"></button>

                        <div class="relative flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200">
                            <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 py-4 sm:px-6">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Starterpack activeren</p>
                                    <h2 id="starter-pack-modal-title-{{ $pack['slug'] }}" class="mt-1 text-xl font-bold text-slate-900">{{ $pack['name'] }}</h2>
                                    <p class="mt-1 text-sm text-slate-500">Kies welke controlelijsten je wilt toevoegen. Alles staat standaard aangevinkt.</p>
                                </div>
                                <button type="button" class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" data-close-starter-pack-modal="{{ $pack['slug'] }}" aria-label="Sluiten">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <form method="POST" action="{{ route('admin.starter-packs.activate', $pack['slug']) }}" data-starter-pack-form="{{ $pack['slug'] }}" class="flex min-h-0 flex-1 flex-col">
                                @csrf
                                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50 px-5 py-3 sm:px-6">
                                    <p class="text-sm font-medium text-slate-700"><span data-selected-template-count="{{ $pack['slug'] }}">{{ $pack['template_count'] }}</span> van {{ $pack['template_count'] }} geselecteerd</p>
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" data-select-all-templates="{{ $pack['slug'] }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Alles aanvinken</button>
                                        <button type="button" data-clear-all-templates="{{ $pack['slug'] }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Alles uitvinken</button>
                                    </div>
                                </div>

                                <div class="min-h-0 flex-1 overflow-y-auto px-5 py-4 sm:px-6">
                                    <div class="space-y-2">
                                        @foreach($pack['templates'] ?? [] as $template)
                                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-3 transition hover:border-blue-200 hover:bg-blue-50/40">
                                                <input type="checkbox" name="templates[]" value="{{ $template['name'] }}" checked data-starter-pack-template="{{ $pack['slug'] }}" class="mt-1 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                                <span class="min-w-0">
                                                    <span class="block text-sm font-semibold text-slate-900">{{ $template['name'] }}</span>
                                                    @if(!empty($template['description']))
                                                        <span class="mt-0.5 block text-xs leading-relaxed text-slate-500">{{ $template['description'] }}</span>
                                                    @endif
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="mt-3 hidden rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700" data-template-selection-error="{{ $pack['slug'] }}">Selecteer minimaal één controlelijst.</p>
                                </div>

                                <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-white px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                                    <button type="button" data-close-starter-pack-modal="{{ $pack['slug'] }}" class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Annuleren</button>
                                    <button type="submit" class="inline-flex justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Geselecteerde controlelijsten activeren</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endunless
            @endforeach
        </div>

        {{-- Footer info --}}
        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-100 p-5 sm:p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Hoe werkt het?</h3>
            <div class="mt-4 grid sm:grid-cols-3 gap-4">
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold shrink-0">1</div>
                    <div>
                        <p class="text-sm font-medium text-slate-900">Starterpack activeren</p>
                        <p class="text-xs text-slate-500 mt-0.5">Alle compliance-controlelijsten worden toegevoegd aan je templatebibliotheek.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold shrink-0">2</div>
                    <div>
                        <p class="text-sm font-medium text-slate-900">Zelf lijsten maken</p>
                        <p class="text-xs text-slate-500 mt-0.5">Kies welke templates je wilt inzetten en maak daar takenlijsten van wanneer het jou uitkomt.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold shrink-0">3</div>
                    <div>
                        <p class="text-sm font-medium text-slate-900">Deactiveren</p>
                        <p class="text-xs text-slate-500 mt-0.5">Bij deactiveren worden alle templates van dit pakket uit je bibliotheek verwijderd. Bestaande takenlijsten blijven staan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const activeModalIds = new Set();

    function getModal(slug) {
        return document.getElementById(`starter-pack-modal-${slug}`);
    }

    function getTemplateCheckboxes(slug) {
        return Array.from(document.querySelectorAll(`[data-starter-pack-template="${slug}"]`));
    }

    function updateSelectedCount(slug) {
        const checkboxes = getTemplateCheckboxes(slug);
        const selectedCount = checkboxes.filter((checkbox) => checkbox.checked).length;
        const countElement = document.querySelector(`[data-selected-template-count="${slug}"]`);
        const errorElement = document.querySelector(`[data-template-selection-error="${slug}"]`);

        if (countElement) {
            countElement.textContent = selectedCount;
        }
        if (errorElement && selectedCount > 0) {
            errorElement.classList.add('hidden');
        }

        return selectedCount;
    }

    function openModal(slug) {
        const modal = getModal(slug);
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        activeModalIds.add(slug);
        document.body.classList.add('overflow-hidden');
        updateSelectedCount(slug);

        const firstCheckbox = modal.querySelector(`[data-starter-pack-template="${slug}"]`);
        if (firstCheckbox) {
            firstCheckbox.focus({ preventScroll: true });
        }
    }

    function closeModal(slug) {
        const modal = getModal(slug);
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        activeModalIds.delete(slug);

        if (activeModalIds.size === 0) {
            document.body.classList.remove('overflow-hidden');
        }
    }

    document.querySelectorAll('[data-open-starter-pack-modal]').forEach((button) => {
        button.addEventListener('click', function () {
            openModal(this.dataset.openStarterPackModal);
        });
    });

    document.querySelectorAll('[data-close-starter-pack-modal]').forEach((button) => {
        button.addEventListener('click', function () {
            closeModal(this.dataset.closeStarterPackModal);
        });
    });

    document.querySelectorAll('[data-select-all-templates]').forEach((button) => {
        button.addEventListener('click', function () {
            const slug = this.dataset.selectAllTemplates;
            getTemplateCheckboxes(slug).forEach((checkbox) => {
                checkbox.checked = true;
            });
            updateSelectedCount(slug);
        });
    });

    document.querySelectorAll('[data-clear-all-templates]').forEach((button) => {
        button.addEventListener('click', function () {
            const slug = this.dataset.clearAllTemplates;
            getTemplateCheckboxes(slug).forEach((checkbox) => {
                checkbox.checked = false;
            });
            updateSelectedCount(slug);
        });
    });

    document.querySelectorAll('[data-starter-pack-template]').forEach((checkbox) => {
        checkbox.addEventListener('change', function () {
            updateSelectedCount(this.dataset.starterPackTemplate);
        });
    });

    document.querySelectorAll('[data-starter-pack-form]').forEach((form) => {
        form.addEventListener('submit', function (event) {
            const slug = this.dataset.starterPackForm;
            const selectedCount = updateSelectedCount(slug);
            const errorElement = document.querySelector(`[data-template-selection-error="${slug}"]`);

            if (selectedCount === 0) {
                event.preventDefault();
                if (errorElement) {
                    errorElement.classList.remove('hidden');
                }
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;

        Array.from(activeModalIds).forEach((slug) => closeModal(slug));
    });
});
</script>
@endsection
