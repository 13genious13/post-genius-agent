<?php




namespace App\AI\Tools;




use App\AI\Tools\Contracts\AgentToolInterface;
use App\Models\AgentRun;




class GetBusinessProfileTool implements AgentToolInterface
{
    public function key(): string
    {
        return 'get_business_profile';
    }




    public function name(): string
    {
        return 'Get Business Profile';
    }




    public function description(): string
    {
        return 'Returns the business profile and available brand intelligence.';
    }




    public function execute(
        AgentRun $run,
        array $arguments = []
    ): array {
        $business = $run
            ->business()
            ->with([
                'productsServices',
                'audiences',
                'competitors',
                'latestIntelligence',
            ])
            ->firstOrFail();




        return [
            'id' => $business->id,
            'name' => $business->name,
            'industry' => $business->industry,
            'website' => $business->website,
            'description' => $business->description,




            'products_services' =>
                $business
                    ->productsServices
                    ->map(fn ($item) => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' =>
                            $item->description,
                    ])
                    ->values()
                    ->all(),




            'audiences' =>
                $business
                    ->audiences
                    ->map(fn ($item) => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' =>
                            $item->description,
                    ])
                    ->values()
                    ->all(),




            'competitors' =>
                $business
                    ->competitors
                    ->map(fn ($item) => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'website' =>
                            $item->website,
                    ])
                    ->values()
                    ->all(),




            'intelligence' =>
                $business->latestIntelligence
                    ? [
                    'id' =>
                        $business
                            ->latestIntelligence
                            ->id,




                    'summary' =>
                        $business
                            ->latestIntelligence
                            ->executive_summary,
                ]
                    : null,
        ];
    }
}
