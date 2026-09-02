@push('scripts')
<style>
    .sa-tab-btn {
        color: #475569;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all .2s ease;
    }
    .sa-tab-btn:hover {
        color: #1d4ed8;
        background-color: #eff6ff;
        border-color: #93c5fd;
    }
    .sa-tab-btn.active {
        color: #ffffff;
        background-color: #2563eb;
        border-color: #2563eb;
        box-shadow: 0 8px 16px rgba(37, 99, 235, 0.18);
    }
    .sa-incident-tab-btn {
        color: #475569;
        background-color: transparent;
        transition: all .2s ease;
    }
    .sa-incident-tab-btn.active {
        color: #ffffff;
        background-color: #334155;
    }
</style>
<script>
(() => {
    document.querySelectorAll('[data-delete-company]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const companyName = form.dataset.companyName ?? '';
            const confirmation = window.prompt(`Typ "${companyName}" om dit bedrijf en alle onderliggende gebruikers definitief te verwijderen.`);
            if (confirmation !== companyName) {
                event.preventDefault();
                if (confirmation !== null) window.alert('De bedrijfsnaam komt niet exact overeen. Het bedrijf is niet verwijderd.');
                return;
            }
            form.querySelector('input[name="confirmation_name"]').value = confirmation;
        });
    });

    const communicationCounts = @json($communicationCounts ?? []);
    const previewModal = document.getElementById('sa-message-preview');
    document.querySelectorAll('[data-preview-close]').forEach((button) => button.addEventListener('click', () => previewModal?.classList.add('hidden')));
    document.querySelectorAll('[data-preview-form]').forEach((button) => button.addEventListener('click', () => {
        const form = document.getElementById(button.dataset.previewForm);
        if (!form || !previewModal) return;
        document.getElementById('sa-preview-kind').textContent = button.dataset.previewKind || 'Voorbeeld';
        document.getElementById('sa-preview-title').textContent = form.querySelector('[name="subject"], [name="title"]')?.value || 'Nog geen titel ingevuld';
        document.getElementById('sa-preview-message').textContent = form.querySelector('[name="message"]')?.value || 'Nog geen bericht ingevuld.';
        previewModal.classList.remove('hidden');
    }));
    document.querySelectorAll('[data-confirm-send]').forEach((button) => button.addEventListener('click', (event) => {
        if (!window.confirm(button.dataset.confirmSend)) event.preventDefault();
    }));

    const mailForm = document.getElementById('broadcast-mail-form');
    const notificationForm = document.getElementById('broadcast-notification-form');
    const bindDraft = (form, key) => {
        if (!form) return;
        const fields = [...form.querySelectorAll('input[name], textarea[name], select[name]')].filter((field) => !['_token', 'send_mode'].includes(field.name));
        try {
            const draft = JSON.parse(localStorage.getItem(key) || '{}');
            fields.forEach((field) => { if (draft[field.name] === undefined) return; field.type === 'checkbox' ? field.checked = !!draft[field.name] : field.value = draft[field.name]; });
        } catch (_) {}
        const save = () => { const draft = {}; fields.forEach((field) => draft[field.name] = field.type === 'checkbox' ? field.checked : field.value); localStorage.setItem(key, JSON.stringify(draft)); };
        fields.forEach((field) => field.addEventListener('input', save));
        fields.forEach((field) => field.addEventListener('change', save));
    };
    bindDraft(mailForm, 'taskcheck-superadmin-mail-draft');
    bindDraft(notificationForm, 'taskcheck-superadmin-notification-draft');

    const updateMailCount = () => {
        const includeInactive = mailForm?.querySelector('[name="include_inactive"]')?.checked;
        const count = includeInactive ? communicationCounts.all_companies : communicationCounts.active_companies;
        const element = document.getElementById('mail-recipient-count'); if (element) element.textContent = count ?? 0;
    };
    const updateNotificationCount = () => {
        const audience = notificationForm?.querySelector('[name="audience"]')?.value || 'all';
        const inactive = notificationForm?.querySelector('[name="include_inactive"]')?.checked;
        const key = audience === 'admins' ? (inactive ? 'all_admins' : 'active_admins') : audience === 'employees' ? (inactive ? 'all_employees' : 'active_employees') : (inactive ? 'all_users' : 'active_users');
        const element = document.getElementById('notification-recipient-count'); if (element) element.textContent = communicationCounts[key] ?? 0;
    };
    mailForm?.querySelector('[name="include_inactive"]')?.addEventListener('change', updateMailCount);
    notificationForm?.querySelector('[name="audience"]')?.addEventListener('change', updateNotificationCount);
    notificationForm?.querySelector('[name="include_inactive"]')?.addEventListener('change', updateNotificationCount);
    updateMailCount(); updateNotificationCount();

    document.querySelectorAll('[data-table-search]').forEach((input) => input.addEventListener('input', () => {
        const group = input.dataset.tableSearch;
        const term = input.value.trim().toLowerCase();
        let visibleRows = 0;
        document.querySelectorAll(`[data-search-row="${group}"]`).forEach((row) => {
            const hidden = Boolean(term && !row.dataset.searchText.includes(term));
            row.classList.toggle('hidden', hidden);
            if (!hidden) visibleRows++;
        });
        document.querySelector(`[data-table-empty="${group}"]`)?.classList.toggle('hidden', visibleRows > 0);
    }));
    const tabPanels = Array.from(document.querySelectorAll('.sa-tab-panel'));
    const tabFromQuery = new URLSearchParams(window.location.search).get('tab');
    const allowedTabs = new Set(['overview', 'communications', 'companies', 'users', 'usage', 'monitoring', 'invoices', 'templates']);
    const serverTab = @json($activeDashboardTab);
    const initialTab = (tabFromQuery && allowedTabs.has(tabFromQuery))
        ? tabFromQuery
        : (allowedTabs.has(serverTab) ? serverTab : 'overview');

    const activateTab = (name) => {
        tabPanels.forEach((panel) => {
            const isActive = panel.dataset.tabPanel === name;
            panel.classList.toggle('hidden', !isActive);
        });
    };

    if (tabPanels.length) {
        activateTab(initialTab);
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const feedUrl = @json(route('super-admin.errors.feed'));
    const ticketUrl = @json(route('super-admin.incidents.store'));
    const ticketShowUrlTemplate = @json(route('super-admin.incidents.show', ['incident' => '__ID__']));
    const ticketAnalyzeUrlTemplate = @json(route('super-admin.incidents.analyze', ['incident' => '__ID__']));
    const ticketStatusUrlTemplate = @json(route('super-admin.incidents.status.update', ['incident' => '__ID__']));
    const listEl = document.getElementById('sa-errors-list');
    const modalEl = document.getElementById('sa-ticket-modal');
    const ticketMetaEl = document.getElementById('sa-ticket-meta');
    const ticketErrorEl = document.getElementById('sa-ticket-error');
    const ticketContextEl = document.getElementById('sa-ticket-context');
    const ticketAiResultEl = document.getElementById('sa-ticket-ai-result');
    const ticketAiBtn = document.getElementById('sa-ticket-ai-btn');
    const incidentsListEl = document.getElementById('sa-incidents-active-list');
    let currentTicketId = null;

    const incidentTabButtons = Array.from(document.querySelectorAll('.sa-incident-tab-btn'));
    const incidentPanels = {
        active: document.getElementById('sa-incidents-active-list'),
        archive: document.getElementById('sa-incidents-archive-list'),
    };

    const activateIncidentTab = (tabName) => {
        incidentTabButtons.forEach((button) => {
            const isActive = button.dataset.incidentTabTarget === tabName;
            button.classList.toggle('active', isActive);
        });
        Object.entries(incidentPanels).forEach(([name, panel]) => {
            if (!panel) return;
            panel.classList.toggle('hidden', name !== tabName);
        });
    };

    if (incidentTabButtons.length) {
        incidentTabButtons.forEach((button) => {
            button.addEventListener('click', () => activateIncidentTab(button.dataset.incidentTabTarget));
        });
        activateIncidentTab('active');
    }

    const safeText = (value) => String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');

    const ticketShowUrl = (id) => ticketShowUrlTemplate.replace('__ID__', String(id));
    const ticketAnalyzeUrl = (id) => ticketAnalyzeUrlTemplate.replace('__ID__', String(id));
    const ticketStatusUrl = (id) => ticketStatusUrlTemplate.replace('__ID__', String(id));

    const renderIncidentTicketCard = (ticket, opts = {}) => {
        if (!ticket) return '';
        const ticketId = Number(ticket.id || 0);
        if (!ticketId) return '';

        const dateText = opts.dateText || new Date().toLocaleString('nl-NL', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        }).replace(',', '');

        const escapedTitle = safeText(ticket.title || 'Incident ticket');
        const escapedMessage = safeText(ticket.error_message || '');
        const escapedStatus = safeText(ticket.status || 'open');

        return `
            <div class="rounded-lg border border-slate-200 p-3">
                <p class="text-xs text-slate-500">#${ticketId} · ${escapedStatus} · ${dateText}</p>
                <p class="text-sm font-medium text-slate-900 mt-1">${escapedTitle}</p>
                <p class="text-xs text-slate-600 mt-1 break-all">${escapedMessage}</p>
                <div class="mt-2 flex items-center gap-2">
                    <button type="button" class="rounded bg-slate-800 px-2 py-1 text-xs text-white hover:bg-slate-900 sa-ticket-open" data-ticket-id="${ticketId}">Open</button>
                    <form method="POST" action="${safeText(ticketStatusUrl(ticketId))}">
                        <input type="hidden" name="_token" value="${safeText(csrf || '')}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="status" value="resolved">
                        <button type="submit" class="rounded bg-emerald-600 px-2 py-1 text-xs text-white hover:bg-emerald-700">Afronden</button>
                    </form>
                    <form method="POST" action="${safeText(ticketStatusUrl(ticketId))}">
                        <input type="hidden" name="_token" value="${safeText(csrf || '')}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="status" value="ignored">
                        <button type="submit" class="rounded bg-slate-500 px-2 py-1 text-xs text-white hover:bg-slate-600">Archiveer</button>
                    </form>
                    <span class="text-[11px] text-emerald-700">Net aangemaakt</span>
                </div>
            </div>
        `;
    };

    const prependIncidentTicket = (ticket, opts = {}) => {
        if (!incidentsListEl) return;

        const ticketId = Number(ticket?.id || 0);
        if (!ticketId) return;
        if (incidentsListEl.querySelector(`.sa-ticket-open[data-ticket-id="${ticketId}"]`)) {
            return;
        }

        const emptyState = incidentsListEl.querySelector('p.text-sm.text-slate-500');
        if (emptyState && emptyState.textContent?.includes('Nog geen tickets')) {
            emptyState.remove();
        }

        incidentsListEl.insertAdjacentHTML('afterbegin', renderIncidentTicketCard(ticket, opts));
        bindTicketButtons();
    };

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

                if (data.ticket) {
                    prependIncidentTicket(data.ticket, {
                        dateText: new Date().toLocaleString('nl-NL', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                        }).replace(',', ''),
                    });
                }
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
            <div class="sa-error-card rounded-xl border border-red-200 bg-red-50 p-3" data-error-level="${safeText(error.level || 'ERROR')}" data-error-text="${safeText((error.message || '').toLowerCase())}">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs text-red-700 font-semibold">${safeText(error.level)} · ${error.count || 1}× · laatst ${safeText(error.last_seen || 'onbekend')}</p>
                        <p class="text-sm text-slate-900 mt-1 break-words line-clamp-3">${safeText(error.message || '')}</p>
                        <p class="mt-1 text-[11px] text-slate-500">Eerste keer: ${safeText(error.first_seen || 'onbekend')}</p>
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

    const filterErrors = () => {
        const term = (document.getElementById('sa-error-search')?.value || '').toLowerCase();
        const level = document.getElementById('sa-error-level')?.value || '';
        document.querySelectorAll('.sa-error-card').forEach((card) => {
            card.classList.toggle('hidden', !!((term && !card.dataset.errorText.includes(term)) || (level && card.dataset.errorLevel !== level)));
        });
    };
    document.getElementById('sa-error-search')?.addEventListener('input', filterErrors);
    document.getElementById('sa-error-level')?.addEventListener('change', filterErrors);

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

    const copyFeedback = (btn, okLabel = 'Gekopieerd') => {
        const prev = btn.textContent;
        btn.textContent = okLabel;
        setTimeout(() => { btn.textContent = prev; }, 1500);
    };

    document.querySelectorAll('[data-copy-target]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const id = btn.getAttribute('data-copy-target');
            const el = id ? document.getElementById(id) : null;
            const text = el?.textContent?.trim() ?? '';
            if (!text) return;
            try {
                await navigator.clipboard.writeText(text);
                copyFeedback(btn);
            } catch {
                window.prompt('Kopieer deze URL:', text);
            }
        });
    });

    document.querySelectorAll('[data-copy-text]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const text = btn.getAttribute('data-copy-text') ?? '';
            if (!text) return;
            try {
                await navigator.clipboard.writeText(text);
                copyFeedback(btn);
            } catch {
                window.prompt('Kopieer deze HTML:', text);
            }
        });
    });
})();
</script>
@endpush
