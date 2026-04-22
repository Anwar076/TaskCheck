<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // If user has no company, they should register one
        if (!$user->company_id) {
            // Allow access to subscription pages and registration
            if ($request->routeIs('subscription.*') || $request->routeIs('register')) {
                return $next($request);
            }
            
            return redirect()->route('subscription.choose-plan')
                ->with('warning', 'Please choose a subscription plan to continue.');
        }

        $company = $user->company;

        if (!$company) {
            return redirect()->route('subscription.choose-plan')
                ->with('error', 'Your company account is not found. Please contact support.');
        }

        // Check if company can access
        if (!$company->canAccess()) {
            // If trial expired and no subscription, redirect to choose plan
            if ($company->trialExpired() && !$company->hasActiveSubscription()) {
                return redirect()->route('subscription.choose-plan')
                    ->with('error', 'Your 14-day free trial has expired. Please choose a subscription plan to continue.');
            }

            // Otherwise, subscription might be cancelled or expired
            return redirect()->route('subscription.choose-plan')
                ->with('error', 'Your subscription is not active. Please choose a plan to continue.');
        }

        // Allow access to subscription pages
        if ($request->routeIs('subscription.*')) {
            return $next($request);
        }

        // Show warning if trial is ending soon (less than 3 days)
        if ($company->isOnTrial() && $company->trialDaysRemaining() <= 3) {
            $request->session()->flash('trial_warning', [
                'message' => "Your free trial ends in {$company->trialDaysRemaining()} day(s). Please choose a subscription plan.",
                'days_remaining' => $company->trialDaysRemaining(),
            ]);
        }

        return $next($request);
    }
}

