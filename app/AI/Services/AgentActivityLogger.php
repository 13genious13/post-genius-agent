<?php




namespace App\AI\Services;




use App\Models\AgentActivity;
use App\Models\AgentRun;
use App\Models\AgentTask;




class AgentActivityLogger
{
    public function log(
        AgentRun $run,
        string $eventType,
        string $title,
        ?string $message = null,
        ?string $agentKey = null,
        ?AgentTask $task = null,
        array $data = [],
        string $level = 'info'
    ): AgentActivity {
        return AgentActivity::create([
            'agent_run_id' =>
                $run->id,




            'agent_task_id' =>
                $task?->id,




            'agent_key' =>
                $agentKey,




            'event_type' =>
                $eventType,




            'level' =>
                $level,




            'title' =>
                $title,




            'message' =>
                $message,




            'data' =>
                $data ?: null,
        ]);
    }




    public function info(
        AgentRun $run,
        string $title,
        ?string $message = null,
        ?string $agentKey = null,
        ?AgentTask $task = null,
        array $data = []
    ): AgentActivity {
        return $this->log(
            $run,
            'info',
            $title,
            $message,
            $agentKey,
            $task,
            $data,
            'info'
        );
    }




    public function success(
        AgentRun $run,
        string $title,
        ?string $message = null,
        ?string $agentKey = null,
        ?AgentTask $task = null,
        array $data = []
    ): AgentActivity {
        return $this->log(
            $run,
            'success',
            $title,
            $message,
            $agentKey,
            $task,
            $data,
            'success'
        );
    }




    public function warning(
        AgentRun $run,
        string $title,
        ?string $message = null,
        ?string $agentKey = null,
        ?AgentTask $task = null,
        array $data = []
    ): AgentActivity {
        return $this->log(
            $run,
            'warning',
            $title,
            $message,
            $agentKey,
            $task,
            $data,
            'warning'
        );
    }




    public function error(
        AgentRun $run,
        string $title,
        ?string $message = null,
        ?string $agentKey = null,
        ?AgentTask $task = null,
        array $data = []
    ): AgentActivity {
        return $this->log(
            $run,
            'error',
            $title,
            $message,
            $agentKey,
            $task,
            $data,
            'error'
        );
    }
}
