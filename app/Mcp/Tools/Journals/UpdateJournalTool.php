<?php

namespace App\Mcp\Tools\Journals;

use App\Actions\Archivist\Journals\UpdateJournal;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Update an existing journal entry (PUT). Returns {success, id}. When editing content, first '.
    'fetch the entry with get_journal(with_links: true) so you can see and preserve existing '.
    '[[wikilinks]] — links are not auto-created from text, but existing [[…]] markup should be '.
    'kept if you want them rendered as links on read.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class UpdateJournalTool extends Tool
{
    protected function action(): UpdateJournal
    {
        return UpdateJournal::make();
    }
}
