<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubmissionStatus;
use App\Enums\SubmissionTaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveSubmissionTaskRequest;
use App\Http\Requests\RejectSubmissionTaskRequest;
use App\Http\Requests\ReviewSubmissionRequest;
use App\Models\Communication\Notification;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use App\Services\Ai\SubmissionReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubmissionReviewController extends Controller
{
    public function show(Submission $submission)
    {
        $submission->load(['user', 'taskList.assignments', 'submissionTasks.task', 'submissionTasks.completedBy', 'submissionTasks.correctiveActionOwner', 'submissionTasks.verifier', 'submissionTasks.auditEvents.actor']);
        $correctiveActionOwners = User::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('admin.submissions.show', compact('submission', 'correctiveActionOwners'));
    }

    public function aiReview(Submission $submission, SubmissionReviewService $ai)
    {
        try {
            if (! $ai->isEnabled()) {
                return redirect()->back()->with('error', 'AI-review is niet geconfigureerd. Voeg een OPENAI_API_KEY toe aan je .env bestand.');
            }

            $review = $ai->review($submission);
            $metadata = $submission->metadata ?? [];
            $metadata['ai_review'] = [
                'overall_status' => $review['overall_status'],
                'summary' => $review['summary'],
                'missing_required_tasks' => $review['missing_required_tasks'],
                'notes' => $review['notes'],
                'model' => $review['_model'] ?? null,
                'ran_at' => now()->toIso8601String(),
            ];
            $submission->update(['metadata' => $metadata]);

            return redirect()->back()->with('success', 'AI-review is uitgevoerd.');
        } catch (\Throwable $exception) {
            Log::error('AI submission review failed', ['submission_id' => $submission->id, 'error' => $exception->getMessage()]);

            return redirect()->back()->with('error', 'AI-review is mislukt: '.$exception->getMessage());
        }
    }

    public function review(ReviewSubmissionRequest $request, Submission $submission)
    {
        $validated = $request->validated();
        $submission->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Inzending succesvol beoordeeld.');
    }

    public function rejectTask(RejectSubmissionTaskRequest $request, SubmissionTask $submissionTask)
    {
        $validated = $request->validated();

        $notification = DB::transaction(function () use ($submissionTask, $validated) {
            $submissionTask->update([
                'status' => SubmissionTaskStatus::PENDING->value,
                'rejection_reason' => $validated['rejection_reason'],
                'corrective_action' => $validated['corrective_action'],
                'corrective_action_owner_id' => $validated['corrective_action_owner_id'],
                'corrective_action_due_at' => $validated['corrective_action_due_at'],
                'corrective_action_completed_at' => null,
                'verification_note' => null,
                'verified_by' => null,
                'verified_at' => null,
                'rejected_at' => now(),
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'redo_requested' => false,
                'redo_reason' => null,
            ]);
            $created = Notification::createTaskRejected(
                app(\App\Services\CollaborativeSubmissionService::class)->notifyUserIdForTask($submissionTask),
                $submissionTask->task->title,
                $validated['rejection_reason'],
                $submissionTask->submission_id
            );
            $submissionTask->submission->update(['status' => SubmissionStatus::IN_PROGRESS->value]);

            return $created;
        });

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Taak afgekeurd. Medewerker moet de taak opnieuw uitvoeren en daarna de checklist opnieuw indienen.', 'notification_id' => $notification?->id, 'notification_user_id' => $notification?->user_id]);
        }

        return redirect()->back()->with('success', 'Taak afgekeurd. Medewerker moet de taak opnieuw uitvoeren en daarna de checklist opnieuw indienen.');
    }

    public function requestRedo(Request $request, SubmissionTask $submissionTask)
    {
        $validated = $request->validate(['redo_reason' => 'nullable|string']);
        $notification = DB::transaction(fn () => $submissionTask->requestRedo(auth()->id(), $validated['redo_reason'] ?? null));

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Opnieuw doen aangevraagd. De medewerker kan deze taak opnieuw uitvoeren en is op de hoogte gebracht.', 'notification_id' => $notification?->id, 'notification_user_id' => $notification?->user_id]);
        }

        return redirect()->back()->with('success', 'Opnieuw doen aangevraagd. De medewerker kan deze taak opnieuw uitvoeren en is op de hoogte gebracht.');
    }

    public function approveTask(ApproveSubmissionTaskRequest $request, SubmissionTask $submissionTask)
    {
        $validated = $request->validated();

        $submissionTask->approve(auth()->id(), $validated['manager_comment'] ?? null);
        if ($submissionTask->corrective_action) {
            $submissionTask->update(['corrective_action_completed_at' => now(), 'verification_note' => $validated['verification_note'], 'verified_by' => auth()->id(), 'verified_at' => now()]);
        }
        $this->updateSubmissionStatusIfAllTasksReviewed($submissionTask->submission);

        return $request->ajax() || $request->expectsJson()
            ? response()->json(['success' => true, 'message' => 'Taak succesvol goedgekeurd.'])
            : redirect()->back()->with('success', 'Taak succesvol goedgekeurd.');
    }

    public function approveAll(Request $request, Submission $submission)
    {
        $submission->loadMissing('taskList');
        $wantsJson = $request->ajax() || $request->expectsJson();
        if (! $submission->taskList?->requires_review) {
            return $wantsJson
                ? response()->json(['success' => false, 'message' => 'Deze takenlijst hoeft niet gecontroleerd te worden.'], 422)
                : redirect()->back()->with('error', 'Deze takenlijst hoeft niet gecontroleerd te worden.');
        }

        $approvedCount = DB::transaction(function () use ($submission) {
            $tasks = $submission->submissionTasks()->completed()->whereNull('corrective_action')->lockForUpdate()->get();
            $tasks->each(fn (SubmissionTask $task) => $task->approve(auth()->id()));
            $this->updateSubmissionStatusIfAllTasksReviewed($submission);

            return $tasks->count();
        });
        if ($approvedCount === 0) {
            return $wantsJson
                ? response()->json(['success' => false, 'message' => 'Er zijn geen taken die in één keer goedgekeurd kunnen worden.'], 422)
                : redirect()->back()->with('error', 'Er zijn geen taken die in één keer goedgekeurd kunnen worden.');
        }

        $message = $approvedCount === 1 ? '1 taak succesvol goedgekeurd.' : "{$approvedCount} taken succesvol goedgekeurd.";

        return $wantsJson
            ? response()->json(['success' => true, 'message' => $message, 'approved_count' => $approvedCount, 'submission_status' => $submission->fresh()->status])
            : redirect()->back()->with('success', $message);
    }

    private function updateSubmissionStatusIfAllTasksReviewed(Submission $submission): void
    {
        $submission->load('submissionTasks');
        if ($submission->submissionTasks->isEmpty() || ! $submission->submissionTasks->every(fn ($task) => in_array($task->status, [SubmissionTaskStatus::APPROVED->value, SubmissionTaskStatus::REJECTED->value], true))) {
            return;
        }

        $submission->update(['status' => $submission->submissionTasks->contains('status', SubmissionTaskStatus::REJECTED->value)
            ? SubmissionStatus::REJECTED->value
            : SubmissionStatus::REVIEWED->value]);
    }
}
