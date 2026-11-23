<?php

namespace Tests\Feature\Mcp\Tools;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

final class DestructiveAnnotationsTest extends FeatureTestCase
{
    #[Test]
    public function tools_list_advertises_the_potentially_destructive_writes(): void
    {
        Http::fake([
            '*/v1/users/me' => Http::response(['api_profile' => 'agent', 'scopes' => ['agent_write']]),
        ]);
        Http::preventStrayRequests();

        $response = $this->withHeader('Authorization', 'Bearer test-token')->postJson('/mcp', [
            'jsonrpc' => '2.0',
            'method' => 'tools/list',
            'id' => 1,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['result' => ['tools']]);
        $tools = collect($response->json('result.tools'))->keyBy('name');

        foreach ([
            'update-character-tool',
            'patch-session-tool',
            'update-faction-tool',
            'update-location-tool',
            'update-item-tool',
            'update-quest-tool',
            'update-journal-tool',
            'create-link-tool',
            'update-entity-properties-tool',
            'create-session-from-notes-tool',
        ] as $name) {
            $this->assertTrue($tools->get($name)['annotations']['destructiveHint'] ?? false, $name);
        }

        $this->assertTrue($tools->get('patch-session-tool')['annotations']['openWorldHint'] ?? false);
        $this->assertSame([['type' => 'oauth2', 'scopes' => ['agent_read']]], $tools->get('list-campaigns-tool')['_meta']['securitySchemes']);
        $this->assertSame([['type' => 'oauth2', 'scopes' => ['agent_read', 'agent_write']]], $tools->get('patch-session-tool')['_meta']['securitySchemes']);

        // Both hosts require explicit hints; onboarding tools also carry a human-readable title.
        $getStarted = $tools->get('get-started-tool');
        $this->assertSame('Get started with Archivist', $getStarted['title']);
        $this->assertTrue($getStarted['annotations']['readOnlyHint']);
        $this->assertFalse($getStarted['annotations']['destructiveHint']);
        $this->assertFalse($getStarted['annotations']['openWorldHint']);
        $createSession = $tools->get('create-session-from-notes-tool');
        $this->assertSame('Create session from notes', $createSession['title']);
        $this->assertFalse($createSession['annotations']['openWorldHint']);

        // Connector-only hosts can discover the consent requirement even if they omit the setup guide.
        $this->assertStringContainsString('separate explicit user reply approving that save', $getStarted['description']);
        $this->assertStringContainsString('explicit user reply approving that exact save', $createSession['description']);
        $this->assertStringContainsString('host tool approval do not replace that reply', $createSession['description']);
    }
}
