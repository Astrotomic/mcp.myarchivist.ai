<?php

namespace App\Mcp\Tools\Characters;

use App\Actions\Archivist\Characters\DeleteCharacter;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Delete a character. The API cleans up all outgoing and inbound Link rows and unbrackets '.
    '[[alias]] markup in every referring text field (Character descriptions, session summaries, '.
    'beat descriptions, moment content, journal entries) so no dangling wikilinks remain.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteCharacterTool extends Tool
{
    protected function action(): DeleteCharacter
    {
        return DeleteCharacter::make();
    }
}
