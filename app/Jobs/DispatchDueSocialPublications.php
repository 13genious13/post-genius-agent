<?php
namespace App\Jobs;
use App\Models\SocialPublication;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
class DispatchDueSocialPublications implements ShouldQueue {
 use Queueable;
 public int $tries=1;
 public function handle():void {SocialPublication::where('status','scheduled')->where('scheduled_at','<=',now())->orderBy('scheduled_at')->limit(100)->pluck('id')->each(fn($id)=>PublishSocialPublication::dispatch((int)$id));}
}
