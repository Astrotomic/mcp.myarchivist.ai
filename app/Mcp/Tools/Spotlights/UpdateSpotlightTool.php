<?php

namespace App\Mcp\Tools\Spotlights;

use App\Actions\Archivist\Spotlights\UpdateSpotlight;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Partially update a recap spotlight (title, description, character/player names, style, custom_theme, order_index, source_moment_id).')]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class UpdateSpotlightTool extends Tool
{
    protected function action(): UpdateSpotlight
    {
        return UpdateSpotlight::make();
    }
}
