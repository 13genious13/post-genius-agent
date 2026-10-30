<?php

namespace Database\Seeders;

use App\Models\AIProvider;
use Illuminate\Database\Seeder;

class AIProviderSeeder extends Seeder
{
    public function run(): void
    {
        AIProvider::updateOrCreate(
            [
                'key' => 'nebius',
            ],
            [
                'name' =>
                    'Nebius Token Factory',

                'driver' =>
                    'nebius',

                'base_url' =>
                    env(
                        'NEBIUS_BASE_URL',
                        'https://api.tokenfactory.nebius.com/v1/'
                    ),

                'default_model' =>
                    env(
                        'NEBIUS_DEFAULT_MODEL'
                    ),

                'temperature' =>
                    0.60,

                'max_tokens' =>
                    4096,

                'is_active' =>
                    true,

                'is_default' =>
                    true,

                'priority' =>
                    10,
            ]
        );
    }
}
