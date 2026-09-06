<?php

namespace App\Mcp\Tools\HeroAwards;

use App\Actions\Archivist\HeroAwards\DeleteHeroAward;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Delete a hero award.')]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteHeroAwardTool extends Tool
{
    protected function action(): DeleteHeroAward
    {
        return DeleteHeroAward::make();
    }
}
