<?php
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
// Require from routes/web.php inside the EXISTING auth + active-user middleware group.
Route::get('/businesses/{business}/analytics',[AnalyticsController::class,'index'])->name('analytics.index');
Route::post('/businesses/{business}/analytics/sync',[AnalyticsController::class,'sync'])->middleware('throttle:3,1')->name('analytics.sync');
Route::get('/businesses/{business}/campaigns/{campaign}/analytics',[AnalyticsController::class,'campaign'])->name('analytics.campaign');
Route::post('/businesses/{business}/campaigns/{campaign}/analytics/memory',[AnalyticsController::class,'rebuildMemory'])->middleware('throttle:3,1')->name('analytics.memory.refresh');
