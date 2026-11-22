<?php

namespace App\Mcp\Tools\Sessions;

use App\Actions\Archivist\Sessions\CreateSessionFromNotes;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a game session from text: session notes/recap (format "notes", about 500-3,300 words) or a '.
    '"Speaker: words" transcript (format "transcript", about 875-66,000 words; also unlocks quotes and cast analysis). '.
    'Archivist processes it (about 10 minutes) into a recap, timeline beats, key moments and suggested compendium records, '.
    'then the GM approves them in Session Review at the returned url; nothing is added to the campaign until then. '.
    'COSTS ONE SESSION from the campaign owner\'s allowance: show the user a preview and the cost, and call this only '.
    'after they explicitly confirm. Only the campaign owner or a co-GM can do this. '.
    'Send only text the user provided or confirmed; never invent events or names to reach the minimum length. '.
    'Generate one idempotency_key per draft and reuse it on any retry, so a retry returns the same session '.
    '(replayed: true) instead of spending another. '.
    'Errors come back as JSON with a code: too_short / too_long (details has the limits), invalid_transcript_format, '.
    'not_a_session (the text did not read as a tabletop session; show the reason), no_sessions_remaining (save the '.
    'text as a journal instead and point to plans), subscription_required, timeout (the session may exist; retry with '.
    'the same idempotency_key).'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class CreateSessionFromNotesTool extends Tool
{
    protected function action(): CreateSessionFromNotes
    {
        return CreateSessionFromNotes::make();
    }
}
