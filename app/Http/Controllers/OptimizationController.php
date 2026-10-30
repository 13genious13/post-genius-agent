<?php
namespace App\Http\Controllers;
use App\Jobs\GenerateCampaignOptimizations;
use App\Models\Business;
use App\Models\Campaign;
use App\Models\OptimizationRun;
use App\Models\OptimizationRecommendation;
use App\Models\OptimizationExperiment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class OptimizationController extends Controller {
 use AuthorizesRequests;
 private function scoped(Business $business,Campaign $campaign):void{abort_unless((int)$campaign->business_id===(int)$business->id,404);}
 public function index(Business $business,Campaign $campaign){$this->authorize('view',$business);$this->scoped($business,$campaign);
  $runs=OptimizationRun::query()->where('business_id',$business->id)->where('campaign_id',$campaign->id)->latest()->paginate(15);
  return view('optimization.index',compact('business','campaign','runs'));}
 public function store(Request $request,Business $business,Campaign $campaign){$this->authorize('update',$business);$this->scoped($business,$campaign);
  abort_unless(config('optimization.enabled'),403,'Optimization is disabled.');
  $run=DB::transaction(function()use($business,$campaign,$request){
   Campaign::query()->whereKey($campaign->id)->lockForUpdate()->firstOrFail();
   $active=OptimizationRun::query()->where('business_id',$business->id)->where('campaign_id',$campaign->id)->whereIn('status',['pending','running'])->first();
   if($active)return $active;
   return OptimizationRun::create(['business_id'=>$business->id,'campaign_id'=>$campaign->id,'requested_by'=>$request->user()->id,'status'=>'pending','idempotency_key'=>(string)Str::uuid()]);
  });
  // afterResponse only if the job is new: dispatch is safe for duplicate calls because service atomically claims pending.
  GenerateCampaignOptimizations::dispatch($run->id)->afterCommit();
  return redirect()->route('optimization.show',[$business,$campaign,$run])->with('status','Optimization analysis queued.');
 }
 public function show(Business $business,Campaign $campaign,OptimizationRun $optimizationRun){$this->authorize('view',$business);$this->scoped($business,$campaign);
  abort_unless((int)$optimizationRun->business_id===(int)$business->id && (int)$optimizationRun->campaign_id===(int)$campaign->id,404);
  $optimizationRun->load(['recommendations.experiment']);return view('optimization.show',compact('business','campaign','optimizationRun'));}
 public function review(Request $request,Business $business,Campaign $campaign,OptimizationRecommendation $recommendation){$this->authorize('update',$business);$this->scoped($business,$campaign);
  abort_unless((int)$recommendation->business_id===(int)$business->id && (int)$recommendation->campaign_id===(int)$campaign->id,404);
  $data=$request->validate(['decision'=>'required|in:approved,rejected','note'=>'nullable|string|max:2000']);
  DB::transaction(function()use($recommendation,$data,$request){
   $r=OptimizationRecommendation::query()->lockForUpdate()->findOrFail($recommendation->id);
   if($r->status!=='proposed')throw ValidationException::withMessages(['decision'=>'This recommendation was already reviewed.']);
   $r->update(['status'=>$data['decision'],'review_note'=>$data['note']??null,'reviewed_by'=>$request->user()->id,'reviewed_at'=>now()]);
  });
  return back()->with('status','Review saved. No content or campaigns were changed.');
 }
 public function experiment(Request $request,Business $business,Campaign $campaign,OptimizationRecommendation $recommendation){$this->authorize('update',$business);$this->scoped($business,$campaign);
  abort_unless((int)$recommendation->business_id===(int)$business->id && (int)$recommendation->campaign_id===(int)$campaign->id,404);
  $data=$request->validate(['hypothesis'=>'required|string|max:255','test_plan'=>'required|string|max:5000','success_metric'=>'required|in:interaction_rate,clicks,reach','target'=>'nullable|numeric|min:0']);
  DB::transaction(function()use($recommendation,$data,$request){
   $r=OptimizationRecommendation::query()->lockForUpdate()->findOrFail($recommendation->id);
   if($r->status!=='approved')throw ValidationException::withMessages(['hypothesis'=>'Approve the recommendation before planning an experiment.']);
   OptimizationExperiment::firstOrCreate(['optimization_recommendation_id'=>$r->id],[
    'business_id'=>$r->business_id,'campaign_id'=>$r->campaign_id,'created_by'=>$request->user()->id,
    'hypothesis'=>$data['hypothesis'],'test_plan'=>$data['test_plan'],'status'=>'draft',
    'success_criteria'=>['metric'=>$data['success_metric'],'target'=>$data['target']??null],
    'baseline_metrics'=>$r->supporting_metrics]);
  });
  return back()->with('status','Draft experiment saved. No automatic publication or campaign changes.');
 }
}
