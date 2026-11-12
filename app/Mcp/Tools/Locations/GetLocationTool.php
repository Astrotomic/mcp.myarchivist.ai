<?php

namespace App\Mcp\Tools\Locations;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Locations\GetLocation;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a specific location by ID. Pass with_properties: true to also return its structured Properties tables (fields with key, field_type, options, connection_types, value and, for connection fields, connections with entity ids).')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetLocationTool extends Tool
{
    protected function action(): ApiAction
    {
        return GetLocation::make();
    }
}
