<?php

namespace App\Mcp\Tools\Factions;

use App\Actions\Archivist\Factions\DeleteFaction;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Delete a faction. The API cleans up all outgoing and inbound Link rows and unbrackets '.
    '[[alias]] markup for this faction in every referring text field.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteFactionTool extends Tool
{
    protected function action(): DeleteFaction
    {
        return DeleteFaction::make();
    }
}
