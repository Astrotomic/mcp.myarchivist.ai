<?php

namespace App\Mcp\Tools\Campaigns;

use App\Actions\Archivist\Campaigns\AskCampaign;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Ask a natural-language question about a campaign and get an answer written from that campaign\'s own '.
    'recorded content (sessions, characters, factions, locations, items, quests, journals). Use this for '.
    'open-ended recall questions such as "What happened to the Silver Hand?" or "Who has the moonstone?". '.
    'Do not use it to list, filter, or edit records: use the list_* and get_* tools for those. Requires '.
    '`campaign_id` and `question`. Read-only; nothing is created or changed.'
)]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class AskCampaignTool extends Tool
{
    protected function action(): AskCampaign
    {
        return AskCampaign::make();
    }
}
