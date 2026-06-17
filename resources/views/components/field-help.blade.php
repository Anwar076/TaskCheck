@props(['text' => null])

<div
    class="relative inline-flex shrink-0"
    x-data="{
        open: false,
        above: false,
        pos: { top: 0, left: 0 },
        place() {
            const btn = this.$refs.trigger;
            if (!btn) return;

            const rect = btn.getBoundingClientRect();
            const gap = 8;
            const tooltipHeight = 96;
            const tooltipWidth = Math.min(240, window.innerWidth - 16);
            const half = tooltipWidth / 2;

            let left = rect.left + (rect.width / 2);
            left = Math.max(half + 8, Math.min(left, window.innerWidth - half - 8));

            const roomBelow = window.innerHeight - rect.bottom - gap;
            this.above = roomBelow < tooltipHeight && rect.top > tooltipHeight + gap;

            this.pos = {
                top: this.above ? rect.top - gap : rect.bottom + gap,
                left,
            };
        },
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => this.place());
            }
        },
    }"
    @keydown.escape.window="open = false"
    @scroll.window="if (open) place()"
    @resize.window="if (open) place()"
>
    <button
        type="button"
        x-ref="trigger"
        class="inline-flex h-4 w-4 items-center justify-center rounded-full border border-slate-300 bg-white text-[10px] font-bold leading-none text-slate-500 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
        @click.stop="toggle()"
        :aria-expanded="open.toString()"
        aria-label="Toon uitleg"
    >?</button>

    <template x-teleport="body">
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.outside="open = false"
            x-cloak
            :style="`
                position: fixed;
                top: ${pos.top}px;
                left: ${pos.left}px;
                transform: translate(-50%, ${above ? '-100%' : '0'});
                z-index: 9999;
            `"
            class="w-60 max-w-[calc(100vw-1rem)] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs leading-relaxed text-slate-600 shadow-xl"
            role="tooltip"
        >
            {{ $text ?? $slot }}
        </div>
    </template>
</div>
