<?php

namespace App\AI\Services;

use App\AI\Contracts\AIProviderInterface;
use App\AI\DTOs\AIRequest;
use App\AI\DTOs\AIResponse;
use App\AI\Providers\NebiusProvider;
use InvalidArgumentException;

class AIManager
{
    public function __construct(
        protected AIUsageLogger $logger
    ) {
    }

    public function provider(
        ?string $provider = null
    ): AIProviderInterface {

        $provider ??=
            config('ai.default');

        return match ($provider) {

            'nebius' =>
            app(
                NebiusProvider::class
            ),

            default =>
            throw new InvalidArgumentException(
                "Unsupported AI provider: {$provider}"
            ),
        };
    }

    public function chat(
        AIRequest $request,
        ?string $provider = null
    ): AIResponse {

        $provider ??=
            config('ai.default');

        try {

            $response = $this
                ->provider($provider)
                ->chat($request);

            $this->logger->success(
                $request,
                $response
            );

            return $response;

        } catch (\Throwable $e) {

            $this->logger->failure(
                $request,
                $provider,
                $e
            );

            throw $e;
        }
    }
}
