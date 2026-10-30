<?php
return [
 'enabled'=>env('OPTIMIZATION_ENABLED',true),
 'min_posts'=>(int)env('OPTIMIZATION_MIN_POSTS',3),
 'max_posts'=>(int)env('OPTIMIZATION_MAX_POSTS',60),
 'max_recommendations'=>(int)env('OPTIMIZATION_MAX_RECOMMENDATIONS',5),
 'minimum_reach'=>(int)env('OPTIMIZATION_MIN_REACH',20),
 'queue'=>env('OPTIMIZATION_QUEUE','optimization'),
 'use_ai'=>env('OPTIMIZATION_USE_AI',true),
];
