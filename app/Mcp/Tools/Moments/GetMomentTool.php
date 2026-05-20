<?php

namespace App\Mcp\Tools\Moments;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Moments\GetMoment;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a specific moment by ID.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetMomentTool extends Tool
{
    protected function action(): ApiAction
    {
        return GetMoment::make();
    }
}
