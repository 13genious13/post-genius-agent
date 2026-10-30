<?php

namespace App\Console\Commands;

use App\AI\DTOs\AIRequest;
use App\AI\Services\AIManager;
use Illuminate\Console\Command;

class TestAI extends Command
{
    protected $signature = 'ai:test';

    protected $description = 'Test PostGenius AI infrastructure';

    public function handle(
        AIManager $manager
    ): int {

        $this->info(
            'Testing PostGenius AI...'
        );

        try {

            $response =
                $manager->chat(
                    new AIRequest(
                        messages: [
                            [
                                'role' =>
                                    'system',

                                'content' =>
                                    'You are an AI marketing strategist.',
                            ],
                            [
                                'role' =>
                                    'user',

                                'content' =>
                                    'Give three marketing ideas for a local dental clinic.',
                            ],
                        ],

                        feature:
                            'cli_test'
                    )
                );

            $this->newLine();

            $this->info(
                'Provider: ' .
                $response->provider
            );

            $this->info(
                'Model: ' .
                (
                    $response->model
                    ?? 'Unknown'
                )
            );

            $this->info(
                'Tokens: ' .
                (
                    $response->totalTokens
                    ?? 0
                )
            );

            $this->info(
                'Latency: ' .
                (
                    $response->latencyMs
                    ?? 0
                ) .
                ' ms'
            );

            $this->newLine();

            $this->line(
                $response->content
            );

            return self::SUCCESS;

        } catch (\Throwable $e) {

            $this->error(
                $e->getMessage()
            );

            return self::FAILURE;
        }
    }
}
