<?php

namespace App\Providers;

use App\AI\Services\AIManager;
use App\AI\Services\AIUsageLogger;
use Illuminate\Support\ServiceProvider;
use App\Research\Providers\TavilySearchProvider;
use App\Research\Services\ResearchEvidenceBuilder;
use App\Research\Services\WebSearchManager;
use App\AI\Tools\ToolRegistry;
use App\AI\Tools\GetBusinessProfileTool;
use App\AI\Tools\GetCampaignTool;
use App\AI\Tools\GetResearchTool;
use App\AI\Tools\GetStrategyTool;
use App\AI\Tools\GetStrategyActionsTool;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            AIUsageLogger::class
        );

        $this->app->singleton(
            AIManager::class
        );

        $this->app->singleton(
            TavilySearchProvider::class
        );

        $this->app->singleton(
            WebSearchManager::class
        );

        $this->app->singleton(
            ResearchEvidenceBuilder::class
        );

        $this->app->singleton(
            ToolRegistry::class,
            function ($app) {
                $registry = new ToolRegistry();




                $registry
                    ->register(
                        $app->make(
                            GetBusinessProfileTool::class
                        )
                    )
                    ->register(
                        $app->make(
                            GetCampaignTool::class
                        )
                    )
                    ->register(
                        $app->make(
                            GetResearchTool::class
                        )
                    )
                    ->register(
                        $app->make(
                            GetStrategyTool::class
                        )
                    )
                    ->register(
                        $app->make(
                            GetStrategyActionsTool::class
                        )
                    );
                return $registry;
            }
        );

        $this->app->singleton(\App\AI\Content\ContentAgent::class);


    }

    public function boot(): void
    {
        //
    }
}
