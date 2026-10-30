<?php
namespace App\Optimization;
use App\Models\OptimizationRun;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
class OptimizationService {
 public function __construct(private EvidenceCollector $collector,private RecommendationValidator $validator,private OptimizationAgent $agent){}
 public function execute(int $runId):void {
  $run=DB::transaction(function()use($runId){
   $r=OptimizationRun::query()->lockForUpdate()->findOrFail($runId);
   if($r->status!=='pending')return null;
   $r->update(['status'=>'running','started_at'=>now()]);return $r;
  });
  if(!$run)return;
  try{
   if(!config('optimization.enabled'))throw new \RuntimeException('Optimization disabled.');
   $evidence=$this->collector->collect((int)$run->business_id,(int)$run->campaign_id);
   $min=max(2,(int)config('optimization.min_posts',3));
   if($evidence['eligible_posts']<$min){$run->update(['status'=>'insufficient_data','eligible_posts'=>$evidence['eligible_posts'],'evidence_snapshot'=>$evidence,'summary'=>['message'=>'Not enough comparable posts for optimization.'],'completed_at'=>now()]);return;}
   $recommendations=config('optimization.use_ai',true)?$this->agent->propose((int)$run->business_id,(int)$run->requested_by,$evidence):[];
   $valid=$this->validator->normalize($recommendations,$evidence);
   DB::transaction(function()use($run,$evidence,$valid){
    $locked=OptimizationRun::query()->lockForUpdate()->findOrFail($run->id);
    if($locked->status!=='running')return;
    foreach($valid as $item)$locked->recommendations()->create($item+['business_id'=>$locked->business_id,'campaign_id'=>$locked->campaign_id,'status'=>'proposed']);
    $locked->update(['status'=>'completed','eligible_posts'=>$evidence['eligible_posts'],'evidence_snapshot'=>$evidence,
     'summary'=>['recommendation_count'=>count($valid),'message'=>'Recommendations are proposals and require human review.'],'completed_at'=>now()]);
   });
  }catch(Throwable $e){Log::error('Optimization failed',['run_id'=>$runId,'exception'=>get_class($e),'message'=>$e->getMessage()]);
   OptimizationRun::query()->whereKey($runId)->where('status','running')->update(['status'=>'failed','error_message'=>'Optimization failed. Check application logs.','completed_at'=>now()]);throw $e;}
 }
}
