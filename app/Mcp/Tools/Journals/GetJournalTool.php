<?php

namespace App\Mcp\Tools\Journals;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Journals\GetJournal;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a specific journal entry by ID including full content and the caller\'s effective permission level.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetJournalTool extends Tool
{
    protected function action(): ApiAction
    {
        return GetJournal::make();
    }
}
