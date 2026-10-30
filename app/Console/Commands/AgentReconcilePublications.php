<?php
namespace App\Console\Commands;
use App\Models\SocialPublication;
use Illuminate\Console\Command;
class AgentReconcilePublications extends Command {
 protected $signature='agent:reconcile-publications {--json : Output JSON}';
 protected $description='Read-only report of publishing attempts requiring manual reconciliation';
 public function handle():int {
  $rows=SocialPublication::query()->where('status','needs_reconciliation')->orWhere(function($q){$q->where('status','publishing')->where('claimed_at','<',now()->subMinutes((int)config('production_readiness.stale_minutes',20)));})->orderBy('id')->limit(200)->get(['id','business_id','campaign_id','status','claimed_at','attempts','external_post_id']);
  if($this->option('json')){$this->line($rows->toJson(JSON_PRETTY_PRINT));}
  else {$this->table(['id','business','campaign','status','claimed_at','attempts','external_id'],$rows->map(fn($p)=>[$p->id,$p->business_id,$p->campaign_id,$p->status,$p->claimed_at,$p->attempts,$p->external_post_id])->all());}
  return self::SUCCESS;
 }
}
