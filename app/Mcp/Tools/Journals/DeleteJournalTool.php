<?php

namespace App\Mcp\Tools\Journals;

use App\Actions\Archivist\Journals\DeleteJournal;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Delete a journal entry. Returns {success: true}. Vector embeddings for the entry are '.
    'best-effort deleted alongside the row.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteJournalTool extends Tool
{
    protected function action(): DeleteJournal
    {
        return DeleteJournal::make();
    }
}
