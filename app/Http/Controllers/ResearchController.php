<?php

namespace App\Http\Controllers;

use App\Jobs\RunCampaignResearch;
use App\Models\Campaign;
use App\Models\ResearchRun;
use Illuminate\Http\Request;

class ResearchController extends Controller
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
            ResearchRun::query()
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




        return view(
            'research.index',
            compact('runs')
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
        ]);




        $runs =
            $campaign
                ->researchRuns()
                ->paginate(10);




        return view(
            'research.campaign',
            compact(
                'campaign',
                'runs'
            )
        );
    }




    public function start(
        Request $request,
        Campaign $campaign
    ) {
        $this->authorize(
            'update',
            $campaign
        );




        $data =
            $request->validate([
                'objective' => [
                    'required',
                    'string',
                    'max:3000',
                ],
            ]);




        $running =
            $campaign
                ->researchRuns()
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'planning',
                        'searching',
                        'analyzing',
                    ]
                )
                ->exists();




        if ($running) {
            return back()->with(
                'error',
                'This campaign already has a research run in progress.'
            );
        }




        $version =
            (
                $campaign
                    ->researchRuns()
                    ->max('version')
                ?? 0
            ) + 1;




        $run =
            $campaign
                ->researchRuns()
                ->create([
                    'business_id' =>
                        $campaign
                            ->business_id,




                    'marketing_goal_id' =>
                        $campaign
                            ->marketing_goal_id,




                    'created_by' =>
                        auth()->id(),




                    'version' =>
                        $version,




                    'status' =>
                        'pending',




                    'research_type' =>
                        'campaign',




                    'objective' =>
                        $data[
                        'objective'
                        ],




                    'search_provider' =>
                        config(
                            'research.provider'
                        ),
                ]);




        RunCampaignResearch::dispatch(
            $run->id
        )->afterCommit();




        return redirect()
            ->route(
                'research.show',
                $run
            )
            ->with(
                'success',
                'Research Agent started.'
            );
    }




    public function show(
        ResearchRun $researchRun
    ) {
        $this->authorizeRun(
            $researchRun
        );




        $researchRun->load([
            'business',
            'campaign',
            'queries',
            'sources.researchQuery',
        ]);




        return view(
            'research.show',
            compact(
                'researchRun'
            )
        );
    }




    public function sources(
        ResearchRun $researchRun
    ) {
        $this->authorizeRun(
            $researchRun
        );




        $researchRun->load([
            'campaign',
            'sources.researchQuery',
        ]);




        return view(
            'research.sources',
            compact(
                'researchRun'
            )
        );
    }




    private function authorizeRun(
        ResearchRun $run
    ): void {
        $allowed =
            auth()->user()
                ->isAdmin()
            || auth()->user()
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
