<?php

namespace App\Mcp\Tools\Sessions;

use App\Actions\Archivist\Sessions\ListSessions;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List game sessions in a campaign. Optionally filter by session type or public-only.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListSessionsTool extends Tool
{
    protected function action(): ListSessions
    {
        return ListSessions::make();
    }
}
