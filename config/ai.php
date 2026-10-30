<?php

return [

    'default' => env(
        'AI_DEFAULT_PROVIDER',
        'nebius'
    ),

    'timeout' => (int) env(
        'AI_REQUEST_TIMEOUT',
        120
    ),

    'providers' => [

        'nebius' => [

            'name' =>
                'Nebius Token Factory',

            'driver' =>
                'nebius',

            'base_url' => env(
                'NEBIUS_BASE_URL',
                'https://api.tokenfactory.nebius.com/v1/'
            ),

            'api_key' =>
                env('NEBIUS_API_KEY'),

            'model' =>
                env('NEBIUS_DEFAULT_MODEL'),

            'temperature' => (float) env(
                'NEBIUS_TEMPERATURE',
                0.6
            ),

            'max_tokens' => (int) env(
                'NEBIUS_MAX_TOKENS',
                4096
            ),

        ],

    ],

];
