<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\Organisation\User::where('role', 'admin')->first();
        $employees = \App\Models\Organisation\User::where('role', 'employee')->get();
        $companyId = $admin?->company_id;

        // Create sample task lists
        $cleaningList = \App\Models\Checklist\TaskList::create([
            'company_id' => $companyId,
            'title' => 'Daily Office Cleaning Checklist',
            'description' => 'Complete daily cleaning tasks for office areas',
            'created_by' => $admin->id,
            'schedule_type' => 'daily',
            'priority' => 'medium',
            'category' => 'Cleaning',
            'is_active' => true,
        ]);

        $safetyList = \App\Models\Checklist\TaskList::create([
            'company_id' => $companyId,
            'title' => 'Weekly Safety Inspection',
            'description' => 'Weekly safety check for workplace compliance',
            'created_by' => $admin->id,
            'schedule_type' => 'weekly',
            'priority' => 'high',
            'category' => 'Safety',
            'is_active' => true,
        ]);

        // Create tasks for cleaning list
        \App\Models\Checklist\Task::create([
            'list_id' => $cleaningList->id,
            'title' => 'Empty all trash bins',
            'description' => 'Empty and replace liners in all office trash bins',
            'required_proof_type' => 'photo',
            'order_index' => 1,
        ]);

        \App\Models\Checklist\Task::create([
            'list_id' => $cleaningList->id,
            'title' => 'Vacuum common areas',
            'description' => 'Vacuum all carpeted common areas and meeting rooms',
            'required_proof_type' => 'photo',
            'order_index' => 2,
        ]);

        \App\Models\Checklist\Task::create([
            'list_id' => $cleaningList->id,
            'title' => 'Clean and sanitize restrooms',
            'description' => 'Complete restroom cleaning including toilets, sinks, and floors',
            'required_proof_type' => 'photo',
            'order_index' => 3,
        ]);

        // Create tasks for safety list
        \App\Models\Checklist\Task::create([
            'list_id' => $safetyList->id,
            'title' => 'Check fire extinguishers',
            'description' => 'Verify all fire extinguishers are properly mounted and charged',
            'required_proof_type' => 'photo',
            'order_index' => 1,
        ]);

        \App\Models\Checklist\Task::create([
            'list_id' => $safetyList->id,
            'title' => 'Test emergency exits',
            'description' => 'Ensure all emergency exits are clear and functional',
            'required_proof_type' => 'text',
            'order_index' => 2,
        ]);

        // Create assignments
        foreach ($employees as $employee) {
            \App\Models\Checklist\ListAssignment::create([
                'list_id' => $cleaningList->id,
                'user_id' => $employee->id,
                'assigned_date' => today(),
                'due_date' => today()->addDay(),
            ]);

            if ($employee->department === 'Operations') {
                \App\Models\Checklist\ListAssignment::create([
                    'list_id' => $safetyList->id,
                    'user_id' => $employee->id,
                    'assigned_date' => today(),
                    'due_date' => today()->addWeek(),
                ]);
            }
        }
    }
}
