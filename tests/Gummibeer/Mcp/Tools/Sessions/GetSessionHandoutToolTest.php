<?php

namespace Tests\Gummibeer\Mcp\Tools\Sessions;

use App\Data\SessionHandoutData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Sessions\GetSessionHandoutTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\GummibeerTestCase;

final class GetSessionHandoutToolTest extends GummibeerTestCase
{
    #[Test]
    public function it_fetches_data(): void
    {
        ArchivistServer::tool(GetSessionHandoutTool::class, [
            'session_id' => 'cmnhoa5e6000004juew4mv3o2',
        ])
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json->assertJsonSchema(SessionHandoutData::class);
                $this->assertMatchesJsonSnapshot($json);
            });
    }
}
