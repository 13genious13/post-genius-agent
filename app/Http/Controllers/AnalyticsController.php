<?php
namespace App\Http\Controllers;
use App\Analytics\PerformanceSummary;
use App\Jobs\SyncSocialAnalytics;
use App\Jobs\RefreshCampaignMemories;
use App\Models\AnalyticsSyncRun;
use App\Models\Business;
use App\Models\Campaign;
use App\Models\MarketingMemory;
use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AnalyticsController extends Controller {
 public function index(Business $business,PerformanceSummary $summary){$this->authorize('view',$business);
  $report=$summary->forBusiness($business->id);$campaigns=$business->campaigns()->orderBy('id')->get(['id','name']);
  $runs=AnalyticsSyncRun::where('business_id',$business->id)->latest()->limit(12)->get();
  $memories=MarketingMemory::where('business_id',$business->id)->where('status','active')->latest('observed_at')->limit(12)->get();
  return view('analytics.index',compact('business','report','campaigns','runs','memories'));
 }
 public function campaign(Business $business,Campaign $campaign,PerformanceSummary $summary){$this->authorize('view',$business);abort_unless($campaign->business_id===$business->id,404);
  $report=$summary->forBusiness($business->id,$campaign->id);$memories=MarketingMemory::where('business_id',$business->id)->where('campaign_id',$campaign->id)->get();
  return view('analytics.campaign',compact('business','campaign','report','memories'));
 }
 public function sync(Request $request,Business $business){$this->authorize('update',$business);abort_unless(config('analytics.enabled'),403);
  $data=$request->validate(['social_account_id'=>['nullable','integer']]);$accountId=$data['social_account_id']??null;
  if($accountId)abort_unless(SocialAccount::where('business_id',$business->id)->whereKey($accountId)->exists(),404);
  $active=AnalyticsSyncRun::where('business_id',$business->id)->where('social_account_id',$accountId)->whereIn('status',['pending','running'])->exists();
  if($active)return back()->with('error','An analytics sync is already running.');
  $recent=AnalyticsSyncRun::where('business_id',$business->id)->where('social_account_id',$accountId)->where('created_at','>=',now()->subMinutes(config('analytics.min_sync_interval_minutes')))->exists();
  if($recent)return back()->with('error','Analytics was synced recently. Try later.');
  $run=AnalyticsSyncRun::create(['business_id'=>$business->id,'social_account_id'=>$accountId,'initiated_by'=>auth()->id(),'trigger'=>'manual','status'=>'pending']);
  SyncSocialAnalytics::dispatch($run->id)->afterCommit();return back()->with('success','Analytics sync queued.');
 }
 public function rebuildMemory(Business $business,Campaign $campaign){$this->authorize('update',$business);abort_unless($campaign->business_id===$business->id,404);
  RefreshCampaignMemories::dispatch($business->id,$campaign->id);return back()->with('success','Memory refresh queued.');
 }
}
