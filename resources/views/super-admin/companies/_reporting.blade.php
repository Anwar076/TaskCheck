@php
    $reportRecipients = collect(old('report_recipients', $company->reportRecipients->map(fn ($recipient) => [
        'id' => $recipient->id,
        'email' => $recipient->email,
        'frequency' => $recipient->frequency,
        'send_time' => substr((string) $recipient->send_time, 0, 5),
        'weekly_day' => $recipient->weekly_day,
        'delivery_format' => $recipient->delivery_format,
        'sections' => $recipient->normalizedSections(),
        'send_url' => route('super-admin.companies.reporting.send-now', [$company, $recipient]),
    ])->all()));
@endphp
<form method="POST" action="{{ route('super-admin.companies.reporting.update', $company) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">@csrf @method('PUT')
    <div><h2 class="text-lg font-semibold text-slate-900">Geplande rapportages</h2><p class="mt-1 text-sm text-slate-500">Beheer namens de klant de planning, bezorgvorm en zichtbare onderdelen van iedere rapportage.</p></div>
    <div id="super-report-recipient-list" class="mt-5 space-y-3">@foreach($reportRecipients as $index => $recipient)@include('admin.settings.partials.report-recipient', ['index' => $index, 'recipient' => $recipient])@endforeach</div>
    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"><button type="button" id="super-add-report-recipient" class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-100">+ Ontvanger toevoegen</button><button class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Rapportageplanning opslaan</button></div>
    <template id="super-report-recipient-template">@include('admin.settings.partials.report-recipient', ['index' => '__INDEX__', 'recipient' => ['id' => null, 'email' => '', 'frequency' => 'daily', 'send_time' => '18:00', 'weekly_day' => 1, 'delivery_format' => 'both']])</template>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('super-report-recipient-list');
    const template = document.getElementById('super-report-recipient-template');
    let index = list?.querySelectorAll('.report-recipient').length ?? 0;
    const sync = card => card?.querySelector('.report-weekday')?.classList.toggle('hidden', card.querySelector('.report-frequency')?.value !== 'weekly');
    list?.querySelectorAll('.report-recipient').forEach(sync);
    document.getElementById('super-add-report-recipient')?.addEventListener('click', function () { const html = template?.innerHTML.replaceAll('__INDEX__', String(index++)); if (!html || !list) return; list.insertAdjacentHTML('beforeend', html); sync(list.lastElementChild); list.lastElementChild?.querySelector('input[type="email"]')?.focus(); });
    list?.addEventListener('change', event => { if (event.target.closest('.report-frequency')) sync(event.target.closest('.report-recipient')); });
    list?.addEventListener('click', event => {
        const sendButton = event.target.closest('.send-report-now');
        if (sendButton) {
            const original = sendButton.textContent;
            sendButton.disabled = true;
            sendButton.textContent = 'Bezig met versturen…';
            fetch(sendButton.dataset.sendUrl, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' } })
                .then(async response => { const body = await response.json().catch(() => ({})); if (!response.ok) throw new Error(body.message || 'Versturen is mislukt.'); alert(body.message); })
                .catch(error => alert(error.message))
                .finally(() => { sendButton.disabled = false; sendButton.textContent = original; });
            return;
        }
        event.target.closest('.remove-report-recipient')?.closest('.report-recipient')?.remove();
    });
});
</script>
