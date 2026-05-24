<?php

namespace Tests\Feature\Http\Middleware;

use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class ArchivistPassthroughMiddlewareTest extends FeatureTestCase
{
    #[Test]
    public function it_returns_a_401_with_resource_metadata_when_unauthenticated(): void
    {
        $this->postJson('/mcp', [
            'jsonrpc' => '2.0',
            'method' => 'initialize',
            'params' => [
                'protocolVersion' => '2024-11-05',
                'capabilities' => [],
                'clientInfo' => ['name' => 'test', 'version' => '1.0'],
            ],
            'id' => 1,
        ])
            ->assertUnauthorized()
            ->assertHeader(
                'WWW-Authenticate',
                sprintf(
                    'Bearer resource_metadata="%s", scope="profile worlds_read sessions_read characters_read"',
                    route('well-known.oauth-protected-resource')
                )
            );
    }
}
