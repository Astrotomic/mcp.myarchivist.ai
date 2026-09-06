<?php

namespace App\Mcp\Tools\Quotes;

use App\Actions\Archivist\Quotes\DeleteQuote;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Delete a session quote.')]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteQuoteTool extends Tool
{
    protected function action(): DeleteQuote
    {
        return DeleteQuote::make();
    }
}
