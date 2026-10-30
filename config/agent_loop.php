<?php
return [
 'enabled'=>env('AGENT_LOOP_ENABLED',false),
 'queue'=>env('AGENT_LOOP_QUEUE','agent-loop'),
 'max_steps'=>min(12,max(1,(int)env('AGENT_LOOP_MAX_STEPS',8))),
 'max_cycles_per_day'=>min(20,max(1,(int)env('AGENT_LOOP_DAILY_CYCLES',3))),
];
