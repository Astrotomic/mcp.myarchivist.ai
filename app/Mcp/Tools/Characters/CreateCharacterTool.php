<?php

namespace App\Mcp\Tools\Characters;

use App\Actions\Archivist\Characters\CreateCharacter;
use App\Mcp\Tools\Tool;
use App\Mcp\WikilinkPrompts;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a new character (PC or NPC) in a campaign. '.WikilinkPrompts::CREATE_COMPENDIUM_TEXT
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateCharacterTool extends Tool
{
    protected function action(): CreateCharacter
    {
        return CreateCharacter::make();
    }
}
