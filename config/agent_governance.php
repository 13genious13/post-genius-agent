<?php
return [
 'enabled'=>env('AGENT_GOVERNANCE_ENABLED',true),
 'max_pending_per_business'=>(int)env('AGENT_GOVERNANCE_MAX_PENDING',100),
 'approval_ttl_hours'=>(int)env('AGENT_GOVERNANCE_APPROVAL_TTL',72),
 'max_requests_per_day'=>(int)env('AGENT_GOVERNANCE_DAILY_LIMIT',100),
];
