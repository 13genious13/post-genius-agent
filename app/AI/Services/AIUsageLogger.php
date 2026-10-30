<?php

namespace App\AI\Services;

use App\AI\DTOs\AIRequest;
use App\AI\DTOs\AIResponse;
use App\Models\AIRequestLog;

class AIUsageLogger
{
    public function success(
        AIRequest $request,
        AIResponse $response
    ): void {

        try {

            AIRequestLog::create([

                'user_id' =>
                    $request->userId,

                'business_id' =>
                    $request->businessId,

                'provider' =>
                    $response->provider,

                'model' =>
                    $response->model,

                'feature' =>
                    $request->feature,

                'status' =>
                    'success',

                'prompt_tokens' =>
                    $response->promptTokens,

                'completion_tokens' =>
                    $response->completionTokens,

                'total_tokens' =>
                    $response->totalTokens,

                'latency_ms' =>
                    $response->latencyMs,

                'metadata' =>
                    $request->metadata,
            ]);

        } catch (\Throwable $e) {

            report($e);
        }
    }

    public function failure(
        AIRequest $request,
        string $provider,
        \Throwable $exception
    ): void {

        try {

            AIRequestLog::create([

                'user_id' =>
                    $request->userId,

                'business_id' =>
                    $request->businessId,

                'provider' =>
                    $provider,

                'feature' =>
                    $request->feature,

                'status' =>
                    'failed',

                'error_message' =>
                    mb_substr(
                        $exception->getMessage(),
                        0,
                        2000
                    ),

                'metadata' =>
                    $request->metadata,
            ]);

        } catch (\Throwable $e) {

            report($e);
        }
    }
}
