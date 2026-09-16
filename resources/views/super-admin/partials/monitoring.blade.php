@php
    $metrics = $platformHealth['metrics'] ?? [];
    $alerts = collect($platformHealth['alerts'] ?? []);
    $alertsExceeded = $alerts->contains(fn ($alert) => ! empty($alert['exceeded']));
    $errorCount = count($recentErrors ?? []);
    $activeTickets = $tickets->filter(fn ($ticket) => $ticket->status !== 'ignored');
    $archivedTickets = $tickets->filter(fn ($ticket) => $ticket->status === 'ignored');
    $openTickets = $tickets->where('status', 'open')->count();
    $healthState = $alertsExceeded ? 'alert' : ($errorCount > 0 ? 'watch' : 'ok');
    $healthCopy = match ($healthState) {
        'alert' => ['Aandacht nodig', 'Een verwerkingsmeting zit op of boven de meldingsgrens.'],
        'watch' => ['Fouten in het log', 'Er zijn recente applicatiefouten. Jobs kunnen nog wel goed lopen.'],
        default => ['Alles stabiel', 'Geen overschreden job-grenzen en geen recente fouten in beeld.'],
    };
    $checkedAt = isset($metrics['checked_at'])
        ? \Carbon\Carbon::parse($metrics['checked_at'])->timezone(config('app.timezone'))
        : now();
    $configuredRecipients = trim((string) config('platform_alerts.recipients'));
    $platformAlertRecipients = collect($configuredRecipients !== '' ? explode(',', $configuredRecipients) : config('app.super_admin_emails', []))
        ->map(fn ($email) => strtolower(trim($email)))
        ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
        ->unique();
@endphp

