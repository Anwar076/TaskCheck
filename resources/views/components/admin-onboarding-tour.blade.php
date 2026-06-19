@php
    $onboarding = $onboarding ?? ['active' => false];
    $adminHelp = $adminHelp ?? ['enabled' => false];
    $isHelp = !empty($adminHelp['enabled']) && empty($onboarding['active']);
    $tour = $isHelp ? ($adminHelp['tour'] ?? null) : ($onboarding['tour'] ?? null);
    $rootId = $isHelp ? 'admin-help-tour-root' : 'onboarding-tour-root';
    $fabLabel = $tour['fab_label'] ?? ('Hulp · stap ' . ($tour['step_number'] ?? 1) . '/' . ($tour['total_steps'] ?? 5));
    $helpAnimate = $isHelp && !empty($adminHelp['just_completed']);
@endphp

@if(!empty($tour['slides']))
    <style>
        @keyframes onboarding-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.65), 0 0 0 0 rgba(255, 255, 255, 0.9); }
            50% { box-shadow: 0 0 0 12px rgba(37, 99, 235, 0), 0 0 24px 4px rgba(255, 255, 255, 0.35); }
        }
        @keyframes onboarding-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        @keyframes onboarding-shimmer {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.85; }
        }
        @keyframes admin-help-fab-land {
            0% {
                opacity: 0;
                transform: translate(calc(50vw - 6rem), calc(-50vh + 4rem)) scale(0.25);
            }
            55% {
                opacity: 1;
                transform: translate(8px, -8px) scale(1.06);
            }
            100% {
                opacity: 1;
                transform: translate(0, 0) scale(1);
            }
        }
        .onboarding-tour-focus {
            position: relative;
            z-index: 250 !important;
            pointer-events: auto !important;
            box-shadow: 0 0 0 3px #fff, 0 0 0 6px rgba(37, 99, 235, 0.45) !important;
            border-radius: 0.75rem;
        }
        .onboarding-tour-clickable {
            cursor: pointer !important;
        }
        .onboarding-tour-ring {
            animation: onboarding-pulse 1.6s ease-out infinite;
        }
        .onboarding-tour-ring-inner {
            border: 2px dashed rgba(37, 99, 235, 0.55);
            animation: onboarding-shimmer 1.4s ease-in-out infinite;
        }
        .onboarding-tour-badge {
            animation: onboarding-bounce 1.1s ease-in-out infinite;
        }
        .onboarding-tour-badge[data-placement="below"] {
            animation-direction: reverse;
        }
        [data-tour-chip].admin-help-fab-wait {
            opacity: 0;
            transform: scale(0.6);
            pointer-events: none;
        }
        [data-tour-chip].admin-help-fab-land {
            animation: admin-help-fab-land 1s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            pointer-events: auto;
        }
        [data-tour-arrow].tour-arrow-top {
            top: -7px;
            bottom: auto;
        }
        [data-tour-arrow].tour-arrow-left {
            top: auto;
            bottom: auto;
            left: -7px;
            right: auto;
        }
        [data-tour-arrow].tour-arrow-right {
            top: auto;
            bottom: auto;
            left: auto;
            right: -7px;
        }
    </style>

    <div id="{{ $rootId }}"
         class="contents"
         data-tour='@json($tour)'
         data-help-animate="{{ $helpAnimate ? '1' : '0' }}"
         aria-live="polite">

        <div data-tour-chip @class([
            'fixed bottom-5 left-5 z-[260]',
            'admin-help-fab-wait' => $helpAnimate,
            'hidden' => !$isHelp && !$helpAnimate,
        ])>
            <button type="button" data-tour-chip-open
                class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 shadow-lg shadow-blue-900/10 hover:bg-blue-50 hover:border-blue-300 transition-all">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-white shrink-0">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M12 18h.01"/></svg>
                </span>
                <span data-tour-chip-text>{{ $fabLabel }}</span>
            </button>
        </div>

        <div data-tour-overlay class="fixed inset-0 z-[240] hidden pointer-events-none">
            <div data-tour-backdrop class="pointer-events-none absolute inset-0 bg-slate-950/70 backdrop-blur-[3px]"></div>

            <div data-tour-mask-top class="pointer-events-none absolute bg-slate-950/72 backdrop-blur-[2px] transition-all duration-150"></div>
            <div data-tour-mask-left class="pointer-events-none absolute bg-slate-950/72 backdrop-blur-[2px] transition-all duration-150"></div>
            <div data-tour-mask-right class="pointer-events-none absolute bg-slate-950/72 backdrop-blur-[2px] transition-all duration-150"></div>
            <div data-tour-mask-bottom class="pointer-events-none absolute bg-slate-950/72 backdrop-blur-[2px] transition-all duration-150"></div>

            <div data-tour-ring class="onboarding-tour-ring pointer-events-none fixed hidden rounded-xl border-[3px] border-blue-500 bg-white/[0.04]"></div>
            <div data-tour-ring-inner class="onboarding-tour-ring-inner pointer-events-none fixed hidden rounded-xl"></div>

            <div data-tour-badge class="onboarding-tour-badge pointer-events-none fixed hidden z-[252]">
                <span class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-bold text-white shadow-xl ring-4 ring-blue-400/30">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0L19 5"/></svg>
                    Klik hier
                </span>
            </div>

            <div data-tour-popover class="pointer-events-auto fixed z-[255] w-[min(26rem,calc(100vw-2rem))] rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/25">
                <div data-tour-arrow class="absolute h-3.5 w-3.5 rotate-45 border border-slate-200 bg-white hidden"></div>
                <div class="relative p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span data-tour-step-badge class="inline-flex h-7 min-w-[1.75rem] items-center justify-center rounded-lg bg-blue-600 px-2 text-xs font-bold text-white">1</span>
                            <p data-tour-step-label class="text-xs font-semibold text-slate-500">Stap 1 van 5</p>
                        </div>
                        <button type="button" data-tour-close class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600" aria-label="Sluiten">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <h3 data-tour-title class="mt-3 text-lg font-bold text-slate-900 leading-snug"></h3>
                    <p data-tour-body class="mt-2 text-sm text-slate-600 leading-relaxed"></p>
                    <p data-tour-cta class="mt-3 hidden rounded-xl border border-blue-100 bg-blue-50 px-3.5 py-2.5 text-xs font-semibold text-blue-900"></p>
                    <p data-tour-wait class="mt-3 hidden rounded-xl border border-amber-100 bg-amber-50 px-3.5 py-2.5 text-xs font-semibold text-amber-900">Even wachten…</p>

                    <div data-tour-actions class="mt-4 flex flex-col sm:flex-row flex-wrap gap-2"></div>

                    <div data-tour-nav class="mt-5 flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
                        <div data-tour-dots class="flex items-center gap-1.5"></div>
                        <div class="flex items-center gap-2">
                            <button type="button" data-tour-prev class="rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100">Vorige</button>
                            <button type="button" data-tour-next class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Volgende tip →</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!$isHelp)
        @error('users')
            <div class="fixed bottom-20 left-1/2 z-[270] w-[min(24rem,calc(100vw-2rem))] -translate-x-1/2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-lg">{{ $message }}</div>
        @enderror
    @endif
@endif
