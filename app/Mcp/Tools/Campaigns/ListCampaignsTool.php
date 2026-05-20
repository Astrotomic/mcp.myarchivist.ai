<?php

namespace App\Mcp\Tools\Campaigns;

use App\Actions\Archivist\Campaigns\ListCampaigns;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List your MyArchivist campaigns. Returns a paginated list of campaigns.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListCampaignsTool extends Tool
{
    protected function action(): ListCampaigns
    {
        return ListCampaigns::make();
    }
}
