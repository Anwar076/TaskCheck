<?php

use App\Http\Controllers\Api\TaskListController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\Api\TemplateController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes - Complete Application API
Route::middleware(['web', 'auth:sanctum'])->group(function () {
    // Dashboard
    Route::get('/dashboard/admin/stats', [DashboardController::class, 'adminStats']);
    Route::get('/dashboard/employee/data', [DashboardController::class, 'employeeData']);
    Route::get('/dashboard/weekly-overview', [DashboardController::class, 'weeklyOverview']);
    Route::get('/dashboard/recent-activity', [DashboardController::class, 'recentActivity']);
    
    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users/statistics', [UserController::class, 'statistics']);
    
    // Task Lists
    Route::get('/lists', [TaskListController::class, 'index']);
    Route::post('/lists', [TaskListController::class, 'store']);
    Route::get('/lists/{id}', [TaskListController::class, 'show']);
    Route::get('/lists/templates/available', [TaskListController::class, 'getTemplates']);
    
    // Tasks
    Route::get('/lists/{listId}/tasks', [TaskController::class, 'index']);
    Route::post('/lists/{listId}/tasks', [TaskController::class, 'store']);
    Route::get('/lists/{listId}/tasks/{taskId}', [TaskController::class, 'show']);
    Route::put('/lists/{listId}/tasks/{taskId}', [TaskController::class, 'update']);
    Route::delete('/lists/{listId}/tasks/{taskId}', [TaskController::class, 'destroy']);
    Route::post('/lists/{listId}/tasks/reorder', [TaskController::class, 'reorder']);
    
    // Templates
    Route::get('/templates', [TemplateController::class, 'index']);
    Route::post('/templates', [TemplateController::class, 'store']);
    Route::get('/templates/{id}', [TemplateController::class, 'show']);
    Route::put('/templates/{id}', [TemplateController::class, 'update']);
    Route::delete('/templates/{id}', [TemplateController::class, 'destroy']);
    
    // Submissions
    Route::get('/submissions', [SubmissionController::class, 'index']);
    Route::post('/submissions', [SubmissionController::class, 'store']);
    Route::get('/submissions/{id}', [SubmissionController::class, 'show']);
    Route::put('/submissions/{submission}', [SubmissionController::class, 'update']);
    Route::post('/submissions/{submission}/complete', [SubmissionController::class, 'complete']);
    Route::post('/submissions/{submission}/tasks/{task}', [SubmissionController::class, 'completeTask']);
});