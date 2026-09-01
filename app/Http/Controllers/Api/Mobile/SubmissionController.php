<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Models\Communication\Notification;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use App\Services\Mobile\MobileSerializer;
use App\Services\Mobile\MobileTaskAccess;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class SubmissionController extends MobileController
{
    public function __construct(
        protected MobileTaskAccess $taskAccess,
        protected ScheduleService $scheduleService,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $accessibleListIds = $this->scheduleService->getScheduledTasksForUser($user)->pluck('id');

        $query = Submission::query()
            ->with(['taskList.location', 'submissionTasks'])
            ->where('company_id', $user->company_id)
            ->where(function ($submissionQuery) use ($user, $accessibleListIds) {
                $submissionQuery->where(function ($personalQuery) use ($user) {
                    $personalQuery->where('user_id', $user->id)
                        ->where('is_team_submission', false);
                })->orWhere(function ($teamQuery) use ($user, $accessibleListIds) {
                    $teamQuery->where('is_team_submission', true)
                        ->where(function ($accessQuery) use ($user, $accessibleListIds) {
                            $accessQuery->whereIn('list_id', $accessibleListIds)
                                ->orWhereHas('submissionTasks', fn ($taskQuery) => $taskQuery->where('completed_by_user_id', $user->id));
                        });
                });
            })
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $items = $query->get()->map(fn ($s) => MobileSerializer::submissionSummary($s))->values();

        return $this->success($items);
    }

    public function show(Request $request, int $id)
    {
        $user = $request->user();

        $submission = Submission::query()
            ->with(['taskList.location', 'submissionTasks.task'])
            ->where('company_id', $user->company_id)
            ->findOrFail($id);

        if (! $this->taskAccess->userCanAccessSubmission($user, $submission)) {
            return $this->error('Geen toegang tot deze inzending.', 403);
        }

        $this->taskAccess->syncMissingSubmissionTasks($submission);

        return $this->success(MobileSerializer::submissionSummary($submission));
    }

    public function complete(Request $request, int $id)
    {
        $user = $request->user();

        $submission = Submission::query()
            ->with(['taskList', 'submissionTasks.task'])
            ->where('company_id', $user->company_id)
            ->findOrFail($id);

        if (! $this->taskAccess->userCanAccessSubmission($user, $submission)) {
            return $this->error('Geen toegang tot deze inzending.', 403);
        }

        $incomplete = $submission->submissionTasks()
            ->whereHas('task', fn ($q) => $q->where('is_required', true))
            ->whereNotIn('status', ['completed', 'approved'])
            ->count();

        if ($incomplete > 0) {
            return $this->error('Voltooi eerst alle verplichte taken.', 422);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
            'employee_signature' => $submission->taskList?->requires_signature
                ? 'required|string'
                : 'nullable|string',
        ]);

        $metadata = is_array($submission->metadata) ? $submission->metadata : [];
        if (! empty($validated['notes'])) {
            $metadata['employee_notes'] = $validated['notes'];
        }

        $submission->update([
            'completed_at' => now(),
            'status' => $submission->completedStatus(),
            'employee_signature' => $validated['employee_signature'] ?? null,
            'notes' => $validated['notes'] ?? $submission->notes,
            'metadata' => $metadata,
        ]);

        $submission->loadMissing(['taskList', 'user']);
        $admins = User::query()
            ->where('company_id', $submission->company_id)
            ->where('role', 'admin')
            ->pluck('id');

        foreach ($admins as $adminUserId) {
            Notification::createSubmissionCompletedForAdmin(
                (int) $adminUserId,
                (int) $submission->id,
                (string) ($submission->user->name ?? 'Een medewerker'),
                (string) ($submission->taskList->title ?? 'Checklist')
            );
        }

        return $this->success(null, 'Checklist ingediend.');
    }
}
