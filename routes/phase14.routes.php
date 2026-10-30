<?php
use App\Http\Controllers\InternalHealthController;
use Illuminate\Support\Facades\Route;
Route::get('/internal/agent-health',InternalHealthController::class)->middleware('throttle:30,1')->name('agent.internal-health');
