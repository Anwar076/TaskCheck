@php
    $file = collect($files ?? [])->first(fn ($item) => is_array($item) && str_starts_with($item['mime_type'] ?? '', 'image/'));
    $approvalKey = $approvalKey ?? 'pending';
    $approvalClasses = [
        'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
        'pending' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'rejected' => 'bg-red-50 text-red-700 ring-red-100',
        'missing' => 'bg-slate-100 text-slate-600 ring-slate-200',
    ];
@endphp
<div class="flex gap-3 rounded-2xl bg-white ring-1 ring-slate-100 p-3 hover:ring-blue-100 hover:shadow-sm transition">
    @if($file)
        <a href="{{ $file['url'] }}" target="_blank" rel="noopener" class="shrink-0 overflow-hidden rounded-xl ring-1 ring-slate-100">
            <img src="{{ $file['url'] }}" alt="" class="h-[4.5rem] w-[5.5rem] object-cover">
        </a>
    @else
        <div class="h-[4.5rem] w-[5.5rem] shrink-0 rounded-xl bg-slate-50 ring-1 ring-dashed ring-slate-200 text-[10px] font-medium text-slate-400 flex items-center justify-center text-center px-1">Geen foto</div>
    @endif
    <div class="min-w-0 flex flex-col justify-center">
        <p class="text-sm font-semibold text-slate-900 leading-snug">{{ $title }}</p>
        @if(!empty($meta))
            <p class="mt-0.5 text-xs text-slate-500 truncate">{{ $meta }}</p>
        @endif
        @if(!empty($result))
            <p class="mt-0.5 text-xs text-slate-600 truncate">{{ $result }}</p>
        @endif
        <span class="mt-2 inline-flex self-start rounded-full px-2 py-0.5 text-[11px] font-semibold ring-1 {{ $approvalClasses[$approvalKey] ?? $approvalClasses['pending'] }}">{{ $approvalLabel }}</span>
    </div>
</div>
