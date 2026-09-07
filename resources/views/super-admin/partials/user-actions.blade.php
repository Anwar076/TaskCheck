<div class="flex items-center justify-end gap-1.5">
    @if($user->company)
        <a href="{{ route('super-admin.users.show', ['user' => $user, 'edit' => 1]) }}" aria-label="{{ $user->name }} bewerken" title="Gebruiker bewerken" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.121 2.121 0 0 1 3 3L8.25 18.1 4 19.25l1.15-4.25L16.862 3.487Z"/><path stroke-linecap="round" d="m14.75 5.6 3 3"/></svg>
        </a>
    @endif
    @unless($user->is(auth()->user()))
        <form method="POST" action="{{ route('super-admin.users.destroy', $user) }}" data-delete-user data-user-name="{{ $user->name }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="confirmation_name">
            <button type="submit" aria-label="{{ $user->name }} verwijderen" title="Gebruiker verwijderen" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V4h6v3m-8 0 1 13h8l1-13M10 11v5m4-5v5"/></svg>
            </button>
        </form>
    @endunless
</div>
