<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskList;
use App\Models\User;
use App\Models\ListAssignment;

class TestAssignmentCommand extends Command
{
    protected $signature = 'test:assignment-create {listId} {userId}';
    protected $description = 'Test creating an assignment directly';

    public function handle()
    {
        $listId = $this->argument('listId');
        $userId = $this->argument('userId');
        
        $this->info("Creating assignment for List ID: {$listId}, User ID: {$userId}");
        
        $list = TaskList::find($listId);
        if (!$list) {
            $this->error("List with ID {$listId} not found");
            return 1;
        }
        
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }
        
        $this->info("List: {$list->title}");
        $this->info("User: {$user->name} ({$user->email})");
        
        // Check if assignment already exists
        $existing = ListAssignment::where('list_id', $listId)
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->first();
            
        if ($existing) {
            $this->warn("Active assignment already exists (ID: {$existing->id})");
            return 1;
        }
        
        // Create new assignment
        $assignment = ListAssignment::create([
            'list_id' => $listId,
            'user_id' => $userId,
            'department' => null,
            'assigned_date' => now(),
            'due_date' => null,
            'is_active' => true
        ]);
        
        $this->info("Assignment created successfully!");
        $this->info("Assignment ID: {$assignment->id}");
        $this->info("Is Active: " . ($assignment->is_active ? 'Yes' : 'No'));
        
        // Test the relationship
        $this->info("\nTesting relationship...");
        $list->load('assignments.user');
        $this->info("Total assignments via relationship: " . $list->assignments->count());
        
        foreach ($list->assignments as $assign) {
            $this->info("- Assignment {$assign->id}: User {$assign->user->name}, Active: " . ($assign->is_active ? 'Yes' : 'No'));
        }
        
        return 0;
    }
}