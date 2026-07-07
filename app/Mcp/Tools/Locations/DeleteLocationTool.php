<?php

namespace App\Mcp\Tools\Locations;

use App\Actions\Archivist\Locations\DeleteLocation;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Delete a location. Child locations are unlinked (their parent_id is cleared). The API '.
    'cleans up all outgoing and inbound Link rows and unbrackets [[alias]] markup for this '.
    'location in every referring text field.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteLocationTool extends Tool
{
    protected function action(): DeleteLocation
    {
        return DeleteLocation::make();
    }
}
