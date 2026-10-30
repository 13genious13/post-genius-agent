<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\Business\AudienceController;
use App\Http\Controllers\Business\BrandController;
use App\Http\Controllers\Business\BusinessIntelligenceController;
use App\Http\Controllers\Business\CompetitorController;
use App\Http\Controllers\Business\ProductServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignBriefController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\MarketingGoalController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\AgentCenterController;
use App\Http\Controllers\AgentRunController;
use App\Http\Controllers\AgentControlController;
use App\Http\Controllers\ContentLibraryController;
use App\Http\Controllers\ContentTaskController;
use App\Http\Controllers\ContentAgentWorkspaceController;
use App\Http\Controllers\CreativeLibraryController;
use App\Http\Controllers\SocialConnectionController;
use App\Http\Controllers\SocialPublicationController;
use App\Http\Controllers\CreativeAgentWorkspaceController;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::resource('businesses', BusinessController::class);
    Route::prefix('businesses/{business}')->name('businesses.')->group(function () {
            Route::get('/products-services', [ProductServiceController::class, 'index'])->name('products.index');
            Route::post('/products-services', [ProductServiceController::class, 'store'])->name('products.store');
            Route::put('/products-services/{item}', [ProductServiceController::class, 'update'])->name('products.update');
            Route::delete('/products-services/{item}', [ProductServiceController::class, 'destroy'])->name('products.destroy');
            Route::get('/audiences', [AudienceController::class, 'index'])->name('audiences.index');
            Route::post('/audiences', [AudienceController::class, 'store'])->name('audiences.store');
            Route::delete('/audiences/{audience}', [AudienceController::class, 'destroy'])->name('audiences.destroy');
            Route::get('/competitors', [CompetitorController::class, 'index'])->name('competitors.index');
            Route::post('/competitors', [CompetitorController::class, 'store'])->name('competitors.store');
            Route::delete('/competitors/{competitor}', [CompetitorController::class, 'destroy'])->name('competitors.destroy');
            Route::get('/brand', [BrandController::class, 'edit'])->name('brand.edit');
            Route::put('/brand', [BrandController::class, 'update'])->name('brand.update');
            Route::get('/intelligence', [BusinessIntelligenceController::class, 'show'])->name('intelligence.show');
            Route::post('/intelligence/generate', [BusinessIntelligenceController::class, 'generate'])->middleware('throttle:5,1')->name('intelligence.generate');
        });
    Route::resource('marketing-goals', MarketingGoalController::class);
    Route::resource('campaigns', CampaignController::class);
    Route::get('/campaigns/{campaign}/brief', [CampaignBriefController::class, 'show'])->name('campaigns.brief.show');
    Route::post('/campaigns/{campaign}/brief/generate', [CampaignBriefController::class, 'generate'])->middleware('throttle:5,1')->name('campaigns.brief.generate');

    Route::get('/research', [ResearchController::class, 'index'])->name('research.index');
    Route::get('/campaigns/{campaign}/research', [ResearchController::class, 'campaign'])->name('research.campaign');
    Route::post('/campaigns/{campaign}/research', [ResearchController::class, 'start'])->middleware('throttle:5,1')->name('research.start');
    Route::get('/research/{researchRun}', [ResearchController::class, 'show'])->name('research.show');
    Route::get('/research/{researchRun}/sources', [ResearchController::class, 'sources'])->name('research.sources');

    Route::get('/strategies', [StrategyController::class, 'index',])->name('strategies.index');
    Route::get('/campaigns/{campaign}/strategy', [StrategyController::class, 'campaign',])->name('strategies.campaign');
    Route::post('/campaigns/{campaign}/strategy', [StrategyController::class, 'generate',])->middleware('throttle:5,1')->name('strategies.generate');
    Route::get('/strategies/{strategy}', [StrategyController::class, 'show',])->name('strategies.show');
    Route::get('/strategies/{strategy}/evidence', [StrategyController::class, 'evidence',])->name('strategies.evidence');

    Route::get('/agent-center', [AgentCenterController::class, 'index',])->name('agent-center.index');
    Route::get('/campaigns/{campaign}/agent', [AgentRunController::class, 'create',])->name('agent-runs.create');
    Route::post('/campaigns/{campaign}/agent', [AgentRunController::class, 'store',])->middleware('throttle:5,1')->name('agent-runs.store');
    Route::get('/agent-runs/{agentRun}', [AgentRunController::class, 'show',])->name('agent-runs.show');
    Route::get('/businesses/{business}/agent-controls', [AgentControlController::class, 'edit',])->name('agent-controls.edit');
    Route::put('/businesses/{business}/agent-controls', [AgentControlController::class, 'update',])->name('agent-controls.update');

    Route::get('/content-agent', [ContentAgentWorkspaceController::class, 'index'])->name('content-agent.index');
    Route::post('/content-agent/tasks', [ContentAgentWorkspaceController::class, 'store'])->middleware('throttle:10,1')->name('content-agent.store');
    Route::put('/content-library/{contentAsset}/edit', [ContentAgentWorkspaceController::class, 'update'])->name('content-agent.update');
    Route::get('/content-library', [ContentLibraryController::class, 'index'])->name('content-library.index');
    Route::get('/content-library/{contentAsset}', [ContentLibraryController::class, 'show'])->name('content-library.show');
    Route::post('/agent-tasks/{agentTask}/generate-content', [ContentTaskController::class, 'generate'])->middleware('throttle:10,1')->name('agent-tasks.generate-content');
    Route::post('/content-library/{contentAsset}/approve', [ContentLibraryController::class, 'approve'])->name('content-library.approve');
    Route::post('/content-library/{contentAsset}/reject', [ContentLibraryController::class, 'reject'])->name('content-library.reject');

    Route::get('/creative-library', [CreativeLibraryController::class,'index'])->name('creative-library.index');
    Route::get('/creative-library/{creativeAsset}', [CreativeLibraryController::class,'show'])->name('creative-library.show');
    Route::get('/creative-library/{creativeAsset}/image', [CreativeLibraryController::class,'image'])->name('creative-library.image');
    Route::post('/content-library/{contentAsset}/generate-creative', [CreativeLibraryController::class,'generate'])->middleware('throttle:5,1')->name('creative-library.generate');
    Route::post('/creative-library/{creativeAsset}/decision', [CreativeLibraryController::class,'decide'])->middleware('throttle:10,1')->name('creative-library.decide');

    Route::get('/businesses/{business}/social-accounts',[SocialConnectionController::class,'index'])->name('social.accounts.index');
    Route::get('/businesses/{business}/social-connect',[SocialConnectionController::class,'connect'])->name('social.accounts.connect');
    Route::get('/social/meta/callback',[SocialConnectionController::class,'callback'])->name('social.meta.callback');
    Route::delete('/businesses/{business}/social-accounts/{account}',[SocialConnectionController::class,'disconnect'])->name('social.accounts.disconnect');
    Route::get('/businesses/{business}/publishing',[SocialPublicationController::class,'index'])->name('social.publications.index');
    Route::get('/content-library/{contentAsset}/publish',[SocialPublicationController::class,'create'])->name('social.publications.create');
    Route::post('/content-library/{contentAsset}/publish',[SocialPublicationController::class,'store'])->middleware('throttle:10,1')->name('social.publications.store');
    Route::get('/publishing/{publication}',[SocialPublicationController::class,'show'])->name('social.publications.show');
    Route::post('/publishing/{publication}/approve',[SocialPublicationController::class,'approve'])->middleware('throttle:10,1')->name('social.publications.approve');
    Route::post('/publishing/{publication}/schedule',[SocialPublicationController::class,'schedule'])->middleware('throttle:10,1')->name('social.publications.schedule');
    Route::post('/publishing/{publication}/cancel',[SocialPublicationController::class,'cancel'])->name('social.publications.cancel');

    require __DIR__.'/phase10.routes.php';
    require __DIR__.'/phase11.routes.php';
    require __DIR__.'/phase12.routes.php';
    require __DIR__.'/phase13.routes.php';
    require __DIR__.'/phase14.routes.php';

    Route::get('/creative-agent', [CreativeAgentWorkspaceController::class, 'index'])->name('creative-agent.index');
    Route::post('/creative-agent/generate', [CreativeAgentWorkspaceController::class, 'generate'])->middleware('throttle:5,1')->name('creative-agent.generate');

    require __DIR__.'/phase18.routes.php';
    require __DIR__.'/phase19.routes.php';
    require __DIR__.'/phase20.routes.php';
});
