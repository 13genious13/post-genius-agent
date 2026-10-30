<?php
// REQUIRE inside the application's existing auth + verified/active user middleware group.
use App\Http\Controllers\OptimizationController;
use Illuminate\Support\Facades\Route;
Route::get('/businesses/{business}/campaigns/{campaign}/optimization',[OptimizationController::class,'index'])->name('optimization.index');
Route::post('/businesses/{business}/campaigns/{campaign}/optimization',[OptimizationController::class,'store'])->middleware('throttle:3,10')->name('optimization.store');
Route::get('/businesses/{business}/campaigns/{campaign}/optimization/{optimizationRun}',[OptimizationController::class,'show'])->name('optimization.show');
Route::post('/businesses/{business}/campaigns/{campaign}/optimization/recommendations/{recommendation}/review',[OptimizationController::class,'review'])->name('optimization.review');
Route::post('/businesses/{business}/campaigns/{campaign}/optimization/recommendations/{recommendation}/experiment',[OptimizationController::class,'experiment'])->name('optimization.experiment');
