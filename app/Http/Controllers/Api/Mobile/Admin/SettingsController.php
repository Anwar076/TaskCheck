<?php

namespace App\Http\Controllers\Api\Mobile\Admin;

use App\Http\Controllers\Api\Mobile\MobileController;
use Illuminate\Http\Request;

class SettingsController extends MobileController
{
    public function show(Request $request)
    {
        $company = $request->user()->company;

        if (!$company) {
            return $this->error('Geen organisatie gekoppeld.', 404);
        }

        return $this->success($this->formatSettings($company));
    }

    public function update(Request $request)
    {
        $company = $request->user()->company;

        if (!$company) {
            return $this->error('Geen organisatie gekoppeld.', 404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'departments' => ['nullable', 'array'],
            'departments.*' => ['nullable', 'string', 'max:100'],
        ]);

        if (isset($validated['phone'])) {
            $validated['phone'] = preg_replace('/\D+/', '', (string) $validated['phone']);
        }

        if (isset($validated['departments'])) {
            $validated['departments'] = collect($validated['departments'])
                ->filter(fn ($item) => is_string($item) && trim($item) !== '')
                ->values()
                ->all();
        }

        $company->update($validated);

        return $this->success($this->formatSettings($company->fresh()), 'Instellingen bijgewerkt.');
    }

    protected function formatSettings($company): array
    {
        return [
            'id' => $company->id,
            'name' => $company->name,
            'address' => $company->address,
            'phone' => $company->phone,
            'email' => $company->email,
            'website' => $company->website,
            'description' => $company->description,
            'departments' => is_array($company->departments) ? $company->departments : [],
            'subscription_plan' => $company->subscription_plan,
        ];
    }
}
