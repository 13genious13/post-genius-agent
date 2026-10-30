<?php
return [
 'provider'=>env('CREATIVE_IMAGE_PROVIDER','disabled'),
 'openai_api_key'=>env('CREATIVE_OPENAI_API_KEY'),
 'openai_image_model'=>env('CREATIVE_OPENAI_IMAGE_MODEL','gpt-image-1'),
 'disk'=>env('CREATIVE_STORAGE_DISK','local'),
 'min_brand_score'=>(int)env('CREATIVE_MIN_BRAND_SCORE',75),
 'max_image_bytes'=>10*1024*1024,
];
