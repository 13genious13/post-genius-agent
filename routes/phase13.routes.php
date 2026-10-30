<?php
use App\Http\Controllers\MarketingCycleController;
use Illuminate\Support\Facades\Route;
// Include inside existing auth + verified middleware group; see INTEGRATION.md.
Route::get('/businesses/{business}/campaigns/{campaign}/agent-loop',[MarketingCycleController::class,'index'])->name('agent-loop.index');
Route::post('/businesses/{business}/campaigns/{campaign}/agent-loop',[MarketingCycleController::class,'store'])->middleware('throttle:5,1')->name('agent-loop.store');
Route::get('/businesses/{business}/campaigns/{campaign}/agent-loop/{cycle}',[MarketingCycleController::class,'show'])->name('agent-loop.show');
Route::post('/businesses/{business}/campaigns/{campaign}/agent-loop/{cycle}/refresh',[MarketingCycleController::class,'refresh'])->name('agent-loop.refresh');
Route::post('/businesses/{business}/campaigns/{campaign}/agent-loop/{cycle}/actions',[MarketingCycleController::class,'requestAction'])->middleware('throttle:10,1')->name('agent-loop.actions.request');
Route::post('/businesses/{business}/campaigns/{campaign}/agent-loop/actions/{actionRequest}/execute',[MarketingCycleController::class,'execute'])->middleware('throttle:10,1')->name('agent-loop.actions.execute');
