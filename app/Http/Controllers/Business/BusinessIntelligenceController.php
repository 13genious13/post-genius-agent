<?php

namespace App\Http\Controllers\Business;

use App\AI\Services\BusinessIntelligenceService;
use App\Http\Controllers\Controller;
use App\Models\Business;

class BusinessIntelligenceController extends Controller
{
    public function show(Business $business)
    {
        $this->authorize('view', $business);

        $business->load([
            'latestIntelligence',
        ]);

        return view(
            'businesses.intelligence.show',
            compact('business')
        );
    }

    public function generate(
        Business $business,
        BusinessIntelligenceService $service
    ) {
        $this->authorize('update', $business);

        if ($business->profile_completion < 50) {
            return back()->with(
                'error',
                'Complete at least 50% of the business profile before generating intelligence.'
            );
        }

        try {
            $intelligence = $service->generate(
                $business,
                auth()->id()
            );

            return redirect()
                ->route(
                    'businesses.intelligence.show',
                    $business
                )
                ->with(
                    'success',
                    "Business Intelligence v{$intelligence->version} generated successfully."
                );

        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Business intelligence could not be generated. Please check the AI connection and try again.'
            );
        }
    }
}
