@php
    $locked = $subscriptionLocked ?? false;
    $unread = $unreadCount ?? 0;
    $itemClass = function (bool $active) {
        return 'flex min-w-0 flex-1 flex-col items-center justify-center gap-0.5 py-1.5 text-[11px] font-semibold transition-colors '.($active ? 'text-blue-600' : 'text-slate-500');
    };
@endphp
<nav class="app-bottom-nav xl:hidden fixed inset-x-0 bottom-0 z-50 border-t border-slate-200 bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/90" aria-label="Hoofdnavigatie">
    <div class="mx-auto flex max-w-lg items-stretch pt-1">
        @unless($locked)
            <a href="{{ route('employee.dashboard') }}" class="{{ $itemClass(request()->routeIs('employee.dashboard')) }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Home
            </a>
            <a href="{{ route('employee.lists.index') }}" class="{{ $itemClass(request()->routeIs('employee.lists.*') || request()->routeIs('employee.submissions.*')) }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Taken
            </a>
            <a href="{{ route('employee.notifications.index') }}" class="{{ $itemClass(request()->routeIs('employee.notifications.*')) }} relative">
                <span class="relative">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    <span data-unread-count-badge class="absolute -top-1 -right-2 inline-flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold text-white {{ $unread > 0 ? '' : 'hidden' }}">{{ $unread > 9 ? '9+' : $unread }}</span>
                </span>
                Meldingen
            </a>
        @endunless
        <a href="{{ route('employee.settings.edit') }}" class="{{ $itemClass(request()->routeIs('employee.settings.*')) }}">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.094c.55 0 1.02.398 1.11.94l.149.894c.07.424.348.78.747.94.19.076.376.163.557.26.376.201.824.189 1.189-.033l.77-.47a1.125 1.125 0 011.45.12l.773.774c.39.389.44.996.12 1.45l-.47.77c-.222.365-.234.813-.033 1.189.097.181.184.367.26.557.16.399.516.678.94.748l.894.149c.542.09.94.56.94 1.11v1.094c0 .55-.398 1.02-.94 1.11l-.894.149c-.424.07-.78.348-.94.747a6.94 6.94 0 01-.26.557c-.201.376-.189.824.033 1.189l.47.77c.32.454.269 1.061-.12 1.45l-.774.773a1.125 1.125 0 01-1.45.12l-.77-.47c-.365-.222-.813-.234-1.189-.033a6.94 6.94 0 01-.557.26c-.399.16-.678.516-.748.94l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.02-.398-1.11-.94l-.149-.894c-.07-.424-.348-.78-.747-.94a6.94 6.94 0 01-.557-.26c-.376-.201-.824-.189-1.189.033l-.77.47a1.125 1.125 0 01-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.47-.77c.222-.365.234-.813.033-1.189a6.94 6.94 0 01-.26-.557c-.16-.399-.516-.678-.94-.748l-.894-.149c-.542-.09-.94-.56-.94-1.11v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.78-.348.94-.747.076-.19.163-.376.26-.557.201-.376.189-.824-.033-1.189l-.47-.77a1.125 1.125 0 01.12-1.45l.774-.773a1.125 1.125 0 011.45-.12l.77.47c.365.222.813.234 1.189.033.181-.097.367-.184.557-.26.399-.16.678-.516.748-.94l.149-.894z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Instellingen
        </a>
    </div>
</nav>
