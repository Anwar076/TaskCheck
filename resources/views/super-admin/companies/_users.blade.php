<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <header class="flex flex-col gap-4 border-b border-slate-200 px-5 py-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><x-super-admin-icon name="profile" /></span>
                <h2 class="text-lg font-semibold text-slate-900">Gebruikers beheren</h2>
            </div>
            <p class="mt-1 text-sm text-slate-500">{{ $companyUsers->count() }} {{ $companyUsers->count() === 1 ? 'gebruiker' : 'gebruikers' }} · beheerders beheren de omgeving, medewerkers voeren taken uit.</p>
        </div>
        <div class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
            <label class="relative block sm:w-72">
                <span class="sr-only">Zoek gebruiker</span>
                <svg class="pointer-events-none absolute left-3 top-2.5 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="1.8" d="m21 21-4.4-4.4m2.4-5.1a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
                <input id="company-user-search" type="search" placeholder="Zoek naam, e-mail of locatie" class="w-full rounded-xl border-slate-300 py-2.5 pl-10 pr-3 text-sm focus:border-blue-500 focus:ring-blue-500">
            </label>
            <button type="button" data-open-dialog="add-company-user" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"><span class="text-lg leading-none">+</span> Gebruiker toevoegen</button>
        </div>
    </header>

    <div class="hidden grid-cols-[minmax(15rem,2fr)_minmax(8rem,1fr)_minmax(7rem,0.7fr)_auto] gap-4 border-b border-slate-100 bg-slate-50/80 px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 md:grid">
        <span>Gebruiker</span><span>Rol en locatie</span><span>Status</span><span class="text-right">Acties</span>
    </div>
    <div id="company-user-list" class="divide-y divide-slate-100">
        @forelse($companyUsers as $user)
            <article data-user-row data-user-search="{{ mb_strtolower($user->name.' '.$user->email.' '.$user->phone.' '.($user->location?->name ?? '').' '.$user->role) }}" class="grid gap-4 px-5 py-4 transition hover:bg-slate-50/60 md:grid-cols-[minmax(15rem,2fr)_minmax(8rem,1fr)_minmax(7rem,0.7fr)_auto] md:items-center">
                <div class="flex min-w-0 items-start gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-700">{{ strtoupper(mb_substr($user->name, 0, 2)) }}</span>
                    <div class="min-w-0"><p class="break-words text-sm font-semibold text-slate-900">{{ $user->name }}</p><a href="mailto:{{ $user->email }}" class="mt-0.5 block break-all text-sm text-slate-500 hover:text-blue-700">{{ $user->email }}</a>@if($user->phone)<p class="mt-0.5 text-xs text-slate-400">{{ $user->phone }}</p>@endif</div>
                </div>
                <div><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->role === 'employee' ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">{{ $user->role === 'employee' ? 'Medewerker' : 'Beheerder' }}</span><p class="mt-1.5 text-xs text-slate-500">{{ $user->location?->name ?? 'Alle locaties' }}</p></div>
                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span><span class="text-sm font-medium {{ $user->is_active ? 'text-emerald-700' : 'text-red-700' }}">{{ $user->is_active ? 'Actief' : 'Geblokkeerd' }}</span></div>
                <div class="flex flex-wrap gap-2 md:justify-end">
                    <button type="button" data-open-dialog="edit-company-user-{{ $user->id }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">Bewerken</button>
                    <form method="POST" action="{{ route('super-admin.companies.users.password-reset', [$company, $user]) }}" onsubmit="return confirm('Wachtwoordlink versturen naar {{ addslashes($user->email) }}?')">@csrf<button class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Resetlink</button></form>
                    @if(!$user->is(auth()->user()))<form method="POST" action="{{ route('super-admin.companies.users.toggle', [$company, $user]) }}" onsubmit="return confirm('Account van {{ addslashes($user->name) }} {{ $user->is_active ? 'blokkeren' : 'activeren' }}?')">@csrf @method('PUT')<button class="rounded-lg px-3 py-2 text-xs font-semibold {{ $user->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">{{ $user->is_active ? 'Blokkeren' : 'Activeren' }}</button></form>@else<span class="self-center px-2 text-xs font-medium text-slate-400">Dit ben jij</span>@endif
                </div>
            </article>
            @include('super-admin.companies._user-dialog', ['dialogUser' => $user])
        @empty
            <div class="px-6 py-14 text-center"><div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-700"><x-super-admin-icon name="profile" /></div><p class="mt-3 font-semibold text-slate-800">Nog geen gebruikers</p><p class="mt-1 text-sm text-slate-500">Voeg de eerste beheerder of medewerker van deze klant toe.</p></div>
        @endforelse
        <div id="company-user-empty-search" class="hidden px-6 py-12 text-center text-sm text-slate-500">Geen gebruikers gevonden met deze zoekopdracht.</div>
    </div>
</section>

<dialog id="add-company-user" class="w-[calc(100%-2rem)] max-w-xl rounded-2xl p-0 shadow-2xl backdrop:bg-slate-950/50">
    <form method="POST" action="{{ route('super-admin.companies.users.store', $company) }}">@csrf
        <div class="flex items-start justify-between border-b border-slate-100 px-5 py-4"><div><h3 class="text-lg font-semibold text-slate-900">Gebruiker toevoegen</h3><p class="mt-0.5 text-sm text-slate-500">Maak een beheerder of medewerker aan voor {{ $company->name }}.</p></div><button type="button" data-close-dialog class="rounded-lg p-2 text-slate-400 hover:bg-slate-100" aria-label="Sluiten">✕</button></div>
        <div class="grid gap-4 p-5 sm:grid-cols-2">
            <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Naam</label><input name="name" value="{{ old('name') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div><label class="mb-1.5 block text-sm font-medium text-slate-700">E-mailadres</label><input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Rol</label><select name="role" required class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"><option value="employee">Medewerker — voert taken uit</option><option value="admin">Beheerder — beheert omgeving</option></select></div>
            <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Locatie</label><select name="location_id" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"><option value="">Alle locaties</option>@foreach($companyLocations as $location)<option value="{{ $location->id }}">{{ $location->name }}</option>@endforeach</select></div>
            <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Telefoon <span class="font-normal text-slate-400">(optioneel)</span></label><input name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Tijdelijk wachtwoord</label><input id="new-company-user-password" name="password" type="text" minlength="12" required autocomplete="new-password" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"><button type="button" id="generate-company-user-password" class="mt-1.5 text-xs font-semibold text-blue-700">Veilig wachtwoord genereren</button></div>
        </div>
        <div class="border-t border-blue-100 bg-blue-50 px-5 py-3 text-xs text-blue-800"><strong>Medewerker:</strong> voert toegewezen taken uit. <strong class="ml-1">Beheerder:</strong> kan de klantomgeving en gebruikers beheren.</div>
        <div class="flex justify-end gap-3 border-t border-slate-100 bg-slate-50 px-5 py-4"><button type="button" data-close-dialog class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700">Annuleren</button><button class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Gebruiker aanmaken</button></div>
    </form>
</dialog>
