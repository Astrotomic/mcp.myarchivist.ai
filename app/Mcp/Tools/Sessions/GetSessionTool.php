<?php

namespace App\Mcp\Tools\Sessions;

use App\Actions\Archivist\Sessions\GetSession;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a specific game session by ID. Optionally include related beats and moments.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetSessionTool extends Tool
{
    protected function action(): GetSession
    {
        return GetSession::make();
    }
}
