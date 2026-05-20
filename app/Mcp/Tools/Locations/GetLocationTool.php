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

#[Description('Get a specific location by ID.')]
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
