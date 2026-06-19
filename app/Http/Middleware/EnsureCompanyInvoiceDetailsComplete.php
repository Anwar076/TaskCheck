<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyInvoiceDetailsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isAdmin() || $user->isSuperAdmin()) {
            return $next($request);
        }

        if ($request->routeIs('admin.settings.*') || $request->routeIs('admin.onboarding.*')) {
            return $next($request);
        }

        $company = $user->company;
        if (!$company) {
            return $next($request);
        }

        if ($company->needsOnboarding() && in_array($company->onboarding_step, [
            \App\Models\Organisation\Company::ONBOARDING_STEP_WELCOME,
            \App\Models\Organisation\Company::ONBOARDING_STEP_ORGANIZATION,
        ], true)) {
            return $next($request);
        }

        $requiredFields = [
            'name' => $company->name,
            'address' => $company->address,
            'phone' => $company->phone,
            'email' => $company->email,
        ];

        foreach ($requiredFields as $value) {
            if (trim((string) $value) === '') {
                return redirect()
                    ->route('admin.settings.edit')
                    ->with('error', 'Vul eerst alle verplichte organisatiegegevens in (naam, adres, telefoon en e-mail) zodat facturen compleet zijn.');
            }
        }

        return $next($request);
    }
}

