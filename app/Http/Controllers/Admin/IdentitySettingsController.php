<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IdentitySettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings.identity', ['company' => auth()->user()->company]);
    }

    public function update(Request $request)
    {
        $company = auth()->user()->company;
        $data = $request->validate([
            'domain' => ['required', 'string', 'max:255', 'regex:/^(?!-)[a-z0-9.-]+(?<!-)$/i'],
            'entra_enabled' => ['nullable', 'boolean'], 'entra_sso_required' => ['nullable', 'boolean'],
            'entra_mfa_required' => ['nullable', 'boolean'],
            'entra_tenant_id' => ['nullable', 'uuid'], 'entra_client_id' => ['nullable', 'uuid'],
            'entra_client_secret' => ['nullable', 'string', 'min:16', 'max:2048'],
            'entra_admin_group_ids' => ['nullable', 'string', 'max:4000'],
            'entra_employee_group_ids' => ['nullable', 'string', 'max:4000'],
        ]);
        foreach (['entra_enabled', 'entra_sso_required', 'entra_mfa_required'] as $field) $data[$field] = $request->boolean($field);
        if ($data['entra_enabled'] && (!$data['entra_tenant_id'] || !$data['entra_client_id'] || (!$request->filled('entra_client_secret') && !$company->entra_client_secret))) {
            return back()->withErrors(['entra_enabled' => 'Tenant ID, client ID en client secret zijn verplicht om Entra te activeren.'])->withInput();
        }
        if (!$request->filled('entra_client_secret')) unset($data['entra_client_secret']);
        foreach (['entra_admin_group_ids', 'entra_employee_group_ids'] as $field) {
            $data[$field] = collect(preg_split('/[\s,;]+/', $data[$field] ?? ''))->filter()
                ->map(fn ($id) => Str::lower(trim($id)))->unique()->values()->all();
        }
        $company->update($data);
        return back()->with('status', 'Identity-instellingen opgeslagen.');
    }

    public function rotateScimToken()
    {
        $company = auth()->user()->company;
        $token = 'tc_scim_'.Str::random(64);
        $company->forceFill(['scim_endpoint_key' => $company->scim_endpoint_key ?: Str::uuid(), 'scim_token_hash' => hash('sha256', $token)])->save();
        return back()->with('status', 'Nieuwe SCIM-token aangemaakt. Kopieer deze nu; hij wordt niet opnieuw getoond.')->with('scim_token', $token);
    }
}
