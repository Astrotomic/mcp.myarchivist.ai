<?php

namespace App\Mcp\Tools\Journals;

use App\Actions\Archivist\Journals\UpdateJournalFolder;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Update a journal folder (PUT semantics). Only owners/admins may write. path must stay '.
    'unique within the campaign; setting parent_id to the folder\'s own id is rejected.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class UpdateJournalFolderTool extends Tool
{
    protected function action(): UpdateJournalFolder
    {
        return UpdateJournalFolder::make();
    }
}
