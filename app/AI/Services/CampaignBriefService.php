<?php




namespace App\AI\Services;




use App\AI\DTOs\AIRequest;
use App\AI\Support\JsonExtractor;
use App\Models\Campaign;
use App\Models\CampaignBrief;
use Illuminate\Support\Facades\DB;
use RuntimeException;




class CampaignBriefService
{
    public function __construct(
        protected AIManager $ai
    ) {
    }




    public function generate(
        Campaign $campaign,
        ?int $userId = null
    ): CampaignBrief {
        $campaign->load([
            'business.productsServices',
            'business.audiences',
            'business.competitors',
            'business.latestIntelligence',
            'marketingGoal',
        ]);




        $context =
            $this->buildContext(
                $campaign
            );




        $response =
            $this->ai->chat(
                new AIRequest(
                    messages: [
                        [
                            'role' => 'system',
                            'content' =>
                                $this->systemPrompt(),
                        ],
                        [
                            'role' => 'user',
                            'content' =>
                                json_encode(
                                    $context,
                                    JSON_PRETTY_PRINT
                                    | JSON_UNESCAPED_SLASHES
                                    | JSON_UNESCAPED_UNICODE
                                ),
                        ],
                    ],




                    temperature: 0.35,




                    maxTokens: 6000,




                    feature:
                    'campaign_brief',




                    userId:
                    $userId,




                    businessId:
                    $campaign->business_id,




                    metadata: [
                        'campaign_id' =>
                            $campaign->id,




                        'marketing_goal_id' =>
                            $campaign
                                ->marketing_goal_id,




                        'operation' =>
                            'generate_campaign_brief',
                    ],




                    jsonMode: true,
                )
            );




        $result =
            JsonExtractor::extract(
                $response->content
            );




        if (!$result) {
            throw new RuntimeException(
                'AI returned invalid campaign brief JSON.'
            );
        }




        $result =
            $this->normalize(
                $result
            );




        return DB::transaction(
            function () use (
                $campaign,
                $response,
                $result
            ) {
                $version =
                    (
                        $campaign
                            ->briefs()
                            ->max('version')
                        ?? 0
                    ) + 1;




                $brief =
                    $campaign
                        ->briefs()
                        ->create([
                            'version' =>
                                $version,




                            'status' =>
                                'generated',




                            'provider' =>
                                $response->provider,




                            'model' =>
                                $response->model,




                            'executive_summary' =>
                                $result[
                                'executive_summary'
                                ],




                            'strategic_direction' =>
                                $result[
                                'strategic_direction'
                                ],




                            'core_message' =>
                                $result[
                                'core_message'
                                ],




                            'audience_strategy' =>
                                $result[
                                'audience_strategy'
                                ],




                            'content_themes' =>
                                $result[
                                'content_themes'
                                ],




                            'platform_strategy' =>
                                $result[
                                'platform_strategy'
                                ],




                            'content_formats' =>
                                $result[
                                'content_formats'
                                ],




                            'cta_strategy' =>
                                $result[
                                'cta_strategy'
                                ],




                            'kpi_recommendations' =>
                                $result[
                                'kpi_recommendations'
                                ],




                            'execution_priorities' =>
                                $result[
                                'execution_priorities'
                                ],




                            'risks' =>
                                $result[
                                'risks'
                                ],




                            'assumptions' =>
                                $result[
                                'assumptions'
                                ],




                            'raw_structured_output' =>
                                $result,




                            'generated_at' =>
                                now(),
                        ]);




                $campaign->forceFill([
                    'brief_generated_at' =>
                        now(),
                ])->save();




                return $brief;
            }
        );
    }




