<?php

namespace App\Http\Controllers;

use App\Models\Marketing\MarketingLinkCampaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MarketingLinkRedirectController extends Controller
{
    public function __invoke(Request $request, string $code): RedirectResponse
    {
        $campaign = MarketingLinkCampaign::query()
            ->where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$campaign) {
            abort(404);
        }

        $campaign->recordClick($request);

        return redirect()->away($campaign->destination_url);
    }
}
