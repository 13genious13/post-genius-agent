<?php




namespace App\Http\Controllers;




use App\Jobs\GenerateCampaignStrategy;
use App\Models\Campaign;
use App\Models\CampaignStrategy;
use App\Models\ResearchRun;
use Illuminate\Http\Request;




class StrategyController extends Controller
{
    public function index()
    {
        $businessIds =
            auth()->user()
                ->businesses()
                ->pluck(
                    'businesses.id'
                );




        $strategies =
            CampaignStrategy::query()
                ->with([
                    'business',
                    'campaign',
                    'researchRun',
                ])
                ->whereIn(
                    'business_id',
                    $businessIds
                )
                ->latest()
                ->paginate(15);




        return view(
            'strategies.index',
            compact('strategies')
        );
    }




    public function campaign(
        Campaign $campaign
    ) {
        $this->authorize(
            'view',
            $campaign
        );




        $campaign->load([
            'business',
            'marketingGoal',
            'latestResearch',
            'latestStrategy',
        ]);




        $researchRuns =
            $campaign
                ->researchRuns()
                ->where(
                    'status',
                    'completed'
                )
                ->latest('version')
                ->get();




        $strategies =
            $campaign
                ->strategies()
                ->with('researchRun')
                ->paginate(10);




        return view(
            'strategies.campaign',
            compact(
                'campaign',
                'researchRuns',
                'strategies'
            )
        );
    }




    public function generate(
        Request $request,
        Campaign $campaign
    ) {
        $this->authorize(
            'update',
            $campaign
        );




        $data =
            $request->validate([
                'research_run_id' => [
                    'required',
                    'integer',
                ],




                'objective' => [
                    'required',
                    'string',
                    'max:3000',
                ],
            ]);




        $research =
            ResearchRun::query()
                ->where(
                    'id',
                    $data[
                    'research_run_id'
                    ]
                )
                ->where(
                    'campaign_id',
                    $campaign->id
                )
                ->where(
                    'business_id',
                    $campaign
                        ->business_id
                )
                ->where(
                    'status',
                    'completed'
                )
                ->firstOrFail();




        $running =
            $campaign
                ->strategies()
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'preparing',
                        'generating',
                    ]
                )
                ->exists();




        if ($running) {
            return back()->with(
                'error',
                'A strategy is already being generated for this campaign.'
            );
        }




        $version =
            (
                $campaign
                    ->strategies()
                    ->max('version')
                ?? 0
            ) + 1;




        $strategy =
            CampaignStrategy::create([
                'business_id' =>
                    $campaign
                        ->business_id,




                'campaign_id' =>
                    $campaign->id,




                'marketing_goal_id' =>
                    $campaign
                        ->marketing_goal_id,




                'research_run_id' =>
                    $research->id,




                'created_by' =>
                    auth()->id(),




                'version' =>
                    $version,




                'status' =>
                    'pending',




                'strategy_type' =>
                    'campaign',




                'objective' =>
                    $data['objective'],
            ]);




        GenerateCampaignStrategy::dispatch(
            $strategy->id
        );




        return redirect()
            ->route(
                'strategies.show',
                $strategy
            )
            ->with(
                'success',
                'Strategy Agent started.'
            );
    }




    public function show(
        CampaignStrategy $strategy
    ) {
        $this->authorizeStrategy(
            $strategy
        );




        $strategy->load([
            'business',
            'campaign',
            'marketingGoal',
            'researchRun',
            'actions',
            'evidence.source',
        ]);




        return view(
            'strategies.show',
            compact('strategy')
        );
    }




    public function evidence(
        CampaignStrategy $strategy
    ) {
        $this->authorizeStrategy(
            $strategy
        );




        $strategy->load([
            'campaign',
            'researchRun',
            'evidence.source',
        ]);




        return view(
            'strategies.evidence',
            compact('strategy')
        );
    }




    private function authorizeStrategy(
        CampaignStrategy $strategy
    ): void {
        $user = auth()->user();




        $allowed =
            $user->isAdmin()
            || $user
                ->businesses()
                ->where(
                    'businesses.id',
                    $strategy->business_id
                )
                ->exists();




        abort_unless(
            $allowed,
            403
        );
    }
}
