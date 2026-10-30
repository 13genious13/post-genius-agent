<?php

namespace App\AI\Content;

use App\AI\DTOs\AIRequest;
use App\AI\Services\AIManager;
use App\AI\Support\JsonExtractor;
use App\Models\AgentTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ContentAgent
{
    public function __construct(private AIManager $ai) {}

    public function generate(AgentTask $task): \App\Models\ContentAsset
    {
        $task->loadMissing('run.campaign.business', 'run.strategy');
        $run = $task->run;
        $campaign = $run?->campaign;
        $business = $campaign?->business;
        if (!$business || $run->business_id !== $business->id || $task->agent_key !== 'content_agent') {
            throw ValidationException::withMessages(['task'=>'Invalid content task context.']);
        }
        $control = $business->agentControl()->firstOrCreate([], ['autonomy_mode'=>'assisted']);
        if (!$control->allow_content_generation) {
            throw ValidationException::withMessages(['task'=>'Content generation is disabled.']);
        }
        $platform = strtolower((string) data_get($task->input, 'platform', 'generic'));
        $allowed = $control->allowed_platforms;
        if (is_array($allowed) && count($allowed) > 0 && $platform !== 'generic' && !in_array($platform, $allowed, true)) {
            throw ValidationException::withMessages(['task'=>'Platform not permitted by agent controls.']);
        }
        $context = [
            'business'=>['name'=>$business->name,'industry'=>$business->industry,'description'=>$business->description,'brand_voice'=>$business->brand_voice,'value_proposition'=>$business->unique_value_proposition,'words_to_avoid'=>$business->words_to_avoid],
            'campaign'=>['name'=>$campaign->name,'objective'=>$campaign->objective,'offer'=>$campaign->offer,'cta'=>$campaign->call_to_action,'constraints'=>$campaign->campaign_constraints],
            'strategy'=>['summary'=>$run->strategy?->executive_summary,'positioning'=>$run->strategy?->positioning,'content_pillars'=>$run->strategy?->content_pillars],
            'task'=>['title'=>$task->title,'description'=>$task->description,'type'=>$task->task_type,'platform'=>$platform,'instructions'=>data_get($task->input,'instructions',[])],
        ];
        $response = $this->ai->chat(new AIRequest(
            messages: [
                ['role'=>'system','content'=>'You are PostGenius Content Agent. Produce accurate marketing copy using ONLY supplied business facts. Never invent statistics, client results, testimonials, prices, guarantees or competitor facts. Respect brand voice and platform. Return a single valid JSON object with keys: title (string), hook (string), caption (string), call_to_action (string), hashtags (array of strings), variants (array of objects with name and caption), creative_brief (object with visual_concept, layout, on_image_text, accessibility_alt). Do not include markdown or internal reasoning.'],
                ['role'=>'user','content'=>json_encode($context, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)],
            ],
            temperature: 0.5,
            maxTokens: 3500,
            feature: 'content_agent',
            userId: $run->started_by,
            businessId: $business->id,
            metadata: ['agent_run_id'=>$run->id,'agent_task_id'=>$task->id,'campaign_id'=>$campaign->id],
            jsonMode: true,
        ));
        $data = JsonExtractor::extract($response->content);
        if (!is_array($data) || !is_string($data['caption'] ?? null) || trim($data['caption']) === '') {
            throw ValidationException::withMessages(['content'=>'Model returned invalid content JSON.']);
        }
        return DB::transaction(function () use ($task,$run,$campaign,$business,$response,$data,$platform) {
            $version = (int) $task->contentAssets()->max('version') + 1;
            return $task->contentAssets()->create([
                'business_id'=>$business->id,'campaign_id'=>$campaign->id,'agent_run_id'=>$run->id,
                'created_by'=>$run->started_by,'version'=>$version,'platform'=>mb_substr($platform,0,40),
                'content_type'=>mb_substr($task->task_type,0,50),'status'=>'draft',
                'title'=>mb_substr((string)($data['title']??$task->title),0,255),
                'hook'=>(string)($data['hook']??''),'caption'=>$data['caption'],
                'call_to_action'=>(string)($data['call_to_action']??''),
                'hashtags'=>array_values(array_filter((array)($data['hashtags']??[]),'is_string')),
                'variants'=>array_values(array_filter((array)($data['variants']??[]),'is_array')),
                'creative_brief'=>(array)($data['creative_brief']??[]),
                'metadata'=>['source'=>'content_agent','requires_approval'=>true],
                'provider'=>$response->provider,'model'=>$response->model,
            ]);
        });
    }
}
