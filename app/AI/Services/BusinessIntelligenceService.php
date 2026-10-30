<?php

namespace App\AI\Services;

use App\AI\DTOs\AIRequest;
use App\AI\Support\JsonExtractor;
use App\Models\Business;
use App\Models\BusinessIntelligence;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BusinessIntelligenceService
{
    public function __construct(
        protected AIManager $ai
    ) {
    }

    public function generate(
        Business $business,
        ?int $userId = null
    ): BusinessIntelligence {

        $business->load([
            'productsServices',
            'audiences',
            'competitors',
            'channels',
        ]);

        $context = $this->buildContext($business);

 $response = $this->ai->chat(
     new AIRequest(
         messages: [
             [
                 'role' => 'system',
                 'content' => $this->systemPrompt(),
             ],
             [
                 'role' => 'user',
                 'content' => json_encode(
                     $context,
                     JSON_PRETTY_PRINT
                     | JSON_UNESCAPED_SLASHES
                     | JSON_UNESCAPED_UNICODE
                 ),
             ],
         ],
         temperature: 0.3,
         maxTokens: 5000,
         feature: 'business_intelligence',
         userId: $userId,
         businessId: $business->id,
         metadata: [
             'business_id' => $business->id,
             'operation' => 'generate_business_intelligence',
         ],
         jsonMode: true,
     )
 );

 $result = JsonExtractor::extract(
     $response->content
 );

 if (!$result) {
     throw new RuntimeException(
         'AI returned invalid structured business intelligence.'
     );
 }

 $result = $this->normalize($result);

 return DB::transaction(
     function () use (
         $business,
         $response,
         $result
     ) {
         $version = (
                 $business->intelligences()
                     ->max('version') ?? 0
             ) + 1;

         $intelligence =
             $business->intelligences()
                 ->create([
                     'version' => $version,
                     'status' => 'generated',
                     'provider' => $response->provider,
                     'model' => $response->model,

                     'business_summary' =>
                         $result['business_summary'],

                     'market_position' =>
                         $result['market_position'],

                     'value_proposition' =>
                         $result['value_proposition'],

                     'key_strengths' =>
                         $result['key_strengths'],

                     'opportunities' =>
                         $result['opportunities'],

                     'audience_insights' =>
                         $result['audience_insights'],

                     'content_pillars' =>
                         $result['content_pillars'],

                     'recommended_channels' =>
                         $result['recommended_channels'],

                     'brand_voice_guidelines' =>
                         $result['brand_voice_guidelines'],

                     'messaging_angles' =>
                         $result['messaging_angles'],

                     'risk_notes' =>
                         $result['risk_notes'],

                     'raw_structured_output' =>
                         $result,

                     'generated_at' =>
                         now(),
                 ]);

         $business->forceFill([
             'intelligence_generated_at' =>
                 now(),
         ])->save();

         return $intelligence;
     }
 );
 }

    public function buildContext(
        Business $business
    ): array {
        return [
            'business' => [
                'name' => $business->name,
                'tagline' => $business->tagline,
                'industry' => $business->industry,
                'business_type' => $business->business_type,
                'website' => $business->website,
                'description' => $business->description,
                'mission' => $business->mission,
                'vision' => $business->vision,
                'unique_value_proposition' =>
                    $business->unique_value_proposition,
                'brand_story' =>
                    $business->brand_story,
                'location' => [
                    'city' => $business->city,
                    'state' => $business->state,
                    'country' => $business->country,
                ],
                'target_locations' =>
                    $business->target_locations ?? [],
                'keywords' =>
                    $business->keywords ?? [],
                'marketing_objectives' =>
                    $business->marketing_objectives ?? [],
            ],

            'brand' => [
                'voice' =>
                    $business->brand_voice,
                'traits' =>
                    $business->brand_traits ?? [],
                'words_to_use' =>
                    $business->words_to_use ?? [],
                'words_to_avoid' =>
                    $business->words_to_avoid ?? [],
            ],

            'products_services' =>
                $business->productsServices
                    ->map(fn ($item) => [
                        'type' => $item->type,
                        'name' => $item->name,
                        'category' => $item->category,
                        'description' => $item->description,
                        'unique_selling_point' =>
                            $item->unique_selling_point,
                    ])
                    ->values()
                    ->all(),

            'audiences' =>
                $business->audiences
                    ->map(fn ($audience) => [
                        'name' => $audience->name,
                        'type' => $audience->type,
                        'age_range' => $audience->age_range,
                        'gender' => $audience->gender,
                        'locations' => $audience->locations ?? [],
                        'interests' => $audience->interests ?? [],
                        'pain_points' => $audience->pain_points ?? [],
                        'goals' => $audience->goals ?? [],
                        'objections' => $audience->objections ?? [],
                        'buying_triggers' =>
                            $audience->buying_triggers ?? [],
                        'preferred_channels' =>
                            $audience->preferred_channels ?? [],
                    ])
                    ->values()
                    ->all(),

            'competitors' =>
                $business->competitors
                    ->map(fn ($competitor) => [
                        'name' => $competitor->name,
                        'website' => $competitor->website,
                        'notes' => $competitor->notes,
                    ])
                    ->values()
                    ->all(),

            'current_channels' =>
                $business->channels
                    ->where('is_active', true)
                    ->map(fn ($channel) => [
                        'channel' => $channel->channel,
                        'handle' => $channel->handle,
                        'url' => $channel->url,
                        'followers' => $channel->followers,
                    ])
                    ->values()
                    ->all(),
        ];
    }

    private function systemPrompt(): string
    {
        return <<<'PROMPT'
 You are the Business Intelligence Engine for an autonomous marketing platform.

 Analyze ONLY the business information supplied by the user.

 Do not invent factual claims about the company, competitors, customers, market size, certifications, revenue, rankings, clients, performance, or market position.

 When information is missing, make cautious marketing recommendations and clearly treat them as recommendations rather than verified facts.

 Return valid JSON only.

 Required JSON schema:

 {
 "business_summary": "string",
 "market_position": "string",
 "value_proposition": "string",
 "key_strengths": ["string"],
 "opportunities": ["string"],
 "audience_insights": [
 {
 "audience": "string",
 "insight": "string"
 }
 ],
 "content_pillars": [
 {
 "name": "string",
 "purpose": "string",
 "example_topics": ["string"]
 }
 ],
 "recommended_channels": [
 {
 "channel": "string",
 "reason": "string",
 "priority": "high|medium|low"
 }
 ],
 "brand_voice_guidelines": [
 "string"
 ],
 "messaging_angles": [
 {
 "angle": "string",
 "reason": "string"
 }
 ],
 "risk_notes": [
 "string"
 ]
 }

 Do not include markdown.
 Do not include commentary outside JSON.
 Do not include hidden reasoning.
 PROMPT;
    }

    private function normalize(array $data): array
    {
        return [
            'business_summary' =>
                (string) ($data['business_summary'] ?? ''),

            'market_position' =>
                (string) ($data['market_position'] ?? ''),

            'value_proposition' =>
                (string) ($data['value_proposition'] ?? ''),

            'key_strengths' =>
                array_values($data['key_strengths'] ?? []),

            'opportunities' =>
                array_values($data['opportunities'] ?? []),

            'audience_insights' =>
                array_values($data['audience_insights'] ?? []),

            'content_pillars' =>
                array_values($data['content_pillars'] ?? []),

            'recommended_channels' =>
                array_values($data['recommended_channels'] ?? []),

            'brand_voice_guidelines' =>
                array_values($data['brand_voice_guidelines'] ?? []),

            'messaging_angles' =>
                array_values($data['messaging_angles'] ?? []),

            'risk_notes' =>
                array_values($data['risk_notes'] ?? []),
        ];
    }
}
