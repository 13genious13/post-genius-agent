<?php

namespace App\Http\Controllers\Insights;

use App\Http\Controllers\Controller;
use App\Models\AgentActivity;
use App\Models\AgentRun;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $filters = $request->validate([
            'business_id' => ['nullable', 'integer', 'min:1'],
            'level' => ['nullable', 'in:info,success,warning,error'],
            'agent' => ['nullable', 'string', 'max:100'],
            'search' => ['nullable', 'string', 'max:120'],
            'days' => ['nullable', 'integer', 'in:7,30,90'],
        ]);
        $businesses = Business::query()
            ->when(!$user->isAdmin(), fn ($q) => $q->whereHas('users', fn ($m) => $m->where('users.id', $user->id)))
            ->orderBy('name')->get(['id', 'name']);
        $allowedIds = $businesses->pluck('id')->all();
        $selectedId = isset($filters['business_id']) ? (int) $filters['business_id'] : null;
        if ($selectedId && !in_array($selectedId, $allowedIds, true)) abort(403);
        $days = (int) ($filters['days'] ?? 30);
        $base = AgentActivity::query()->whereHas('run', function ($q) use ($allowedIds, $selectedId) {
            $q->whereIn('business_id', $selectedId ? [$selectedId] : $allowedIds);
        })->where('agent_activities.created_at', '>=', now()->subDays($days));
        $stats = (clone $base)->select('level', DB::raw('COUNT(*) AS total'))
            ->groupBy('level')->pluck('total', 'level');
        $query = (clone $base)->with(['run:id,business_id,campaign_id,uuid,status', 'run.business:id,name', 'task:id,title,status']);
        if (!empty($filters['level'])) $query->where('level', $filters['level']);
        if (!empty($filters['agent'])) $query->where('agent_key', $filters['agent']);
        if (!empty($filters['search'])) {
            $term = addcslashes($filters['search'], '%_\\');
            $query->where(fn ($q) => $q->where('title', 'like', "%{$term}%")
                ->orWhere('message', 'like', "%{$term}%"));
        }
        $activities = $query->orderByDesc('id')->paginate(25)->withQueryString();
        $agents = (clone $base)->whereNotNull('agent_key')->distinct()->orderBy('agent_key')->pluck('agent_key');
        return view('insights.activity', compact('activities', 'businesses', 'selectedId', 'days', 'stats', 'agents', 'filters'));
    }

    public function run(Request $request, AgentRun $run)
    {
        $user = $request->user();
        $permitted = $user->isAdmin() || $run->business()->whereHas('users',
            fn ($q) => $q->where('users.id', $user->id))->exists();
        abort_unless($permitted, 403);
        $activities = $run->activities()->with('task:id,title,status')->paginate(30);
        return view('insights.run-activity', compact('run', 'activities'));
    }
}
