<?php




namespace App\Models;




use Illuminate\Database\Eloquent\Model;




class AgentTask extends Model
{
    protected $fillable = [
        'agent_run_id',
        'strategy_action_id',
        'parent_task_id',
        'agent_key',
        'task_type',
        'title',
        'description',
        'status',
        'priority',
        'requires_approval',
        'sort_order',
        'input',
        'output',
        'metadata',
        'error_message',
        'started_at',
        'completed_at',
    ];




    protected function casts(): array
    {
        return [
            'requires_approval' => 'boolean',
            'input' => 'array',
            'output' => 'array',
            'metadata' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }




    public function run()
    {
        return $this->belongsTo(
            AgentRun::class,
            'agent_run_id'
        );
    }




    public function strategyAction()
    {
        return $this->belongsTo(
            StrategyAction::class
        );
    }




    public function parent()
    {
        return $this->belongsTo(
            self::class,
            'parent_task_id'
        );
    }




    public function children()
    {
        return $this->hasMany(
            self::class,
            'parent_task_id'
        );
    }




    public function activities()
    {
        return $this->hasMany(
            AgentActivity::class
        );
    }

    public function contentAssets() { return $this->hasMany(\App\Models\ContentAsset::class); }

    public function creativeAssets() { return $this->hasMany(\App\Models\CreativeAsset::class); }
}
