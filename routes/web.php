<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OnboardingScreenController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/login');

Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'store']);
});

Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('admin.onboarding.index'))->name('admin.dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('admin.logout');

    Route::get('/onboarding', [OnboardingScreenController::class, 'index'])->name('admin.onboarding.index');
    Route::get('/onboarding/create', [OnboardingScreenController::class, 'create'])->name('admin.onboarding.create');
    Route::post('/onboarding', [OnboardingScreenController::class, 'store'])->name('admin.onboarding.store');
    Route::get('/onboarding/{onboarding_screen}', [OnboardingScreenController::class, 'show'])->name('admin.onboarding.show');
    Route::get('/onboarding/{onboarding_screen}/edit', [OnboardingScreenController::class, 'edit'])->name('admin.onboarding.edit');
    Route::put('/onboarding/{onboarding_screen}', [OnboardingScreenController::class, 'update'])->name('admin.onboarding.update');
    Route::delete('/onboarding/{onboarding_screen}', [OnboardingScreenController::class, 'destroy'])->name('admin.onboarding.destroy');
    Route::post('/onboarding/{onboarding_screen}/move-up', [OnboardingScreenController::class, 'moveUp'])->name('admin.onboarding.move-up');
    Route::post('/onboarding/{onboarding_screen}/move-down', [OnboardingScreenController::class, 'moveDown'])->name('admin.onboarding.move-down');
});