    private function buildContext(
        Campaign $campaign
    ): array {
        $business =
            $campaign->business;




        $selectedAudienceIds =
            collect(
                $campaign
                    ->target_audience_ids
                ?? []
            )
                ->map(
                    fn ($id) =>
                    (int) $id
                );




        $selectedAudiences =
            $business
                ->audiences
                ->filter(
                    fn ($audience) =>
                    $selectedAudienceIds
                        ->contains(
                            $audience->id
                        )
                );




        return [
            'business' => [
                'name' =>
                    $business->name,




                'industry' =>
                    $business->industry,




                'description' =>
                    $business->description,




                'unique_value_proposition' =>
                    $business
                        ->unique_value_proposition,




                'brand_voice' =>
                    $business->brand_voice,




                'brand_traits' =>
                    $business->brand_traits
                    ?? [],




                'target_locations' =>
                    $business
                        ->target_locations
                    ?? [],




                'marketing_objectives' =>
                    $business
                        ->marketing_objectives
                    ?? [],
            ],




            'business_intelligence' =>
                $business
                    ->latestIntelligence
                    ? [
                    'business_summary' =>
                        $business
                            ->latestIntelligence
                            ->business_summary,




                    'market_position' =>
                        $business
                            ->latestIntelligence
                            ->market_position,




                    'value_proposition' =>
                        $business
                            ->latestIntelligence
                            ->value_proposition,




                    'key_strengths' =>
                        $business
                            ->latestIntelligence
                            ->key_strengths
                        ?? [],




                    'opportunities' =>
                        $business
                            ->latestIntelligence
                            ->opportunities
                        ?? [],




                    'content_pillars' =>
                        $business
                            ->latestIntelligence
                            ->content_pillars
                        ?? [],
                ]
                    : null,




            'marketing_goal' =>
                $campaign->marketingGoal
                    ? [
                    'name' =>
                        $campaign
                            ->marketingGoal
                            ->name,




                    'goal_type' =>
                        $campaign
                            ->marketingGoal
                            ->goal_type,




                    'description' =>
                        $campaign
                            ->marketingGoal
                            ->description,




                    'primary_kpi' =>
                        $campaign
                            ->marketingGoal
                            ->primary_kpi,




                    'target_value' =>
                        $campaign
                            ->marketingGoal
                            ->target_value,




                    'unit' =>
                        $campaign
                            ->marketingGoal
                            ->unit,




                    'success_definition' =>
                        $campaign
                            ->marketingGoal
                            ->success_definition,
                ]
                    : null,




            'campaign' => [
                'name' =>
                    $campaign->name,




                'type' =>
                    $campaign
                        ->campaign_type,




                'objective' =>
                    $campaign->objective,




                'offer' =>
                    $campaign->offer,




                'call_to_action' =>
                    $campaign
                        ->call_to_action,




                'platforms' =>
                    $campaign->platforms
                    ?? [],




                'target_locations' =>
                    $campaign
                        ->target_locations
                    ?? [],




                'content_formats' =>
                    $campaign
                        ->content_formats
                    ?? [],




                'primary_kpis' =>
                    $campaign
                        ->primary_kpis
                    ?? [],




                'budget' =>
                    $campaign->budget,




                'currency' =>
                    $campaign->currency,




                'start_date' =>
                    $campaign
                        ->start_date
                        ?->toDateString(),




                'end_date' =>
                    $campaign
                        ->end_date
                        ?->toDateString(),




                'brand_constraints' =>
                    $campaign
                        ->brand_constraints,




                'campaign_constraints' =>
                    $campaign
                        ->campaign_constraints,
            ],




            'target_audiences' =>
                $selectedAudiences
                    ->map(
                        fn ($audience) => [
                            'name' =>
                                $audience->name,




                            'description' =>
                                $audience
                                    ->description,




                            'pain_points' =>
                                $audience
                                    ->pain_points
                                ?? [],




                            'goals' =>
                                $audience
                                    ->goals
                                ?? [],




                            'objections' =>
                                $audience
                                    ->objections
                                ?? [],




                            'buying_triggers' =>
                                $audience
                                    ->buying_triggers
                                ?? [],




                            'preferred_channels' =>
                                $audience
                                    ->preferred_channels
                                ?? [],
                        ]
                    )
                    ->values()
                    ->all(),




            'products_services' =>
                $business
                    ->productsServices
                    ->where(
                        'is_active',
                        true
                    )
                    ->map(
                        fn ($item) => [
                            'name' =>
                                $item->name,




                            'type' =>
                                $item->type,




                            'description' =>
                                $item
                                    ->description,




                            'unique_selling_point' =>
                                $item
                                    ->unique_selling_point,
                        ]
                    )
                    ->values()
                    ->all(),
        ];
    }




    private function systemPrompt(): string
    {
        return <<<'PROMPT'
You are the Campaign Strategy Engine for PostGenius Agent.




Your job is to convert the supplied business profile, business intelligence, marketing goal and campaign configuration into an actionable campaign brief.




Use ONLY supplied business facts.




Never invent:
- company achievements
- client names
- certifications
- prices
- guarantees
- competitor facts
- performance statistics
- market share
- awards
- research findings




You may make strategic recommendations, but recommendations must not be presented as verified facts.




This stage does not have live web research. Do not claim that you researched the market or competitors.




Return valid JSON only.




Required schema:




{
"executive_summary": "string",
"strategic_direction": "string",
"core_message": "string",




"audience_strategy": [
{
"audience": "string",
"need": "string",
"message": "string",
"action": "string"
}
],




"content_themes": [
{
"theme": "string",
"purpose": "string",
"example_topics": ["string"]
}
],




"platform_strategy": [
{
"platform": "string",
"role": "string",
"content_approach": "string",
"priority": "high|medium|low"
}
],




"content_formats": [
{
"format": "string",
"purpose": "string"
}
],




"cta_strategy": [
{
"cta": "string",
"use_case": "string"
}
],




"kpi_recommendations": [
{
"kpi": "string",
"reason": "string"
}
],




"execution_priorities": [
"string"
],




"risks": [
"string"
],




"assumptions": [
"string"
]
}




Do not output markdown.
Do not output commentary outside JSON.
Do not provide hidden chain-of-thought.
PROMPT;
    }




    private function normalize(
        array $data
    ): array {
        return [
            'executive_summary' =>
                (string) (
                    $data[
                    'executive_summary'
                    ] ?? ''
                ),




            'strategic_direction' =>
                (string) (
                    $data[
                    'strategic_direction'
                    ] ?? ''
                ),




            'core_message' =>
                (string) (
                    $data[
                    'core_message'
                    ] ?? ''
                ),




            'audience_strategy' =>
                array_values(
                    $data[
                    'audience_strategy'
                    ] ?? []
                ),




            'content_themes' =>
                array_values(
                    $data[
                    'content_themes'
                    ] ?? []
                ),




            'platform_strategy' =>
                array_values(
                    $data[
                    'platform_strategy'
                    ] ?? []
                ),




            'content_formats' =>
                array_values(
                    $data[
                    'content_formats'
                    ] ?? []
                ),




            'cta_strategy' =>
                array_values(
                    $data[
                    'cta_strategy'
                    ] ?? []
                ),




            'kpi_recommendations' =>
                array_values(
                    $data[
                    'kpi_recommendations'
                    ] ?? []
                ),




            'execution_priorities' =>
                array_values(
                    $data[
                    'execution_priorities'
                    ] ?? []
                ),




            'risks' =>
                array_values(
                    $data['risks']
                    ?? []
                ),




            'assumptions' =>
                array_values(
                    $data[
                    'assumptions'
                    ] ?? []
                ),
        ];
    }
}
