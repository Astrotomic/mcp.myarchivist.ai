<?php

namespace App\Mcp\Tools\Spotlights;

use App\Actions\Archivist\Spotlights\ListSpotlights;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List recap spotlights in a campaign or session. These are GEMS spotlight cards (title, description, character/player), not session-handout spotlights. Filter by title/description/character search.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListSpotlightsTool extends Tool
{
    protected function action(): ListSpotlights
    {
        return ListSpotlights::make();
    }
}
