@extends('layouts.admin')

@section('title', 'Test Assignment')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold mb-6">Test Assignment Functionaliteit</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Test Assignment to List ID 3</h2>
        
        <form method="POST" action="{{ route('admin.lists.assign', ['list' => 3]) }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Assignment Type</label>
                <div>
                    <label class="inline-flex items-center">
                        <input type="radio" name="assignment_type" value="user" checked>
                        <span class="ml-2">User</span>
                    </label>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="user_ids" class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                <select name="user_ids[]" id="user_ids" class="block w-full border border-gray-300 rounded-md px-3 py-2" required>
                    <option value="">Choose user...</option>
                    @foreach(\App\Models\User::where('role', 'employee')->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label for="assigned_date" class="block text-sm font-medium text-gray-700 mb-2">Assigned Date</label>
                <input type="date" name="assigned_date" id="assigned_date" value="{{ date('Y-m-d') }}" class="block w-full border border-gray-300 rounded-md px-3 py-2" required>
            </div>
            
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create Assignment
            </button>
        </form>
        
        <hr class="my-6">
        
        <h3 class="text-lg font-semibold mb-4">Current Assignments for List ID 3</h3>
        
        @php
            $list = \App\Models\TaskList::with('assignments.user')->find(3);
        @endphp
        
        @php
            // Get ALL assignments for this list, including inactive ones
            $allAssignments = \App\Models\ListAssignment::where('list_id', 3)->with('user')->get();
        @endphp
        
        <h4 class="text-md font-semibold mb-2">All Assignments (including inactive):</h4>
        @if($allAssignments->count() > 0)
            <div class="space-y-2 mb-4">
                @foreach($allAssignments as $assignment)
                    <div class="border rounded p-3 {{ $assignment->is_active ? 'bg-green-50' : 'bg-red-50' }}">
                        <div>
                            <strong>Assignment ID:</strong> {{ $assignment->id }}<br>
                            <strong>User:</strong> {{ $assignment->user ? $assignment->user->name : 'NULL' }}<br>
                            <strong>Department:</strong> {{ $assignment->department ?: 'NULL' }}<br>
                            <strong>Assigned Date:</strong> {{ $assignment->assigned_date }}<br>
                            <strong>Active:</strong> {{ $assignment->is_active ? 'Yes' : 'No' }}
                        </div>
                        @if(!$assignment->is_active)
                            <small class="text-red-600">This assignment is inactive</small>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-red-600 mb-4">No assignments found at all for this list.</p>
        @endif
        
        <h4 class="text-md font-semibold mb-2">Active Assignments Only (via relationship):</h4>
        @if($list && $list->assignments->count() > 0)
            <div class="space-y-2">
                @foreach($list->assignments as $assignment)
                    <div class="border rounded p-3 flex justify-between items-center">
                        <div>
                            <strong>Assignment ID:</strong> {{ $assignment->id }}<br>
                            <strong>User:</strong> {{ $assignment->user ? $assignment->user->name : 'NULL' }}<br>
                            <strong>Department:</strong> {{ $assignment->department ?: 'NULL' }}<br>
                            <strong>Assigned Date:</strong> {{ $assignment->assigned_date }}<br>
                            <strong>Active:</strong> {{ $assignment->is_active ? 'Yes' : 'No' }}
                        </div>
                        <form method="POST" action="{{ route('admin.assignments.destroy', $assignment) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                                    onclick="return confirm('Remove this assignment?')">
                                Remove
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No assignments found for this list.</p>
        @endif
    </div>
</div>
@endsection