@extends('layouts.super-admin')

@section('page-title', 'Nieuw bedrijf')

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}" class="font-medium text-slate-500 hover:text-blue-700">Bedrijven</a>
    <span class="text-slate-400">/</span>
    <span class="font-semibold text-slate-900">Nieuw bedrijf</span>
@endsection

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Nieuwe klant</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">Bedrijf en beheerder aanmaken</h1>
            <p class="mt-1 text-sm text-slate-500">Maak de organisatie, eerste admin en toegang in één gecontroleerde stap aan.</p>
        </div>
        <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-700">← Terug naar bedrijven</a>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Controleer de gemarkeerde gegevens.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('super-admin.companies.store') }}" class="space-y-6">
        @csrf
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-700">1</span>
                    <div><h2 class="font-semibold text-slate-900">Bedrijfsgegevens</h2><p class="text-xs text-slate-500">De basisgegevens van de nieuwe klant.</p></div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
                <div class="sm:col-span-2"><label class="mb-1.5 block text-sm font-medium text-slate-700">Bedrijfsnaam <span class="text-red-500">*</span></label><input name="company_name" value="{{ old('company_name') }}" required autofocus class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Bijv. Restaurant De Haven"></div>
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Zakelijk telefoonnummer</label><input name="company_phone" value="{{ old('company_phone') }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="020 123 45 67"></div>
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Website</label><input name="company_website" value="{{ old('company_website') }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="https://voorbeeld.nl"></div>
                <div class="sm:col-span-2"><label class="mb-1.5 block text-sm font-medium text-slate-700">Adres</label><input name="company_address" value="{{ old('company_address') }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Straat 1, 1234 AB Amsterdam"></div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-700">2</span>
                    <div><h2 class="font-semibold text-slate-900">Eerste beheerder</h2><p class="text-xs text-slate-500">Deze persoon ontvangt het eerste adminaccount.</p></div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Naam beheerder <span class="text-red-500">*</span></label><input name="admin_name" value="{{ old('admin_name') }}" required class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Voor- en achternaam"></div>
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">E-mailadres <span class="text-red-500">*</span></label><input name="admin_email" value="{{ old('admin_email') }}" type="email" required class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="beheerder@bedrijf.nl"></div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Tijdelijk wachtwoord <span class="text-red-500">*</span></label>
                    <div class="flex flex-col gap-2 sm:flex-row"><input id="company-password" name="admin_password" type="text" required minlength="8" class="min-w-0 flex-1 rounded-xl border-slate-300 font-mono text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Minimaal 8 tekens"><button type="button" id="generate-password" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Veilig wachtwoord genereren</button></div>
                    <p class="mt-1.5 text-xs text-slate-500">Deel dit eenmalig veilig met de beheerder. De beheerder kan het daarna wijzigen.</p>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-700">3</span>
                    <div><h2 class="font-semibold text-slate-900">Abonnement en toegang</h2><p class="text-xs text-slate-500">Kies capaciteit, betaling en eventuele einddatum.</p></div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Abonnement <span class="text-red-500">*</span></label><select name="subscription_plan" required class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">@foreach(\App\Models\Organisation\Company::plans() as $key => $plan)<option value="{{ $key }}" @selected(old('subscription_plan', 'starter') === $key)>{{ $plan['name'] }} — {{ $plan['max_users'] === -1 ? 'onbeperkt' : $plan['max_users'] }} gebruikers</option>@endforeach</select></div>
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Einddatum gratis toegang</label><input id="access-end-date" name="access_end_date" type="date" value="{{ old('access_end_date') }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"><p class="mt-1.5 text-xs text-slate-500">Verplicht wanneer maandelijkse betaling uitstaat.</p></div>
                <label class="sm:col-span-2 flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4"><input id="billing-required" type="checkbox" name="billing_required" value="1" class="mt-0.5 rounded border-blue-300 text-blue-600 focus:ring-blue-500" @checked(old('billing_required', true))><span><strong class="block text-sm text-slate-900">Maandelijkse betaling vereist</strong><span class="mt-0.5 block text-xs text-slate-600">Het abonnement loopt door via de normale betaalflow. Zet dit uit voor tijdelijke of gratis toegang.</span></span></label>
            </div>
        </section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}" class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Annuleren</a>
            <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Bedrijf en beheerder aanmaken <span>→</span></button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('generate-password')?.addEventListener('click', () => {
    const alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
    const values = crypto.getRandomValues(new Uint32Array(16));
    document.getElementById('company-password').value = Array.from(values, value => alphabet[value % alphabet.length]).join('');
});
</script>
@endpush
@endsection
