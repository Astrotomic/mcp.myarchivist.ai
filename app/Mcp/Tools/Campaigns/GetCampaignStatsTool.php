<?php

namespace App\Mcp\Tools\Campaigns;

use App\Actions\Archivist\Campaigns\GetCampaignStats;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get statistics for a specific campaign: character count, session count, and more.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetCampaignStatsTool extends Tool
{
    protected function action(): GetCampaignStats
    {
        return GetCampaignStats::make();
    }
}
