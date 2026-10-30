<?php
use App\Http\Controllers\Workflow\ApprovalScheduleController;
use Illuminate\Support\Facades\Route;
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/approvals', [ApprovalScheduleController::class,'approvals'])->name('workflow.approvals');
    Route::get('/schedule', [ApprovalScheduleController::class,'schedule'])->name('workflow.schedule');
    Route::post('/approvals/content/{contentAsset}/revision', [ApprovalScheduleController::class,'requestRevision'])->middleware('throttle:15,1')->name('workflow.revision');
    Route::patch('/schedule/publications/{publication}/reschedule', [ApprovalScheduleController::class,'reschedule'])->middleware('throttle:15,1')->name('workflow.reschedule');
});
