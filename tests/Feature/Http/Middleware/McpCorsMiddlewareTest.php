<?php

namespace Tests\Feature\Http\Middleware;

use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class McpCorsMiddlewareTest extends FeatureTestCase
{
    #[Test]
    public function it_returns_cors_headers_for_mcp_preflight_requests(): void
    {
        $this->options('/mcp', [], [
            'Origin' => 'https://platform.openai.com',
            'Access-Control-Request-Method' => 'POST',
        ])
            ->assertOk()
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, OPTIONS');
    }

    #[Test]
    public function it_returns_cors_headers_for_unauthenticated_mcp_post_requests(): void
    {
        $this->postJson('/mcp', [
            'jsonrpc' => '2.0',
            'method' => 'tools/list',
            'id' => 1,
        ], [
            'Origin' => 'https://platform.openai.com',
        ])
            ->assertUnauthorized()
            ->assertHeader('Access-Control-Allow-Origin', '*');
    }
}
