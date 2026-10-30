<?php

return [
    'provider' => env(
        'RESEARCH_SEARCH_PROVIDER',
        'tavily'
    ),

    'max_queries' => (int) env(
        'RESEARCH_MAX_QUERIES',
        6
    ),

    'results_per_query' => (int) env(
        'RESEARCH_RESULTS_PER_QUERY',
        6
    ),

    'max_source_content' => (int) env(
        'RESEARCH_MAX_SOURCE_CONTENT',
        4000
    ),




    'providers' => [




        'tavily' => [




            'base_url' => env(
                'TAVILY_BASE_URL',
                'https://api.tavily.com'
            ),




            'api_key' => env(
                'TAVILY_API_KEY'
            ),




            'search_depth' => env(
                'TAVILY_SEARCH_DEPTH',
                'advanced'
            ),




            'max_results' => (int) env(
                'TAVILY_MAX_RESULTS',
                6
            ),




            'timeout' => 45,




        ],




    ],




];
