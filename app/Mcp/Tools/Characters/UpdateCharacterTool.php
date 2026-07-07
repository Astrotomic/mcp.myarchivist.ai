<?php

namespace App\Mcp\Tools\Characters;

use App\Actions\Archivist\Characters\UpdateCharacter;
use App\Mcp\Tools\Tool;
use App\Mcp\WikilinkPrompts;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Partially update a character. Only the fields you include are changed. '.WikilinkPrompts::UPDATE_COMPENDIUM_TEXT
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class UpdateCharacterTool extends Tool
{
    protected function action(): UpdateCharacter
    {
        return UpdateCharacter::make();
    }
}
