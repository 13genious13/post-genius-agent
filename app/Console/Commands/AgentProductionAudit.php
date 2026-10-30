<?php
namespace App\Console\Commands;
use App\Production\ReadinessAuditor;
use Illuminate\Console\Command;
class AgentProductionAudit extends Command {
 protected $signature='agent:production-audit {--json : Output machine-readable JSON}';
 protected $description='Check production configuration and required database tables';
 public function handle(ReadinessAuditor $auditor): int {
  $report=$auditor->check();
  if($this->option('json')){$this->line(json_encode($report,JSON_PRETTY_PRINT));}
  else {foreach($report['checks'] as $c){$this->line(($c['pass']?'PASS':'FAIL').' '.$c['check'].' — '.$c['detail']);}}
  return $report['ready']?self::SUCCESS:self::FAILURE;
 }
}
