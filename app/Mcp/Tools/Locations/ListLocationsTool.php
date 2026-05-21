<?php

namespace App\Mcp\Tools\Locations;

use App\Actions\Archivist\Locations\ListLocations;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List locations in a campaign. Optionally filter by name search. Locations can be nested (cities, taverns, dungeons, etc.).')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListLocationsTool extends Tool
{
    protected function action(): ListLocations
    {
        return ListLocations::make();
    }
}
