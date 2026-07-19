<?php

namespace Tests\Archivist\Mcp\Tools\Campaigns;

use Tests\RealWorld\Mcp\Tools\Campaigns\AskCampaignToolTest as RealWorldAskCampaignToolTest;

final class AskCampaignToolTest extends RealWorldAskCampaignToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            [['question' => 'Who is Boromir?'], 'cmr9ilyjy00020ahu90yhvzx6', true],
        ];
    }
}
