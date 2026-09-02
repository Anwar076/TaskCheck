<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class CompanyUserController extends Controller
{
    public function storeCompanyUser(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12'],
            'role' => ['required', Rule::in(['admin', 'employee'])],
            'phone' => ['nullable', 'string', 'max:100'],
            'location_id' => ['nullable', Rule::exists('locations', 'id')->where(fn ($query) => $query->where('company_id', $company->id))],
        ]);

        User::create([
            'company_id' => $company->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'location_id' => $validated['location_id'] ?? null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'users'])
            ->with('success', "Gebruiker {$validated['name']} is toegevoegd aan {$company->name}.");
    }

    public function updateCompanyUser(Request $request, Company $company, User $user): RedirectResponse
    {
        abort_unless((int) $user->company_id === (int) $company->id, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'employee'])],
            'phone' => ['nullable', 'string', 'max:100'],
            'location_id' => ['nullable', Rule::exists('locations', 'id')->where(fn ($query) => $query->where('company_id', $company->id))],
            'password' => ['nullable', 'string', 'min:12'],
        ]);

        if ($user->is(Auth::user()) && $validated['role'] !== 'admin') {
            return back()->with('error', 'Je kunt je eigen superadminaccount niet naar medewerker wijzigen.');
        }

        $payload = collect($validated)->except('password')->all();
        if (! empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }
        $user->update($payload);

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'users'])
            ->with('success', "Gebruiker {$user->name} is bijgewerkt.");
    }

    public function sendCompanyUserPasswordReset(Company $company, User $user): RedirectResponse
    {
        abort_unless((int) $user->company_id === (int) $company->id, 404);

        $status = Password::sendResetLink(['email' => $user->email]);
        $message = $status === Password::RESET_LINK_SENT
            ? "Wachtwoordlink verstuurd naar {$user->email}."
            : 'De wachtwoordlink kon niet worden verstuurd. Controleer de mailinstellingen.';

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'users'])
            ->with($status === Password::RESET_LINK_SENT ? 'success' : 'error', $message);
    }

    public function toggleCompanyUser(Company $company, User $user): RedirectResponse
    {
        abort_unless((int) $user->company_id === (int) $company->id, 404);
        abort_if($user->is(Auth::user()), 422, 'Je kunt je eigen account hier niet blokkeren.');

        $user->update(['is_active' => ! $user->is_active]);

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'users'])
            ->with('success', "Account van {$user->name} is ".($user->is_active ? 'geactiveerd.' : 'geblokkeerd.'));
    }
}
