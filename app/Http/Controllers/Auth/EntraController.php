<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use App\Services\Identity\EntraOidcService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class EntraController extends Controller
{
    public function redirect(Request $request, EntraOidcService $oidc): RedirectResponse
    {
        $validated = $request->validate(['email' => ['required', 'email:rfc', 'max:255']]);
        $domain = Str::lower(Str::after($validated['email'], '@'));
        $company = Company::query()->where('entra_enabled', true)
            ->whereRaw('LOWER(domain) = ?', [$domain])->first();
        if (!$company || !$company->entra_tenant_id || !$company->entra_client_id || !$company->entra_client_secret) {
            throw ValidationException::withMessages(['email' => 'Voor dit e-maildomein is Microsoft SSO niet geconfigureerd.']);
        }

        $state = Str::random(64); $nonce = Str::random(64); $verifier = Str::random(96);
        $request->session()->put('entra_oidc', [
            'state' => $state, 'nonce' => $nonce, 'verifier' => $verifier,
            'company_id' => $company->id, 'started_at' => now()->timestamp,
        ]);
        $challenge = rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');
        return redirect()->away($oidc->authorizationUrl($company, $state, $nonce, $challenge, $validated['email']));
    }

    public function callback(Request $request, EntraOidcService $oidc): RedirectResponse
    {
        $flow = $request->session()->pull('entra_oidc');
        if (!$flow || now()->timestamp - ($flow['started_at'] ?? 0) > 600
            || !hash_equals((string) ($flow['state'] ?? ''), (string) $request->query('state'))) {
            return redirect()->route('login')->withErrors(['email' => 'De SSO-aanvraag is verlopen of ongeldig.']);
        }
        if ($request->filled('error')) {
            return redirect()->route('login')->withErrors(['email' => 'Microsoft-aanmelding is geannuleerd of geweigerd.']);
        }

        try {
            $company = Company::findOrFail($flow['company_id']);
            $tokens = $oidc->exchange($company, (string) $request->query('code'), $flow['verifier']);
            $claims = $oidc->verifyIdToken($company, $tokens['id_token'], $flow['nonce']);
            $email = Str::lower((string) ($claims['preferred_username'] ?? $claims['email'] ?? ''));
            $objectId = (string) ($claims['oid'] ?? $claims['sub'] ?? '');
            $user = User::withoutGlobalScopes()->where('company_id', $company->id)
                ->where(fn ($query) => $query->where('entra_object_id', $objectId)->orWhereRaw('LOWER(email) = ?', [$email]))
                ->first();
            if (!$user || !$user->is_active) {
                throw new \RuntimeException('Account is niet actief of nog niet via SCIM/aangemaakt in TaskCheck.');
            }
            if ($user->entra_tenant_id && !hash_equals($user->entra_tenant_id, (string) $claims['tid'])) {
                throw new \RuntimeException('Account hoort bij een andere Entra tenant.');
            }
            $methods = array_map('strtolower', (array) ($claims['amr'] ?? []));
            if ($company->entra_mfa_required && !in_array('mfa', $methods, true)) {
                throw new \RuntimeException('MFA is voor deze organisatie verplicht, maar is niet door Entra bevestigd.');
            }

            $groups = (array) ($claims['groups'] ?? []);
            $admin = array_intersect($groups, $company->entra_admin_group_ids ?? []) !== [];
            $employee = array_intersect($groups, $company->entra_employee_group_ids ?? []) !== [];
            if (($company->entra_admin_group_ids || $company->entra_employee_group_ids) && !$admin && !$employee) {
                throw new \RuntimeException('Uw Entra-groepen geven geen toegang tot TaskCheck.');
            }

            $user->forceFill([
                'email' => $email, 'entra_object_id' => $objectId, 'entra_tenant_id' => $claims['tid'],
                'role' => $admin ? 'admin' : 'employee', 'email_verified_at' => $user->email_verified_at ?: now(),
                'last_sso_at' => now(),
            ])->save();
            Auth::login($user, false);
            $request->session()->regenerate();
            $request->session()->put('entra_authenticated', true);
            return redirect()->intended(route('dashboard'));
        } catch (Throwable $exception) {
            report($exception);
            return redirect()->route('login')->withErrors(['email' => $exception->getMessage()]);
        }
    }
}
