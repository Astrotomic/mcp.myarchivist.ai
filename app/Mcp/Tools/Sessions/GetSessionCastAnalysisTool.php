<?php

namespace App\Mcp\Tools\Sessions;

use App\Actions\Archivist\Sessions\GetSessionCastAnalysis;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get the cast analysis for a game session, including talk-share breakdown and core session metrics.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetSessionCastAnalysisTool extends Tool
{
    protected function action(): GetSessionCastAnalysis
    {
        return GetSessionCastAnalysis::make();
    }
}
