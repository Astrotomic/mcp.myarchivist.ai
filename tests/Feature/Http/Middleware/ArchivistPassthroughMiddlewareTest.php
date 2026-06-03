<?php

namespace Tests\Feature\Http\Middleware;

use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class ArchivistPassthroughMiddlewareTest extends FeatureTestCase
{
    #[Test]
    public function it_requires_auth_for_unauthenticated_initialize(): void
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

        $response
            ->assertUnauthorized()
            ->assertJsonPath('jsonrpc', '2.0')
            ->assertJsonPath('id', 1)
            ->assertJsonPath('error.code', -32001)
            ->assertJsonPath('error.message', 'Unauthorized');

        $this->assertStringContainsString(
            'resource_metadata="',
            (string) $response->headers->get('WWW-Authenticate')
        );
    }

    #[Test]
    public function it_requires_auth_for_unauthenticated_tools_list(): void
    {
        $response = $this->postJson('/mcp', [
            'jsonrpc' => '2.0',
            'method' => 'tools/list',
            'id' => 2,
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonPath('jsonrpc', '2.0')
            ->assertJsonPath('id', 2)
            ->assertJsonPath('error.code', -32001)
            ->assertJsonPath('error.message', 'Unauthorized');
    }

    #[Test]
    public function it_returns_a_json_rpc_401_with_resource_metadata_for_protected_methods(): void
    {
        $response = $this->postJson('/mcp', [
            'jsonrpc' => '2.0',
            'method' => 'tools/call',
            'params' => [
                'name' => 'list-campaigns-tool',
                'arguments' => [],
            ],
            'id' => 3,
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonPath('jsonrpc', '2.0')
            ->assertJsonPath('id', 3)
            ->assertJsonPath('error.code', -32001)
            ->assertJsonPath('error.message', 'Unauthorized');

        $authenticateHeader = $response->headers->get('WWW-Authenticate');
        $this->assertIsString($authenticateHeader);
        $this->assertStringContainsString('"mcp"', $authenticateHeader);
        $this->assertStringContainsString('resource_metadata="', $authenticateHeader);
        $this->assertStringContainsString('/.well-known/oauth-protected-resource/mcp"', $authenticateHeader);
    }
}
