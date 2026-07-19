<?php

namespace App\Mcp\Tools\Journals;

use App\Actions\Archivist\Journals\DeleteJournalFolder;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Delete a journal folder. Journal entries in the folder are NOT deleted; their folder_id is '.
    'set to null (they move to the campaign root).'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteJournalFolderTool extends Tool
{
    protected function action(): DeleteJournalFolder
    {
        return DeleteJournalFolder::make();
    }
}
