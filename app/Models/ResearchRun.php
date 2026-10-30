<?php




namespace App\Models;




use Illuminate\Database\Eloquent\Model;




class ResearchRun extends Model
{
    protected $fillable = [
        'business_id',
        'campaign_id',
        'marketing_goal_id',
        'created_by',
        'version',
        'status',
        'research_type',
        'objective',
        'research_plan',
        'executive_summary',
        'market_insights',
        'audience_insights',
        'competitor_insights',
        'trend_insights',
        'opportunities',
        'risks',
        'recommendations',
        'unknowns',
        'raw_analysis',
        'ai_provider',
        'ai_model',
        'search_provider',
        'query_count',
        'source_count',
        'successful_queries',
        'failed_queries',
        'total_tokens',
        'total_latency_ms',
        'error_message',
        'started_at',
        'completed_at',
    ];




    protected function casts(): array
    {
        return [
            'research_plan' => 'array',
            'market_insights' => 'array',
            'audience_insights' => 'array',
            'competitor_insights' => 'array',
            'trend_insights' => 'array',
            'opportunities' => 'array',
            'risks' => 'array',
            'recommendations' => 'array',
            'unknowns' => 'array',
            'raw_analysis' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }




    public function business()
    {
        return $this->belongsTo(Business::class);
    }




    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }




    public function marketingGoal()
    {
        return $this->belongsTo(
            MarketingGoal::class
        );
    }




    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }




    public function queries()
    {
        return $this->hasMany(
            ResearchQuery::class
        )->orderBy('sort_order');
    }




    public function sources()
    {
        return $this->hasMany(
            ResearchSource::class
        );
    }


    public function successfulSources()
    {
        return $this->sources()
            ->where(
                'used_in_analysis',
                true
            );
    }
    public function isRunning(): bool
    {
        return in_array(
            $this->status,
            [
                'pending',
                'planning',
                'searching',
                'analyzing',
            ],
            true
        );
    }

    public function strategies()
    {
        return $this->hasMany(
            CampaignStrategy::class
        );
    }

}
