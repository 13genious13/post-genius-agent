<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignBrief extends Model
{
    protected $fillable = [
        'campaign_id',
        'version',
        'status',
        'provider',
        'model',
        'executive_summary',
        'strategic_direction',
        'core_message',
        'audience_strategy',
        'content_themes',
        'platform_strategy',
        'content_formats',
        'cta_strategy',
        'kpi_recommendations',
        'execution_priorities',
        'risks',
        'assumptions',
        'raw_structured_output',
        'generated_at',
    ];




    protected function casts(): array
    {
        return [
            'audience_strategy' => 'array',
            'content_themes' => 'array',
            'platform_strategy' => 'array',
            'content_formats' => 'array',
            'cta_strategy' => 'array',
            'kpi_recommendations' => 'array',
            'execution_priorities' => 'array',
            'risks' => 'array',
            'assumptions' => 'array',
            'raw_structured_output' => 'array',
            'generated_at' => 'datetime',
        ];
    }




    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
