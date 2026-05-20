<?php

namespace App\Mcp\Tools\Beats;

use App\Actions\Archivist\Beats\ListBeats;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List beats in a campaign, ordered by index. Beats represent story moments (major, minor, step).')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListBeatsTool extends Tool
{
    protected function action(): ListBeats
    {
        return ListBeats::make();
    }
}
