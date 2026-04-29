<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;
        $locations = Location::orderByDesc('is_active')->orderBy('name')->get();

        return view('admin.locations.index', compact('company', 'locations'));
    }

    public function create()
    {
        $company = auth()->user()->company;

        if ($company && $company->hasReachedLocationLimit()) {
            return redirect()
                ->route('admin.locations.index')
                ->with('error', 'Je hebt het maximum aantal locaties voor je abonnement bereikt.');
        }

        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $company = auth()->user()->company;

        if ($company && $company->hasReachedLocationLimit()) {
            return redirect()
                ->route('admin.locations.index')
                ->with('error', 'Je hebt het maximum aantal locaties voor je abonnement bereikt.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['company_id'] = auth()->user()->company_id;

        Location::create($validated);

        return redirect()
            ->route('admin.locations.index')
            ->with('success', 'Locatie succesvol toegevoegd.');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $location->update($validated);

        return redirect()
            ->route('admin.locations.index')
            ->with('success', 'Locatie succesvol bijgewerkt.');
    }

    public function destroy(Location $location)
    {
        $location->update(['is_active' => false]);

        return redirect()
            ->route('admin.locations.index')
            ->with('success', 'Locatie is gearchiveerd.');
    }
}
