@extends('layouts.super-admin')

@section('page-title', 'Super Admin Dashboard')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl bg-gradient-to-r from-violet-900 via-slate-900 to-slate-800 p-5 text-white shadow-lg sm:p-6">
        <h1 class="text-2xl font-bold">Platformoverzicht</h1>
        <p class="mt-1 text-violet-100/90">Alle bedrijven, gebruikers, lijsten en inzendingen — los van het bedrijfs-dashboard.</p>
    </div>

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-3 xl:grid-cols-9">
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Bedrijven</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['companies'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Gebruikers</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['users'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Admins</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['admins'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Medewerkers</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['employees'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Actieve locaties</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['locations'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Opslag totaal (GB)</p>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($totals['storage_gb'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Takenlijsten</p>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($totals['task_lists'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Taken (items)</p>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($totals['tasks'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Inzendingen</p>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($totals['submissions'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4 lg:col-span-3">
            <h2 class="text-lg font-semibold text-slate-900 mb-3">Bulk mail naar alle bedrijven</h2>
            <form method="POST" action="{{ route('super-admin.communications.broadcast-mail') }}" class="space-y-3">
                @csrf
                <input
                    name="subject"
                    value="{{ old('subject') }}"
                    class="w-full rounded-lg border-slate-300 text-sm"
                    placeholder="Onderwerp"
                    required
                >
                <textarea
                    name="message"
                    rows="5"
                    class="w-full rounded-lg border-slate-300 text-sm"
                    placeholder="Bericht naar alle bedrijven..."
                    required
                >{{ old('message') }}</textarea>
                <div class="flex items-center justify-between gap-3">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="include_inactive" value="1" @checked(old('include_inactive'))>
                        Ook inactieve bedrijven mailen
                    </label>
                    <button class="rounded-lg bg-violet-700 text-white px-4 py-2 text-sm font-semibold hover:bg-violet-800">
                        Verstuur bulkmail
                    </button>
                </div>
            </form>
            <p class="text-xs text-slate-500 mt-2">Per bedrijf wordt de bedrijfsmail gebruikt, of anders de eerste actieve admin e-mail.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-4 lg:col-span-3">
            <h2 class="text-lg font-semibold text-slate-900 mb-3">Nieuw bedrijf + admin account</h2>
            <form method="POST" action="{{ route('super-admin.companies.store') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3">
                @csrf
                <input name="company_name" value="{{ old('company_name') }}" class="rounded-lg border-slate-300 text-sm" placeholder="Bedrijfsnaam" required>
                <input name="admin_name" value="{{ old('admin_name') }}" class="rounded-lg border-slate-300 text-sm" placeholder="Admin naam" required>
                <input name="admin_email" value="{{ old('admin_email') }}" type="email" class="rounded-lg border-slate-300 text-sm" placeholder="Admin e-mail" required>
                <input name="admin_password" type="text" class="rounded-lg border-slate-300 text-sm" placeholder="Tijdelijk wachtwoord (min 8)" required>
                <select name="subscription_plan" class="rounded-lg border-slate-300 text-sm" required>
                    @foreach(\App\Models\Company::PLANS as $planKey => $plan)
                        <option value="{{ $planKey }}" @selected(old('subscription_plan') === $planKey)>{{ ucfirst($planKey) }}</option>
                    @endforeach
                </select>
                <label class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <input type="checkbox" name="billing_required" value="1" @checked(old('billing_required', true))>
                    Bedrijf moet maandelijks betalen
                </label>
                <input name="access_end_date" type="date" value="{{ old('access_end_date') }}" class="rounded-lg border-slate-300 text-sm">
                <button class="rounded-lg bg-violet-700 text-white px-4 py-2 text-sm font-semibold hover:bg-violet-800">Aanmaken</button>
                <input name="company_phone" value="{{ old('company_phone') }}" class="rounded-lg border-slate-300 text-sm" placeholder="Telefoon (optioneel)">
                <input name="company_address" value="{{ old('company_address') }}" class="rounded-lg border-slate-300 text-sm" placeholder="Adres (optioneel)">
                <input name="company_website" value="{{ old('company_website') }}" class="rounded-lg border-slate-300 text-sm" placeholder="Website (optioneel)">
            </form>
            <p class="text-xs text-slate-500 mt-2">Bij niet-betalen is einddatum verplicht. Bij betalen loopt het door (maandelijks factureren via je normale billing-flow).</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-4 lg:col-span-2">
            <h2 class="text-lg font-semibold text-slate-900 mb-3">Bedrijven overzicht</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b">
                            <th class="py-2 pr-4">Bedrijf</th>
                            <th class="py-2 pr-4">Plan</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4">Facturatie</th>
                            <th class="py-2 pr-4">Einddatum</th>
                            <th class="py-2 pr-4">Users</th>
                            <th class="py-2 pr-4">Opslag (GB)</th>
                            <th class="py-2 pr-4">AI tokens</th>
                            <th class="py-2 pr-4">Locaties</th>
                            <th class="py-2">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                            <tr class="border-b border-slate-100">
                                <td class="py-2 pr-4 font-medium text-slate-900">{{ $company->name }}</td>
                                <td class="py-2 pr-4">{{ ucfirst($company->subscription_plan ?? 'geen') }}</td>
                                <td class="py-2 pr-4">{{ $company->subscription_status ?? '-' }}</td>
                                <td class="py-2 pr-4">{{ $company->billing_mode_label }}</td>
                                <td class="py-2 pr-4">{{ optional($company->subscription_ends_at)->format('d-m-Y') ?? 'onbeperkt' }}</td>
                                <td class="py-2 pr-4">{{ $company->total_users }} (A: {{ $company->admin_users }}, M: {{ $company->employee_users }})</td>
                                <td class="py-2 pr-4">{{ number_format((float) $company->storage_used_gb, 2, ',', '.') }}</td>
                                <td class="py-2 pr-4">
                                    @if($aiUsage['enabled'])
                                        {{ number_format((int) ($aiUsage['by_company'][$company->id] ?? 0), 0, ',', '.') }}
                                    @else
                                        n.v.t.
                                    @endif
                                </td>
                                <td class="py-2 pr-4">{{ (int) $company->active_locations }}</td>
                                <td class="py-2">
                                    <details>
                                        <summary class="cursor-pointer text-violet-700">Beheer</summary>
                                        <form method="POST" action="{{ route('super-admin.companies.subscription.update', $company) }}" class="mt-2 space-y-2 min-w-[220px]">
                                            @csrf
                                            @method('PUT')
                                            <select name="subscription_plan" class="w-full rounded border-slate-300 text-xs">
                                                @foreach(\App\Models\Company::PLANS as $planKey => $plan)
                                                    <option value="{{ $planKey }}" @selected($company->subscription_plan === $planKey)>{{ ucfirst($planKey) }}</option>
                                                @endforeach
                                            </select>
                                            <select name="subscription_status" class="w-full rounded border-slate-300 text-xs">
                                                @foreach(['trial','active','cancelled','expired'] as $status)
                                                    <option value="{{ $status }}" @selected($company->subscription_status === $status)>{{ $status }}</option>
                                                @endforeach
                                            </select>
                                            <label class="inline-flex items-center gap-2 text-xs"><input type="checkbox" name="billing_required" value="1" @checked($company->billing_required)> betalen</label>
                                            <input type="date" name="subscription_ends_at" value="{{ optional($company->subscription_ends_at)->format('Y-m-d') }}" class="w-full rounded border-slate-300 text-xs">
                                            <label class="inline-flex items-center gap-2 text-xs"><input type="checkbox" name="is_active" value="1" @checked($company->is_active)> actief</label>
                                            <button class="w-full rounded bg-slate-800 text-white text-xs py-1.5">Opslaan</button>
                                        </form>
                                    </details>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-6 text-center text-slate-500">Geen bedrijven gevonden.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-xl p-4">
                <h2 class="text-base font-semibold text-slate-900 mb-2">Abonnementen verdeling</h2>
                <div class="space-y-1 text-sm">
                    @forelse($plans as $plan => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">{{ ucfirst(str_replace('_', ' ', $plan)) }}</span>
                            <span class="font-semibold text-slate-900">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-slate-500">Geen data</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-4">
                <h2 class="text-base font-semibold text-slate-900 mb-2">AI gebruik</h2>
                @if($aiUsage['enabled'])
                    <p class="text-sm text-slate-600">Bron: <span class="font-medium">{{ $aiUsage['source_table'] }}</span></p>
                    <p class="text-xl font-bold text-slate-900 mt-1">{{ number_format($aiUsage['total_tokens'], 0, ',', '.') }} tokens</p>
                @else
                    <p class="text-sm text-slate-500">Nog geen AI token tracking tabel gevonden.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-slate-900">Recente errors</h2>
                <span class="text-xs text-slate-500">Realtime update elke 8s</span>
            </div>
            <div id="sa-errors-list" class="space-y-2 max-h-96 overflow-auto">
                @forelse($recentErrors as $error)
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs text-red-700 font-semibold">{{ $error['level'] }} · {{ $error['timestamp'] ?? 'onbekend' }}</p>
                                <p class="text-sm text-slate-900 mt-1 break-all">{{ $error['message'] }}</p>
                            </div>
                            <button
                                class="shrink-0 rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700 sa-ticket-btn"
                                data-fingerprint="{{ $error['fingerprint'] }}"
                                data-title="Automatisch error ticket"
                                data-message="{{ $error['message'] }}"
                                data-context="{{ $error['raw'] }}"
                                data-company-id="{{ $error['company_id'] ?? '' }}"
                                data-occurred="{{ $error['timestamp'] ?? '' }}"
                                data-request-url="{{ $error['request_url'] ?? '' }}"
                                data-http-method="{{ $error['http_method'] ?? '' }}"
                                data-user-agent="{{ $error['user_agent'] ?? '' }}"
                                data-device-type="{{ $error['device_type'] ?? '' }}"
                            >Ticket</button>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Geen recente fouten gevonden.</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <h2 class="text-lg font-semibold text-slate-900 mb-3">Incident tickets</h2>
            <div class="space-y-2 max-h-96 overflow-auto">
                @forelse($tickets as $ticket)
                    <div class="rounded-lg border border-slate-200 p-3">
                        <p class="text-xs text-slate-500">#{{ $ticket->id }} · {{ $ticket->status }} · {{ optional($ticket->error_occurred_at ?? $ticket->created_at)->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}</p>
                        <p class="text-sm font-medium text-slate-900 mt-1">{{ $ticket->title }}</p>
                        <p class="text-xs text-slate-600 mt-1 break-all">{{ $ticket->error_message }}</p>
                        <div class="mt-2 flex items-center gap-2">
                            <button type="button" class="rounded bg-slate-800 px-2 py-1 text-xs text-white hover:bg-slate-900 sa-ticket-open" data-ticket-id="{{ $ticket->id }}">Open</button>
                            <form method="POST" action="{{ route('super-admin.incidents.status.update', $ticket) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $ticket->status === 'resolved' ? 'open' : 'resolved' }}">
                                <button type="submit" class="rounded {{ $ticket->status === 'resolved' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700' }} px-2 py-1 text-xs text-white">
                                    {{ $ticket->status === 'resolved' ? 'Heropen' : 'Afronden' }}
                                </button>
                            </form>
                            @if($ticket->ai_analyzed_at)
                                <span class="text-[11px] text-emerald-700">AI geanalyseerd</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Nog geen tickets.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div id="sa-ticket-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/60" data-ticket-close></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-3xl rounded-2xl bg-white shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <h3 class="text-base font-semibold text-slate-900">Ticket details</h3>
                <button type="button" class="rounded p-1 text-slate-500 hover:bg-slate-100" data-ticket-close>✕</button>
            </div>
            <div class="p-4 space-y-3 max-h-[70vh] overflow-auto">
                <div id="sa-ticket-meta" class="text-sm text-slate-700"></div>
                <div id="sa-ticket-error" class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-slate-900 break-all"></div>
                <div id="sa-ticket-context" class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-xs text-slate-700 whitespace-pre-wrap break-all"></div>
                <div>
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-slate-900">AI analyse</h4>
                        <button type="button" id="sa-ticket-ai-btn" class="rounded bg-violet-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-violet-800">AI analyseer</button>
                    </div>
                    <div id="sa-ticket-ai-result" class="mt-2 rounded-lg border border-violet-200 bg-violet-50 p-3 text-sm text-slate-900 whitespace-pre-wrap break-all"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(() => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const feedUrl = @json(route('super-admin.errors.feed'));
    const ticketUrl = @json(route('super-admin.incidents.store'));
    const ticketShowUrlTemplate = @json(route('super-admin.incidents.show', ['incident' => '__ID__']));
    const ticketAnalyzeUrlTemplate = @json(route('super-admin.incidents.analyze', ['incident' => '__ID__']));
    const listEl = document.getElementById('sa-errors-list');
    const modalEl = document.getElementById('sa-ticket-modal');
    const ticketMetaEl = document.getElementById('sa-ticket-meta');
    const ticketErrorEl = document.getElementById('sa-ticket-error');
    const ticketContextEl = document.getElementById('sa-ticket-context');
    const ticketAiResultEl = document.getElementById('sa-ticket-ai-result');
    const ticketAiBtn = document.getElementById('sa-ticket-ai-btn');
    let currentTicketId = null;

    const safeText = (value) => String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');

    const ticketShowUrl = (id) => ticketShowUrlTemplate.replace('__ID__', String(id));
    const ticketAnalyzeUrl = (id) => ticketAnalyzeUrlTemplate.replace('__ID__', String(id));

    const openTicketModal = () => {
        modalEl.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };
    const closeTicketModal = () => {
        modalEl.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };
    document.querySelectorAll('[data-ticket-close]').forEach((el) => {
        el.addEventListener('click', closeTicketModal);
    });

    const bindTicketButtons = () => {
        document.querySelectorAll('.sa-ticket-btn').forEach((btn) => {
            btn.onclick = async () => {
                if (btn.dataset.sent === '1') return;
                const payload = {
                    fingerprint: btn.dataset.fingerprint,
                    title: btn.dataset.title || 'Automatisch error ticket',
                    error_message: btn.dataset.message || '',
                    context: btn.dataset.context || '',
                    error_occurred_at: btn.dataset.occurred || null,
                    company_id: btn.dataset.companyId ? Number(btn.dataset.companyId) : null,
                    request_url: btn.dataset.requestUrl || window.location.href,
                    http_method: btn.dataset.httpMethod || null,
                    user_agent: btn.dataset.userAgent || navigator.userAgent,
                    device_type: btn.dataset.deviceType || null,
                };
                const res = await fetch(ticketUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();
                btn.dataset.sent = '1';
                btn.textContent = data.created ? 'Aangemaakt' : 'Bestond al';
                btn.classList.remove('bg-red-600', 'hover:bg-red-700');
                btn.classList.add('bg-slate-500');
            };
        });

        document.querySelectorAll('.sa-ticket-open').forEach((btn) => {
            btn.onclick = async () => {
                const id = btn.dataset.ticketId;
                if (!id) return;
                currentTicketId = id;
                ticketMetaEl.textContent = 'Laden...';
                ticketErrorEl.textContent = '';
                ticketContextEl.textContent = '';
                ticketAiResultEl.textContent = '';
                openTicketModal();
                try {
                    const res = await fetch(ticketShowUrl(id), { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) throw new Error('Kon ticket niet laden');
                    const data = await res.json();
                    const t = data.ticket;
                    const d = data.display || {};
                    ticketMetaEl.innerHTML = `
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div><span class="font-semibold">Ticket:</span> #${safeText(t.id)}</div>
                            <div><span class="font-semibold">Status:</span> ${safeText(t.status)}</div>
                            <div><span class="font-semibold">Tijd:</span> ${safeText(d.occurred_at ?? d.created_at ?? t.error_occurred_at ?? t.created_at)}</div>
                            <div><span class="font-semibold">Bedrijf:</span> ${safeText(t.company?.name ?? 'onbekend')}</div>
                            <div><span class="font-semibold">URL:</span> <span class="break-all">${safeText(t.request_url ?? 'onbekend')}</span></div>
                            <div><span class="font-semibold">Methode:</span> ${safeText(t.http_method ?? '-')}</div>
                            <div><span class="font-semibold">Device:</span> ${safeText(t.device_type ?? '-')}</div>
                            <div><span class="font-semibold">IP:</span> ${safeText(t.ip_address ?? '-')}</div>
                            <div class="sm:col-span-2"><span class="font-semibold">User agent:</span> <span class="break-all">${safeText(t.user_agent ?? '-')}</span></div>
                        </div>
                    `;
                    ticketErrorEl.textContent = t.error_message || '';
                    ticketContextEl.textContent = t.context || 'Geen extra context';
                    ticketAiResultEl.textContent = t.ai_analysis || 'Nog geen AI analyse. Klik op "AI analyseer".';
                } catch (error) {
                    ticketMetaEl.textContent = 'Ticket laden mislukt.';
                }
            };
        });
    };

    const renderErrors = (errors) => {
        if (!Array.isArray(errors) || errors.length === 0) {
            listEl.innerHTML = '<p class="text-sm text-slate-500">Geen recente fouten gevonden.</p>';
            return;
        }
        listEl.innerHTML = errors.map((error) => `
            <div class="rounded-lg border border-red-200 bg-red-50 p-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs text-red-700 font-semibold">${error.level} · ${error.timestamp ?? 'onbekend'}</p>
                        <p class="text-sm text-slate-900 mt-1 break-all">${(error.message || '').replace(/</g, '&lt;')}</p>
                    </div>
                    <button
                        class="shrink-0 rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700 sa-ticket-btn"
                        data-fingerprint="${error.fingerprint}"
                        data-title="Automatisch error ticket"
                        data-message="${safeText(error.message || '')}"
                        data-context="${safeText(error.raw || '')}"
                        data-company-id="${error.company_id ?? ''}"
                        data-occurred="${error.timestamp ?? ''}"
                        data-request-url="${safeText(error.request_url || '')}"
                        data-http-method="${safeText(error.http_method || '')}"
                        data-user-agent="${safeText(error.user_agent || '')}"
                        data-device-type="${safeText(error.device_type || '')}"
                    >Ticket</button>
                </div>
            </div>
        `).join('');
        bindTicketButtons();
    };

    const refresh = async () => {
        try {
            const res = await fetch(feedUrl, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const data = await res.json();
            renderErrors(data.errors || []);
        } catch (e) {
            // noop
        }
    };

    ticketAiBtn?.addEventListener('click', async () => {
        if (!currentTicketId) return;
        ticketAiBtn.disabled = true;
        ticketAiBtn.textContent = 'Analyseren...';
        try {
            const res = await fetch(ticketAnalyzeUrl(currentTicketId), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (!res.ok || !data.success) {
                ticketAiResultEl.textContent = data.message || 'AI analyse mislukt.';
            } else {
                ticketAiResultEl.textContent = data.analysis || 'Geen analyse teruggekregen.';
            }
        } catch (e) {
            ticketAiResultEl.textContent = 'AI analyse mislukt door netwerkfout.';
        } finally {
            ticketAiBtn.disabled = false;
            ticketAiBtn.textContent = 'AI analyseer';
        }
    });

    bindTicketButtons();
    setInterval(refresh, 8000);
})();
</script>
@endpush
@endsection

