<?php

namespace App\Mcp\Tools\Moments;

use App\Actions\Archivist\Moments\ListMoments;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List moments in a campaign or session. Optionally filter by label search. Moments capture memorable quotes and events.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListMomentsTool extends Tool
{
    protected function action(): ListMoments
    {
        return ListMoments::make();
    }
}
