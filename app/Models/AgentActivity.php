<?php




namespace App\Models;




use Illuminate\Database\Eloquent\Model;




class AgentActivity extends Model
{
    protected $fillable = [
        'agent_run_id',
        'agent_task_id',
        'agent_key',
        'event_type',
        'level',
        'title',
        'message',
        'data',
    ];




    protected function casts(): array
    {
        return [
            'data' => 'array',
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
