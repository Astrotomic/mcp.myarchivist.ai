<?php

namespace App\Mcp\Tools\Sessions;

use App\Actions\Archivist\Sessions\GetSessionTranscript;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get the cleaned transcript for a game session, including utterances, full text, and aggregate stats.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetSessionTranscriptTool extends Tool
{
    protected function action(): GetSessionTranscript
    {
        return GetSessionTranscript::make();
    }
}
