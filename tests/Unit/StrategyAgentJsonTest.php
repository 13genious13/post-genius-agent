<?php

namespace Tests\Unit;

use App\AI\Agents\StrategyAgent;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class StrategyAgentJsonTest extends TestCase
{
    private function callProtected(string $method, mixed ...$args): mixed
    {
        // No network, database, AI provider, or container required.
        $agent = (new ReflectionClass(StrategyAgent::class))->newInstanceWithoutConstructor();
        $reflection = new ReflectionMethod(StrategyAgent::class, $method);
        return $reflection->invoke($agent, ...$args);
    }

    public function test_parses_plain_json(): void
    {
        self::assertSame(['hello' => 'world'], $this->callProtected('decodeStrategyJson', '{"hello":"world"}'));
    }

    public function test_parses_fenced_json(): void
    {
        self::assertSame(['hello' => 'world'], $this->callProtected('decodeStrategyJson', "```json\n{\"hello\":\"world\"}\n```"));
    }

    public function test_parses_json_with_surrounding_text_and_quoted_braces(): void
    {
        $result = $this->callProtected('decodeStrategyJson', 'Here: {"message":"a } brace", "value":1} Done.');
        self::assertSame('a } brace', $result['message']);
    }

    public function test_rejects_truncated_json(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->callProtected('decodeStrategyJson', '{"executive_summary":"incomplete", "actions": [');
    }

    public function test_rejects_empty_output(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->callProtected('decodeStrategyJson', '');
    }

    public function test_extracts_structured_content(): void
    {
        self::assertSame('{"a":1}', $this->callProtected('responseText', [
            ['type' => 'text', 'text' => '{"a":1}'],
        ]));
    }

    public function test_rejects_missing_strategy_fields(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->callProtected('validateGeneratedStrategy', ['executive_summary' => 'Hello']);
    }
}
