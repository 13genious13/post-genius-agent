<?php




namespace App\Http\Controllers;




use App\Jobs\ExecuteAgentRun;
use App\Models\AgentControl;
use App\Models\AgentRun;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;




class AgentRunController extends Controller
{
    public function create(
        Campaign $campaign
    ) {
        $this->authorize(
            'view',
            $campaign
        );




        $campaign->load([
            'business.agentControl',
            'marketingGoal',
            'latestResearch',
            'latestStrategy',
        ]);




        return view(
            'agent-center.create',
            compact('campaign')
        );
    }




    public function store(
        Request $request,
        Campaign $campaign
    ) {
        $this->authorize(
            'update',
            $campaign
        );




        $campaign->load([
            'business.agentControl',
            'marketingGoal',
            'latestResearch',
            'latestStrategy',
        ]);




        if (
            !$campaign->latestResearch
            || $campaign
                ->latestResearch
                ->status !== 'completed'
        ) {
            return back()->with(
                'error',
                'Complete campaign research before starting the Agent.'
            );
        }




        if (
            !$campaign->latestStrategy
            || $campaign
                ->latestStrategy
                ->status !== 'completed'
        ) {
            return back()->with(
                'error',
                'Complete campaign strategy before starting the Agent.'
            );
        }




        if (
            $campaign
                ->latestStrategy
                ->research_run_id
            !==
            $campaign
                ->latestResearch
                ->id
        ) {
            return back()->with(
                'error',
                'The latest strategy does not use the latest research. Generate a new strategy first.'
            );
        }




        $data =
            $request->validate([
                'objective' => [
                    'required',
                    'string',
                    'max:3000',
                ],
            ]);




        $existing =
            AgentRun::query()
                ->where(
                    'campaign_id',
                    $campaign->id
                )
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'running',
                    ]
                )
                ->exists();




        if ($existing) {
            return back()->with(
                'error',
                'An Agent run is already active for this campaign.'
            );
        }




        $control =
            AgentControl::firstOrCreate(
                [
                    'business_id' =>
                        $campaign
                            ->business_id,
                ],
                [
                    'autonomy_mode' =>
                        'assisted',
                ]
            );




        $run =
            AgentRun::create([
                'business_id' =>
                    $campaign
                        ->business_id,




                'campaign_id' =>
                    $campaign->id,




                'marketing_goal_id' =>
                    $campaign
                        ->marketing_goal_id,




                'research_run_id' =>
                    $campaign
                        ->latestResearch
                        ->id,




                'campaign_strategy_id' =>
                    $campaign
                        ->latestStrategy
                        ->id,




                'started_by' =>
                    auth()->id(),




                'uuid' =>
                    (string) Str::uuid(),




                'run_type' =>
                    'campaign',




                'status' =>
                    'pending',




                'objective' =>
                    $data['objective'],




                'autonomy_mode' =>
                    $control
                        ->autonomy_mode,




                'progress' =>
                    0,
            ]);




        ExecuteAgentRun::dispatch(
            $run->id
        );




        return redirect()
            ->route(
                'agent-runs.show',
                $run
            )
            ->with(
                'success',
                'Agent run started.'
            );
    }




    public function show(
        AgentRun $agentRun
    ) {
        $this->authorizeRun(
            $agentRun
        );




        $agentRun->load([
            'business',
            'campaign',
            'researchRun',
            'strategy',
            'tasks',
            'toolCalls',
            'activities' =>
                fn ($query) =>
                $query->latest()
                    ->limit(100),
        ]);




        return view(
            'agent-center.show',
            [
                'run' =>
                    $agentRun,
            ]
        );
    }




    private function authorizeRun(
        AgentRun $run
    ): void {
        $user = auth()->user();




        $allowed =
            $user->isAdmin()
            || $user
                ->businesses()
                ->where(
                    'businesses.id',
                    $run->business_id
                )
                ->exists();




        abort_unless(
            $allowed,
            403
        );
    }
}
