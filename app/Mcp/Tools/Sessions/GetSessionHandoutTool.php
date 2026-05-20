<?php

namespace App\Mcp\Tools\Sessions;

use App\Actions\Archivist\Sessions\GetSessionHandout;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get the generated session handout for a game session, including summary, outlines, spotlights, and notable moments.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetSessionHandoutTool extends Tool
{
    protected function action(): GetSessionHandout
    {
        return GetSessionHandout::make();
    }
}
