<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Keep users logged in until they explicitly log out.
        $request->authenticate(true);

        $request->session()->regenerate();

        // Redirect based on user role
        $user = Auth::user();
        if ($user->role === 'admin') {
            if ($user->isSuperAdmin()) {
                return redirect()->route('super-admin.dashboard');
            }

            $preferredDashboard = (string) $request->session()->get('dashboard_mode', 'admin');

            return redirect()->route($preferredDashboard === 'employee' ? 'employee.dashboard' : 'admin.dashboard');
        } elseif ($user->role === 'employee') {
            return redirect()->route('employee.dashboard');
        }

        if ($this->isAppShellRequest($request)) {
            return redirect()->route('dashboard', ['source' => 'pwa']);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $params = ['logout' => time()];

        if ($this->isAppShellRequest($request)) {
            $params['source'] = 'pwa';
        }

        return redirect()->route('login', $params);
    }

    private function isAppShellRequest(Request $request): bool
    {
        if ($request->query('source') === 'pwa' || $request->input('source') === 'pwa') {
            return true;
        }

        if ($request->header('X-PWABuilder-Rewrite')) {
            return true;
        }

        $userAgent = (string) $request->userAgent();

        if ($userAgent !== '' && (
            str_contains($userAgent, 'Capacitor')
            || str_contains($userAgent, 'TaskCheck')
            || str_contains($userAgent, 'wv')
        )) {
            return true;
        }

        return $request->headers->get('sec-fetch-dest') === 'empty';
    }
}
