<?php

namespace App\Mcp\Tools\Factions;

use App\Actions\Archivist\Factions\ListFactions;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List factions in a campaign. Factions represent guilds, organisations, or other groups.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListFactionsTool extends Tool
{
    protected function action(): ListFactions
    {
        return ListFactions::make();
    }
}
