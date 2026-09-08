@props(['solutions' => []])

<div class="mt-14">
    <p class="blog-kicker">Gerelateerde oplossingen</p>
    <div class="mt-5 grid gap-4 sm:grid-cols-3">
        @foreach($solutions as [$title, $desc, $routeName])
        <a href="{{ route($routeName) }}" class="blog-topic group flex flex-col rounded-[16px] border border-[#e6e8ec] bg-white p-5 transition hover:-translate-y-0.5 hover:border-[#d7e2f7] hover:shadow-[0_12px_32px_-12px_rgba(23,43,99,.15)]">
            <span class="text-sm font-extrabold text-slate-900 transition group-hover:text-blue-700">{{ $title }}</span>
            <span class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">{{ $desc }}</span>
            <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-bold text-blue-600">
                Bekijk oplossing
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </span>
        </a>
        @endforeach
    </div>
</div>
