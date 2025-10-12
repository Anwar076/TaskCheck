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
        $request->authenticate();

        $request->session()->regenerate();

        // Check if request is from PWA
        $isPwa = $request->query('source') === 'pwa' || 
                 $request->header('User-Agent') && str_contains($request->header('User-Agent'), 'wv') ||
                 $request->header('X-PWABuilder-Rewrite') ||
                 request()->headers->get('sec-fetch-dest') === 'empty';

        // Redirect based on user role
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'employee') {
            return redirect()->route('employee.dashboard');
        }

        // If from PWA, redirect to appropriate dashboard, otherwise to generic dashboard
        if ($isPwa) {
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

        // Check if request is from PWA and redirect to login with PWA source
        $isPwa = $request->query('source') === 'pwa' || 
                 $request->header('User-Agent') && str_contains($request->header('User-Agent'), 'wv') ||
                 $request->header('X-PWABuilder-Rewrite') ||
                 request()->headers->get('sec-fetch-dest') === 'empty';

        if ($isPwa) {
            return redirect()->route('login', ['source' => 'pwa', 'logout' => time()]);
        }

        return redirect('/')->with('logout', time());
    }
}
