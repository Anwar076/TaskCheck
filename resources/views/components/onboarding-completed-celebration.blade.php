@props([
    'data' => [],
])

@php
    $listTitle = $data['list_title'] ?? 'Je eerste lijst';
@endphp

<div id="onboarding-celebration" class="fixed inset-0 z-[300] flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-labelledby="onboarding-celebration-title">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-celebration-dismiss></div>

    <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/25 overflow-hidden">
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-6 py-8 text-center text-white">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 backdrop-blur">
                <svg class="h-9 w-9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 id="onboarding-celebration-title" class="text-2xl font-bold">Gefeliciteerd!</h2>
            <p class="mt-2 text-blue-100 text-sm sm:text-base">Je account is volledig ingesteld.</p>
        </div>

        <div class="px-6 py-6 space-y-5">
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                <strong>Je eerste lijst is aangemaakt:</strong> {{ $listTitle }}
            </div>

            <div class="space-y-3 text-sm text-slate-600 leading-relaxed">
                <p class="font-semibold text-slate-900">Zo bekijk je hoe de lijst eruitziet voor je medewerker:</p>
                <ul class="space-y-2.5">
                    <li class="flex gap-2.5">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">1</span>
                        <span><strong class="text-slate-800">Wijs de lijst aan jezelf</strong> via &ldquo;Lijst toewijzen&rdquo;, of laat je medewerker inloggen op de app.</span>
                    </li>
                    <li class="flex gap-2.5">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">2</span>
                        <span>Klik rechtsboven op <strong class="text-slate-800">Medewerkersweergave</strong> om te wisselen en de lijst in te vullen.</span>
                    </li>
                </ul>
            </div>

            <div class="flex flex-col gap-2.5 sm:flex-row">
                <button type="button" data-celebration-assign
                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Lijst aan mezelf toewijzen
                </button>
                <form method="POST" action="{{ route('dashboard.switch') }}" class="flex-1">
                    @csrf
                    <input type="hidden" name="mode" value="employee">
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                        Naar medewerkersweergave
                    </button>
                </form>
            </div>

            <button type="button" data-celebration-dismiss
                class="w-full rounded-xl px-4 py-2.5 text-sm font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-colors">
                Later bekijken
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes onboarding-switch-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.5); }
        50% { box-shadow: 0 0 0 6px rgba(37, 99, 235, 0); }
    }
    .onboarding-highlight-switch {
        animation: onboarding-switch-pulse 1.6s ease-out infinite;
        border-color: rgb(96 165 250) !important;
        background-color: rgb(239 246 255) !important;
        color: rgb(29 78 216) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('onboarding-celebration');
        if (!modal) return;

        const close = () => {
            modal.remove();
            document.dispatchEvent(new CustomEvent('admin-help:land-fab'));
        };

        modal.querySelectorAll('[data-celebration-dismiss]').forEach((el) => {
            el.addEventListener('click', close);
        });

        modal.querySelector('[data-celebration-assign]')?.addEventListener('click', () => {
            close();
            const assignSection = document.getElementById('assignments-container');
            if (assignSection) {
                assignSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            if (typeof showAssignModal === 'function') {
                window.setTimeout(() => {
                    showAssignModal();
                    const userRadio = document.querySelector('input[name="assignment_type"][value="user"]');
                    if (userRadio) {
                        userRadio.checked = true;
                        if (typeof toggleAssignmentType === 'function') {
                            toggleAssignmentType();
                        }
                    }
                    const userSelect = document.getElementById('user_ids');
                    const selfId = @json(auth()->id());
                    if (userSelect && selfId) {
                        userSelect.value = String(selfId);
                    }
                }, 350);
            }
        });

        document.querySelectorAll('[data-onboarding-employee-switch]').forEach((el) => {
            el.classList.add('onboarding-highlight-switch');
        });
    });
</script>
