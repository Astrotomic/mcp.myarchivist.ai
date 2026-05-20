<?php

namespace App\Mcp\Tools\Journals;

use App\Actions\Archivist\Journals\ListJournals;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List journal entries in a campaign. Results are filtered to entries the caller can see. Content is omitted from the list; use get_journal to fetch full content.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListJournalsTool extends Tool
{
    protected function action(): ListJournals
    {
        return ListJournals::make();
    }
}
