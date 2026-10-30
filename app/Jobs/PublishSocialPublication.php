<?php
namespace App\Jobs;
use App\Models\SocialPublication;
use App\Models\SocialPublicationEvent;
use App\Social\MetaPublisher;
use App\Social\PublicationGate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Throwable;
class PublishSocialPublication implements ShouldQueue {
 use Queueable;
 public int $tries=1;public int $timeout=150;
 public function __construct(public int $publicationId){$this->onQueue('publishing');}
 public function handle(MetaPublisher $publisher,PublicationGate $gate, \App\Production\PublishingSafety $safety):void {
  $claimed=DB::transaction(function(){
   $p=SocialPublication::whereKey($this->publicationId)->lockForUpdate()->first();
   if(!$p||$p->status!=='scheduled'||!$p->scheduled_at||$p->scheduled_at->isFuture())return false;
   $p->update(['status'=>'publishing','claimed_at'=>now(),'attempts'=>$p->attempts+1]);SocialPublicationEvent::create(['social_publication_id'=>$p->id,'event'=>'claimed','from_status'=>'scheduled','to_status'=>'publishing']);return true;
  });if(!$claimed)return;
  $p=SocialPublication::findOrFail($this->publicationId);
  try{$safety->assertCanPublish($p);$gate->assertAllowed($p);$result=$publisher->publish($p);
   DB::transaction(function()use($p,$result){$locked=SocialPublication::whereKey($p->id)->lockForUpdate()->firstOrFail();$locked->update(['status'=>'published','published_at'=>now(),'external_post_id'=>$result['external_post_id'],'provider_result'=>$result['provider_result'],'last_error'=>null]);SocialPublicationEvent::create(['social_publication_id'=>$p->id,'event'=>'published','from_status'=>'publishing','to_status'=>'published','data'=>['external_post_id'=>$result['external_post_id']]]);});
  }catch(Throwable $e){DB::transaction(function()use($p,$e){$locked=SocialPublication::whereKey($p->id)->lockForUpdate()->firstOrFail();$locked->update(['status'=>'needs_reconciliation','last_error'=>mb_substr($e->getMessage(),0,1000)]);SocialPublicationEvent::create(['social_publication_id'=>$p->id,'event'=>'needs_reconciliation','from_status'=>'publishing','to_status'=>'needs_reconciliation','message'=>'Publishing may or may not have reached Meta. Verify manually before retrying.']);});report($e);}
 }
}