<section data-tab-panel="monitoring" class="sa-tab-panel space-y-5 {{ $activeDashboardTab !== 'monitoring' ? 'hidden' : '' }}">
    <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-5 text-white shadow-sm sm:p-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-blue-100/80">Platformgezondheid</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight">Monitoring</h2>
                <p class="mt-1 max-w-xl text-sm text-blue-100/90">Live gebruik, achtergrondtaken en fouten — zodat je ziet of TaskCheck soepel draait.</p>
                <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold ring-1 ring-white/20">
                    <span class="h-2 w-2 rounded-full {{ $healthState === 'ok' ? 'bg-emerald-300' : ($healthState === 'watch' ? 'bg-amber-300' : 'bg-red-300') }}"></span>
                    {{ $healthCopy[0] }}
                </div>
                <p class="mt-2 max-w-xl text-xs text-blue-100/80">{{ $healthCopy[1] }}</p>
            </div>
            <div class="flex flex-col items-stretch gap-3 sm:items-end">
                <div class="grid grid-cols-3 gap-2 sm:gap-3">
                    <div class="rounded-2xl bg-white/12 px-3 py-3 ring-1 ring-white/15">
                        <p class="text-[11px] font-medium text-blue-100">Nu online</p>
                        <p class="mt-0.5 text-xl font-bold">{{ number_format($metrics['active_users'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/12 px-3 py-3 ring-1 ring-white/15">
                        <p class="text-[11px] font-medium text-blue-100">Fouten</p>
                        <p class="mt-0.5 text-xl font-bold">{{ $errorCount }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/12 px-3 py-3 ring-1 ring-white/15">
                        <p class="text-[11px] font-medium text-blue-100">Open tickets</p>
                        <p class="mt-0.5 text-xl font-bold">{{ $openTickets }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('super-admin.platform-alerts.test') }}">
                    @csrf
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-50 sm:w-auto">
                        Test alert-mail nu
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-2xl border border-blue-200 bg-blue-50/80 p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Actieve gebruikers</p>
            <p class="mt-1 text-3xl font-bold text-blue-900">{{ number_format($metrics['active_users'] ?? 0, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-blue-800/80">Laatste {{ $metrics['session_window_minutes'] ?? 15 }} minuten · geen e-mailalert</p>
        </div>
        <div class="rounded-2xl border border-indigo-200 bg-indigo-50/70 p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700">Actieve sessies</p>
            <p class="mt-1 text-3xl font-bold text-indigo-900">{{ number_format($metrics['active_sessions'] ?? 0, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-indigo-800/80">Laatste {{ $metrics['session_window_minutes'] ?? 15 }} minuten · geen e-mailalert</p>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Actieve inzendingen</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($metrics['submissions_in_progress'] ?? 0, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500">Bijgewerkt in de laatste {{ $metrics['submissions_activity_window_minutes'] ?? 60 }} minuten · geen e-mailalert</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Openstaand (alles)</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($metrics['submissions_in_progress_total'] ?? 0, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500">Inzendingen in uitvoering, alle tijd</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Achtergrondverwerking</h3>
                <p class="mt-1 text-sm text-slate-500">Alleen vertraagde of mislukte jobs kunnen een mail sturen. Gebruikscijfers hierboven doen dat niet.</p>
            </div>
            <p class="text-xs text-slate-400">Momentopname: {{ $checkedAt->format('d-m-Y H:i:s') }}</p>
        </div>

        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            @foreach($alerts as $alert)
                <div class="rounded-2xl border p-4 {{ $alert['exceeded'] ? 'border-red-200 bg-red-50' : 'border-slate-200 bg-slate-50/70' }}">
                    <div class="flex items-start justify-between gap-3">
                        <h4 class="text-sm font-semibold text-slate-900">{{ $alert['label'] }}</h4>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold ring-1 {{ $alert['exceeded'] ? 'bg-red-100 text-red-800 ring-red-200' : 'bg-white text-slate-600 ring-slate-200' }}">
                            {{ ! $alert['available'] ? 'Niet gemeten' : ($alert['threshold'] <= 0 ? 'Meldingen uitgeschakeld' : ($alert['exceeded'] ? 'Aandacht nodig' : 'Onder de grens')) }}
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $alert['available'] ? number_format($alert['value'], 0, ',', '.') : '—' }}</p>
                    <p class="mt-1 text-xs text-slate-500">
                        @if($alert['key'] === 'stalled_jobs')
                            Minstens {{ $metrics['stalled_jobs_minutes'] ?? 15 }} minuten vertraagd. Toekomstige taken tellen niet mee.
                        @else
                            Mislukt in de laatste {{ $metrics['failed_jobs_window_minutes'] ?? 15 }} minuten. Oude fouten tellen niet mee.
                        @endif
                        @if($alert['threshold'] > 0) Meldingsgrens: {{ $alert['threshold'] }}. @endif
                    </p>
                    @unless($alert['available'])
                        <p class="mt-2 text-xs text-amber-700">Deze meting is niet beschikbaar voor de ingestelde verwerking of foutregistratie. Hiervoor wordt geen probleem- of herstelmail verstuurd.</p>
                    @endunless
                </div>
            @endforeach
        </div>

        <p class="mt-4 rounded-xl bg-blue-50 px-4 py-3 text-sm text-blue-800">
            @if(config('platform_alerts.enabled'))
                Eén mail bij een nieuw verwerkingsprobleem. Geen herhaalmails zolang het aanhoudt. Eén herstelmail nadat de meting {{ config('platform_alerts.recovery_minutes') }} minuten onder de grens blijft.
            @else
                Automatische e-mailmeldingen zijn uitgeschakeld.
            @endif
        </p>
        <p class="mt-2 text-xs text-slate-500">Herstel betekent dat de meting weer onder de grens ligt. Eerder mislukte taken worden niet automatisch opnieuw uitgevoerd.</p>

        <div class="mt-5 border-t border-slate-100 pt-4">
            <h4 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Laatste 10 mailmeldingen · historie</h4>
            <p class="mt-1 text-xs text-slate-400">Dit overzicht kan oudere meldingen bevatten. Geregistreerd na verzending aan de maildienst; bezorging is niet bevestigd.</p>
            <ul class="mt-3 divide-y divide-slate-100 text-sm">
                @forelse($recentPlatformAlerts as $log)
                    <li class="flex flex-wrap items-center justify-between gap-2 py-2.5">
                        <span class="text-slate-700">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-semibold ring-1 {{ $log->event_type === 'recovered' ? 'bg-emerald-50 text-emerald-800 ring-emerald-200' : 'bg-amber-50 text-amber-800 ring-amber-200' }}">
                                {{ match($log->event_type) { 'opened' => 'Nieuw probleem', 'recovered' => 'Herstel', default => 'Oude drempelmelding' } }}
                            </span>
                            <span class="ml-2">{{ config('platform_alerts.labels.'.$log->alert_key, $log->alert_key) }}: {{ $log->metric_value }} / {{ $log->threshold }}</span>
                        </span>
                        <time datetime="{{ $log->sent_at->toIso8601String() }}" class="text-xs text-slate-500">{{ $log->sent_at->timezone(config('app.timezone'))->format('d-m-Y H:i') }}</time>
                    </li>
                @empty
                    <li class="py-3 text-slate-500">Nog geen automatische mailmeldingen geregistreerd.</li>
                @endforelse
            </ul>
        </div>
        <p class="mt-4 text-xs text-slate-500">Ontvangers: {{ $platformAlertRecipients->isNotEmpty() ? $platformAlertRecipients->implode(', ') : 'niet geconfigureerd' }}.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Fouten bij klanten</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Zelfde crash wordt één kaart. Handig als iets zich herhaalt — dan weet je dat het geen losse hiccup is.</p>
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700">Live</span>
                    <form method="POST" action="{{ route('super-admin.errors.clear') }}" onsubmit="return confirm('Alle huidige foutmeldingen verdwijnen uit dit overzicht. Nieuwe fouten komen daarna weer in beeld. Doorgaan?')">
                        @csrf
                        <button type="submit" class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Alles wissen
                        </button>
                    </form>
                </div>
            </div>
            <div class="mb-3 flex gap-2">
                <input id="sa-error-search" type="search" class="min-w-0 flex-1 rounded-xl border-slate-300 text-sm" placeholder="Zoek klant, pagina of fout…">
                <select id="sa-error-level" class="rounded-xl border-slate-300 text-sm">
                    <option value="">Alle niveaus</option>
                    <option value="ERROR">Error</option>
                    <option value="CRITICAL">Kritiek</option>
                </select>
            </div>
            <div id="sa-errors-list" class="max-h-[28rem] space-y-2 overflow-auto">
                @forelse($recentErrors as $error)
                    @php
                        $repeats = (int) ($error['count'] ?? 1);
                        $searchText = strtolower(($error['short_title'] ?? '').' '.($error['message'] ?? '').' '.($error['company_name'] ?? '').' '.($error['path'] ?? ''));
                    @endphp
                    <div class="sa-error-card rounded-xl border {{ $repeats > 1 ? 'border-blue-200 bg-blue-50/60' : 'border-slate-200 bg-slate-50/70' }} p-3.5" data-error-level="{{ $error['level'] }}" data-error-text="{{ $searchText }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex rounded-full bg-white px-2 py-0.5 text-[11px] font-semibold text-blue-800 ring-1 ring-blue-200">{{ $repeats }}×</span>
                                    @if(!empty($error['company_name']))
                                        <span class="truncate text-[11px] font-semibold text-slate-700">{{ $error['company_name'] }}</span>
                                    @else
                                        <span class="text-[11px] text-slate-500">Platform</span>
                                    @endif
                                    @if(!empty($error['path']))
                                        <span class="truncate text-[11px] text-slate-400">{{ $error['path'] }}</span>
                                    @endif
                                </div>
                                <p class="mt-1.5 text-sm font-semibold text-slate-900">{{ $error['short_title'] ?? Str::limit($error['message'], 110) }}</p>
                                <p class="mt-1 line-clamp-2 break-words text-xs text-slate-500">{{ $error['message'] }}</p>
                                <p class="mt-1.5 text-[11px] text-slate-400">Laatst {{ $error['last_seen'] ?? 'onbekend' }} · eerst {{ $error['first_seen'] ?? 'onbekend' }}</p>
                            </div>
                            @if(!empty($error['has_ticket']))
                                <button type="button" class="sa-ticket-open shrink-0 rounded-lg bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-blue-700" data-ticket-id="{{ $error['ticket_id'] }}">Open ticket</button>
                            @else
                                <button
                                    class="sa-ticket-btn shrink-0 rounded-lg bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-blue-700"
                                    data-fingerprint="{{ $error['fingerprint'] }}"
                                    data-title="{{ $error['short_title'] ?? 'Fout in TaskCheck' }}"
                                    data-message="{{ $error['message'] }}"
                                    data-context="{{ $error['raw'] }}"
                                    data-company-id="{{ $error['company_id'] ?? '' }}"
                                    data-occurred="{{ $error['timestamp'] ?? '' }}"
                                    data-request-url="{{ $error['request_url'] ?? '' }}"
                                    data-http-method="{{ $error['http_method'] ?? '' }}"
                                    data-user-agent="{{ $error['user_agent'] ?? '' }}"
                                    data-device-type="{{ $error['device_type'] ?? '' }}"
                                >Opvolgen</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center">
                        <p class="text-sm font-semibold text-slate-800">Geen recente fouten</p>
                        <p class="mt-1 text-xs text-slate-500">Als dezelfde crash bij klanten terugkomt, zie je hier één kaart in plaats van een lange log.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Incident tickets</h3>
                    <p class="mt-0.5 text-xs text-slate-500">{{ $activeTickets->count() }} actief · {{ $archivedTickets->count() }} in archief</p>
                </div>
            </div>
            <div class="mb-3 inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 p-1">
                <button type="button" class="sa-incident-tab-btn active rounded-lg px-3 py-1.5 text-xs font-semibold" data-incident-tab-target="active">Actief</button>
                <button type="button" class="sa-incident-tab-btn rounded-lg px-3 py-1.5 text-xs font-semibold" data-incident-tab-target="archive">Archief</button>
            </div>
            <div id="sa-incidents-active-list" class="sa-incident-tab-panel max-h-96 space-y-2 overflow-auto">
                @forelse($activeTickets as $ticket)
                    <div class="rounded-xl border border-slate-200 p-3" data-incident-ticket-id="{{ $ticket->id }}">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-xs text-slate-500">#{{ $ticket->id }} · {{ $ticket->status }} · {{ optional($ticket->error_occurred_at ?? $ticket->created_at)->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}</p>
                            @if($ticket->ai_analyzed_at)
                                <span class="shrink-0 text-[11px] font-semibold text-emerald-700">AI geanalyseerd</span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm font-medium text-slate-900">{{ $ticket->title }}</p>
                        <p class="mt-1 break-all text-xs text-slate-600">{{ $ticket->error_message }}</p>
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <button type="button" class="sa-ticket-open rounded-lg bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-blue-700" data-ticket-id="{{ $ticket->id }}">Open</button>
                            <form method="POST" action="{{ route('super-admin.incidents.status.update', $ticket) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $ticket->status === 'resolved' ? 'open' : 'resolved' }}">
                                <button type="submit" class="rounded-lg {{ $ticket->status === 'resolved' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700' }} px-2.5 py-1.5 text-xs font-semibold text-white">
                                    {{ $ticket->status === 'resolved' ? 'Heropen' : 'Afronden' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('super-admin.incidents.status.update', $ticket) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="ignored">
                                <button type="submit" class="rounded-lg bg-slate-500 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-slate-600">Archiveer</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Nog geen tickets.</p>
                @endforelse
            </div>
            <div id="sa-incidents-archive-list" class="sa-incident-tab-panel hidden max-h-96 space-y-2 overflow-auto">
                @forelse($archivedTickets as $ticket)
                    <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-3" data-incident-ticket-id="{{ $ticket->id }}">
                        <p class="text-xs text-slate-500">#{{ $ticket->id }} · gearchiveerd · {{ optional($ticket->error_occurred_at ?? $ticket->created_at)->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}</p>
                        <p class="mt-1 text-sm font-medium text-slate-900">{{ $ticket->title }}</p>
                        <p class="mt-1 break-all text-xs text-slate-600">{{ $ticket->error_message }}</p>
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <button type="button" class="sa-ticket-open rounded-lg bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-blue-700" data-ticket-id="{{ $ticket->id }}">Open</button>
                            <form method="POST" action="{{ route('super-admin.incidents.status.update', $ticket) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="open">
                                <button type="submit" class="rounded-lg bg-amber-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-amber-700">Herstel</button>
                            </form>
                            @if($ticket->ai_analyzed_at)
                                <span class="text-[11px] font-semibold text-emerald-700">AI geanalyseerd</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Geen gearchiveerde tickets.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
