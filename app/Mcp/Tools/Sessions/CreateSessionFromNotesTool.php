<?php

namespace App\Mcp\Tools\Sessions;

use App\Actions\Archivist\Sessions\CreateSessionFromNotes;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Title;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Title('Create session from notes')]
#[Description(
    'Creates a game session in a campaign from text: session notes or a recap (format "notes", about 500-3,300 '.
    'words) or a "Speaker: words" transcript (format "transcript", about 875-66,000 words; transcripts also get '.
    'quotes and cast analysis). The full text goes in this call, so very long transcripts are better uploaded as files '.
    'in the Archivist app, and a transcript that has been shortened should be sent as notes. Archivist processes it '.
    'in about 10 minutes into a recap, timeline beats, key moments and suggested compendium records. The GM then approves them in Session Review at the returned url, and nothing '.
    'is added to the campaign until then. Each new session uses one session from the campaign owner\'s allowance and '.
    'can\'t be undone from here, so it is meant to be called after the user confirms a preview of the text. Only the '.
    'campaign owner or a co-GM can create sessions. The text should be the user\'s own material. '.
    'idempotency_key identifies one draft: repeating a call with the same key returns the original session '.
    '(replayed: true) instead of creating another. '.
    'Error codes: too_short and too_long (details has the limits); invalid_transcript_format; not_a_session (the '.
    'text was not recognised as a tabletop session; the message explains why); no_sessions_remaining; '.
    'subscription_required; in_progress (an earlier call with the same key is still running; retry with the same '.
    'key); timeout (the session may exist; retry with the same key).'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class CreateSessionFromNotesTool extends Tool
{
    protected function action(): CreateSessionFromNotes
    {
        return CreateSessionFromNotes::make();
    }
}
