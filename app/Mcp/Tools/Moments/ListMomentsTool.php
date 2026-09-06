<?php

namespace App\Mcp\Tools\Moments;

use App\Actions\Archivist\Moments\ListMoments;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'List Key Moments (Moments) in a campaign or session. These are the same Moment records; '.
    'the product now calls them Key Moments and they can carry an image plus presentation '.
    'metadata (style, customTheme, variant). Filter by label search, comma-separated '.
    'categories (Key Moments typically use "key-moment"), or kind. Pass with_links=true '.
    'before editing content.'
)]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListMomentsTool extends Tool
{
    protected function action(): ListMoments
    {
        return ListMoments::make();
    }
}
