<?php




namespace App\AI\Orchestration;




class AgentRunContext
{
    public function __construct(
        public array $business = [],
        public array $campaign = [],
        public array $research = [],
        public array $strategy = [],
        public array $actions = [],
    ) {
    }




    public function toArray(): array
    {
        return [
            'business' =>
                $this->business,




            'campaign' =>
                $this->campaign,




            'research' =>
                $this->research,




            'strategy' =>
                $this->strategy,




            'actions' =>
                $this->actions,
        ];
    }
}
