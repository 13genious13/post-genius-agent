<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessIntelligence extends Model
{
    protected $fillable = [
        'business_id',
        'version',
        'status',
        'provider',
        'model',
        'business_summary',
        'market_position',
        'value_proposition',
        'key_strengths',
        'opportunities',
        'audience_insights',
        'content_pillars',
        'recommended_channels',
        'brand_voice_guidelines',
        'messaging_angles',
        'risk_notes',
        'raw_structured_output',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'key_strengths' => 'array',
            'opportunities' => 'array',
            'audience_insights' => 'array',
            'content_pillars' => 'array',
            'recommended_channels' => 'array',
            'brand_voice_guidelines' => 'array',
            'messaging_angles' => 'array',
            'risk_notes' => 'array',
            'raw_structured_output' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
