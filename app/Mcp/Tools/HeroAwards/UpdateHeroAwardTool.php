<?php

namespace App\Mcp\Tools\HeroAwards;

use App\Actions\Archivist\HeroAwards\UpdateHeroAward;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Partially update a hero award (title, description, character/player names, style, variant, tone_mode, award_mode, custom_theme, order_index).')]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class UpdateHeroAwardTool extends Tool
{
    protected function action(): UpdateHeroAward
    {
        return UpdateHeroAward::make();
    }
}
