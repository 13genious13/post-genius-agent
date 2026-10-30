<?php




namespace App\Models;




use Illuminate\Database\Eloquent\Model;




class CampaignStrategy extends Model
{
    protected $fillable = [
        'business_id',
        'campaign_id',
        'marketing_goal_id',
        'research_run_id',
        'created_by',
        'version',
        'status',
        'strategy_type',
        'objective',
        'executive_summary',
        'strategic_thesis',
        'positioning',
        'audience_strategy',
        'messaging_framework',
        'content_pillars',
        'channel_strategy',
        'funnel_strategy',
        'campaign_phases',
        'kpi_framework',
        'experiments',
        'risks',
        'assumptions',
        'agent_explanation',
        'raw_strategy',
        'ai_provider',
        'ai_model',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'latency_ms',
        'error_message',
        'started_at',
        'completed_at',
    ];




    protected function casts(): array
    {
        return [
            'positioning' => 'array',
            'audience_strategy' => 'array',
            'messaging_framework' => 'array',
            'content_pillars' => 'array',
            'channel_strategy' => 'array',
            'funnel_strategy' => 'array',
            'campaign_phases' => 'array',
            'kpi_framework' => 'array',
            'experiments' => 'array',
            'risks' => 'array',
            'assumptions' => 'array',
            'agent_explanation' => 'array',
            'raw_strategy' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }




    public function business()
    {
        return $this->belongsTo(
            Business::class
        );
    }




    public function campaign()
    {
        return $this->belongsTo(
            Campaign::class
        );
    }




    public function marketingGoal()
    {
        return $this->belongsTo(
            MarketingGoal::class
        );
    }




    public function researchRun()
    {
        return $this->belongsTo(
            ResearchRun::class
        );
    }




    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }




    public function actions()
    {
        return $this->hasMany(
            StrategyAction::class
        )->orderBy('sort_order');
    }




    public function evidence()
    {
        return $this->hasMany(
            StrategyEvidence::class
        );
    }




    public function isRunning(): bool
    {
        return in_array(
            $this->status,
            [
                'pending',
                'preparing',
                'generating',
            ],
            true
        );
    }
}
