<?php

namespace App\Mcp\Tools\Spotlights;

use App\Actions\Archivist\Spotlights\DeleteSpotlight;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Delete a recap spotlight.')]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteSpotlightTool extends Tool
{
    protected function action(): DeleteSpotlight
    {
        return DeleteSpotlight::make();
    }
}
