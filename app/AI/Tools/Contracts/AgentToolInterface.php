<?php




namespace App\AI\Tools\Contracts;




use App\Models\AgentRun;




interface AgentToolInterface
{
    public function key(): string;




    public function name(): string;




    public function description(): string;




    public function execute(
        AgentRun $run,
        array $arguments = []
    ): array;
}
