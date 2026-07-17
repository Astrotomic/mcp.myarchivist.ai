<?php

namespace Tests\RealWorld\Mcp\Tools\Campaigns;

use App\Data\AnswerData;
use App\Mcp\Tools\Campaigns\AskCampaignTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresEnvironmentVariable;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class AskCampaignToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId, bool $snapshot = false): void
    {
        $this->assertToolReturnsData(
            AskCampaignTool::class,
            array_merge($query, ['campaign_id' => $campaignId]),
            AnswerData::class,
            snapshot: $snapshot,
        );
    }
}
