<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImpersonationController extends Controller
{
    public function start(Request $request, Company $company, User $user): RedirectResponse
    {
        abort_unless((int) $user->company_id === (int) $company->id, 404);
        abort_if($request->session()->has('impersonator_id'), 422, 'Er is al een meekijksessie actief.');
        abort_if($user->is($request->user()), 422, 'Je bent al ingelogd als deze gebruiker.');

        $impersonator = $request->user();
        $request->session()->put([
            'impersonator_id' => $impersonator->id,
            'impersonated_user_id' => $user->id,
        ]);

        Log::notice('Superadmin started user impersonation', [
            'impersonator_id' => $impersonator->id,
            'impersonated_user_id' => $user->id,
            'company_id' => $company->id,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->to($user->role === 'admin' ? route('admin.dashboard') : route('employee.dashboard'))
            ->with('success', "Je kijkt nu mee als {$user->name}.");
    }

    public function stop(Request $request): RedirectResponse
    {
        $impersonatorId = $request->session()->pull('impersonator_id');
        $impersonatedUserId = $request->session()->pull('impersonated_user_id');
        abort_unless($impersonatorId, 403, 'Er is geen meekijksessie actief.');

        $impersonator = User::query()->findOrFail($impersonatorId);
        abort_unless($impersonator->isSuperAdmin(), 403);

        Auth::login($impersonator);
        $request->session()->regenerate();

        Log::notice('Superadmin stopped user impersonation', [
            'impersonator_id' => $impersonator->id,
            'impersonated_user_id' => $impersonatedUserId,
        ]);

        return redirect()->route('super-admin.dashboard', ['tab' => 'users'])
            ->with('success', 'Meekijksessie beëindigd. Je bent weer ingelogd als superadmin.');
    }
}
