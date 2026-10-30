<?php




namespace App\Http\Controllers;




use App\Models\AgentRun;




class AgentCenterController extends Controller
{
    public function index()
    {
        $businessIds =
            auth()->user()
                ->businesses()
                ->pluck(
                    'businesses.id'
                );




        $runs =
            AgentRun::query()
                ->with([
                    'business',
                    'campaign',
                ])
                ->whereIn(
                    'business_id',
                    $businessIds
                )
                ->latest()
                ->paginate(15);




        $stats = [
            'total' =>
                AgentRun::whereIn(
                    'business_id',
                    $businessIds
                )->count(),




            'running' =>
                AgentRun::whereIn(
                    'business_id',
                    $businessIds
                )
                    ->whereIn(
                        'status',
                        [
                            'pending',
                            'running',
                        ]
                    )
                    ->count(),




            'completed' =>
                AgentRun::whereIn(
                    'business_id',
                    $businessIds
                )
                    ->where(
                        'status',
                        'completed'
                    )
                    ->count(),




            'failed' =>
                AgentRun::whereIn(
                    'business_id',
                    $businessIds
                )
                    ->where(
                        'status',
                        'failed'
                    )
                    ->count(),
        ];




        return view(
            'agent-center.index',
            compact(
                'runs',
                'stats'
            )
        );
    }
}
