<?php
namespace App\Production;
use Illuminate\Support\Facades\{DB,Schema};
use Throwable;
final class ReadinessAuditor {
 public function check(): array {
  $checks=[];
  $add=function(string $key,bool $ok,string $detail)use(&$checks){$checks[]=['check'=>$key,'pass'=>$ok,'detail'=>$detail];};
  $add('app_debug',!config('app.debug'),'APP_DEBUG must be false');
  $add('app_key',filled(config('app.key')),'APP_KEY must be configured');
  $add('https',str_starts_with((string)config('app.url'),'https://'),'APP_URL must use HTTPS');
  $add('database_queue',config('queue.default')!=='sync','Use an asynchronous queue driver');
  $add('governance',config('agent_governance.enabled')===true,'Governance must be enabled');
  $add('publishing_kill_switch',config('production_readiness.publish_kill_switch')===true,'Start with publishing disabled until live tests pass');
  foreach(['businesses','campaigns','social_publications','agent_governance_limits','marketing_cycles','optimization_runs'] as $table){
   try{$add('table_'.$table,Schema::hasTable($table),'Required Phase 1–13 table: '.$table);}catch(Throwable $e){$add('table_'.$table,false,'Database unavailable');}
  }
  try{DB::select('SELECT 1');$add('db_connection',true,'Database connection works');}catch(Throwable $e){$add('db_connection',false,'Database unavailable');}
  return ['ready'=>collect($checks)->every(fn($x)=>$x['pass']),'checks'=>$checks];
 }
}
