<?php

namespace App\Mcp\Tools\HeroAwards;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\HeroAwards\GetHeroAward;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a hero award by ID, including title, description, character/player names, card style, variant, and custom theme.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetHeroAwardTool extends Tool
{
    protected function action(): ApiAction
    {
        return GetHeroAward::make();
    }
}
