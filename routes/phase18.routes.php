<?php

use App\Http\Controllers\Insights\ActivityController;
use App\Http\Controllers\Insights\AIUsageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('/activity/runs/{run}', [ActivityController::class, 'run'])->name('activity.runs.show');
    Route::get('/ai-usage', [AIUsageController::class, 'index'])->name('ai-usage.index');
    Route::get('/ai-usage/export', [AIUsageController::class, 'export'])->middleware('throttle:10,1')->name('ai-usage.export');
});
