<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;
        
        if ($request->wantsJson()) {
            $users = User::where('company_id', $companyId)
                ->whereIn('role', ['employee', 'admin'])
                ->where('is_active', true)
                ->select('id', 'name', 'department', 'email')
                ->orderBy('name')
                ->get();
            
            return response()->json(['users' => $users]);
        }
        
        return view('admin.users.index-api');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companyId = auth()->user()->company_id;
        $departments = collect(auth()->user()->company?->departments ?? [])
            ->filter(fn ($item) => is_string($item) && trim($item) !== '')
            ->values()
            ->all();

        return view('admin.users.create', [
            'roleLimits' => $this->getRoleLimitsAndUsage(auth()->user()->company),
            'locations' => Location::where('company_id', $companyId)->where('is_active', true)->orderBy('name')->get(),
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $companyDepartments = collect(auth()->user()->company?->departments ?? [])
            ->filter(fn ($item) => is_string($item) && trim($item) !== '')
            ->values()
            ->all();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,employee',
            'department' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(fn ($query) => $query->where('company_id', auth()->user()->company_id)),
            ],
        ]);

        if (!empty($validated['department']) && !empty($companyDepartments) && !in_array($validated['department'], $companyDepartments, true)) {
            throw ValidationException::withMessages([
                'department' => 'Selecteer een geldige afdeling uit de instellingen.',
            ]);
        }

        $this->ensureRoleLimitNotExceeded(
            auth()->user()->company,
            $validated['role']
        );

        $validated['password'] = bcrypt($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        $validated['company_id'] = auth()->user()->company_id;

        $user = User::create($validated);

        return redirect()->route('admin.users.index', ['updated' => time()])
            ->with('success', 'Gebruiker succesvol aangemaakt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Ensure user belongs to same company
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to user.');
        }
        
        $user->load(['submissions.taskList', 'assignments.taskList']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Ensure user belongs to same company
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to user.');
        }
        
        $companyId = auth()->user()->company_id;
        $departments = collect(auth()->user()->company?->departments ?? [])
            ->filter(fn ($item) => is_string($item) && trim($item) !== '')
            ->values()
            ->all();

        return view('admin.users.edit', [
            'user' => $user,
            'roleLimits' => $this->getRoleLimitsAndUsage(auth()->user()->company),
            'locations' => Location::where('company_id', $companyId)->orderByDesc('is_active')->orderBy('name')->get(),
            'departments' => $departments,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Ensure user belongs to same company
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to user.');
        }
        
        $companyDepartments = collect(auth()->user()->company?->departments ?? [])
            ->filter(fn ($item) => is_string($item) && trim($item) !== '')
            ->values()
            ->all();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,employee',
            'department' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(fn ($query) => $query->where('company_id', auth()->user()->company_id)),
            ],
        ]);

        if (!empty($validated['department']) && !empty($companyDepartments) && !in_array($validated['department'], $companyDepartments, true)) {
            throw ValidationException::withMessages([
                'department' => 'Selecteer een geldige afdeling uit de instellingen.',
            ]);
        }

        if ($validated['role'] !== $user->role) {
            $this->ensureRoleLimitNotExceeded(
                auth()->user()->company,
                $validated['role']
            );
        }

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()->route('admin.users.show', ['user' => $user->id, 'updated' => time()])
            ->with('success', 'Gebruiker succesvol bijgewerkt.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Ensure user belongs to same company
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to user.');
        }
        
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index', ['updated' => time()])
                ->with('error', 'Je kunt je eigen account niet verwijderen.');
        }

        \DB::beginTransaction();
        
        try {
            // Delete all related records before deleting the user
            
            // 1. Delete list assignments
            \App\Models\ListAssignment::where('user_id', $user->id)->delete();
            
            // 2. Delete submissions and their related submission tasks
            $submissions = \App\Models\Submission::where('user_id', $user->id)->get();
            foreach ($submissions as $submission) {
                \App\Models\SubmissionTask::where('submission_id', $submission->id)->delete();
            }
            \App\Models\Submission::where('user_id', $user->id)->delete();
            
            // 3. Delete submission tasks where user reviewed (reviewed_by)
            \App\Models\SubmissionTask::where('reviewed_by', $user->id)->update(['reviewed_by' => null]);
            
            // 4. Delete notifications
            \App\Models\Notification::where('user_id', $user->id)->delete();
            
            // 5. Delete task assignments
            \App\Models\TaskAssignment::where('user_id', $user->id)->delete();
            
            // 6. Update task lists created_by to null or another admin
            $replacementAdmin = \App\Models\User::where('company_id', $user->company_id)
                ->where('role', 'admin')
                ->where('id', '!=', $user->id)
                ->first();
            
            if ($replacementAdmin) {
                \App\Models\TaskList::where('created_by', $user->id)
                    ->update(['created_by' => $replacementAdmin->id]);
            } else {
                \App\Models\TaskList::where('created_by', $user->id)
                    ->update(['created_by' => null]);
            }

            // Now delete the user
            $user->delete();
            
            \DB::commit();
            
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('admin.users.index', ['updated' => time()])
                ->with('error', 'Fout bij verwijderen gebruiker: ' . $e->getMessage());
        }

        return redirect()->route('admin.users.index', ['updated' => time()])
            ->with('success', 'Gebruiker succesvol verwijderd.');
    }

    private function ensureRoleLimitNotExceeded(?Company $company, string $targetRole): void
    {
        if (!$company || !in_array($targetRole, ['admin', 'employee'], true)) {
            return;
        }

        $planLimits = [
            'starter' => ['admin' => 1, 'employee' => 5],
            'professional' => ['admin' => 2, 'employee' => 10],
            'business' => ['admin' => 5, 'employee' => 20],
            'enterprise' => ['admin' => 5, 'employee' => 20],
            'custom' => ['admin' => null, 'employee' => null],
        ];

        $planKey = $company->subscription_plan ?: 'starter';
        $limits = $planLimits[$planKey] ?? $planLimits['starter'];
        $roleLimit = $limits[$targetRole] ?? null;

        if ($roleLimit === null) {
            return;
        }

        $currentCount = User::where('company_id', $company->id)
            ->where('role', $targetRole)
            ->count();

        if ($currentCount >= $roleLimit) {
            $roleLabel = $targetRole === 'admin' ? 'admin' : 'medewerker';
            throw ValidationException::withMessages([
                'role' => "Limiet bereikt: maximaal {$roleLimit} {$roleLabel} account(s) voor het {$planKey} plan.",
            ]);
        }
    }

    private function getRoleLimitsAndUsage(?Company $company): array
    {
        $planLimits = [
            'starter' => ['admin' => 1, 'employee' => 5],
            'professional' => ['admin' => 2, 'employee' => 10],
            'business' => ['admin' => 5, 'employee' => 20],
            'enterprise' => ['admin' => 5, 'employee' => 20],
            'custom' => ['admin' => null, 'employee' => null],
        ];

        $planKey = $company?->subscription_plan ?: 'starter';
        $limits = $planLimits[$planKey] ?? $planLimits['starter'];

        if (!$company) {
            return [
                'plan' => $planKey,
                'admin' => ['current' => 0, 'max' => $limits['admin']],
                'employee' => ['current' => 0, 'max' => $limits['employee']],
            ];
        }

        return [
            'plan' => $planKey,
            'admin' => [
                'current' => User::where('company_id', $company->id)->where('role', 'admin')->count(),
                'max' => $limits['admin'],
            ],
            'employee' => [
                'current' => User::where('company_id', $company->id)->where('role', 'employee')->count(),
                'max' => $limits['employee'],
            ],
        ];
    }
}
