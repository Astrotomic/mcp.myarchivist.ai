<?php

namespace App\Mcp\Tools\Spotlights;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Spotlights\GetSpotlight;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a recap spotlight by ID, including title, description, character/player names, and card style. This is a GEMS spotlight card, not a session-handout spotlight.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetSpotlightTool extends Tool
{
    protected function action(): ApiAction
    {
        return GetSpotlight::make();
    }
}
