<?php

namespace App\Mcp\Tools\Journals;

use App\Actions\Archivist\Journals\ListJournalFolders;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List journal folders for a campaign. Folders are ordered by path and position for tree rendering.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListJournalFoldersTool extends Tool
{
    protected function action(): ListJournalFolders
    {
        return ListJournalFolders::make();
    }
}
