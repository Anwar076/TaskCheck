<?php

namespace App\Http\Middleware;

use App\Services\Platform\AdminOnboardingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    public function __construct(private AdminOnboardingService $onboarding)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isAdmin() || $user->isSuperAdmin()) {
            return $next($request);
        }

        $company = $user->company;
        if (!$company || !$company->needsOnboarding()) {
            return $next($request);
        }

        $routeName = (string) $request->route()?->getName();
        if ($this->onboarding->routeAllowedDuringOnboarding($routeName, $company)) {
            return $next($request);
        }

        $targetRoute = $company->onboardingRouteName();
        $params = $company->onboardingRouteParameters();

        return redirect()->route($targetRoute, $params);
    }
}
