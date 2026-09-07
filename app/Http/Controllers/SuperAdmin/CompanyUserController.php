<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CompanyUserController extends Controller
{
    public function showUser(User $user): View
    {
        $user->load(['company', 'location']);

        return view('super-admin.users.show', [
            'user' => $user,
            'company' => $user->company,
            'companyLocations' => $user->company
                ? $user->company->locations()->withoutGlobalScope('company')->orderBy('name')->get()
                : collect(),
        ]);
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is($request->user()), 422, 'Je kunt je eigen account niet verwijderen.');
        $request->validate([
            'confirmation_name' => ['required', 'string', Rule::in([$user->name])],
        ], [
            'confirmation_name.in' => 'Vul de volledige naam exact in om de gebruiker te verwijderen.',
        ]);

        $name = $user->name;
        DB::transaction(function () use ($user) {
            $replacement = User::where('company_id', $user->company_id)
                ->where('id', '!=', $user->id)
                ->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
                ->orderBy('id')->first();
            $lists = DB::table('lists')->where('created_by', $user->id);
            if ($lists->exists() && ! $replacement) {
                throw ValidationException::withMessages([
                    'user' => 'Voeg eerst een andere gebruiker aan dit bedrijf toe om de bestaande takenlijsten over te nemen.',
                ]);
            }
            if ($replacement) {
                $lists->update(['created_by' => $replacement->id]);
            }

            // Match account deletion in the admin: remove assignments and
            // submissions, while retaining lists and other users' reviews.
            DB::table('list_assignments')->where('user_id', $user->id)->delete();
            DB::table('submission_tasks')->where('reviewed_by', $user->id)->update(['reviewed_by' => null]);
            DB::table('submissions')->where('user_id', $user->id)->delete();
            DB::table('sessions')->where('user_id', $user->id)->delete();
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            $user->tokens()->delete();
            $user->delete();
        });

        return redirect()->route('super-admin.dashboard', ['tab' => 'users'])
            ->with('success', "Gebruiker {$name} is verwijderd.");
    }

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

        $redirect = $request->boolean('return_to_user_detail')
            ? redirect()->route('super-admin.users.show', $user)
            : redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'users']);

        return $redirect->with('success', "Gebruiker {$user->name} is bijgewerkt.");
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
