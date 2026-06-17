@props(['for' => null, 'help' => null])

<label
    @if($for) for="{{ $for }}" @endif
    {{ $attributes->merge(['class' => 'mb-1.5 flex items-center gap-1.5 text-sm font-medium text-slate-700']) }}
>
    <span>{{ $slot }}</span>
    @if($help)
        <x-field-help>{{ $help }}</x-field-help>
    @endif
</label>
