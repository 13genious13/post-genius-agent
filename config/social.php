<?php
return [
 'graph_version'=>env('META_GRAPH_VERSION','v24.0'),
 'app_id'=>env('META_APP_ID'),
 'app_secret'=>env('META_APP_SECRET'),
 'redirect_uri'=>env('META_REDIRECT_URI'),
 'scopes'=>array_filter(explode(',',env('META_SCOPES','pages_show_list,pages_manage_posts,pages_read_engagement,instagram_basic,instagram_content_publish'))),
 'http_timeout'=>20,
 'allowed_media_hosts'=>array_values(array_filter(array_map('trim',explode(',',env('SOCIAL_ALLOWED_MEDIA_HOSTS',''))))),
 'max_attempts'=>3,
 'instagram_poll_attempts'=>8,
 'instagram_poll_sleep'=>5,
];
