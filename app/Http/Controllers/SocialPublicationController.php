<?php
namespace App\Http\Controllers;
use App\Jobs\PublishSocialPublication;
use App\Models\Business;
use App\Models\ContentAsset;
use App\Models\CreativeAsset;
use App\Models\SocialAccount;
use App\Models\SocialPublication;
use App\Models\SocialPublicationEvent;
use App\Social\PublicationGate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class SocialPublicationController extends Controller {
 private function member(int $id):void {abort_unless(auth()->user()->businesses()->whereKey($id)->exists(),403);}
 public function index(Business $business){$this->member($business->id);$publications=SocialPublication::with(['account','content'])->where('business_id',$business->id)->latest()->paginate(20);return view('social.publications',compact('business','publications'));}
 public function create(ContentAsset $contentAsset){$this->member($contentAsset->business_id);$this->authorize('update',$contentAsset->campaign);$accounts=SocialAccount::where('business_id',$contentAsset->business_id)->where('platform',$contentAsset->platform)->where('status','active')->get();$creatives=CreativeAsset::where('content_asset_id',$contentAsset->id)->where('status','approved')->orderByDesc('version')->get();return view('social.create',compact('contentAsset','accounts','creatives'));}
 public function store(Request $request,ContentAsset $contentAsset){$this->member($contentAsset->business_id);$this->authorize('update',$contentAsset->campaign);
  $data=$request->validate(['social_account_id'=>['required','integer'],'creative_asset_id'=>['nullable','integer'],'scheduled_at'=>['required','date','after:now'],'media_public_url'=>['nullable','url','max:2000']]);
  abort_unless($contentAsset->status==='approved',422,'Approve content before preparing publication.');
  $account=SocialAccount::where('business_id',$contentAsset->business_id)->where('platform',$contentAsset->platform)->findOrFail($data['social_account_id']);
  $creative=null;if(!empty($data['creative_asset_id']))$creative=CreativeAsset::where('business_id',$contentAsset->business_id)->where('content_asset_id',$contentAsset->id)->findOrFail($data['creative_asset_id']);
  $url=$data['media_public_url']??null;if($creative && !$url)abort(422,'Image publishing requires a public CDN media URL.');if($url){$parts=parse_url($url);abort_unless(($parts['scheme']??null)==='https' && !empty($parts['host']) && filter_var($parts['host'],FILTER_VALIDATE_IP)===false && !in_array(strtolower($parts['host']),['localhost','localhost.localdomain'],true) && in_array(strtolower($parts['host']),array_map('strtolower',config('social.allowed_media_hosts',[])),true),422,'Use an externally reachable HTTPS image URL on an approved CDN.');}
  $p=SocialPublication::create(['business_id'=>$contentAsset->business_id,'campaign_id'=>$contentAsset->campaign_id,'content_asset_id'=>$contentAsset->id,'creative_asset_id'=>$creative?->id,'social_account_id'=>$account->id,'created_by'=>auth()->id(),'idempotency_key'=>(string)Str::uuid(),'status'=>'draft','scheduled_at'=>$data['scheduled_at'],'caption_snapshot'=>$contentAsset->caption,'media_disk'=>$creative?->storage_disk,'media_path'=>$creative?->storage_path,'media_public_url'=>$url]);
  SocialPublicationEvent::create(['social_publication_id'=>$p->id,'actor_id'=>auth()->id(),'event'=>'created','to_status'=>'draft']);return redirect()->route('social.publications.show',$p);
 }
 public function show(SocialPublication $publication){$this->member($publication->business_id);$publication->load(['account','content','creative','events']);return view('social.show',compact('publication'));}
 public function approve(SocialPublication $publication){$this->member($publication->business_id);$this->authorize('update',$publication->content->campaign);
  DB::transaction(function()use($publication){$p=SocialPublication::whereKey($publication->id)->lockForUpdate()->firstOrFail();abort_unless($p->status==='draft',409);$p->update(['approved_by'=>auth()->id(),'approved_at'=>now()]);SocialPublicationEvent::create(['social_publication_id'=>$p->id,'actor_id'=>auth()->id(),'event'=>'approved','from_status'=>'draft','to_status'=>'draft']);});return back()->with('success','Publication approved. Schedule it separately.');}
 public function schedule(SocialPublication $publication,PublicationGate $gate){$this->member($publication->business_id);$this->authorize('update',$publication->content->campaign);
  DB::transaction(function()use($publication,$gate){$p=SocialPublication::whereKey($publication->id)->lockForUpdate()->firstOrFail();abort_unless($p->status==='draft',409);try{$gate->assertAllowed($p);}catch(\RuntimeException $e){abort(422,$e->getMessage());}
   abort_unless($p->scheduled_at && $p->scheduled_at->isFuture(),422,'Scheduled time must be in the future.');$p->update(['status'=>'scheduled']);SocialPublicationEvent::create(['social_publication_id'=>$p->id,'actor_id'=>auth()->id(),'event'=>'scheduled','from_status'=>'draft','to_status'=>'scheduled']);});return back()->with('success','Publication scheduled.');}
 public function cancel(SocialPublication $publication){$this->member($publication->business_id);$this->authorize('update',$publication->content->campaign);
  DB::transaction(function()use($publication){$p=SocialPublication::whereKey($publication->id)->lockForUpdate()->firstOrFail();abort_unless(in_array($p->status,['draft','scheduled','failed'],true),409);$old=$p->status;$p->update(['status'=>'cancelled']);SocialPublicationEvent::create(['social_publication_id'=>$p->id,'actor_id'=>auth()->id(),'event'=>'cancelled','from_status'=>$old,'to_status'=>'cancelled']);});return back()->with('success','Publication cancelled.');}
}
