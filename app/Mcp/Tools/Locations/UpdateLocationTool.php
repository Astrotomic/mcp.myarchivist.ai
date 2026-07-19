<?php

namespace App\Mcp\Tools\Locations;

use App\Actions\Archivist\Locations\UpdateLocation;
use App\Mcp\Tools\Tool;
use App\Mcp\WikilinkPrompts;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Partially update a location. '.WikilinkPrompts::UPDATE_COMPENDIUM_TEXT
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class UpdateLocationTool extends Tool
{
    protected function action(): UpdateLocation
    {
        return UpdateLocation::make();
    }
}
