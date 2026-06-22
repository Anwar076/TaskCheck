@extends('layouts.admin')

@section('page-title', 'Notificatie maken')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <a href="{{ route('admin.notifications.index') }}" class="text-slate-500 hover:text-slate-700">Notificaties</a>
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Nieuwe notificatie</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                Terug naar notificaties
            </a>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                <h1 class="text-lg sm:text-xl font-bold text-slate-900">Nieuwe notificatie</h1>
                <p class="text-sm text-slate-500">Verstuur een melding naar actieve gebruikers binnen je bedrijf.</p>
            </div>

            <form method="POST" action="{{ route('admin.notifications.store') }}" class="p-4 sm:p-6 space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-semibold text-slate-800">Titel</label>
                    <input
                        id="title"
                        name="title"
                        type="text"
                        value="{{ old('title') }}"
                        maxlength="120"
                        required
                        class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-semibold text-slate-800">Bericht</label>
                    <textarea
                        id="message"
                        name="message"
                        rows="6"
                        maxlength="2000"
                        required
                        class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <fieldset>
                    <legend class="block text-sm font-semibold text-slate-800">Ontvangers</legend>
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach([
                            'all' => 'Alle gebruikers',
                            'employees' => 'Alle medewerkers',
                            'admins' => 'Alle beheerders',
                            'specific' => 'Specifieke gebruikers',
                        ] as $value => $label)
                            <label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                <input
                                    type="radio"
                                    name="target"
                                    value="{{ $value }}"
                                    class="border-slate-300 text-blue-600 focus:ring-blue-500"
                                    {{ old('target', 'all') === $value ? 'checked' : '' }}
                                    data-target-option
                                >
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('target')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </fieldset>

                <div id="specific-users" class="rounded-xl border border-slate-200 overflow-hidden hidden">
                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-slate-800">Gebruikers selecteren</p>
                        <p class="text-xs text-slate-500">{{ $users->count() }} actieve gebruiker(s)</p>
                    </div>
                    <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                        @forelse($users as $user)
                            <label class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50">
                                <input
                                    type="checkbox"
                                    name="user_ids[]"
                                    value="{{ $user->id }}"
                                    class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                    {{ in_array((string) $user->id, old('user_ids', []), true) ? 'checked' : '' }}
                                >
                                <span class="min-w-0">
                                    <span class="block text-sm font-medium text-slate-900">{{ $user->name }}</span>
                                    <span class="block text-xs text-slate-500 truncate">{{ $user->email }} · {{ $user->role === 'admin' ? 'Beheerder' : 'Medewerker' }}{{ $user->department ? ' · '.$user->department : '' }}</span>
                                </span>
                            </label>
                        @empty
                            <div class="px-4 py-8 text-center text-sm text-slate-500">Geen actieve gebruikers gevonden.</div>
                        @endforelse
                    </div>
                    @error('user_ids')
                        <p class="px-4 py-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('user_ids.*')
                        <p class="px-4 py-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-2">
                    <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Annuleren
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700">
                        Versturen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function syncSpecificUsersVisibility() {
    const selected = document.querySelector('[data-target-option]:checked');
    const panel = document.getElementById('specific-users');
    if (!selected || !panel) return;
    panel.classList.toggle('hidden', selected.value !== 'specific');
}

document.querySelectorAll('[data-target-option]').forEach((option) => {
    option.addEventListener('change', syncSpecificUsersVisibility);
});

syncSpecificUsersVisibility();
</script>
@endsection
