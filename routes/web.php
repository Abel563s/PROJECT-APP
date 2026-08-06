<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SystemSettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\MaterialProgressController;
use App\Http\Controllers\Admin\ProjectSiteController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard - accessible by all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/data', [AdminDashboardController::class, 'data'])->name('dashboard.data');

        // Project Management
        Route::get('/projects/closeout', [\App\Http\Controllers\ProjectController::class, 'closeoutIndex'])->name('projects.closeout.index');
        Route::get('/projects/{project}/closeout', [\App\Http\Controllers\ProjectController::class, 'closeoutShow'])->name('projects.closeout.show');
        Route::get('/projects/analytics', [\App\Http\Controllers\ProjectController::class, 'analytics'])->name('projects.analytics');
        Route::get('/projects/issues', [\App\Http\Controllers\ProjectController::class, 'issuesIndex'])->name('projects.issues.index');
        Route::get('/projects/ror', [\App\Http\Controllers\ProjectController::class, 'rorIndex'])->name('projects.ror.index');
        Route::get('/projects/sep', [\App\Http\Controllers\ProjectController::class, 'sepIndex'])->name('projects.sep.index');

        // Project Payments
        Route::get('/projects/payments', [\App\Http\Controllers\Admin\ProjectPaymentController::class, 'index'])->name('projects.payments.index');
        Route::get('/projects/{project}/payments', [\App\Http\Controllers\Admin\ProjectPaymentController::class, 'manage'])->name('projects.payments.manage');
        Route::post('/projects/{project}/payments', [\App\Http\Controllers\Admin\ProjectPaymentController::class, 'store'])->name('projects.payments.store');
        Route::get('/payments/{payment}', [\App\Http\Controllers\Admin\ProjectPaymentController::class, 'show'])->name('projects.payments.show');

        Route::resource('projects', \App\Http\Controllers\ProjectController::class);
        Route::patch('/projects/{project}/toggle-status', [\App\Http\Controllers\ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
        Route::get('/projects/next-code', [\App\Http\Controllers\ProjectController::class, 'nextCode'])->name('projects.next-code');
        Route::get('/projects/issues', [\App\Http\Controllers\ProjectController::class, 'issuesIndex'])->name('projects.issues.index');
        Route::get('/projects/issues/report', [\App\Http\Controllers\ProjectController::class, 'issuesReport'])->name('projects.issues.report');
        Route::get('/projects/issues/create-modal', [\App\Http\Controllers\ProjectController::class, 'issuesIndex'])->name('projects.issues.create-modal');
        Route::post('/projects/issues', [\App\Http\Controllers\ProjectController::class, 'issuesStore'])->name('projects.issues.store');
        Route::get('/projects/issues/{issue}', [\App\Http\Controllers\ProjectController::class, 'issuesShow'])->name('projects.issues.show');
        Route::get('/projects/issues/{issue}/edit', [\App\Http\Controllers\ProjectController::class, 'issuesEdit'])->name('projects.issues.edit');
        Route::put('/projects/issues/{issue}', [\App\Http\Controllers\ProjectController::class, 'issuesUpdate'])->name('projects.issues.update');
        Route::delete('/projects/issues/{issue}', [\App\Http\Controllers\ProjectController::class, 'issuesDestroy'])->name('projects.issues.destroy');
        Route::get('/projects/search', [\App\Http\Controllers\ProjectController::class, 'search'])->name('projects.search');
        Route::resource('progress-updates', \App\Http\Controllers\Admin\ProjectProgressUpdateController::class);
        Route::resource('weekly-updates', \App\Http\Controllers\Admin\ProjectWeeklyUpdateController::class);

        // Material Tracker
        Route::resource('material', MaterialController::class);
        Route::get('material-progress', [MaterialProgressController::class, 'index'])->name('material-progress.index');
        Route::get('material-progress/{material}', [MaterialProgressController::class, 'show'])->name('material-progress.show');
        Route::get('material-report', [MaterialProgressController::class, 'report'])->name('material.report');
        Route::get('projects/search', [\App\Http\Controllers\ProjectController::class, 'search'])->name('projects.search');
        Route::resource('project-sites', ProjectSiteController::class)->only(['index','create','store','update','destroy']);

        // System Settings
        Route::get('/settings', [SystemSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SystemSettingController::class, 'updateSetting'])->name('settings.update');
        Route::patch('/settings/profile', [SystemSettingController::class, 'updateProfile'])->name('settings.profile.update');
        Route::put('/settings/password', [SystemSettingController::class, 'updatePassword'])->name('settings.password.update');

        // User Management / Access Control
        Route::resource('users', AdminUserController::class);
        Route::get('/users/{user}/password-modal', [AdminUserController::class, 'passwordModal'])->name('users.password.modal');
        Route::get('/roles', [AdminUserController::class, 'roles'])->name('roles.index');

    });

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
});
