<?php
// Include inside existing authenticated + active-user middleware group.
use App\Http\Controllers\AgentGovernanceController;
use Illuminate\Support\Facades\Route;
Route::get('/businesses/{business}/agent-governance',[AgentGovernanceController::class,'index'])->name('agent-governance.index');
Route::put('/businesses/{business}/agent-governance',[AgentGovernanceController::class,'update'])->name('agent-governance.update');
Route::post('/businesses/{business}/agent-governance/requests',[AgentGovernanceController::class,'createRequest'])->middleware('throttle:10,1')->name('agent-governance.requests.store');
Route::post('/businesses/{business}/agent-governance/requests/{actionRequest}/decide',[AgentGovernanceController::class,'decide'])->middleware('throttle:20,1')->name('agent-governance.requests.decide');
