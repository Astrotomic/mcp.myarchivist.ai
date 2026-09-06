<?php

namespace App\Mcp\Tools\Quotes;

use App\Actions\Archivist\Quotes\CreateQuote;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a memorable quote attached to a session. Requires text. Optional speaker, '.
    'character_name, context, style (default verdant), and custom_theme. A recap pipeline is '.
    'created for the session if one does not exist. Quotes do not use wikilinks.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateQuoteTool extends Tool
{
    protected function action(): CreateQuote
    {
        return CreateQuote::make();
    }
}
