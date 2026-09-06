<?php

namespace App\Mcp\Tools\Spotlights;

use App\Actions\Archivist\Spotlights\CreateSpotlight;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a recap spotlight attached to a session. Requires title. Optional description, '.
    'character_name, player_name, style (default verdant), custom_theme, and source_moment_id. '.
    'A recap pipeline is created for the session if one does not exist. Spotlights do not use '.
    'wikilinks. This is a GEMS card, not a session-handout spotlight.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateSpotlightTool extends Tool
{
    protected function action(): CreateSpotlight
    {
        return CreateSpotlight::make();
    }
}
