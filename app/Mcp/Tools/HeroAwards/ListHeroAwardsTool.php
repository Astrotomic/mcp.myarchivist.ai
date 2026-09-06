<?php

namespace App\Mcp\Tools\HeroAwards;

use App\Actions\Archivist\HeroAwards\ListHeroAwards;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List hero awards in a campaign or session. Hero awards are player achievement cards (title, description, character, optional player name, card style). Filter by title/description/character search.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListHeroAwardsTool extends Tool
{
    protected function action(): ListHeroAwards
    {
        return ListHeroAwards::make();
    }
}
