<?php

namespace App\Mcp\Tools\Journals;

use App\Actions\Archivist\Journals\CreateJournalFolder;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a new journal folder in a campaign. Folders organize journal entries hierarchically; '.
    'path is a "/"-separated string that must be unique within the campaign. Only campaign '.
    'owners/admins can create folders.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateJournalFolderTool extends Tool
{
    protected function action(): CreateJournalFolder
    {
        return CreateJournalFolder::make();
    }
}
