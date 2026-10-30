<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIProvider extends Model
{
    protected $table = 'ai_providers';
    protected $fillable = [
        'key',
        'name',
        'driver',
        'base_url',
        'default_model',
        'temperature',
        'max_tokens',
        'is_active',
        'is_default',
        'priority',
        'settings',
        'last_tested_at',
        'last_test_success',
        'last_test_message',
    ];

    protected function casts(): array
    {
        return [
            'temperature' => 'float',
            'max_tokens' => 'integer',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'priority' => 'integer',
            'settings' => 'array',
            'last_tested_at' => 'datetime',
            'last_test_success' => 'boolean',
        ];
    }
}
