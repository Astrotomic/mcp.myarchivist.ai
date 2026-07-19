<?php

namespace Tests\RealWorld\Mcp\Tools\Campaigns;

use App\Data\CampaignDataShort;
use App\Mcp\Tools\Campaigns\ListCampaignsTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class ListCampaignsToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $ownerId): void
    {
        $this->assertToolReturnsPaginatedData(
            ListCampaignsTool::class,
            $query,
            CampaignDataShort::class,
            ['owner_id' => $ownerId],
        );
    }
}
