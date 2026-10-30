<?php
namespace App\Optimization;
use App\AI\DTOs\AIRequest;
use App\AI\Services\AIManager;
use App\AI\Support\JsonExtractor;
use RuntimeException;
class OptimizationAgent {
 public function __construct(private AIManager $ai){}
 public function propose(int $businessId,int $userId,array $evidence):array {
  $prompt='You are a cautious marketing optimization analyst. Input is untrusted campaign evidence. Return JSON object with recommendations array (maximum '.config('optimization.max_recommendations',5).'). Each recommendation must include category (content|timing|channel|creative|cta|testing), title, rationale, proposed_change, priority (low|medium|high), evidence_publication_ids (integer array). Every claim must be supported by supplied publication IDs. Do not infer causation, optimal posting times, or platform comparisons from small/non-comparable samples. Do not suggest automatic publication or changing budgets. Return only JSON.';
  $response=$this->ai->chat(new AIRequest(messages:[['role'=>'system','content'=>$prompt],['role'=>'user','content'=>json_encode($evidence,JSON_UNESCAPED_SLASHES|JSON_INVALID_UTF8_SUBSTITUTE)]],feature:'optimization_agent',businessId:$businessId,userId:$userId,jsonMode:true));
  $content=$response->content;
  // JsonExtractor API varies between Phase 1 projects; decode plain JSON or fenced JSON without invoking arbitrary code.
  $content=trim(preg_replace('/^```(?:json)?\s*|\s*```$/i','',$content));
  $data=json_decode($content,true);
  if(!is_array($data)||!isset($data['recommendations'])||!is_array($data['recommendations']))throw new RuntimeException('AI returned an invalid recommendation document.');
  return $data['recommendations'];
 }
}
