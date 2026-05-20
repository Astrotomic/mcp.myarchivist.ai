<?php

namespace App\Mcp\Tools\Characters;

use App\Actions\Archivist\Characters\ListCharacters;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List characters in a campaign. Optionally filter by name search, character type, and approval status.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListCharactersTool extends Tool
{
    protected function action(): ListCharacters
    {
        return ListCharacters::make();
    }
}
