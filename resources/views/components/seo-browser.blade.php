@props([
    'url' => 'app.taskcheck.nl/dashboard',
])

<div {{ $attributes->class('new-browser') }}>
    <div class="new-browser-bar" aria-hidden="true">
        <i></i><i></i><i></i>
        <span>{{ $url }}</span>
    </div>
    {{ $slot }}
</div>
