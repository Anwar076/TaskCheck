<?php

namespace App\Http\Controllers\Api\Mobile\Admin;

use App\Http\Controllers\Api\Mobile\MobileController;
use App\Models\Organisation\Location;
use App\Models\Submissions\Submission;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\User;
use Illuminate\Http\Request;

class DashboardController extends MobileController
{
    public function index(Request $request)
    {
        $user = $request->user();
        $companyId = $user->company_id;
        $selectedLocationId = null;

        if ($request->filled('location_id')) {
            $candidate = (int) $request->get('location_id');
            if (Location::where('company_id', $companyId)->where('id', $candidate)->exists()) {
                $selectedLocationId = $candidate;
            }
        }

        $submissionQuery = Submission::query()->where('company_id', $companyId);
        $listQuery = TaskList::query()->where('company_id', $companyId);

        if ($selectedLocationId) {
            $submissionQuery->whereHas('taskList', fn ($q) => $q->where('location_id', $selectedLocationId));
            $listQuery->where('location_id', $selectedLocationId);
        }

        $stats = [
            'total_users' => User::where('company_id', $companyId)->count(),
            'active_users' => User::where('company_id', $companyId)->where('is_active', true)->count(),
            'total_lists' => (clone $listQuery)->count(),
            'active_lists' => (clone $listQuery)->where('is_active', true)->count(),
            'total_submissions' => (clone $submissionQuery)->count(),
            'pending_review' => (clone $submissionQuery)->where('status', 'completed')->count(),
            'in_progress' => (clone $submissionQuery)->where('status', 'in_progress')->count(),
            'reviewed' => (clone $submissionQuery)->where('status', 'reviewed')->count(),
            'rejected' => (clone $submissionQuery)->where('status', 'rejected')->count(),
            'completed_today' => (clone $submissionQuery)->whereDate('completed_at', today())->count(),
        ];

        $recent = (clone $submissionQuery)
            ->with(['user', 'taskList'])
            ->where('status', 'completed')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'status' => $s->status,
                'list_title' => $s->taskList?->title,
                'user_name' => $s->user?->name,
                'created_at' => $s->created_at?->toIso8601String(),
            ])
            ->values();

        $locations = Location::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $company = $user->company;

        return $this->success([
            'stats' => $stats,
            'recent_submissions' => $recent,
            'locations' => $locations,
            'company' => $company ? ['id' => $company->id, 'name' => $company->name] : null,
        ]);
    }
}
