@php
    $logoVersion = filemtime(public_path('logos/taskcheck-logo.png'));
@endphp

<img src="{{ asset('logos/taskcheck-logo.png') }}?v={{ $logoVersion }}"
     alt="TaskCheck — Maak elke controle aantoonbaar"
     width="640"
     height="160"
     decoding="async"
     {{ $attributes->class(['block h-auto max-w-full object-contain object-left']) }}>
