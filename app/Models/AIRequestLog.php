<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIRequestLog extends Model
{
    protected $table = 'ai_request_logs';
    protected $fillable = [
        'user_id',
        'business_id',
        'provider',
        'model',
        'feature',
        'status',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'latency_ms',
        'estimated_cost',
        'error_message',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'estimated_cost' => 'decimal:6',
            'metadata' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
