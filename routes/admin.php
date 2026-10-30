<?php

use App\Http\Controllers\Admin\AIInfrastructureController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserManagementController;

Route::middleware(['auth', 'admin',])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
        Route::get('/ai-infrastructure', [AIInfrastructureController::class, 'index'])->name('ai.infrastructure');
        Route::post('/ai-infrastructure/{provider}/test', [AIInfrastructureController::class, 'test'])->name('ai.infrastructure.test');
    });
