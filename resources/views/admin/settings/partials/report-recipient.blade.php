@php($recipient = (array) $recipient)
@php($sections = \App\Models\Organisation\CompanyReportRecipient::normalizeSections($recipient['sections'] ?? null))
@php($sendUrl = $sendUrl ?? ($recipient['send_url'] ?? null))
<article class="report-recipient rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    @if(!empty($recipient['id']))<input type="hidden" name="report_recipients[{{ $index }}][id]" value="{{ $recipient['id'] }}">@endif
    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-6">
        <div class="xl:col-span-2"><label class="block text-xs font-semibold text-slate-600">E-mailadres</label><input type="email" required name="report_recipients[{{ $index }}][email]" value="{{ $recipient['email'] ?? '' }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm" placeholder="naam@bedrijf.nl"></div>
        <div><label class="block text-xs font-semibold text-slate-600">Frequentie</label><select name="report_recipients[{{ $index }}][frequency]" class="report-frequency mt-1 w-full rounded-lg border-slate-300 text-sm"><option value="daily" @selected(($recipient['frequency'] ?? '') === 'daily')>Dagelijks</option><option value="weekly" @selected(($recipient['frequency'] ?? '') === 'weekly')>Wekelijks</option></select></div>
        <div class="report-weekday"><label class="block text-xs font-semibold text-slate-600">Verzenddag</label><select name="report_recipients[{{ $index }}][weekly_day]" class="mt-1 w-full rounded-lg border-slate-300 text-sm">@foreach(\App\Models\Organisation\Company::WEEKDAYS as $dayKey => $dayLabel)<option value="{{ $loop->iteration }}" @selected((int)($recipient['weekly_day'] ?? 1) === $loop->iteration)>{{ $dayLabel }}</option>@endforeach</select></div>
        <div><label class="block text-xs font-semibold text-slate-600">Tijdstip</label><input type="time" required name="report_recipients[{{ $index }}][send_time]" value="{{ $recipient['send_time'] ?? '18:00' }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></div>
        <div><label class="block text-xs font-semibold text-slate-600">Ontvangst</label><select name="report_recipients[{{ $index }}][delivery_format]" class="mt-1 w-full rounded-lg border-slate-300 text-sm"><option value="email" @selected(($recipient['delivery_format'] ?? '') === 'email')>Opgemaakte e-mail</option><option value="pdf" @selected(($recipient['delivery_format'] ?? '') === 'pdf')>PDF-bijlage</option><option value="both" @selected(($recipient['delivery_format'] ?? 'both') === 'both')>E-mail + PDF</option></select></div>
    </div>
    <fieldset class="mt-4 border-t border-slate-100 pt-4">
        <legend class="text-xs font-semibold text-slate-700">Onderdelen in deze rapportage</legend>
        <p class="mt-1 text-xs text-slate-500">Vink alleen aan wat voor deze ontvanger relevant is.</p>
        <div class="mt-3 grid gap-2 sm:grid-cols-2 xl:grid-cols-3">
            @foreach([
                'summary' => ['Samenvatting', 'Totalen, status en voltooiingspercentage'],
                'top_lists' => ['Meest gebruikte lijsten', 'Lijsten met de meeste inzendingen'],
                'employee_performance' => ['Prestaties medewerkers', 'Resultaten uitgesplitst per account'],
                'attention_points' => ['Opmerkingen & afwijkingen', 'Alleen punten die aandacht nodig hebben'],
                'task_overview' => ['Individuele taken', 'Alle taken met hun afgeronde of open status'],
            ] as $sectionKey => [$sectionLabel, $sectionDescription])
                <label class="flex cursor-pointer items-start gap-2 rounded-lg border border-slate-200 p-3 hover:bg-slate-50">
                    <input type="hidden" name="report_recipients[{{ $index }}][sections][{{ $sectionKey }}]" value="0">
                    <input type="checkbox" name="report_recipients[{{ $index }}][sections][{{ $sectionKey }}]" value="1" @checked($sections[$sectionKey]) class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span><span class="block text-xs font-semibold text-slate-700">{{ $sectionLabel }}</span><span class="mt-0.5 block text-[11px] leading-snug text-slate-500">{{ $sectionDescription }}</span></span>
                </label>
            @endforeach
        </div>
        @error("report_recipients.{$index}.sections")<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
    </fieldset>
    <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs text-slate-500">Tijdzone: Nederland (Europe/Amsterdam)</p>
        <div class="flex flex-wrap items-center gap-3">
            @if($sendUrl)
                <button type="button" data-send-url="{{ $sendUrl }}" class="send-report-now rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 disabled:cursor-wait disabled:opacity-60">Rapportage nu versturen</button>
            @else
                <span class="text-xs text-slate-400">Sla eerst op om een rapportage te versturen.</span>
            @endif
            <button type="button" class="remove-report-recipient text-xs font-semibold text-red-600 hover:text-red-800">Ontvanger verwijderen</button>
        </div>
    </div>
</article>
