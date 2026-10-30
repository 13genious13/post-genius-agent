<?php
use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class,'page'])->defaults('page','home')->name('public.home');
foreach (['features','how-it-works','pricing','about','faq','contact','privacy','terms'] as $page) {
    Route::get('/'.$page, [PublicSiteController::class,'page'])->defaults('page',$page)->name('public.'.$page);
}
Route::post('/contact', [PublicSiteController::class,'contactSubmit'])->middleware('throttle:5,10')->name('public.contact.submit');
Route::get('/sitemap.xml', [PublicSiteController::class,'sitemap'])->name('public.sitemap');
Route::get('/robots.txt', [PublicSiteController::class,'robots'])->name('public.robots');
