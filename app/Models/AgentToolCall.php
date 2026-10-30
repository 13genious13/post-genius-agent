<?php




namespace App\Models;




use Illuminate\Database\Eloquent\Model;




class AgentToolCall extends Model
{
    protected $fillable = [
        'agent_run_id',
        'agent_task_id',
        'agent_key',
        'tool_key',
        'status',
        'arguments',
        'result',
        'error_message',
        'latency_ms',
        'started_at',
        'completed_at',
    ];




    protected function casts(): array
    {
        return [
            'arguments' => 'array',
            'result' => 'array',
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




    public function task()
    {
        return $this->belongsTo(
            AgentTask::class,
            'agent_task_id'
        );
    }
}
