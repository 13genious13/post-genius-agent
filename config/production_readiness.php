<?php
return [
 'enabled'=>env('PRODUCTION_READINESS_ENABLED',true),
 'publish_kill_switch'=>env('PUBLISH_KILL_SWITCH',true),
 'require_governance'=>env('PUBLISH_REQUIRE_GOVERNANCE',true),
 'scheduler_limit'=>(int)env('PUBLISH_DISPATCH_LIMIT',50),
 'stale_minutes'=>(int)env('PUBLISH_STALE_MINUTES',20),
 'health_token'=>env('INTERNAL_HEALTH_TOKEN'),
];
