<?php

namespace Tests\Feature\Http\Middleware;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class ArchivistPassthroughMiddlewareTest extends FeatureTestCase
{
    #[Test]
    public function it_accepts_an_api_key_header(): void
    {
        $response = $this->withHeader('x-api-key', 'test-api-key')->postJson('/mcp', [
            'jsonrpc' => '2.0',
            'method' => 'initialize',
            'params' => [
                'protocolVersion' => '2024-11-05',
                'capabilities' => [],
                'clientInfo' => ['name' => 'test', 'version' => '1.0'],
            ],
            'id' => 1,
        ]);

        $response->assertOk()->assertJsonPath('result.serverInfo.name', 'Archivist AI');
    }

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
            ->assertJsonPath('error.data.connect_url', 'https://www.myarchivist.ai/mcp');

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
            ->assertJsonPath('error.data.connect_url', 'https://www.myarchivist.ai/mcp');
    }

    #[Test]
    public function it_returns_a_json_rpc_401_with_resource_metadata_for_protected_methods(): void
    {
        Http::fake();

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
            ->assertJsonPath('error.message', 'Connect Archivist AI in your MCP client and sign in, then retry. Setup: https://www.myarchivist.ai/mcp')
            ->assertJsonPath('error.data.connect_url', 'https://www.myarchivist.ai/mcp');

        $authenticateHeader = $response->headers->get('WWW-Authenticate');
        $this->assertIsString($authenticateHeader);
        $this->assertStringContainsString('"mcp"', $authenticateHeader);
        $this->assertStringContainsString('resource_metadata="', $authenticateHeader);
        $this->assertStringContainsString('/.well-known/oauth-protected-resource/mcp"', $authenticateHeader);

        $this->getJson('/.well-known/oauth-protected-resource/mcp')
            ->assertOk()
            ->assertJsonPath('authorization_servers.0', 'https://app.myarchivist.ai');

        Http::assertNothingSent();
    }

    #[Test]
    public function an_api_rejected_oauth_token_returns_a_tool_level_reconnect_challenge(): void
    {
        Http::fake(['*/v1/*' => Http::response(['detail' => 'Invalid token'], 401)]);
        $metadata = route('mcp.oauth.protected-resource.nested', ['path' => 'mcp']);

        $this->withToken('expired-token')->postJson('/mcp', [
            'jsonrpc' => '2.0',
            'method' => 'tools/call',
            'params' => ['name' => 'list-campaigns-tool', 'arguments' => []],
            'id' => 4,
        ])
            ->assertOk()
            ->assertJsonPath('id', 4)
            ->assertJsonPath('result.isError', true)
            ->assertJsonPath('result.content.0.text', 'Reconnect Archivist AI in your MCP client and sign in again, then retry. Setup: https://www.myarchivist.ai/mcp')
            ->assertJsonPath('result._meta.mcp/www_authenticate.0', sprintf(
                'Bearer realm="mcp", resource_metadata="%s", error="invalid_token", error_description="Reconnect Archivist AI and sign in again"',
                $metadata,
            ));
    }

    #[Test]
    public function a_rejected_api_key_does_not_trigger_oauth(): void
    {
        Http::fake(['*/v1/*' => Http::response(['detail' => 'Invalid API key'], 401)]);

        $this->withHeaders(['X-API-Key' => 'revoked-key', 'Authorization' => 'Bearer unused-token'])->postJson('/mcp', [
            'jsonrpc' => '2.0',
            'method' => 'tools/call',
            'params' => ['name' => 'list-campaigns-tool', 'arguments' => []],
            'id' => 5,
        ])
            ->assertOk()
            ->assertJsonPath('result.isError', true)
            ->assertJsonPath('result.content.0.text', 'MyArchivist API error 401: Invalid API key')
            ->assertJsonMissingPath('result._meta.mcp/www_authenticate');
    }
}
