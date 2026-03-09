<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            // Create company with trial period
            $company = Company::create([
                'name' => $request->company_name,
                'subscription_status' => 'trial',
                'trial_ends_at' => now()->addDays(30),
            ]);

            // Create user as admin for the company
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
                'role' => 'admin',
                'company_id' => $company->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Welcome! You have started your 30-day free trial.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
