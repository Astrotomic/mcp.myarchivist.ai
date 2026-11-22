<?php

namespace App\Mcp\Tools\Sessions;

use App\Actions\Archivist\Sessions\PatchSession;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Update selected fields of an existing game session: title, session_date, summary, notes, type, public, or image. '.
    'Omitted fields stay unchanged. A supplied summary or notes value replaces the entire field; null clears it. '.
    'Notes are user-supplied content for rawNotes sessions. Setting public to true publishes the session; '.
    'setting it to false removes the session-level public flag. Replacing or clearing image can delete the previous managed image. '.
    'Before replacing the summary, fetch the session with with_links: true to preserve its existing wikilinks. '.
    'The API strips [[alias]] markup for aliases without a link on this session. '.
    'To introduce a new wikilink, first create a link from this GameSession to the intended entity.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(false)]
#[IsOpenWorld(true)]
class PatchSessionTool extends Tool
{
    protected function action(): PatchSession
    {
        return PatchSession::make();
    }
}
