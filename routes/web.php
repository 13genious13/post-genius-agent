<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AIPlaygroundController;

require __DIR__.'/public_site.php';

Route::get('/coming-soon/{module}', function (string $module) {

    $title=str($module)
        ->replace('-', ' ')
        ->title()
        ->toString();

    return view('coming-soon', [
        'title' => $title,
    ]);

})->name('coming-soon');


Route::middleware(['auth', 'verified',])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/ai/playground', [AIPlaygroundController::class, 'index'])->name('ai.playground');
    Route::post('/ai/playground', [AIPlaygroundController::class, 'generate'])->middleware('throttle:20,1')->name('ai.playground.generate');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
