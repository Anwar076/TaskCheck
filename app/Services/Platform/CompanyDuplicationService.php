<?php

namespace App\Services\Platform;

use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyDuplicationService
{
    /** @return array{company: Company, admin: User, lists: int, tasks: int, locations: int} */
    public function duplicate(Company $source, array $data): array
    {
        return DB::transaction(function () use ($source, $data) {
            $copySettings = (bool) ($data['copy_settings'] ?? false);
            $trialEnd = Company::trialEndForPlan($data['subscription_plan']);
            $plan = Company::plan($data['subscription_plan']) ?? [];
            $company = Company::query()->create([
                'name' => $data['company_name'],
                'company_type' => $source->company_type,
                'email' => $data['admin_email'],
                'subscription_plan' => $data['subscription_plan'],
                'subscription_status' => 'trial',
                'trial_ends_at' => $trialEnd,
                'billing_required' => false,
                'billing_period' => $plan['billing_period'] ?? 'monthly',
                'billing_start_date' => $trialEnd->toDateString(),
                'max_users' => $plan['max_users'] ?? 5,
                'max_locations' => $plan['max_locations'] ?? 1,
                'max_storage_gb' => $plan['max_storage_gb'] ?? 5,
                'description' => $copySettings ? $source->description : null,
                'departments' => $copySettings ? $source->departments : null,
                'working_hours' => $copySettings ? $source->working_hours : null,
                'calendar_time_mode' => $copySettings ? $source->calendar_time_mode : Company::CALENDAR_TIME_MODE_WORKING_HOURS,
                'onboarding_step' => Company::ONBOARDING_STEP_COMPLETED,
                'onboarding_completed_at' => now(),
                'is_active' => true,
            ]);

            $admin = User::query()->create([
                'company_id' => $company->id,
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'password' => Hash::make($data['account_setup'] === 'password' ? $data['admin_password'] : Str::random(64)),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $locationMap = [];
            if ($data['copy_locations'] ?? false) {
                foreach ($source->locations()->orderBy('id')->get() as $location) {
                    $copy = $location->replicate();
                    $copy->company_id = $company->id;
                    $copy->save();
                    $locationMap[$location->id] = $copy->id;
                }
            }

            $listMap = [];
            $taskCount = 0;
            if ($data['copy_lists'] ?? false) {
                $sourceLists = TaskList::withoutGlobalScope('company')
                    ->where('company_id', $source->id)
                    ->with(['tasks', 'assignments'])
                    ->orderByRaw('CASE WHEN parent_list_id IS NULL THEN 0 ELSE 1 END')
                    ->orderBy('id')
                    ->get();

                foreach ($sourceLists as $sourceList) {
                    $list = $sourceList->replicate();
                    $list->company_id = $company->id;
                    $list->created_by = $admin->id;
                    $list->location_id = $sourceList->location_id ? ($locationMap[$sourceList->location_id] ?? null) : null;
                    $list->parent_list_id = $sourceList->parent_list_id ? ($listMap[$sourceList->parent_list_id] ?? null) : null;
                    $list->template_id = null;
                    $list->save();
                    $listMap[$sourceList->id] = $list->id;

                    foreach ($sourceList->tasks as $sourceTask) {
                        $task = $sourceTask->replicate();
                        $task->list_id = $list->id;
                        $task->created_by = $admin->id;
                        $task->save();
                        $taskCount++;
                    }

                    foreach ($sourceList->assignments as $assignment) {
                        if ($assignment->user_id || (!$copySettings && $assignment->department)) {
                            continue;
                        }
                        $copy = $assignment->replicate();
                        $copy->list_id = $list->id;
                        $copy->user_id = null;
                        $copy->save();
                    }
                }
            }

            if ($data['copy_reporting'] ?? false) {
                foreach ($source->reportRecipients()->get() as $recipient) {
                    $copy = $recipient->replicate(['last_sent_at']);
                    $copy->company_id = $company->id;
                    $copy->last_sent_at = null;
                    $copy->save();
                }
            }

            return ['company' => $company, 'admin' => $admin, 'lists' => count($listMap), 'tasks' => $taskCount, 'locations' => count($locationMap)];
        });
    }
}
