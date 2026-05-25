<?php

namespace Tests\Feature\Http\Middleware;

use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class ArchivistPassthroughMiddlewareTest extends FeatureTestCase
{
    #[Test]
    public function it_returns_a_401_with_resource_metadata_when_unauthenticated(): void
    {
        $response = $this->postJson('/mcp', [
            'jsonrpc' => '2.0',
            'method' => 'initialize',
            'params' => [
                'protocolVersion' => '2024-11-05',
                'capabilities' => [],
                'clientInfo' => ['name' => 'test', 'version' => '1.0'],
            ],
            'id' => 1,
        ]);

        $response->assertUnauthorized();

        $authenticateHeader = $response->headers->get('WWW-Authenticate');
        $this->assertIsString($authenticateHeader);
        $this->assertStringContainsString('"mcp"', $authenticateHeader);
        $this->assertStringContainsString('resource_metadata="', $authenticateHeader);
        $this->assertStringContainsString('/.well-known/oauth-protected-resource/mcp"', $authenticateHeader);
        $this->assertStringContainsString('error="invalid_token"', $authenticateHeader);
    }
}
