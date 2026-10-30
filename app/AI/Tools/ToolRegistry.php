<?php




namespace App\AI\Tools;




use App\AI\Tools\Contracts\AgentToolInterface;
use InvalidArgumentException;




class ToolRegistry
{
    protected array $tools = [];




    public function register(
        AgentToolInterface $tool
    ): self {
        $this->tools[
        $tool->key()
        ] = $tool;




        return $this;
    }




    public function has(
        string $key
    ): bool {
        return isset(
            $this->tools[$key]
);
}




    public function get(
        string $key
    ): AgentToolInterface {
        if (!$this->has($key)) {
            throw new InvalidArgumentException(
                "Agent tool [{$key}] is not registered."
            );
        }




return $this->tools[$key];
}




    public function all(): array
    {
        return $this->tools;
    }




    public function definitions(): array
    {
        return collect(
            $this->tools
        )
            ->map(
                fn (
                    AgentToolInterface $tool
                ) => [
                    'key' =>
                        $tool->key(),




                    'name' =>
                        $tool->name(),




                    'description' =>
                        $tool->description(),
                ]
            )
            ->values()
            ->all();
    }
}
