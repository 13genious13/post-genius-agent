<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessAudience extends Model
{
    protected $fillable = [
        'business_id',
        'name',
        'type',
        'age_range',
        'gender',
        'locations',
        'interests',
        'pain_points',
        'goals',
        'objections',
        'buying_triggers',
        'preferred_channels',
        'description',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'locations' => 'array',
            'interests' => 'array',
            'pain_points' => 'array',
            'goals' => 'array',
            'objections' => 'array',
            'buying_triggers' => 'array',
            'preferred_channels' => 'array',
            'is_primary' => 'boolean',
        ];
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
