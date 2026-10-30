<?php
namespace App\AgentGovernance;
final class ActionCatalog {
 // Phase 12 is governance-only. A future executor must implement and explicitly register each capability.
 public const ACTIONS=[
  'content.generate'=>['label'=>'Generate content','risk'=>'low','capability'=>'content'],
  'creative.generate'=>['label'=>'Generate creative','risk'=>'low','capability'=>'creative'],
  'research.refresh'=>['label'=>'Refresh research','risk'=>'low','capability'=>'research'],
  'optimization.analyze'=>['label'=>'Analyze optimization','risk'=>'low','capability'=>'optimization'],
  'publication.schedule'=>['label'=>'Schedule publication','risk'=>'high','capability'=>'scheduling'],
  'publication.publish'=>['label'=>'Publish content','risk'=>'high','capability'=>'publishing'],
 ];
 public static function get(string $key):array {return self::ACTIONS[$key]??throw new \InvalidArgumentException('Unsupported action key.');}
}
