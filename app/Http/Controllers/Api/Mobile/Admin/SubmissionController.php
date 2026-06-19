<?php

namespace App\Http\Controllers\Api\Mobile\Admin;

use App\Http\Controllers\Api\Mobile\MobileController;
use App\Models\Submissions\Submission;
use App\Services\Mobile\MobileSerializer;
use Illuminate\Http\Request;

class SubmissionController extends MobileController
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $query = Submission::query()
            ->with(['user', 'taskList.location', 'submissionTasks'])
            ->where('company_id', $companyId)
            ->orderByDesc('created_at');

        if ($request->filled('tab')) {
            $tab = $request->get('tab');
            if ($tab === 'to_review') {
                $query->where('status', 'completed');
            } elseif ($tab === 'done') {
                $query->whereIn('status', ['reviewed', 'rejected']);
            } elseif ($tab === 'in_progress') {
                $query->where('status', 'in_progress');
            }
        }

        $base = Submission::where('company_id', $companyId);
        $meta = [
            'to_review_count' => (clone $base)->where('status', 'completed')->count(),
            'done_count' => (clone $base)->whereIn('status', ['reviewed', 'rejected'])->count(),
            'in_progress_count' => (clone $base)->where('status', 'in_progress')->count(),
        ];

        $items = $query->get()
            ->map(fn ($s) => MobileSerializer::adminSubmissionListItem($s))
            ->values();

        return response()->json([
            'success' => true,
            'data' => $items,
            'meta' => $meta,
        ]);
    }

    public function show(Request $request, int $id)
    {
        $submission = Submission::query()
            ->with(['user', 'taskList.location', 'submissionTasks.task'])
            ->where('company_id', $request->user()->company_id)
            ->findOrFail($id);

        return $this->success(MobileSerializer::adminSubmissionDetail($submission));
    }

    public function review(Request $request, int $id)
    {
        $submission = Submission::query()
            ->where('company_id', $request->user()->company_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,needs_revision,reviewed'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $statusMap = [
            'approved' => 'reviewed',
            'reviewed' => 'reviewed',
            'rejected' => 'rejected',
            'needs_revision' => 'in_progress',
        ];

        $metadata = is_array($submission->metadata) ? $submission->metadata : [];
        if (!empty($validated['admin_notes'])) {
            $metadata['admin_notes'] = $validated['admin_notes'];
        }

        $submission->update([
            'status' => $statusMap[$validated['status']] ?? $validated['status'],
            'metadata' => $metadata,
        ]);

        return $this->success(MobileSerializer::adminSubmissionDetail($submission->fresh(['user', 'taskList.location', 'submissionTasks.task'])), 'Inzending beoordeeld.');
    }
}
