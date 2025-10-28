<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TaskList;
use App\Models\ListAssignment;

class TestAssignment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:assignment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test assignment functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Assignment Functionality...');
        
        // Get users and lists
        $users = User::all();
        $lists = TaskList::all();
        
        $this->info('Users found: ' . $users->count());
        foreach($users as $user) {
            $this->line("- ID: {$user->id}, Name: {$user->name}, Email: {$user->email}");
        }
        
        $this->info('Lists found: ' . $lists->count());
        foreach($lists as $list) {
            $this->line("- ID: {$list->id}, Title: {$list->title}");
        }
        
        // Check existing assignments
        $assignments = ListAssignment::with(['user', 'taskList'])->get();
        $this->info('Existing assignments: ' . $assignments->count());
        foreach($assignments as $assignment) {
            $userName = $assignment->user ? $assignment->user->name : 'NULL';
            $listTitle = $assignment->taskList ? $assignment->taskList->title : 'NULL';
            $this->line("- Assignment ID: {$assignment->id}, User: {$userName}, List: {$listTitle}, Department: {$assignment->department}");
        }
        
        // Test creating a new assignment
        if ($users->count() > 0 && $lists->count() > 0) {
            $user = $users->first();
            $list = $lists->first();
            
            $this->info('Testing assignment creation...');
            
            try {
                $assignment = ListAssignment::create([
                    'list_id' => $list->id,
                    'user_id' => $user->id,
                    'department' => null,
                    'assigned_date' => now()->format('Y-m-d'),
                    'due_date' => null,
                    'is_active' => true,
                ]);
                
                $this->info("Successfully created assignment ID: {$assignment->id}");
                
                // Clean up - remove the test assignment
                $assignment->delete();
                $this->info("Test assignment cleaned up");
                
            } catch (\Exception $e) {
                $this->error("Failed to create assignment: " . $e->getMessage());
            }
        } else {
            $this->error("No users or lists found for testing");
        }
        
        return 0;
    }
}
