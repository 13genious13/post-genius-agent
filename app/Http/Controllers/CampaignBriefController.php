<?php

namespace App\Http\Controllers;

use App\AI\Services\CampaignBriefService;
use App\Models\Campaign;

class CampaignBriefController extends Controller
{
    public function show(
        Campaign $campaign
    ) {
        $this->authorize(
            'view',
            $campaign
        );




        $campaign->load([
            'business',
            'marketingGoal',
            'latestBrief',
        ]);




        return view(
            'campaigns.brief',
            compact('campaign')
        );
    }




    public function generate(
        Campaign $campaign,
        CampaignBriefService $service
    ) {
        $this->authorize(
            'update',
            $campaign
        );




        if (!$campaign->objective) {
            return back()->with(
                'error',
                'Campaign objective is required before generating a brief.'
            );
        }




        try {
            $brief =
                $service->generate(
                    $campaign,
                    auth()->id()
                );




            return redirect()
                ->route(
                    'campaigns.brief.show',
                    $campaign
                )
                ->with(
                    'success',
                    "Campaign Brief v{$brief->version} generated successfully."
                );




        } catch (\Throwable $e) {
            report($e);




            return back()->with(
                'error',
                'Campaign brief could not be generated. Check the AI connection and application logs.'
            );
        }
    }
}
