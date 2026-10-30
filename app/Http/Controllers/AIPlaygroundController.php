<?php

namespace App\Http\Controllers;

use App\AI\DTOs\AIRequest;
use App\AI\Services\AIManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AIPlaygroundController extends Controller
{
    /**
     * Show AI Playground.
     */
    public function index(): View
    {
        return view('ai.playground');
    }

    /**
     * Generate AI response.
     */
    public function generate(
        Request $request,
        AIManager $manager
    ): RedirectResponse {

        $data = $request->validate([
            'prompt' => [
                'required',
                'string',
                'max:10000',
            ],
        ]);

        try {

            $response = $manager->chat(
                new AIRequest(
                    messages: [
                        [
                            'role' => 'system',
                            'content' => 'You are PostGenius Agent, an expert AI marketing assistant. Provide practical, professional marketing guidance.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $data['prompt'],
                        ],
                    ],

                    feature: 'ai_playground',

                    userId: auth()->id(),

                    metadata: [
                        'source' => 'playground',
                    ],
                )
            );

            return back()
                ->withInput()
                ->with('ai_response', $response->content)
                ->with('ai_meta', [
                    'provider' => $response->provider,
                    'model' => $response->model,
                    'tokens' => $response->totalTokens,
                    'latency' => $response->latencyMs,
                ]);

        } catch (\Throwable $e) {

            \Illuminate\Support\Facades\Log::error(
                'PostGenius Nemotron AI Error',
                [
                    'exception_class' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'exception' => $e,
                ]
            );

            return back()
                ->withInput()
                ->withErrors([
                    'ai' => 'AI request failed. Please contact support or check the application logs.',
                ]);
        }
    }

}
