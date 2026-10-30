<?php
namespace App\Http\Controllers\Phase20;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Campaign;
use App\Models\PublicationMetricSnapshot;
use App\Models\SocialPublication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PerformanceDashboardController extends Controller
{
    private const METRICS = ['reach','impressions','likes','comments','shares','saves','clicks','video_views','interactions'];

    private function accessibleBusinesses(Request $request)
    {
        return $request->user()->isAdmin()
            ? Business::query()
            : $request->user()->businesses();
    }

    private function selection(Request $request): array
    {
        $data = $request->validate([
            'business_id' => ['nullable','integer','min:1'],
            'campaign_id' => ['nullable','integer','min:1'],
            'from' => ['nullable','date_format:Y-m-d'],
            'to' => ['nullable','date_format:Y-m-d','after_or_equal:from'],
        ]);
        $businesses = $this->accessibleBusinesses($request)->orderBy('name')->get(['businesses.id','businesses.name']);
        $business = $businesses->firstWhere('id', (int)($data['business_id'] ?? 0)) ?: ($businesses->first());
        if (isset($data['business_id']) && (!$business || $business->id !== (int)$data['business_id'])) abort(403);
        $campaign = null;
        if (!empty($data['campaign_id'])) {
            abort_unless($business,403);
            $campaign = Campaign::query()->where('business_id',$business->id)->findOrFail($data['campaign_id']);
        }
        return [$data,$businesses,$business,$campaign];
    }

    private function dataset(Business $business, ?Campaign $campaign, array $data)
    {
        // Each social post contributes its latest snapshot IN THE SELECTED PERIOD only.
        // Cumulative metrics must not be summed across snapshots of the same post.
        $latest = PublicationMetricSnapshot::query()
            ->where('business_id',$business->id)
            ->when(!empty($data['from']), fn($q) => $q->whereDate('snapshot_date','>=',$data['from']))
            ->when(!empty($data['to']), fn($q) => $q->whereDate('snapshot_date','<=',$data['to']))
            ->select('social_publication_id')->selectRaw('MAX(id) as latest_id')
            ->groupBy('social_publication_id');
        return PublicationMetricSnapshot::query()
            ->joinSub($latest,'latest',fn($join) => $join->on('publication_metric_snapshots.id','=','latest.latest_id'))
            ->join('social_publications as posts',function($join) use ($business) {
                $join->on('posts.id','=','publication_metric_snapshots.social_publication_id')
                    ->where('posts.business_id','=',$business->id);
            })
            ->when($campaign,fn($q) => $q->where('posts.campaign_id',$campaign->id))
            ->orderByDesc('publication_metric_snapshots.snapshot_date')
            ->get(['publication_metric_snapshots.*','posts.campaign_id','posts.status as publication_status']);
    }

    public function index(Request $request)
    {
        [$filters,$businesses,$business,$campaign] = $this->selection($request);
        $rows = $business ? $this->dataset($business,$campaign,$filters) : collect();
        $totals = [];
        foreach(self::METRICS as $metric) {
            $observed = $rows->filter(fn($row) => $row->{$metric} !== null);
            $totals[$metric] = ['value'=>$observed->sum($metric),'sample_size'=>$observed->count()];
        }
        $daily = $rows->groupBy(fn($r) => $r->snapshot_date?->format('Y-m-d') ?? 'Unknown')
            ->map(fn($group) => ['date'=>$group->first()->snapshot_date?->format('Y-m-d'),
                'impressions'=>$group->sum('impressions'),'reach'=>$group->sum('reach'),
                'posts'=>$group->count()])->values();
        $campaigns = $business ? Campaign::where('business_id',$business->id)->orderBy('name')->get(['id','name']) : collect();
        $publicationCounts = $business ? SocialPublication::where('business_id',$business->id)
            ->when($campaign,fn($q)=>$q->where('campaign_id',$campaign->id))
            ->select('status',DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total','status') : collect();
        return view('phase20.performance',compact('filters','businesses','business','campaign','campaigns','rows','totals','daily','publicationCounts'));
    }

    public function export(Request $request): StreamedResponse
    {
        [$filters,,$business,$campaign] = $this->selection($request);
        abort_unless($business,404);
        $rows = $this->dataset($business,$campaign,$filters);
        return response()->streamDownload(function() use ($rows) {
            $stream = fopen('php://output','w');
            $columns = ['social_publication_id','snapshot_date','platform',...self::METRICS];
            fputcsv($stream,$columns);
            foreach($rows as $row) {
                $values = [];
                foreach($columns as $col) {
                    $value = $col === 'snapshot_date' ? $row->snapshot_date?->format('Y-m-d') : $row->{$col};
                    // Prevent spreadsheet formula execution in exported text.
                    if (is_string($value) && preg_match('/^[=+@\-\t\r]/u',$value)) $value = "'".$value;
                    $values[] = $value;
                }
                fputcsv($stream,$values);
            }
            fclose($stream);
        },'postgenius-performance-'.now()->format('Ymd').'.csv',['Content-Type'=>'text/csv; charset=UTF-8']);
    }
}
