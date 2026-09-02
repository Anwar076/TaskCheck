<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
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
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (! $user->company_id) {
            if ($this->isAllowedWhileLocked($request)) {
                return $next($request);
            }

            return $this->deny($request, 'Kies een abonnement om verder te gaan.', 'subscription.choose-plan');
        }

        $company = $user->company;

        if (! $company) {
            return $this->deny($request, 'Je bedrijfsaccount is niet gevonden. Neem contact op met support.', 'subscription.choose-plan');
        }

        if ($this->isAllowedWhileLocked($request)) {
            if (! $company->canAccess()) {
                View::share('subscriptionLocked', true);
                View::share('subscriptionLockMessage', $company->accessLockMessage() ?? 'Je hebt geen actief abonnement.');
            }

            return $next($request);
        }

        if (! $company->canAccess()) {
            $redirectRoute = $user->isAdmin()
                ? 'admin.settings.edit'
                : 'employee.settings.edit';

            return $this->deny(
                $request,
                $company->accessLockMessage() ?? 'Je hebt geen actief abonnement.',
                $redirectRoute
            );
        }

        if ($company->isOnTrial() && $company->trialDaysRemaining() <= 3) {
            $message = $company->isManagedAccount()
                ? "Je proefperiode eindigt over {$company->trialDaysRemaining()} dag(en). Het abonnement {$company->getPlanDisplayName()} is al toegewezen; op {$company->billing_start_date?->format('d-m-Y')} ontvang je de betaallink."
                : "Je proefperiode eindigt over {$company->trialDaysRemaining()} dag(en). Kies een abonnement om door te gaan.";
            $request->session()->flash('trial_warning', [
                'message' => $message,
                'days_remaining' => $company->trialDaysRemaining(),
                'choose_plan' => ! $company->isManagedAccount(),
            ]);
        }

        return $next($request);
    }

    private function isAllowedWhileLocked(Request $request): bool
    {
        if ($request->routeIs([
            'subscription.*',
            'admin.settings.*',
            'employee.settings.*',
            'profile.*',
            'logout',
            'refresh-csrf',
            'push.*',
            'admin.notifications.realtime-feed',
            'admin.notifications.mark-read',
            'admin.notifications.mark-all-read',
            'employee.notifications.realtime-feed',
            'employee.notifications.mark-read',
            'employee.notifications.mark-all-read',
        ])) {
            return true;
        }

        return $request->is([
            'api/user',
            'api/mobile/me',
            'api/mobile/logout',
            'api/mobile/admin/settings',
        ]);
    }

    private function deny(Request $request, string $message, string $redirectRoute): Response
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $message,
                'code' => 'subscription_locked',
            ], 403);
        }

        return redirect()->route($redirectRoute)->with('error', $message);
    }
}
