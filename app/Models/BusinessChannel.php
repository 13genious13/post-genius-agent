<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessChannel extends Model
{
    protected $fillable = [
        'business_id',
        'channel',
        'handle',
        'url',
        'followers',
        'is_active',
        'is_priority',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'followers' => 'integer',
            'is_active' => 'boolean',
            'is_priority' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
