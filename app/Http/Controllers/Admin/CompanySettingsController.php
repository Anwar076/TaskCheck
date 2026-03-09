<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
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
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
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

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }

            $path = $request->file('logo')->store('company-logos', 'public');
            $validated['logo_path'] = $path;
        }

        unset($validated['logo'], $validated['remove_logo']);
        $company->update($validated);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Organisatie-instellingen succesvol bijgewerkt.');
    }
}
