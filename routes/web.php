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

        // Project Management
        Route::get('/projects/closeout', [\App\Http\Controllers\ProjectController::class, 'closeoutIndex'])->name('projects.closeout.index');
        Route::get('/projects/{project}/closeout', [\App\Http\Controllers\ProjectController::class, 'closeoutShow'])->name('projects.closeout.show');
        Route::get('/projects/analytics', [\App\Http\Controllers\ProjectController::class, 'analytics'])->name('projects.analytics');

        // Project Payments
        Route::get('/projects/payments', [\App\Http\Controllers\Admin\ProjectPaymentController::class, 'index'])->name('projects.payments.index');
        Route::get('/projects/{project}/payments', [\App\Http\Controllers\Admin\ProjectPaymentController::class, 'manage'])->name('projects.payments.manage');
        Route::post('/projects/{project}/payments', [\App\Http\Controllers\Admin\ProjectPaymentController::class, 'store'])->name('projects.payments.store');
        Route::get('/payments/{payment}', [\App\Http\Controllers\Admin\ProjectPaymentController::class, 'show'])->name('projects.payments.show');

        Route::resource('projects', \App\Http\Controllers\ProjectController::class);
        Route::resource('progress-updates', \App\Http\Controllers\Admin\ProjectProgressUpdateController::class);
        Route::resource('weekly-updates', \App\Http\Controllers\Admin\ProjectWeeklyUpdateController::class);

        // System Settings
        Route::get('/settings', [SystemSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SystemSettingController::class, 'updateSetting'])->name('settings.update');
        Route::patch('/settings/profile', [SystemSettingController::class, 'updateProfile'])->name('settings.profile.update');
        Route::put('/settings/password', [SystemSettingController::class, 'updatePassword'])->name('settings.password.update');

        // User Management / Access Control
        Route::resource('users', AdminUserController::class);
        Route::get('/roles', [AdminUserController::class, 'index'])->name('roles.index');

    });

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
});
