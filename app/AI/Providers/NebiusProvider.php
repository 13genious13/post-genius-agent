<?php

namespace App\AI\Providers;

use App\AI\Contracts\AIProviderInterface;
use App\AI\DTOs\AIRequest;
use App\AI\DTOs\AIResponse;
use App\AI\Exceptions\AIProviderException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class NebiusProvider implements AIProviderInterface
{
    public function key(): string
    {
        return 'nebius';
    }

    public function chat(
        AIRequest $request
    ): AIResponse {

        $config = config(
            'ai.providers.nebius'
        );

        if (empty($config['api_key'])) {
            throw new AIProviderException(
                'Nebius API key is not configured.',
                $this->key()
            );
        }

        $model = $request->model
            ?: $config['model'];

        if (empty($model)) {
            throw new AIProviderException(
                'Nebius model is not configured.',
                $this->key()
            );
        }

        $payload = [
            'model' => $model,

            'messages' =>
                $request->messages,

            'temperature' =>
                $request->temperature
                ?? $config['temperature'],

            'max_tokens' =>
                $request->maxTokens
                ?? $config['max_tokens'],
        ];

        if (!empty($request->tools)) {

            $payload['tools'] =
                $request->tools;

            $payload['tool_choice'] =
                'auto';
        }

        if ($request->jsonMode) {

            $payload['response_format'] = [
                'type' => 'json_object',
            ];
        }

        $started = microtime(true);

        try {

            $response = Http::baseUrl(
                rtrim(
                    $config['base_url'],
                    '/'
                ) . '/'
            )
                ->withToken(
                    $config['api_key']
                )
                ->acceptJson()
                ->asJson()
                ->timeout(
                    config(
                        'ai.timeout',
                        120
                    )
                )
                ->connectTimeout(15)
                ->post(
                    'chat/completions',
                    $payload
                );

            $latency = (int) round(
                (
                    microtime(true)
                    - $started
                ) * 1000
            );

            if (!$response->successful()) {

                throw new AIProviderException(
                    $this->safeErrorMessage(
                        $response->status()
                    ),
                    $this->key(),
                    $response->status()
                );
            }

            $data = $response->json();

            $message = data_get(
                $data,
                'choices.0.message',
                []
            );

            return new AIResponse(
                content: (string) (
                    $message['content']
                    ?? ''
                ),

                provider:
                $this->key(),

                model:
                $data['model']
                ?? $model,

                promptTokens:
                data_get(
                    $data,
                    'usage.prompt_tokens'
                ),

                completionTokens:
                data_get(
                    $data,
                    'usage.completion_tokens'
                ),

                totalTokens:
                data_get(
                    $data,
                    'usage.total_tokens'
                ),

                latencyMs:
                $latency,

                toolCalls:
                $message['tool_calls']
                ?? [],

                raw:
                $data,
            );

        } catch (AIProviderException $e) {

            throw $e;

        } catch (
        ConnectionException
        | RequestException $e
        ) {

            throw new AIProviderException(
                'Unable to connect to the AI service.',
                $this->key(),
                null,
                $e
            );

        } catch (\Throwable $e) {

            report($e);

            throw new AIProviderException(
                'AI service request failed.',
                $this->key(),
                null,
                $e
            );
        }
    }

    protected function safeErrorMessage(
        int $status
    ): string {

        return match ($status) {

            401 =>
            'AI authentication failed.',

            403 =>
            'AI access is not permitted.',

            429 =>
            'AI request limit reached.',

            500, 502, 503, 504 =>
            'AI service is temporarily unavailable.',

            default =>
            'AI request failed.',
        };
    }

    public function testConnection(): array
    {
        try {

            $response = $this->chat(
                new AIRequest(
                    messages: [
                        [
                            'role' => 'user',
                            'content' =>
                                'Reply only with CONNECTION_OK',
                        ],
                    ],
                    temperature: 0,
                    maxTokens: 20,
                    feature:
                    'connection_test'
                )
            );

            return [
                'success' => true,

                'provider' =>
                    $this->key(),

                'model' =>
                    $response->model,

                'latency_ms' =>
                    $response->latencyMs,

                'message' =>
                    trim(
                        $response->content
                    ),
            ];

        } catch (\Throwable $e) {

            return [
                'success' => false,

                'provider' =>
                    $this->key(),

                'message' =>
                    $e->getMessage(),
            ];
        }
    }
}
