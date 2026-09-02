<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checklist\ListAssignment;
use App\Models\Checklist\TaskList;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskListAssignmentController extends Controller
{
    public function assign(Request $request, TaskList $list)
    {
        // Zorg dat de lijst bij hetzelfde bedrijf hoort
        if ($list->company_id !== auth()->user()->company_id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Geen toegang tot deze lijst.'], 403);
            }
            abort(403, 'Geen toegang tot deze lijst.');
        }

        try {
            \Log::info('Assignment request received', [
                'list_id' => $list->id,
                'request_data' => $request->all(),
            ]);

            $validationRules = [
                'assignment_type' => 'required|in:user,department',
                'assigned_date' => 'required|date',
                'due_date' => 'nullable|date|after_or_equal:assigned_date',
            ];

            // Add conditional validation based on assignment type
            if ($request->assignment_type === 'user') {
                $validationRules['user_ids'] = 'required|exists:users,id';
            } elseif ($request->assignment_type === 'department') {
                $validationRules['department'] = 'required|string|max:100';
            }

            $validatedData = $request->validate($validationRules);

            \Log::info('Validation passed', ['validated_data' => $validatedData]);

            $assignments = [];
            $skippedAssignments = 0;

            if ($validatedData['assignment_type'] === 'user') {
                $userIds = array_values(array_unique(array_map(
                    'intval',
                    is_array($validatedData['user_ids']) ? $validatedData['user_ids'] : [$validatedData['user_ids']]
                )));

                foreach ($userIds as $userId) {
                    $selectedUser = \App\Models\Organisation\User::query()
                        ->where('id', $userId)
                        ->where('company_id', auth()->user()->company_id)
                        ->whereIn('role', ['employee', 'admin'])
                        ->where('is_active', true)
                        ->first();

                    if (! $selectedUser) {
                        throw ValidationException::withMessages([
                            'user_ids' => 'Een of meer geselecteerde medewerkers zijn ongeldig voor jouw bedrijf.',
                        ]);
                    }

                    if ($list->location_id && (int) $selectedUser->location_id !== (int) $list->location_id) {
                        throw ValidationException::withMessages([
                            'user_ids' => $selectedUser->name.' hoort niet bij de locatie van deze takenlijst.',
                        ]);
                    }

                    $existingAssignment = \App\Models\Checklist\ListAssignment::where('list_id', $list->id)
                        ->where('user_id', $userId)
                        ->where('is_active', true)
                        ->first();

                    if (! $existingAssignment) {
                        $assignment = \App\Models\Checklist\ListAssignment::create([
                            'list_id' => $list->id,
                            'user_id' => $userId,
                            'department' => null,
                            'assigned_date' => $validatedData['assigned_date'],
                            'due_date' => $validatedData['due_date'] ?? null,
                            'is_active' => true,
                        ]);
                        $assignments[] = $assignment;
                        if ($selectedUser->isEmployee()) {
                            \App\Models\Communication\Notification::createListAssigned(
                                (int) $selectedUser->id,
                                (int) $list->id,
                                (string) $list->title,
                                'user'
                            );
                        }
                        \Log::info('Created user assignment', ['assignment_id' => $assignment->id, 'user_id' => $userId]);
                    } else {
                        $skippedAssignments++;
                        \Log::info('Skipped duplicate user assignment', ['user_id' => $userId]);
                    }
                }
            } elseif ($validatedData['assignment_type'] === 'department') {
                // Check if department assignment already exists
                $existingAssignment = \App\Models\Checklist\ListAssignment::where('list_id', $list->id)
                    ->where('department', $validatedData['department'])
                    ->where('is_active', true)
                    ->first();

                if (! $existingAssignment) {
                    $assignment = \App\Models\Checklist\ListAssignment::create([
                        'list_id' => $list->id,
                        'user_id' => null,
                        'department' => $validatedData['department'],
                        'assigned_date' => $validatedData['assigned_date'],
                        'due_date' => $validatedData['due_date'] ?? null,
                        'is_active' => true,
                    ]);
                    $assignments[] = $assignment;

                    $departmentUsers = \App\Models\Organisation\User::query()
                        ->where('company_id', auth()->user()->company_id)
                        ->where('role', 'employee')
                        ->where('is_active', true)
                        ->where('department', $validatedData['department'])
                        ->when($list->location_id, fn ($q) => $q->where('location_id', $list->location_id))
                        ->get(['id']);

                    foreach ($departmentUsers as $departmentUser) {
                        \App\Models\Communication\Notification::createListAssigned(
                            (int) $departmentUser->id,
                            (int) $list->id,
                            (string) $list->title,
                            'department'
                        );
                    }

                    \Log::info('Created department assignment', ['assignment_id' => $assignment->id, 'department' => $validatedData['department']]);
                } else {
                    $skippedAssignments++;
                    \Log::info('Skipped duplicate department assignment', ['department' => $validatedData['department']]);
                }
            }

            $message = 'Takenlijst succesvol toegewezen aan '.count($assignments).' toewijzing(en).';
            if ($skippedAssignments > 0) {
                $message .= ' '.$skippedAssignments.' duplicaat toewijzing(en) overgeslagen.';
            }

            if (count($assignments) > 0) {
                $company = auth()->user()->company;
                if ($company) {
                    $onboardingJustCompleted = app(\App\Services\Platform\AdminOnboardingService::class)
                        ->handleAssignmentCreated($company, (int) $list->id);

                    if ($onboardingJustCompleted) {
                        if (request()->ajax() || request()->wantsJson()) {
                            return response()->json([
                                'success' => true,
                                'message' => $message,
                                'assignments_created' => count($assignments),
                                'assignments_skipped' => $skippedAssignments,
                                'onboarding_completed' => true,
                                'redirect' => route('admin.lists.show', $list),
                            ]);
                        }

                        return redirect()->route('admin.lists.show', $list)
                            ->with('onboarding_completed', [
                                'list_title' => $list->title,
                                'list_id' => $list->id,
                            ]);
                    }
                }
            }

            // Check if it's an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'assignments_created' => count($assignments),
                    'assignments_skipped' => $skippedAssignments,
                ]);
            }

            return redirect()->route('admin.lists.show', $list)
                ->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Assignment validation failed', ['errors' => $e->errors()]);

            // Check if it's an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validatie mislukt. Controleer je invoer.',
                    'errors' => $e->errors(),
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validatie mislukt. Controleer je invoer.');
        } catch (\Exception $e) {
            \Log::error('Assignment failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            // Check if it's an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Er is een fout opgetreden bij het toewijzen van de lijst: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het toewijzen van de lijst: '.$e->getMessage());
        }
    }

    public function removeAssignment(ListAssignment $assignment)
    {
        try {
            \Log::info('Removing assignment', ['assignment_id' => $assignment->id]);

            $assignment->update(['is_active' => false]);

            // Check if it's an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Toewijzing succesvol verwijderd.',
                ]);
            }

            return redirect()->back()
                ->with('success', 'Toewijzing succesvol verwijderd.');

        } catch (\Exception $e) {
            \Log::error('Failed to remove assignment', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
            ]);

            // Check if it's an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Er is een fout opgetreden bij het verwijderen van de toewijzing: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Er is een fout opgetreden bij het verwijderen van de toewijzing: '.$e->getMessage());
        }
    }
}
