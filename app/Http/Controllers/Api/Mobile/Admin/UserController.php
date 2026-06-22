<?php

namespace App\Http\Controllers\Api\Mobile\Admin;

use App\Http\Controllers\Api\Mobile\MobileController;
use App\Models\Organisation\Location;
use App\Models\Organisation\User;
use App\Services\Mobile\MobileSerializer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends MobileController
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $query = User::query()
            ->with('location')
            ->where('company_id', $companyId)
            ->whereIn('role', ['admin', 'employee']);

        if ($request->filled('search')) {
            $search = '%'.$request->get('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('department', 'like', $search);
            });
        }

        $users = $query->orderBy('name')->get()
            ->map(fn ($u) => MobileSerializer::adminUser($u))
            ->values();

        return $this->success($users);
    }

    public function show(Request $request, int $id)
    {
        $user = $this->findCompanyUser($request, $id);

        return $this->success(MobileSerializer::adminUser($user));
    }

    public function store(Request $request)
    {
        $companyId = $request->user()->company_id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,employee'],
            'department' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
            'location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(fn ($q) => $q->where('company_id', $companyId)),
            ],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            $validated['password'] = Str::password(32);
        }
        $validated['company_id'] = $companyId;
        $validated['is_active'] = $validated['is_active'] ?? true;

        $user = User::create($validated);

        if (empty($request->input('password'))) {
            try {
                $user->sendInvitationNotification($request->user());
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $this->success(MobileSerializer::adminUser($user->load('location')), 'Gebruiker aangemaakt.', 201);
    }

    public function update(Request $request, int $id)
    {
        $user = $this->findCompanyUser($request, $id);
        $companyId = $request->user()->company_id;

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['sometimes', 'in:admin,employee'],
            'department' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
            'location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(fn ($q) => $q->where('company_id', $companyId)),
            ],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return $this->success(MobileSerializer::adminUser($user->fresh('location')), 'Gebruiker bijgewerkt.');
    }

    public function destroy(Request $request, int $id)
    {
        $user = $this->findCompanyUser($request, $id);

        if ($user->id === $request->user()->id) {
            return $this->error('Je kunt je eigen account niet verwijderen.', 422);
        }

        $user->update(['is_active' => false]);

        return $this->success(null, 'Gebruiker gedeactiveerd.');
    }

    protected function findCompanyUser(Request $request, int $id): User
    {
        return User::query()
            ->with('location')
            ->where('company_id', $request->user()->company_id)
            ->whereIn('role', ['admin', 'employee'])
            ->findOrFail($id);
    }
}
