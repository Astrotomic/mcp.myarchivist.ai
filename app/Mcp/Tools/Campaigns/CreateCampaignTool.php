<?php

namespace App\Mcp\Tools\Campaigns;

use App\Actions\Archivist\Campaigns\CreateCampaign;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a new MyArchivist campaign. Title is required; description, system (e.g. "D&D 5e"), '.
    'summary, language, and tone fields are optional. Campaign creation is limited by the '.
    'account\'s subscription tier; if the tier limit is exceeded the API returns a 403 with the '.
    'tier name and cap.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateCampaignTool extends Tool
{
    protected function action(): CreateCampaign
    {
        return CreateCampaign::make();
    }
}
