<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OnboardingController extends Controller
{
    public function start(): RedirectResponse
    {
        $company = $this->company();

        if ($company->onboarding_step === Company::ONBOARDING_STEP_WELCOME) {
            $company->advanceOnboardingTo(Company::ONBOARDING_STEP_ORGANIZATION);
        }

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Vul je organisatiegegevens in en sla op om verder te gaan.');
    }

    public function continueUsers(): RedirectResponse
    {
        $company = $this->company();
        $employeeCount = User::where('company_id', $company->id)
            ->where('role', 'employee')
            ->where('is_active', true)
            ->count();

        if ($employeeCount < 1) {
            throw ValidationException::withMessages([
                'users' => 'Voeg minimaal één medewerker toe voordat je verdergaat.',
            ]);
        }

        $company->advanceOnboardingTo(Company::ONBOARDING_STEP_LIST_CHOICE);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Top! Kies nu hoe je je eerste takenlijst wilt maken.');
    }

    public function chooseList(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'choice' => ['required', Rule::in(['template', 'custom'])],
        ]);

        $company = $this->company();
        $company->update([
            'onboarding_step' => Company::ONBOARDING_STEP_LIST_CREATE,
            'onboarding_list_mode' => $validated['choice'],
        ]);

        if ($validated['choice'] === 'template') {
            return redirect()
                ->route('admin.templates.index')
                ->with('success', 'Kies een template en maak je eerste takenlijst.');
        }

        return redirect()
            ->route('admin.lists.create')
            ->with('success', 'Maak je eerste takenlijst. Daarna wijs je een medewerker toe.');
    }

    private function company(): Company
    {
        $company = auth()->user()?->company;
        abort_if(!$company, 403, 'Geen organisatie gekoppeld.');

        return $company;
    }
}
