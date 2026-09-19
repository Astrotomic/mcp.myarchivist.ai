<?php

namespace App\Mcp\Tools\HeroAwards;

use App\Actions\Archivist\HeroAwards\CreateHeroAward;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a hero award attached to a session. Requires title and description. Optional '.
    'character_name, player_name, style (default verdant), variant (default square), '.
    'tone_mode (default), award_mode (moment or theme), and custom_theme. Hero awards do not '.
    'use wikilinks. Card images are rendered by the app, not uploaded via init_image_upload.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateHeroAwardTool extends Tool
{
    protected function action(): CreateHeroAward
    {
        return CreateHeroAward::make();
    }
}
