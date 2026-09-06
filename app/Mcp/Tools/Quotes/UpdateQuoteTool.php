<?php

namespace App\Mcp\Tools\Quotes;

use App\Actions\Archivist\Quotes\UpdateQuote;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Partially update a session quote (text, speaker, character_name, context, style, custom_theme, order_index).')]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class UpdateQuoteTool extends Tool
{
    protected function action(): UpdateQuote
    {
        return UpdateQuote::make();
    }
}
