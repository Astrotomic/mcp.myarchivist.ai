<?php

namespace App\Mcp\Tools\Campaigns;

use App\Actions\Archivist\Campaigns\UpdateCampaign;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Partially update a MyArchivist campaign. Only the campaign owner can update; only fields '.
    'included in the request are changed. Cannot be used on archived campaigns.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(true)]
class UpdateCampaignTool extends Tool
{
    protected function action(): UpdateCampaign
    {
        return UpdateCampaign::make();
    }
}
