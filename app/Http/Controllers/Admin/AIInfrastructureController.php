<?php

namespace App\Http\Controllers\Admin;

use App\AI\Services\AIManager;
use App\Http\Controllers\Controller;
use App\Models\AIProvider;
use App\Models\AIRequestLog;

class AIInfrastructureController extends Controller
{
    public function index()
    {
        $providers =
            AIProvider::orderBy(
                'priority'
            )->get();

        $logs =
            AIRequestLog::latest()
                ->limit(50)
                ->get();

        $stats = [
            'requests' =>
                AIRequestLog::count(),

            'successful' =>
                AIRequestLog::where(
                    'status',
                    'success'
                )->count(),

            'failed' =>
                AIRequestLog::where(
                    'status',
                    'failed'
                )->count(),

            'tokens' =>
                AIRequestLog::sum(
                    'total_tokens'
                ),

            'average_latency' =>
                (int) AIRequestLog::where(
                    'status',
                    'success'
                )->avg('latency_ms'),
        ];

        return view(
            'admin.ai.infrastructure',
            compact(
                'providers',
                'logs',
                'stats'
            )
        );
    }

    public function test(
        string $provider,
        AIManager $manager
    ) {
        $result = $manager
            ->provider($provider)
            ->testConnection();

        $record =
            AIProvider::where(
                'key',
                $provider
            )->firstOrFail();

        $record->update([
            'last_tested_at' =>
                now(),

            'last_test_success' =>
                $result['success'],

            'last_test_message' =>
                $result['message']
                ?? null,
        ]);

        if ($result['success']) {

            return back()->with(
                'success',
                'AI connection successful.'
            );
        }

        return back()->with(
            'error',
            $result['message']
            ?? 'Connection failed.'
        );
    }
}
