<?php
namespace App\Optimization;
use Illuminate\Support\Str;
class RecommendationValidator {
 public function normalize(array $proposed,array $evidence):array {
  $validIds=array_fill_keys(array_map(fn($p)=>(int)$p['publication_id'],$evidence['posts']),true);
  $out=[];$limit=max(1,min(10,(int)config('optimization.max_recommendations',5)));
  foreach($proposed as $item){
   if(!is_array($item))continue;
   $ids=array_values(array_unique(array_filter(array_map('intval',(array)($item['evidence_publication_ids']??[])),fn($id)=>isset($validIds[$id]))));
   if(!$ids)continue;
   $title=trim((string)($item['title']??''));$rationale=trim((string)($item['rationale']??''));$change=trim((string)($item['proposed_change']??''));
   if(!$title || !$rationale || !$change)continue;
   $out[]=['recommendation_key'=>'r'.(count($out)+1),'category'=>in_array(($item['category']??''),['content','timing','channel','creative','cta','testing'],true)?$item['category']:'testing',
    'title'=>mb_substr($title,0,250),'rationale'=>mb_substr($rationale,0,4000),'proposed_change'=>mb_substr($change,0,4000),
    'priority'=>in_array(($item['priority']??''),['low','medium','high'],true)?$item['priority']:'medium',
    'confidence'=>'low','evidence_publication_ids'=>$ids,
    'supporting_metrics'=>['evidence_count'=>count($ids),'metric_definition'=>$evidence['metric_definition']]];
   if(count($out)>=$limit)break;
  }
  return $out;
 }
}
