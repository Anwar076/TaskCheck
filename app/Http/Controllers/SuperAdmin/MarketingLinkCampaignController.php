<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Marketing\MarketingLinkCampaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MarketingLinkCampaignController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'nullable',
                'string',
                'max:64',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('marketing_link_campaigns', 'code'),
            ],
            'destination_url' => 'nullable|url|max:2048',
        ], [
            'code.regex' => 'Code mag alleen kleine letters, cijfers en streepjes bevatten (bijv. juni-mail-2026).',
            'code.unique' => 'Deze code bestaat al; kies een andere.',
        ]);

        $code = $validated['code'] ?? MarketingLinkCampaign::generateUniqueCode($validated['name']);

        MarketingLinkCampaign::create([
            'code' => $code,
            'name' => $validated['name'],
            'destination_url' => $validated['destination_url']
                ?? config('services.marketing_link.default_destination', 'https://taskcheck.nl'),
            'created_by' => Auth::id(),
            'is_active' => true,
        ]);

        return redirect()
            ->route('super-admin.dashboard', ['tab' => 'communications'])
            ->with('success', 'Tracklink aangemaakt. Gebruik de track-URL in je mail.');
    }

    public function destroy(MarketingLinkCampaign $marketingLink): RedirectResponse
    {
        $marketingLink->update(['is_active' => false]);

        return redirect()
            ->route('super-admin.dashboard', ['tab' => 'communications'])
            ->with('success', 'Tracklink gedeactiveerd.');
    }
}
