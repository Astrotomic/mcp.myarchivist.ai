<?php

namespace App\Mcp\Tools\Factions;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Factions\GetFaction;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a specific faction by ID. Pass with_properties: true to also return its structured Properties tables (fields with key, field_type, options, connection_types, value and, for connection fields, connections with entity ids).')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetFactionTool extends Tool
{
    protected function action(): ApiAction
    {
        return GetFaction::make();
    }
}
