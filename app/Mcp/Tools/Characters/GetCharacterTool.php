<?php

namespace App\Mcp\Tools\Characters;

use App\Actions\Archivist\Characters\GetCharacter;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a specific character by ID including aliases, backstory, and speaker linkage.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetCharacterTool extends Tool
{
    protected function action(): GetCharacter
    {
        return GetCharacter::make();
    }
}
