<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $companyId = auth()->user()->company_id;
            $query = User::where('company_id', $companyId);

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->get('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%");
                });
            }

            // Role filter
            if ($request->filled('role')) {
                $query->where('role', $request->get('role'));
            }

            // Status filter
            if ($request->filled('is_active')) {
                $query->where('is_active', $request->get('is_active') === 'true');
            }

            $users = $query->latest()->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Users retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,employee',
                'is_active' => 'boolean',
                'department' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'employee_id' => 'nullable|string|max:50|unique:users',
            ]);

            \DB::beginTransaction();

            $validated['company_id'] = auth()->user()->company_id;
            $validated['password'] = bcrypt($validated['password']);
            $validated['email_verified_at'] = now();
            $user = User::create($validated);

            \DB::commit();

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User created successfully'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Ensure user belongs to same company
            if ($user->company_id !== auth()->user()->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to user.',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Ensure user belongs to same company
            if ($user->company_id !== auth()->user()->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to user.',
                ], 403);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => 'nullable|string|min:8|confirmed',
                'role' => 'required|in:admin,employee',
                'is_active' => 'boolean',
                'department' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'employee_id' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            ]);

            \DB::beginTransaction();

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'is_active' => $validated['is_active'] ?? true,
                'department' => $validated['department'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'employee_id' => $validated['employee_id'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = bcrypt($validated['password']);
            }

            $user->update($updateData);

            \DB::commit();

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Ensure user belongs to same company
            if ($user->company_id !== auth()->user()->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to user.',
                ], 403);
            }

            // Prevent deleting yourself
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account.'
                ], 422);
            }

            // Prevent deleting the last admin (only count admins from same company)
            if ($user->role === 'admin') {
                $adminCount = User::where('role', 'admin')
                    ->where('company_id', auth()->user()->company_id)
                    ->count();
                if ($adminCount <= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete the last admin user'
                    ], 422);
                }
            }

            \DB::beginTransaction();

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
            
            // 4. Delete notifications (has cascade, but delete explicitly for clarity)
            \App\Models\Notification::where('user_id', $user->id)->delete();
            
            // 5. Delete task assignments (has cascade, but delete explicitly for clarity)
            \App\Models\TaskAssignment::where('user_id', $user->id)->delete();
            
            // 6. Update task lists created_by to null or another admin
            // First, try to find another admin from same company
            $replacementAdmin = \App\Models\User::where('company_id', $user->company_id)
                ->where('role', 'admin')
                ->where('id', '!=', $user->id)
                ->first();
            
            if ($replacementAdmin) {
                \App\Models\TaskList::where('created_by', $user->id)
                    ->update(['created_by' => $replacementAdmin->id]);
            } else {
                // If no other admin, set to null
                \App\Models\TaskList::where('created_by', $user->id)
                    ->update(['created_by' => null]);
            }

            // Now delete the user
            $user->delete();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $companyId = auth()->user()->company_id;
            $stats = [
                'total_users' => User::where('company_id', $companyId)->count(),
                'admin_users' => User::where('company_id', $companyId)->where('role', 'admin')->count(),
                'employee_users' => User::where('company_id', $companyId)->where('role', 'employee')->count(),
                'active_users' => User::where('company_id', $companyId)->where('is_active', true)->count(),
                'inactive_users' => User::where('company_id', $companyId)->where('is_active', false)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'User statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
