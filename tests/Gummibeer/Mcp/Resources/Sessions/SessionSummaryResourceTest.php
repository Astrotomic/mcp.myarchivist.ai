<?php

namespace Tests\Gummibeer\Mcp\Resources\Sessions;

use App\Mcp\Resources\Sessions\SessionSummaryResource;
use App\Mcp\Servers\ArchivistServer;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class SessionSummaryResourceTest extends TestCase
{
    #[Test]
    public function it_fetches_data(): void
    {
        ArchivistServer::resource(SessionSummaryResource::class, [
            'session_id' => 'cmnhoa5e6000004juew4mv3o2',
        ])
            ->dump();
    }
}
