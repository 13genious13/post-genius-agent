<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessCompetitor extends Model
{
    protected $fillable = [
        'business_id',
        'name',
        'website',
        'notes',
        'strengths',
        'weaknesses',
        'differentiators',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'strengths' => 'array',
            'weaknesses' => 'array',
            'differentiators' => 'array',
            'is_primary' => 'boolean',
        ];
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
