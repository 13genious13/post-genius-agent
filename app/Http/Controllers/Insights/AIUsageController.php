<?php

namespace App\Http\Controllers\Insights;

use App\Http\Controllers\Controller;
use App\Models\AIRequestLog;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AIUsageController extends Controller
{
    private function scopeFor(Request $request, ?int $businessId = null)
    {
        $user = $request->user();
        $allowed = $user->isAdmin() ? null : $user->businesses()->pluck('businesses.id')->all();
        return AIRequestLog::query()->where(function ($q) use ($user, $allowed) {
            if ($allowed === null) return; // Admin sees all records, including unassigned/system calls.
            $q->whereIn('business_id', $allowed)
                ->orWhere(fn ($own) => $own->whereNull('business_id')->where('user_id', $user->id));
        })->when($businessId, fn ($q) => $q->where('business_id', $businessId));
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = $request->validate([
            'business_id' => ['nullable', 'integer', 'min:1'],
            'days' => ['nullable', 'integer', 'in:7,30,90'],
            'provider' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:success,failed,pending'],
        ]);
        $businesses = Business::query()->when(!$user->isAdmin(),
            fn ($q) => $q->whereHas('users', fn ($m) => $m->where('users.id', $user->id)))
            ->orderBy('name')->get(['id', 'name']);
        $allowedIds = $businesses->pluck('id')->all();
        $selectedId = isset($filters['business_id']) ? (int) $filters['business_id'] : null;
        if ($selectedId && !in_array($selectedId, $allowedIds, true)) abort(403);
        $days = (int) ($filters['days'] ?? 30);
        $base = $this->scopeFor($request, $selectedId)->where('created_at', '>=', now()->subDays($days));
        $summary = (clone $base)->selectRaw('COUNT(*) AS requests, COALESCE(SUM(total_tokens),0) AS tokens, COALESCE(SUM(estimated_cost),0) AS cost, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS failures', ['failed'])->first();
        $providers = (clone $base)->select('provider')->selectRaw('COUNT(*) AS requests, COALESCE(SUM(total_tokens),0) AS tokens, COALESCE(SUM(estimated_cost),0) AS cost')
            ->groupBy('provider')->orderByDesc('requests')->get();
        $daily = (clone $base)->selectRaw('DATE(created_at) AS day, COUNT(*) AS requests, COALESCE(SUM(total_tokens),0) AS tokens')
            ->groupBy(DB::raw('DATE(created_at)'))->orderBy('day')->get();
        $query = clone $base;
        if (!empty($filters['provider'])) $query->where('provider', $filters['provider']);
        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        $logs = $query->with('business:id,name')->latest('id')->paginate(25)->withQueryString();
        return view('insights.ai-usage', compact('businesses', 'selectedId', 'days', 'filters', 'summary', 'providers', 'daily', 'logs'));
    }

    public function export(Request $request)
    {
        $filters = $request->validate([
            'business_id' => ['nullable', 'integer', 'min:1'],
            'days' => ['nullable', 'integer', 'in:7,30,90'],
            'provider' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:success,failed,pending'],
        ]);
        $selectedId = isset($filters['business_id']) ? (int) $filters['business_id'] : null;
        if ($selectedId) {
            $user = $request->user();
            abort_unless($user->isAdmin() || $user->businesses()->where('businesses.id', $selectedId)->exists(), 403);
        }
        $query = $this->scopeFor($request, $selectedId)
            ->where('created_at', '>=', now()->subDays((int) ($filters['days'] ?? 30)));
        if (!empty($filters['provider'])) $query->where('provider', $filters['provider']);
        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Business ID', 'Provider', 'Model', 'Feature', 'Status', 'Prompt tokens', 'Completion tokens', 'Total tokens', 'Latency ms', 'Estimated cost']);
            foreach ($query->orderBy('id')->cursor() as $log) {
                $values = [$log->created_at?->toIso8601String(), $log->business_id, $log->provider, $log->model, $log->feature, $log->status, $log->prompt_tokens, $log->completion_tokens, $log->total_tokens, $log->latency_ms, $log->estimated_cost];
                // Prevent spreadsheet formula injection when CSV is opened in Excel.
                fputcsv($out, array_map(fn ($v) => is_string($v) && preg_match('/^[\s]*[=+@\-]/u', $v) ? "'".$v : $v, $values));
            }
            fclose($out);
        }, 'postgenius-ai-usage-'.now()->format('Ymd').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
