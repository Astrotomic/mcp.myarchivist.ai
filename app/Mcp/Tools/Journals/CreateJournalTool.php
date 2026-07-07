<?php

namespace App\Mcp\Tools\Journals;

use App\Actions\Archivist\Journals\CreateJournal;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a new journal entry in a campaign. Returns {success, id} on success. content is '.
    'plain text; content_rich accepts a ProseMirror-style JSON document. Journals participate '.
    'in the wikilinks system on serialization only: [[wikilinks]] in stored content are '.
    'rendered when you fetch with with_links=true, but on write the API does not create Link '.
    'rows from the text automatically. Create Link rows explicitly with create_link '.
    '(from_type="Journal", from_id=journal_id) if you want persistent link tracking.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateJournalTool extends Tool
{
    protected function action(): CreateJournal
    {
        return CreateJournal::make();
    }
}
