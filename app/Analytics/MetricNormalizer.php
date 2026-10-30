<?php
namespace App\Analytics;
class MetricNormalizer {
 public function normalize(string $platform,array $raw):array {
  $map=$platform==='instagram' ? [
   'reach'=>'reach','likes'=>'likes','comments'=>'comments','shares'=>'shares','saves'=>'saved','interactions'=>'total_interactions','video_views'=>'views'
  ] : [
   'reach'=>'post_impressions_unique','impressions'=>'post_impressions','clicks'=>'post_clicks','video_views'=>'post_video_views'
  ];
  $output=[];
  foreach($map as $column=>$metric){$value=$raw[$metric]??null;$output[$column]=$this->numeric($value);}
  if($platform==='facebook'){
   $reactions=$raw['post_reactions_by_type_total']??null;
   $output['likes']=is_array($reactions)?$this->numeric($reactions['like']??null):null;
   $output['interactions']=null; // Do not synthesize incomplete metrics as provider totals.
  }
  return $output;
 }
 private function numeric(mixed $value):?int {if(!is_numeric($value)|| (float)$value<0)return null;return min(PHP_INT_MAX,(int)$value);}
}
