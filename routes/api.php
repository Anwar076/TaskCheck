<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskListController;
use App\Http\Controllers\Api\TemplateController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Mobile\Admin\DashboardController as MobileAdminDashboardController;
use App\Http\Controllers\Api\Mobile\Admin\LocationController as MobileAdminLocationController;
use App\Http\Controllers\Api\Mobile\Admin\SettingsController as MobileAdminSettingsController;
use App\Http\Controllers\Api\Mobile\Admin\SubmissionController as MobileAdminSubmissionController;
use App\Http\Controllers\Api\Mobile\Admin\SubmissionTaskController as MobileAdminSubmissionTaskController;
use App\Http\Controllers\Api\Mobile\Admin\TaskController as MobileAdminTaskController;
use App\Http\Controllers\Api\Mobile\Admin\TaskListController as MobileAdminTaskListController;
use App\Http\Controllers\Api\Mobile\Admin\TemplateController as MobileAdminTemplateController;
use App\Http\Controllers\Api\Mobile\Admin\UserController as MobileAdminUserController;
use App\Http\Controllers\Api\Mobile\Admin\WeeklyOverviewController as MobileAdminWeeklyOverviewController;
use App\Http\Controllers\Api\Mobile\AuthController as MobileAuthController;
use App\Http\Controllers\Api\Mobile\DashboardController as MobileDashboardController;
use App\Http\Controllers\Api\Mobile\LocationController as MobileLocationController;
use App\Http\Controllers\Api\Mobile\NotificationController as MobileNotificationController;
use App\Http\Controllers\Api\Mobile\PushController as MobilePushController;
use App\Http\Controllers\Api\Mobile\SubmissionController as MobileSubmissionController;
use App\Http\Controllers\Api\Mobile\TaskController as MobileTaskController;
use App\Http\Controllers\Api\Mobile\TaskListController as MobileTaskListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Scim\UserController as ScimUserController;

Route::prefix('scim/v2/{endpointKey}')->middleware(['scim.auth', 'throttle:120,1'])->group(function () {
    Route::get('/Users', [ScimUserController::class, 'index']);
    Route::post('/Users', [ScimUserController::class, 'store']);
    Route::get('/Users/{user}', [ScimUserController::class, 'show']);
    Route::put('/Users/{user}', [ScimUserController::class, 'replace']);
    Route::patch('/Users/{user}', [ScimUserController::class, 'patch']);
    Route::delete('/Users/{user}', [ScimUserController::class, 'destroy']);
});

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
    // Place statistics route BEFORE the dynamic /users/{id} route
    Route::get('/users/statistics', [UserController::class, 'statistics']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
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

// TaskCheck mobile app (Expo) — Sanctum Bearer tokens
Route::prefix('mobile')->group(function () {
    Route::post('/login', [MobileAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [MobileAuthController::class, 'logout']);
        Route::get('/me', [MobileAuthController::class, 'me']);

        Route::get('/dashboard', [MobileDashboardController::class, 'index']);

        Route::get('/task-lists', [MobileTaskListController::class, 'index']);
        Route::get('/task-lists/{id}', [MobileTaskListController::class, 'show']);
        Route::post('/task-lists/{id}/start', [MobileTaskListController::class, 'start']);

        Route::post('/tasks/{id}/complete', [MobileTaskController::class, 'complete']);
        Route::post('/tasks/{id}/upload-proof', [MobileTaskController::class, 'uploadProof']);

        Route::get('/submissions', [MobileSubmissionController::class, 'index']);
        Route::get('/submissions/{id}', [MobileSubmissionController::class, 'show']);
        Route::post('/submissions/{id}/complete', [MobileSubmissionController::class, 'complete']);

        Route::get('/locations', [MobileLocationController::class, 'index']);
        Route::get('/locations/{id}', [MobileLocationController::class, 'show']);

        Route::post('/push/register', [MobilePushController::class, 'register']);
        Route::delete('/push/register', [MobilePushController::class, 'unregister']);

        Route::get('/notifications', [MobileNotificationController::class, 'index']);
        Route::get('/notifications/unread-count', [MobileNotificationController::class, 'unreadCount']);
        Route::post('/notifications/{id}/mark-read', [MobileNotificationController::class, 'markRead']);
        Route::post('/notifications/mark-all-read', [MobileNotificationController::class, 'markAllRead']);

        Route::prefix('admin')->middleware('mobile.admin')->group(function () {
            Route::get('/dashboard', [MobileAdminDashboardController::class, 'index']);

            Route::get('/users', [MobileAdminUserController::class, 'index']);
            Route::post('/users', [MobileAdminUserController::class, 'store']);
            Route::get('/users/{id}', [MobileAdminUserController::class, 'show']);
            Route::put('/users/{id}', [MobileAdminUserController::class, 'update']);
            Route::delete('/users/{id}', [MobileAdminUserController::class, 'destroy']);

            Route::get('/task-lists', [MobileAdminTaskListController::class, 'index']);
            Route::post('/task-lists', [MobileAdminTaskListController::class, 'store']);
            Route::get('/task-lists/{id}', [MobileAdminTaskListController::class, 'show']);
            Route::put('/task-lists/{id}', [MobileAdminTaskListController::class, 'update']);
            Route::delete('/task-lists/{id}', [MobileAdminTaskListController::class, 'destroy']);
            Route::post('/task-lists/{id}/assign', [MobileAdminTaskListController::class, 'assign']);

            Route::post('/task-lists/{listId}/tasks', [MobileAdminTaskController::class, 'store']);
            Route::put('/task-lists/{listId}/tasks/{taskId}', [MobileAdminTaskController::class, 'update']);
            Route::delete('/task-lists/{listId}/tasks/{taskId}', [MobileAdminTaskController::class, 'destroy']);

            Route::get('/submissions', [MobileAdminSubmissionController::class, 'index']);
            Route::get('/submissions/{id}', [MobileAdminSubmissionController::class, 'show']);
            Route::post('/submissions/{id}/review', [MobileAdminSubmissionController::class, 'review']);

            Route::post('/submission-tasks/{submissionTaskId}/approve', [MobileAdminSubmissionTaskController::class, 'approve']);
            Route::post('/submission-tasks/{submissionTaskId}/reject', [MobileAdminSubmissionTaskController::class, 'reject']);
            Route::post('/submission-tasks/{submissionTaskId}/redo', [MobileAdminSubmissionTaskController::class, 'redo']);

            Route::get('/locations', [MobileAdminLocationController::class, 'index']);
            Route::post('/locations', [MobileAdminLocationController::class, 'store']);
            Route::get('/locations/{id}', [MobileAdminLocationController::class, 'show']);
            Route::put('/locations/{id}', [MobileAdminLocationController::class, 'update']);
            Route::delete('/locations/{id}', [MobileAdminLocationController::class, 'destroy']);

            Route::get('/settings', [MobileAdminSettingsController::class, 'show']);
            Route::put('/settings', [MobileAdminSettingsController::class, 'update']);

            Route::get('/templates', [MobileAdminTemplateController::class, 'index']);

            Route::get('/weekly-overview', [MobileAdminWeeklyOverviewController::class, 'index']);
        });
    });
});
