<?php
use App\Http\Controllers\Phase20\PerformanceDashboardController;
use App\Http\Controllers\Phase20\SystemSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/performance',[PerformanceDashboardController::class,'index'])->name('performance.index');
    Route::get('/performance/export',[PerformanceDashboardController::class,'export'])->middleware('throttle:10,1')->name('performance.export');
});
Route::middleware(['auth','verified','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/system-settings',[SystemSettingsController::class,'index'])->name('system-settings.index');
    Route::put('/system-settings',[SystemSettingsController::class,'update'])->middleware('throttle:10,1')->name('system-settings.update');
});
