@props([
    'text',
    'trigger' => 'scroll',
])

@php($gradientId = uniqid('tc-reveal-'))

<span {{ $attributes->class(['tc-text-reveal']) }}
      data-text="{{ $text }}"
      @if($trigger === 'scroll') data-tc-text-reveal @else data-tc-text-reveal-load @endif>
    <span class="tc-text-reveal-base">{{ $text }}</span>
    <span class="tc-text-reveal-color" aria-hidden="true">{{ $text }}</span>
    <svg class="tc-text-reveal-curve" viewBox="0 0 300 8" preserveAspectRatio="none" aria-hidden="true">
        <defs>
            <linearGradient id="{{ $gradientId }}" x1="0" y1="0" x2="300" y2="0" gradientUnits="userSpaceOnUse">
                <stop offset="0%" stop-color="#2563eb" />
                <stop offset="100%" stop-color="#6366f1" />
            </linearGradient>
        </defs>
        <path d="M1 6 C75 1,225 1,299 6" stroke="url(#{{ $gradientId }})" stroke-width="3" stroke-linecap="round" fill="none" />
    </svg>
</span>
