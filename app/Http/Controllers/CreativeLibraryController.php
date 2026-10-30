<?php
namespace App\Http\Controllers;
use App\Jobs\GenerateCreativeAsset;
use App\Models\ContentAsset;
use App\Models\CreativeApproval;
use App\Models\CreativeAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
class CreativeLibraryController extends Controller {
 private function canView(int $businessId):void {abort_unless(auth()->user()->businesses()->where('businesses.id',$businessId)->exists(),403);}
 private function canEdit(CreativeAsset|ContentAsset $asset):void {$this->canView($asset->business_id); $this->authorize('update',$asset->campaign);}
 public function index(){ $ids=auth()->user()->businesses()->pluck('businesses.id');$assets=CreativeAsset::with(['contentAsset','business'])->whereIn('business_id',$ids)->latest()->paginate(15);return view('creative-library.index',compact('assets'));}
 public function show(CreativeAsset $creativeAsset){$this->canView($creativeAsset->business_id);$creativeAsset->load(['contentAsset','approvals.actor']);return view('creative-library.show',['asset'=>$creativeAsset]);}
 public function image(CreativeAsset $creativeAsset){$this->canView($creativeAsset->business_id);abort_unless($creativeAsset->storage_path && $creativeAsset->storage_disk,404);$disk=Storage::disk($creativeAsset->storage_disk);abort_unless($disk->exists($creativeAsset->storage_path),404);return $disk->response($creativeAsset->storage_path,null,['Content-Type'=>$creativeAsset->mime_type,'Cache-Control'=>'private, max-age=300','X-Content-Type-Options'=>'nosniff','Content-Security-Policy'=>"default-src 'none'; sandbox"]);}
 public function generate(ContentAsset $contentAsset){$contentAsset->load('campaign');$this->canEdit($contentAsset);
  abort_unless(in_array($contentAsset->status,['approved','draft'],true),422,'Content must be a draft or approved.');
  $control=$contentAsset->business->agentControl;abort_unless($control?->allow_creative_generation,403,'Creative generation disabled in Agent Controls.');
  $asset=DB::transaction(function()use($contentAsset){
   $content=ContentAsset::whereKey($contentAsset->id)->lockForUpdate()->firstOrFail();
   $active=CreativeAsset::where('content_asset_id',$content->id)->whereIn('status',['queued','generating','reviewing'])->exists();abort_if($active,409,'A creative is already generating.');
   $version=(int)CreativeAsset::where('content_asset_id',$content->id)->max('version')+1;
   return CreativeAsset::create(['business_id'=>$content->business_id,'campaign_id'=>$content->campaign_id,'content_asset_id'=>$content->id,'agent_task_id'=>$content->agent_task_id,'created_by'=>auth()->id(),'version'=>$version,'platform'=>$content->platform,'status'=>'queued']);
  });
  GenerateCreativeAsset::dispatch($asset->id)->afterCommit();
  return redirect()->route('creative-library.show',$asset)->with('success','Creative generation queued.');
 }
 public function decide(Request $request,CreativeAsset $creativeAsset){$creativeAsset->load('campaign');$this->canEdit($creativeAsset);
  $data=$request->validate(['decision'=>['required',Rule::in(['approve','reject','request_changes'])],'notes'=>['nullable','string','max:2000']]);
  DB::transaction(function()use($creativeAsset,$data){
   $asset=CreativeAsset::whereKey($creativeAsset->id)->lockForUpdate()->firstOrFail();
   abort_unless(in_array($asset->status,['awaiting_approval','revision_required'],true),409,'Creative is not reviewable.');
   if($data['decision']==='approve') abort_unless($asset->status==='awaiting_approval' && ($asset->brand_review['passed']??false) && $asset->storage_path,422,'Brand review must pass before approval.');
   $newStatus=match($data['decision']){'approve'=>'approved','reject'=>'rejected','request_changes'=>'revision_required'};
   $asset->update(['status'=>$newStatus,'review_notes'=>$data['notes']??null,'reviewed_by'=>auth()->id(),'reviewed_at'=>now()]);
   CreativeApproval::create(['creative_asset_id'=>$asset->id,'actor_id'=>auth()->id(),'decision'=>$data['decision'],'notes'=>$data['notes']??null]);
  });
  return back()->with('success','Review decision recorded.');
 }
}
