<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnforceIdentityPolicy
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($request->routeIs('logout')) {
            return $next($request);
        }
        if ($user && $user->is_active === false) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['email' => 'Dit account is gedeactiveerd.']);
        }
        if ($user?->company?->entra_enabled && $user->company->entra_sso_required
            && !$request->session()->boolean('entra_authenticated') && !$request->routeIs('logout')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['email' => 'Meld aan met Microsoft om verder te gaan.']);
        }
        return $next($request);
    }
}
