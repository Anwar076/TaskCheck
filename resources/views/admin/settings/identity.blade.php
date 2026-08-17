@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 p-4 sm:p-6">
    <div><h1 class="text-2xl font-bold text-slate-900">Microsoft Entra ID & SCIM</h1><p class="mt-1 text-sm text-slate-600">Configureer SSO, MFA-bevestiging, groepsrollen en gebruikersprovisioning.</p></div>
    @if(session('status'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('status') }}</div>@endif
    @if(session('scim_token'))<div class="rounded-xl border border-amber-300 bg-amber-50 p-4"><p class="font-semibold text-amber-900">Eenmalig zichtbare SCIM-token</p><code class="mt-2 block break-all rounded bg-white p-3 text-sm">{{ session('scim_token') }}</code></div>@endif
    @if($errors->any())<div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <form method="POST" action="{{ route('admin.settings.identity.update') }}" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf @method('PUT')
        <div><label class="block text-sm font-semibold">Zakelijk e-maildomein</label><input name="domain" required value="{{ old('domain', $company->domain) }}" placeholder="bedrijf.nl" class="mt-1 block w-full rounded-lg border-slate-300"></div>
        <div class="grid gap-4 sm:grid-cols-3">
            @foreach(['entra_enabled' => 'Entra SSO actief', 'entra_sso_required' => 'Lokale login blokkeren', 'entra_mfa_required' => 'MFA-claim verplicht'] as $name => $label)
                <label class="flex items-center gap-2 rounded-lg border p-3"><input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $company->$name))><span class="text-sm font-medium">{{ $label }}</span></label>
            @endforeach
        </div>
        <div class="grid gap-4 sm:grid-cols-2"><div><label class="block text-sm font-semibold">Tenant ID</label><input name="entra_tenant_id" value="{{ old('entra_tenant_id', $company->entra_tenant_id) }}" class="mt-1 block w-full rounded-lg border-slate-300"></div><div><label class="block text-sm font-semibold">Client ID</label><input name="entra_client_id" value="{{ old('entra_client_id', $company->entra_client_id) }}" class="mt-1 block w-full rounded-lg border-slate-300"></div></div>
        <div><label class="block text-sm font-semibold">Client secret</label><input type="password" name="entra_client_secret" autocomplete="new-password" placeholder="Leeg laten om bestaand secret te behouden" class="mt-1 block w-full rounded-lg border-slate-300"></div>
        <div><label class="block text-sm font-semibold">Admin-groeps-ID's</label><textarea name="entra_admin_group_ids" rows="2" class="mt-1 block w-full rounded-lg border-slate-300" placeholder="GUID's, gescheiden door komma's">{{ old('entra_admin_group_ids', implode(', ', $company->entra_admin_group_ids ?? [])) }}</textarea></div>
        <div><label class="block text-sm font-semibold">Medewerker-groeps-ID's</label><textarea name="entra_employee_group_ids" rows="2" class="mt-1 block w-full rounded-lg border-slate-300">{{ old('entra_employee_group_ids', implode(', ', $company->entra_employee_group_ids ?? [])) }}</textarea></div>
        <p class="text-xs text-slate-500">Redirect URI: <code>{{ route('entra.callback') }}</code>. Configureer group claims en Conditional Access in Entra. Activeer verplichte SSO pas na een geslaagde test.</p>
        <button class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white">Opslaan</button>
    </form>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><h2 class="font-bold">SCIM 2.0</h2>
        @if($company->scim_endpoint_key)<p class="mt-3 text-sm">Tenant URL</p><code class="mt-1 block break-all rounded bg-slate-50 p-3 text-sm">{{ url('/api/scim/v2/'.$company->scim_endpoint_key) }}</code>@endif
        <form method="POST" action="{{ route('admin.settings.identity.scim-token') }}" class="mt-4">@csrf<button class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold">{{ $company->scim_token_hash ? 'SCIM-token roteren' : 'SCIM-token aanmaken' }}</button></form>
    </div>
</div>
@endsection
