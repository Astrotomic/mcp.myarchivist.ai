<?php

namespace App\Mcp\Tools\Campaigns;

use App\Actions\Archivist\Campaigns\AskCampaign;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('RAG ask endpoint for campaign questions.')]
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
