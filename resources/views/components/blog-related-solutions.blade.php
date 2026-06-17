@props(['solutions' => []])

<div class="mt-12">
    <p class="text-sm font-semibold text-slate-500 uppercase tracking-widest mb-5">Gerelateerde oplossingen</p>
    <div class="grid sm:grid-cols-3 gap-5">
        @foreach($solutions as [$title, $desc, $routeName])
        <a href="{{ route($routeName) }}"
           class="group flex flex-col rounded-2xl border border-slate-200 bg-white p-5 hover:border-blue-300 hover:shadow-sm transition">
            <span class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition">{{ $title }}</span>
            <span class="mt-2 text-sm text-slate-500 leading-relaxed flex-1">{{ $desc }}</span>
            <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600">
                Bekijk oplossing
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </span>
        </a>
        @endforeach
    </div>
</div>
