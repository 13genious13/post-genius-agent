<?php




namespace App\Models;




use Illuminate\Database\Eloquent\Model;




class AgentRun extends Model
{
    protected $fillable = [
        'business_id',
        'campaign_id',
        'marketing_goal_id',
        'research_run_id',
        'campaign_strategy_id',
        'started_by',
        'uuid',
        'run_type',
        'status',
        'current_agent',
        'current_stage',
        'objective',
        'autonomy_mode',
        'progress',
        'context',
        'result',
        'summary',
        'metrics',
        'error_message',
        'started_at',
        'completed_at',
    ];




    protected function casts(): array
    {
        return [
            'context' => 'array',
            'result' => 'array',
            'summary' => 'array',
            'metrics' => 'array',
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




    public function strategy()
    {
        return $this->belongsTo(
            CampaignStrategy::class,
            'campaign_strategy_id'
        );
    }




    public function starter()
    {
        return $this->belongsTo(
            User::class,
            'started_by'
        );
    }




    public function tasks()
    {
        return $this->hasMany(
            AgentTask::class
        )->orderBy('sort_order');
    }




    public function activities()
    {
        return $this->hasMany(
            AgentActivity::class
        )->latest();
    }




    public function toolCalls()
    {
        return $this->hasMany(
            AgentToolCall::class
        )->latest();
    }




    public function isRunning(): bool
    {
        return in_array(
            $this->status,
            [
                'pending',
                'running',
            ],
            true
        );
    }
}
