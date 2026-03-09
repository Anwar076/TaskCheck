<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SubscriptionController extends Controller
{
    /**
     * Show subscription plans
     */
    public function choosePlan(): View
    {
        $company = Auth::user()->company;
        $trialDaysRemaining = $company ? $company->trialDaysRemaining() : 0;
        $currentPlan = $company ? $company->subscription_plan : null;
        $trialExpired = $company ? $company->trialExpired() : false;

        return view('subscription.choose-plan', [
            'plans' => Company::PLANS,
            'trialDaysRemaining' => $trialDaysRemaining,
            'currentPlan' => $currentPlan,
            'trialExpired' => $trialExpired,
            'company' => $company,
        ]);
    }

    /**
     * Show subscription details
     */
    public function show(): View
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('subscription.choose-plan');
        }

        return view('subscription.show', [
            'company' => $company,
            'planDetails' => $company->getPlanDetails(),
        ]);
    }

    /**
     * Activate a subscription plan (simplified - no actual payment)
     */
    public function activate(Request $request): RedirectResponse
    {
        $request->validate([
            'plan' => ['required', 'in:starter,professional,enterprise,custom'],
        ]);

        $company = Auth::user()->company;

        if (!$company) {
            return redirect()->route('subscription.choose-plan')
                ->with('error', 'Organisatie niet gevonden.');
        }

        // For demo purposes, we'll just activate the subscription
        // In production, you'd integrate with Stripe, PayPal, etc.
        $company->activateSubscription($request->plan, 12); // 12 months

        return redirect()->route('subscription.show')
            ->with('success', "Je bent succesvol geabonneerd op het {$request->plan} plan!");
    }

    /**
     * Cancel subscription
     */
    public function cancel(): RedirectResponse
    {
        $company = Auth::user()->company;

        if (!$company) {
            return redirect()->route('subscription.choose-plan');
        }

        $company->update([
            'subscription_status' => 'cancelled',
        ]);

        return redirect()->route('subscription.show')
            ->with('success', 'Je abonnement is opgezegd. Het blijft actief tot het einde van de factureringsperiode.');
    }
}

