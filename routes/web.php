<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TaskListController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Always show welcome page for web browsers
    return view('welcome');
})->name('welcome');

// CSRF token refresh route (for handling expired sessions)
Route::get('/refresh-csrf', function () {
    return response()->json(['token' => csrf_token()]);
})->name('refresh-csrf');

// Public pages (only for web browsers)
Route::get('/features', function () {
    return view('features');
})->name('features');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/help', function () {
    return view('help');
})->name('help');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/status', function () {
    return view('status');
})->name('status');

Route::get('/security', function () {
    return view('security');
})->name('security');

Route::get('/api', function () {
    return view('api');
})->name('api');

Route::get('/integrations', function () {
    return view('integrations');
})->name('integrations');

Route::get('/blog', function () {
    return view('blog');
})->name('blog');

Route::get('/careers', function () {
    return view('careers');
})->name('careers');

Route::get('/documentation', function () {
    return view('documentation');
})->name('documentation');

// Redirect dashboard based on user role
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('employee.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('lists', TaskListController::class);
    Route::resource('lists.tasks', TaskController::class)->shallow();
    Route::resource('users', UserController::class);
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    
    // Additional admin routes
    Route::post('/lists/{list}/assign', [TaskListController::class, 'assign'])->name('lists.assign');
    Route::delete('/assignments/{assignment}', [TaskListController::class, 'removeAssignment'])->name('assignments.destroy');
    Route::get('/submissions', [TaskListController::class, 'submissions'])->name('submissions.index');
    Route::get('/submissions/{submission}', [TaskListController::class, 'showSubmission'])->name('submissions.show');
    Route::post('/submissions/{submission}/review', [TaskListController::class, 'reviewSubmission'])->name('submissions.review');
    Route::post('/submission-tasks/{submissionTask}/reject', [TaskListController::class, 'rejectTask'])->name('submission-tasks.reject');
    Route::post('/submission-tasks/{submissionTask}/redo', [TaskListController::class, 'requestRedo'])->name('submission-tasks.redo');
    
    // Weekly overview and daily sub-lists
    Route::get('/weekly-overview', [TaskListController::class, 'weeklyOverview'])->name('weekly-overview');
    Route::post('/lists/{list}/create-daily-sublists', [TaskListController::class, 'createDailySubLists'])->name('lists.create-daily-sublists');
    Route::post('/lists/{list}/create-day-list', [TaskListController::class, 'createDayList'])->name('lists.create-day-list');
});

// Employee Routes
Route::middleware(['auth', 'verified', 'employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::get('/lists', [SubmissionController::class, 'index'])->name('lists.index');
    Route::get('/lists/{list}', [SubmissionController::class, 'show'])->name('lists.show');
    Route::post('/lists/{list}/start', [SubmissionController::class, 'start'])->name('submissions.start');
    Route::get('/submissions/{submission}', [SubmissionController::class, 'edit'])->name('submissions.edit');
    Route::put('/submissions/{submission}', [SubmissionController::class, 'update'])->name('submissions.update');
    Route::post('/submissions/{submission}/complete', [SubmissionController::class, 'complete'])->name('submissions.complete');
    Route::post('/submissions/{submission}/tasks/{task}', [SubmissionController::class, 'completeTask'])->name('submissions.tasks.complete');
    
    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\Employee\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-read', [App\Http\Controllers\Employee\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\Employee\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [App\Http\Controllers\Employee\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
