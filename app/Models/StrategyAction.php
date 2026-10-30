<?php




namespace App\Models;




use Illuminate\Database\Eloquent\Model;




class StrategyAction extends Model
{
    protected $fillable = [
        'campaign_strategy_id',
        'action_type',
        'title',
        'description',
        'platform',
        'funnel_stage',
        'priority',
        'sort_order',
        'instructions',
        'success_metrics',
        'status',
    ];




    protected function casts(): array
    {
        return [
            'instructions' => 'array',
            'success_metrics' => 'array',
        ];
    }




    public function strategy()
    {
        return $this->belongsTo(
            CampaignStrategy::class,
            'campaign_strategy_id'
        );
    }

    public function agentTasks()
    {
        return $this->hasMany(
            AgentTask::class
        );
    }

}
