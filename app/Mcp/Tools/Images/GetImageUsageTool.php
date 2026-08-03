<?php

namespace App\Mcp\Tools\Images;

use App\Actions\Archivist\Images\GetImageUsage;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Return the calling account\'s image feature quota for a campaign: `used`/`limit` counts for '.
    'the current billing cycle, `tier` name, `can_access` flag, and cycle window. Call this when you '.
    'want to reason about headroom before an image operation that could 403 on a depleted or '.
    'tier-locked quota.'
)]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetImageUsageTool extends Tool
{
    protected function action(): GetImageUsage
    {
        return GetImageUsage::make();
    }
}
