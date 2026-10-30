<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;




    protected $fillable = [
        'business_id',
        'marketing_goal_id',
        'created_by',
        'name',
        'campaign_type',
        'status',
        'objective',
        'offer',
        'call_to_action',
        'target_audience_ids',
        'platforms',
        'target_locations',
        'content_formats',
        'primary_kpis',
        'budget',
        'currency',
        'start_date',
        'end_date',
        'brand_constraints',
        'campaign_constraints',
        'notes',
        'brief_generated_at',
    ];




    protected function casts(): array
    {
        return [
            'target_audience_ids' => 'array',
            'platforms' => 'array',
            'target_locations' => 'array',
            'content_formats' => 'array',
            'primary_kpis' => 'array',
            'budget' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'brief_generated_at' => 'datetime',
        ];
    }




    public function business()
    {
        return $this->belongsTo(Business::class);
    }




    public function marketingGoal()
    {
        return $this->belongsTo(MarketingGoal::class);
    }




    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }




    public function briefs()
    {
        return $this->hasMany(CampaignBrief::class)
            ->latest('version');
    }




    public function latestBrief()
    {
        return $this->hasOne(CampaignBrief::class)
            ->latestOfMany('version');
    }

    public function audiences()
    {
        $ids = $this->target_audience_ids ?? [];
        return BusinessAudience::query()
            ->where('business_id', $this->business_id)
            ->whereIn('id', $ids)
            ->get();
    }

    public function researchRuns()
    {
        return $this->hasMany(
            ResearchRun::class
        )->latest('version');
    }

    public function latestResearch()
    {
        return $this->hasOne(
            ResearchRun::class
        )->latestOfMany('version');
    }

    public function strategies()
    {
        return $this->hasMany(
            CampaignStrategy::class
        )->latest('version');
    }

    public function latestStrategy()
    {
        return $this->hasOne(
            CampaignStrategy::class
        )->latestOfMany('version');
    }

    public function agentRuns()
    {
        return $this->hasMany(
            AgentRun::class
        );
    }

    public function creativeAssets() { return $this->hasMany(\App\Models\CreativeAsset::class); }

}
