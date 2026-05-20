<?php

namespace App\Mcp\Tools\Campaigns;

use App\Actions\Archivist\Campaigns\GetCampaign;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a specific MyArchivist campaign by its ID.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetCampaignTool extends Tool
{
    protected function action(): GetCampaign
    {
        return GetCampaign::make();
    }
}
