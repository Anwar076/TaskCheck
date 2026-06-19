<?php

namespace App\Http\Controllers\Api\Mobile\Admin;

use App\Http\Controllers\Api\Mobile\MobileController;
use App\Models\Organisation\Location;
use App\Services\Mobile\MobileSerializer;
use Illuminate\Http\Request;

class LocationController extends MobileController
{
    public function index(Request $request)
    {
        $locations = Location::query()
            ->where('company_id', $request->user()->company_id)
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get()
            ->map(fn ($l) => MobileSerializer::adminLocation($l))
            ->values();

        return $this->success($locations);
    }

    public function show(Request $request, int $id)
    {
        $location = $this->findCompanyLocation($request, $id);

        return $this->success(MobileSerializer::adminLocation($location));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['company_id'] = $request->user()->company_id;
        $validated['is_active'] = $validated['is_active'] ?? true;

        $location = Location::create($validated);

        return $this->success(MobileSerializer::adminLocation($location), 'Locatie aangemaakt.', 201);
    }

    public function update(Request $request, int $id)
    {
        $location = $this->findCompanyLocation($request, $id);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $location->update($validated);

        return $this->success(MobileSerializer::adminLocation($location->fresh()), 'Locatie bijgewerkt.');
    }

    public function destroy(Request $request, int $id)
    {
        $location = $this->findCompanyLocation($request, $id);
        $location->update(['is_active' => false]);

        return $this->success(null, 'Locatie gearchiveerd.');
    }

    protected function findCompanyLocation(Request $request, int $id): Location
    {
        return Location::query()
            ->where('company_id', $request->user()->company_id)
            ->findOrFail($id);
    }
}
