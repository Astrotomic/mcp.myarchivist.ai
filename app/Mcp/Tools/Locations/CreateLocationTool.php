<?php

namespace App\Mcp\Tools\Locations;

use App\Actions\Archivist\Locations\CreateLocation;
use App\Mcp\Tools\Tool;
use App\Mcp\WikilinkPrompts;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a new location in a campaign. Locations can nest (parent_id references another '.
    'Location in the same campaign). '.WikilinkPrompts::CREATE_COMPENDIUM_TEXT
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateLocationTool extends Tool
{
    protected function action(): CreateLocation
    {
        return CreateLocation::make();
    }
}
