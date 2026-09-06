<?php

namespace App\Mcp\Tools\Quotes;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Quotes\GetQuote;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a session quote by ID, including speaker, text, context, character name, and card style.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetQuoteTool extends Tool
{
    protected function action(): ApiAction
    {
        return GetQuote::make();
    }
}
