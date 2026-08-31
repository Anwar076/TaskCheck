<section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between gap-3"><div><h2 class="font-semibold text-slate-900">Abonnement beheren</h2><p class="text-xs text-slate-500">Plan, toegang en facturatie.</p></div><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusStyles }}">{{ $statusLabel }}</span></div>
    @php
        $mollieStatusLabel = match($mollieBilling['status'] ?? null) {
            'active' => 'Actief', 'pending' => 'In afwachting', 'suspended' => 'Gepauzeerd', 'cancelled' => 'Geannuleerd', 'completed' => 'Afgerond', default => 'Onbekend',
        };
        $planBillingPeriod = $company->getPlanDetails()['billing_period'] ?? 'monthly';
        $billingPeriodLabel = match($mollieBilling['interval'] ?? null) {
            '1 month' => 'Maandelijks', '3 months' => 'Per kwartaal', '6 months' => 'Halfjaarlijks', '12 months' => 'Jaarlijks',
            default => \App\Models\Organisation\Company::billingPeriod($planBillingPeriod)['label'] ?? ucfirst($planBillingPeriod),
        };
    @endphp
    <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
        @if(!$mollieBilling['connected'])
            <div class="flex items-start gap-2"><span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-slate-400"></span><div><p class="text-sm font-semibold text-slate-800">Geen automatische incasso gekoppeld</p><p class="mt-0.5 text-xs text-slate-500">Er is nog geen actief Mollie-abonnement met een volgende facturatiedatum.</p></div></div>
        @elseif(!$mollieBilling['available'])
            <div class="flex items-start gap-2"><span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-amber-500"></span><div><p class="text-sm font-semibold text-slate-800">Mollie tijdelijk niet bereikbaar</p><p class="mt-0.5 text-xs text-slate-500">De facturatiegegevens konden niet worden opgehaald. Probeer het later opnieuw.</p></div></div>
        @else
            <div class="grid grid-cols-2 gap-x-3 gap-y-2 text-xs">
                <span class="text-slate-500">Volgende facturatie</span><strong class="text-right text-slate-900">{{ $mollieBilling['next_payment_date']?->format('d-m-Y') ?? 'Nog niet gepland' }}</strong>
                <span class="text-slate-500">Betaalfrequentie</span><strong class="text-right text-slate-900">{{ $billingPeriodLabel }}</strong>
                <span class="text-slate-500">Bedrag</span><strong class="text-right text-slate-900">{{ $mollieBilling['amount'] !== null ? ($mollieBilling['currency'].' '.number_format((float)$mollieBilling['amount'], 2, ',', '.')) : '—' }}</strong>
                <span class="text-slate-500">Mollie-status</span><strong class="text-right {{ ($mollieBilling['status'] ?? null) === 'active' ? 'text-emerald-700' : 'text-slate-900' }}">{{ $mollieStatusLabel }}</strong>
            </div>
        @endif
    </div>
    <form method="POST" action="{{ route('super-admin.companies.subscription.update', $company) }}" class="mt-5 space-y-4">@csrf @method('PUT')
        <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Abonnement</label><select id="subscription-plan" name="subscription_plan" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">@foreach(\App\Models\Organisation\Company::plans() as $planKey => $planData)<option value="{{ $planKey }}" @selected(old('subscription_plan', $company->subscription_plan) === $planKey)>{{ $planData['name'] }}</option>@endforeach</select></div>
        <div id="custom-subscription-fields" class="space-y-3 rounded-xl border border-blue-200 bg-blue-50 p-3">
            <p class="text-sm font-semibold text-slate-900">Klant-specifiek abonnement</p>
            <div><label class="mb-1 block text-xs font-medium text-slate-700">Naam</label><input name="custom_subscription_name" value="{{ old('custom_subscription_name', $company->custom_subscription_name) }}" placeholder="Bijv. Kwalitaria Plus" class="w-full rounded-lg border-slate-300 text-sm">@error('custom_subscription_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="mb-1 block text-xs font-medium text-slate-700">Prijs per maand (excl. btw)</label><input name="custom_monthly_price" type="number" min="0" step="0.01" value="{{ old('custom_monthly_price', $company->custom_monthly_price) }}" class="w-full rounded-lg border-slate-300 text-sm">@error('custom_monthly_price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
            <div class="grid grid-cols-3 gap-2">
                <div><label class="mb-1 block text-xs font-medium text-slate-700">Gebruikers</label><input name="custom_max_users" type="number" min="-1" value="{{ old('custom_max_users', $company->max_users ?? -1) }}" class="w-full rounded-lg border-slate-300 text-sm"></div>
                <div><label class="mb-1 block text-xs font-medium text-slate-700">Locaties</label><input name="custom_max_locations" type="number" min="-1" value="{{ old('custom_max_locations', $company->max_locations ?? -1) }}" class="w-full rounded-lg border-slate-300 text-sm"></div>
                <div><label class="mb-1 block text-xs font-medium text-slate-700">Opslag GB</label><input name="custom_max_storage_gb" type="number" min="-1" value="{{ old('custom_max_storage_gb', $company->max_storage_gb ?? -1) }}" class="w-full rounded-lg border-slate-300 text-sm"></div>
            </div>
            <p class="text-[11px] text-slate-500">Gebruik -1 voor onbeperkt.</p>
        </div>
        <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Abonnementsstatus</label><select name="subscription_status" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">@foreach(['trial' => 'Proefperiode', 'active' => 'Actief', 'cancelled' => 'Geannuleerd', 'expired' => 'Verlopen'] as $value => $label)<option value="{{ $value }}" @selected(old('subscription_status', $status) === $value)>{{ $label }}</option>@endforeach</select></div>
        <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Einddatum gratis toegang</label><input type="date" name="subscription_ends_at" value="{{ old('subscription_ends_at', optional($company->subscription_ends_at)->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"><p class="mt-1 text-xs text-slate-500">Verplicht bij gratis toegang. Een ingevulde datum wordt ook bij maandelijkse betaling bewaard.</p>@error('subscription_ends_at')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror</div>
        <div class="space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-3"><label class="flex items-start gap-2 text-sm text-slate-700"><input type="checkbox" name="billing_required" value="1" class="mt-0.5 rounded border-slate-300 text-blue-600" @checked(old('billing_required', $company->billing_required))><span><strong class="font-medium text-slate-900">Maandelijkse betaling</strong><br><span class="text-xs text-slate-500">Facturatie en abonnement blijven doorlopen.</span></span></label><label class="flex items-start gap-2 border-t border-slate-200 pt-2 text-sm text-slate-700"><input type="checkbox" name="is_active" value="1" class="mt-0.5 rounded border-slate-300 text-blue-600" @checked(old('is_active', $company->is_active))><span><strong class="font-medium text-slate-900">Platformtoegang actief</strong><br><span class="text-xs text-slate-500">Gebruikers van dit bedrijf kunnen inloggen.</span></span></label></div>
        <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Wijzigingen opslaan</button>
    </form>
</section>
