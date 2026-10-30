<?php




namespace App\Http\Controllers;




use App\Models\AIRequestLog;
use App\Models\Campaign;




class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();




        $businessIds = $user
            ->businesses()
            ->pluck('businesses.id');




        $businesses =
            $businessIds->count();




        $activeCampaigns =
            Campaign::query()
                ->whereIn(
                    'business_id',
                    $businessIds
                )
                ->where(
                    'status',
                    'active'
                )
                ->count();




        $aiRequests =
            AIRequestLog::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->count();




        $tokensUsed =
            AIRequestLog::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'status',
                    'success'
                )
                ->sum(
                    'total_tokens'
                );




        $recentRequests =
            AIRequestLog::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->latest()
                ->limit(5)
                ->get();




        return view(
            'dashboard',
            compact(
                'businesses',
                'activeCampaigns',
                'aiRequests',
                'tokensUsed',
                'recentRequests'
            )
        );
    }
}
