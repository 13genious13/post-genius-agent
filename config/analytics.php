<?php
return [
 'enabled'=>env('ANALYTICS_ENABLED',true),
 'lookback_days'=>(int)env('ANALYTICS_LOOKBACK_DAYS',30),
 'sync_limit'=>(int)env('ANALYTICS_SYNC_LIMIT',100),
 'min_sync_interval_minutes'=>(int)env('ANALYTICS_MIN_SYNC_INTERVAL',360),
 'retention_days'=>(int)env('ANALYTICS_RETENTION_DAYS',365),
 'graph_timeout'=>(int)env('ANALYTICS_GRAPH_TIMEOUT',25),
 'memory_min_sample'=>(int)env('ANALYTICS_MEMORY_MIN_SAMPLE',3),
 // Versioned allowlists; metric availability depends on Meta API version, account, media type and permissions.
 'instagram_media_metrics'=>array_filter(explode(',',env('ANALYTICS_IG_METRICS','reach,likes,comments,saved,shares,total_interactions'))),
 'facebook_post_metrics'=>array_filter(explode(',',env('ANALYTICS_FB_METRICS','post_impressions_unique,post_clicks,post_reactions_by_type_total'))),
];
