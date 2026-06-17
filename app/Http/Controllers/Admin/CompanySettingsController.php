<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\Platform\AdminOnboardingService;
use Illuminate\Support\Facades\Storage;

class CompanySettingsController extends Controller
{
    /**
     * Show the company settings form.
     */
    public function edit()
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Geen organisatie gekoppeld.');
        }

        return view('admin.settings.edit', compact('company'));
    }

    /**
     * Update the company settings.
     */
    public function update(Request $request)
    {
        $company = auth()->user()->company;

        if (!$company) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Geen organisatie gekoppeld.');
        }

        // Ensure user is admin of this company
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Geen toegang tot organisatie-instellingen.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:7', 'max:15'],
            'email' => ['required', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'departments_text' => ['nullable', 'string', 'max:4000'],
            'departments' => ['nullable', 'array'],
            'departments.*' => ['nullable', 'string', 'max:100'],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // 2MB
            ],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        // Handle logo removal
        if ($request->boolean('remove_logo') && $company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
            $validated['logo_path'] = null;
        }

        // Enforce normalized phone storage (digits only).
        $validated['phone'] = preg_replace('/\D+/', '', (string) ($validated['phone'] ?? ''));

        $departmentInput = [];
        if (!empty($validated['departments']) && is_array($validated['departments'])) {
            $departmentInput = $validated['departments'];
        } else {
            $departmentInput = preg_split('/\r\n|\r|\n/', (string) ($validated['departments_text'] ?? ''));
        }

        $departments = collect($departmentInput)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->map(fn ($item) => mb_substr($item, 0, 100))
            ->unique()
            ->values()
            ->all();

        $validated['departments'] = !empty($departments) ? $departments : null;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }

            $path = $request->file('logo')->store('company-logos', 'public');
            $validated['logo_path'] = $path;
        }

        unset($validated['logo'], $validated['remove_logo'], $validated['departments_text'], $validated['departments']);
        $company->update($validated);
        $company->refresh();

        app(AdminOnboardingService::class)->handleOrganizationSaved($company);

        if ($company->needsOnboarding() && $company->onboarding_step === \App\Models\Company::ONBOARDING_STEP_USERS) {
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Organisatiegegevens opgeslagen. Voeg nu je team toe.');
        }

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Organisatie-instellingen succesvol bijgewerkt.');
    }
}
