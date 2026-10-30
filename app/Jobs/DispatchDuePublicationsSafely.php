<?php
namespace App\Jobs;
use App\Models\SocialPublication;
use App\Production\PublishingSafety;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
class DispatchDuePublicationsSafely implements ShouldQueue {
 use Queueable;
 public int $tries=1;
 public function handle(PublishingSafety $safety):void {
  if(config('production_readiness.publish_kill_switch',true))return;
  SocialPublication::query()->where('status','scheduled')->where('scheduled_at','<=',now())->orderBy('scheduled_at')->limit(max(1,min(200,(int)config('production_readiness.scheduler_limit',50))))->get()->each(function($p)use($safety){
   try{$safety->assertCanPublish($p);}catch(\RuntimeException $e){return;}
   PublishSocialPublication::dispatch($p->id)->onQueue('publishing');
  });
 }
}
