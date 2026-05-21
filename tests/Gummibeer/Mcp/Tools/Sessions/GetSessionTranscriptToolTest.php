<?php

namespace Tests\Gummibeer\Mcp\Tools\Sessions;

use App\Data\SessionHandoutData;
use App\Data\SessionTranscriptData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Sessions\GetSessionHandoutTool;
use App\Mcp\Tools\Sessions\GetSessionTranscriptTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\GummibeerTestCase;

final class GetSessionTranscriptToolTest extends GummibeerTestCase
{
    #[Test]
    public function it_fetches_data(): void
    {
        ArchivistServer::tool(GetSessionTranscriptTool::class, [
            'session_id' => 'cmn3ep276000804jrkdtsl58r',
        ])
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json->assertJsonSchema(SessionTranscriptData::class);
                $this->assertMatchesJsonSnapshot($json);
            });
    }
}
