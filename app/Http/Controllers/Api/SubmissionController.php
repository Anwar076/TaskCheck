<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubmissionController extends Controller
{
    /**
     * Display a listing of submissions
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Submission::with(['user', 'taskList', 'submissionTasks.task'])
                ->orderBy('created_at', 'desc');

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->get('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereHas('user', function ($userQ) use ($searchTerm) {
                        $userQ->where('name', 'like', "%{$searchTerm}%")
                              ->orWhere('email', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('taskList', function ($listQ) use ($searchTerm) {
                        $listQ->where('title', 'like', "%{$searchTerm}%");
                    });
                });
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }

            // Date range filter
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->get('date_from'));
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->get('date_to'));
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $submissions = $query->paginate($perPage);

            // Add calculated fields
            $submissions->getCollection()->transform(function ($submission) {
                $totalTasks = $submission->submissionTasks->count();
                $completedTasks = $submission->submissionTasks->where('status', 'completed')->count();
                
                $submission->progress_percentage = $totalTasks > 0 
                    ? round(($completedTasks / $totalTasks) * 100) 
                    : 0;
                    
                $submission->total_tasks = $totalTasks;
                $submission->completed_tasks = $completedTasks;
                
                return $submission;
            });

            return response()->json([
                'success' => true,
                'data' => $submissions->items(),
                'total' => $submissions->total(),
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'per_page' => $submissions->perPage(),
                'from' => $submissions->firstItem(),
                'to' => $submissions->lastItem(),
                'prev_page_url' => $submissions->previousPageUrl(),
                'next_page_url' => $submissions->nextPageUrl(),
                'message' => 'Submissions retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve submissions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve submissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created submission
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'list_id' => 'required|exists:lists,id',
                'status' => 'sometimes|in:in_progress,completed,reviewed,rejected',
                'notes' => 'nullable|string'
            ]);

            $validated['status'] = $validated['status'] ?? 'in_progress';
            $validated['started_at'] = now();

            $submission = Submission::create($validated);
            $submission->load(['user', 'taskList']);

            return response()->json([
                'success' => true,
                'data' => $submission,
                'message' => 'Submission created successfully'
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified submission
     */
    public function show(string $id): JsonResponse
    {
        try {
            $submission = Submission::with(['user', 'taskList', 'submissionTasks.task'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $submission,
                'message' => 'Submission retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Submission not found'
            ], 404);
        }
    }

    /**
     * Update the specified submission
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $submission = Submission::findOrFail($id);
            
            $validated = $request->validate([
                'status' => 'sometimes|in:in_progress,completed,reviewed,rejected',
                'notes' => 'nullable|string',
                'admin_notes' => 'nullable|string',
                'reviewed_by' => 'nullable|exists:users,id'
            ]);

            if (isset($validated['status']) && $validated['status'] === 'reviewed') {
                $validated['reviewed_at'] = now();
                $validated['reviewed_by'] = $validated['reviewed_by'] ?? auth()->id();
            }

            $submission->update($validated);
            $submission->load(['user', 'taskList']);

            return response()->json([
                'success' => true,
                'data' => $submission,
                'message' => 'Submission updated successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified submission
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $submission = Submission::findOrFail($id);
            $submission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Submission deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete the specified submission
     */
    public function complete(Request $request, Submission $submission): JsonResponse
    {
        try {
            // Check if all required tasks are completed
            $pendingRequiredTasks = $submission->submissionTasks()
                ->where('status', '!=', 'completed')
                ->whereHas('task', function ($query) {
                    $query->where('is_required', true);
                })
                ->count();

            if ($pendingRequiredTasks > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot complete submission. There are still required tasks pending.',
                    'pending_required_tasks' => $pendingRequiredTasks
                ], 400);
            }

            $validated = $request->validate([
                'employee_signature' => 'nullable|string',
                'notes' => 'nullable|string'
            ]);

            $submission->update([
                'status' => 'completed',
                'completed_at' => now(),
                'employee_signature' => $validated['employee_signature'] ?? null,
                'notes' => $validated['notes'] ?? null
            ]);

            $submission->load(['user', 'taskList', 'submissionTasks.task']);

            return response()->json([
                'success' => true,
                'data' => $submission,
                'message' => 'Submission completed successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete a specific task within a submission
     */
    public function completeTask(Request $request, Submission $submission, $taskId): JsonResponse
    {
        try {
            $submissionTask = $submission->submissionTasks()
                ->where('task_id', $taskId)
                ->firstOrFail();

            $validated = $request->validate([
                'proof_text' => 'nullable|string',
                'proof_files' => 'nullable|array',
                'digital_signature' => 'nullable|string'
            ]);

            $submissionTask->update([
                'status' => 'completed',
                'completed_at' => now(),
                'proof_text' => $validated['proof_text'] ?? null,
                'proof_files' => $validated['proof_files'] ?? null,
                'digital_signature' => $validated['digital_signature'] ?? null
            ]);

            $submissionTask->load(['task']);

            return response()->json([
                'success' => true,
                'data' => $submissionTask,
                'message' => 'Task completed successfully'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete task: ' . $e->getMessage()
            ], 500);
        }
    }
}
