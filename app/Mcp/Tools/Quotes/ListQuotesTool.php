<?php

namespace App\Mcp\Tools\Quotes;

use App\Actions\Archivist\Quotes\ListQuotes;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List memorable session quotes in a campaign or session. Quotes are recap cards (speaker, text, context, style). Filter by text/speaker/context search.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListQuotesTool extends Tool
{
    protected function action(): ListQuotes
    {
        return ListQuotes::make();
    }
}
