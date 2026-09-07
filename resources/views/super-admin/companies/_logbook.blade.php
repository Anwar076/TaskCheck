<div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1fr)_18rem]">
    <div class="min-w-0 space-y-6">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-lg font-semibold text-slate-900">Toevoegen aan het logboek</h2>
                <p class="mt-1 text-sm text-slate-500">Leg een afspraak vast of plak een e-mail om de context bij dit bedrijf te bewaren.</p>
            </div>
            <form method="POST" action="{{ route('super-admin.companies.logbook.store', $company) }}" class="space-y-4 p-5">
                @csrf
                <div class="grid gap-4 sm:grid-cols-[10rem_minmax(0,1fr)]">
                    <div><label for="log-category" class="mb-1.5 block text-sm font-medium text-slate-700">Soort bericht</label><select id="log-category" name="category" class="w-full rounded-xl border-slate-300 text-sm">@foreach(['note' => 'Notitie', 'email' => 'E-mail', 'call' => 'Telefoongesprek'] as $key => $label)<option value="{{ $key }}" @selected(old('category', 'note') === $key)>{{ $label }}</option>@endforeach</select></div>
                    <div><label for="log-title" class="mb-1.5 block text-sm font-medium text-slate-700">Onderwerp <span class="font-normal text-slate-400">(optioneel)</span></label><input id="log-title" name="title" value="{{ old('title') }}" maxlength="200" class="w-full rounded-xl border-slate-300 text-sm" placeholder="Bijvoorbeeld: afspraken over nieuwe vestiging"></div>
                </div>
                <div><label for="log-body" class="mb-1.5 block text-sm font-medium text-slate-700">Bericht</label><textarea id="log-body" name="body" rows="6" required maxlength="20000" class="w-full rounded-xl border-slate-300 text-sm leading-relaxed" placeholder="Schrijf een notitie of plak hier de inhoud van een e-mail…">{{ old('body') }}</textarea><p class="mt-1 text-xs text-slate-400">Tekst en regeleinden worden bewaard. Je verstuurt hiermee geen e-mail.</p></div>
                <details @if(old('occurred_at')) open @endif class="text-sm"><summary class="cursor-pointer font-medium text-slate-600">Een eerder contactmoment vastleggen</summary><label for="log-date" class="mt-3 block text-xs text-slate-500">Datum en tijd ({{ config('app.timezone') }}); leeg laten voor nu</label><input id="log-date" type="datetime-local" name="occurred_at" value="{{ old('occurred_at') }}" class="mt-1 rounded-xl border-slate-300 text-sm"></details>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4"><span class="text-xs text-slate-500">Alleen zichtbaar voor superadmins</span><button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Toevoegen</button></div>
            </form>
        </section>
        <section aria-label="Tijdlijn" class="min-w-0">
            <div class="mb-4 flex items-center justify-between"><h2 class="text-lg font-semibold text-slate-900">Tijdlijn</h2><span class="text-xs text-slate-500">{{ $logEntries->total() }} {{ $logEntries->total() === 1 ? 'bericht' : 'berichten' }}</span></div>
            <form method="GET" action="{{ route('super-admin.companies.show', $company) }}" class="mb-6 flex flex-col gap-2 sm:flex-row">
                <input type="hidden" name="section" value="logbook">
                <label class="min-w-0 flex-1"><span class="sr-only">Zoeken in logboek</span><input type="search" name="log_search" value="{{ request('log_search') }}" maxlength="200" class="w-full rounded-xl border-slate-300 text-sm" placeholder="Zoek op bericht, wijziging of naam…"></label>
                <label><span class="sr-only">Filter categorie</span><select name="log_type" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Alle categorieën</option>@foreach(\App\Models\Platform\CompanyLogEntry::CATEGORIES as $key => $label)<option value="{{ $key }}" @selected(request('log_type') === $key)>{{ $label }}</option>@endforeach</select></label>
                <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Zoeken</button>
                @if(request()->filled('log_search') || request()->filled('log_type'))<a href="{{ route('super-admin.companies.show', ['company' => $company, 'section' => 'logbook']) }}" class="self-center px-2 text-sm text-blue-700">Wissen</a>@endif
            </form>
            <ol class="relative space-y-5 before:absolute before:bottom-4 before:left-5 before:top-4 before:w-px before:bg-slate-200">
                @forelse($logEntries as $entry)
                    <li class="relative flex min-w-0 gap-3 sm:gap-4">
                        <div aria-hidden="true" class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-bold ring-4 ring-slate-50 {{ $entry->event ? 'bg-slate-200 text-slate-600' : 'bg-blue-100 text-blue-700' }}">{{ mb_strtoupper(mb_substr($entry->actor_name, 0, 2)) }}</div>
                        <article class="min-w-0 flex-1 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-500"><span class="font-semibold text-slate-700">{{ $entry->actor_name }}</span><span aria-hidden="true">·</span><time datetime="{{ $entry->occurred_at->toIso8601String() }}">{{ $entry->occurred_at->timezone(config('app.timezone'))->translatedFormat('d M Y · H:i') }}</time><span class="rounded-full px-2 py-0.5 {{ $entry->event ? 'bg-slate-100 text-slate-600' : 'bg-blue-50 text-blue-700' }}">{{ \App\Models\Platform\CompanyLogEntry::CATEGORIES[$entry->category] ?? $entry->category }}</span></div>
                            <h3 class="mt-2 break-words text-sm font-semibold text-slate-900">{{ $entry->title }}</h3>
                            @if($entry->body)
                                @if(mb_strlen($entry->body) > 700)
                                    <p class="mt-2 whitespace-pre-wrap break-words text-sm leading-relaxed text-slate-600">{{ \Illuminate\Support\Str::limit($entry->body, 400) }}</p>
                                    <details class="mt-3"><summary class="cursor-pointer text-sm font-semibold text-blue-700">Volledig bericht lezen</summary><div class="mt-3 whitespace-pre-wrap break-words text-sm leading-relaxed text-slate-600">{{ $entry->body }}</div></details>
                                @else
                                    <div class="mt-2 whitespace-pre-wrap break-words text-sm leading-relaxed text-slate-600">{{ $entry->body }}</div>
                                @endif
                            @endif
                            <p class="mt-3 text-[11px] text-slate-400">{{ $entry->event ? 'Automatisch vastgelegd' : 'Handmatig toegevoegd' }}@if(!$entry->event) · Opgeslagen {{ $entry->created_at->timezone(config('app.timezone'))->format('d-m-Y H:i') }}@endif</p>
                        </article>
                    </li>
                @empty
                    <li class="relative rounded-2xl border border-dashed border-slate-200 bg-white px-6 py-10 text-center"><p class="font-semibold text-slate-700">{{ request()->filled('log_search') || request()->filled('log_type') ? 'Geen berichten gevonden' : 'Het logboek is nog leeg' }}</p><p class="mt-1 text-sm text-slate-500">{{ request()->filled('log_search') || request()->filled('log_type') ? 'Pas je zoekopdracht of categorie aan.' : 'Voeg de eerste notitie toe. Nieuwe wijzigingen verschijnen hier automatisch.' }}</p></li>
                @endforelse
            </ol>
            <div class="mt-6">{{ $logEntries->links() }}</div>
        </section>
    </div>
    <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="font-semibold text-slate-900">Over dit logboek</h2>
        <p class="mt-3 text-sm leading-relaxed text-slate-500">Alle contactmomenten en wijzigingen bij {{ $company->name }} op één plek.</p>
        <dl class="mt-4 space-y-4 text-sm"><div><dt class="font-semibold text-slate-700">Automatisch</dt><dd class="mt-1 leading-relaxed text-slate-500">Nieuwe gebruikers, gewijzigde bedrijfsgegevens, abonnementen en locaties. Inclusief de uitvoerder en wijzigingen.</dd></div><div><dt class="font-semibold text-slate-700">Zelf toevoegen</dt><dd class="mt-1 leading-relaxed text-slate-500">Notities, gekopieerde e-mails en samenvattingen van gesprekken.</dd></div></dl>
        <p class="mt-5 border-t border-slate-100 pt-4 text-xs leading-relaxed text-slate-400">Automatische registratie begint bij ingebruikname van het logboek. Eerdere wijzigingen worden niet achteraf gereconstrueerd.</p>
    </aside>
</div>
