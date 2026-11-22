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

        // Both hosts require explicit hints; onboarding tools also carry a human-readable title.
        $getStarted = $tools->get('get-started-tool');
        $this->assertSame('Get started with Archivist', $getStarted['title']);
        $this->assertTrue($getStarted['annotations']['readOnlyHint']);
        $this->assertFalse($getStarted['annotations']['destructiveHint']);
        $this->assertFalse($getStarted['annotations']['openWorldHint']);
        $this->assertSame('Create session from notes', $tools->get('create-session-from-notes-tool')['title']);
        $this->assertFalse($tools->get('create-session-from-notes-tool')['annotations']['openWorldHint']);
    }
}
