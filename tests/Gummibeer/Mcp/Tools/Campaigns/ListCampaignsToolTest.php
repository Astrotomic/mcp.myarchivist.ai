<?php

namespace Tests\Gummibeer\Mcp\Tools\Campaigns;

use App\Data\CampaignDataShort;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Campaigns\ListCampaignsTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\GummibeerTestCase;

final class ListCampaignsToolTest extends GummibeerTestCase
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[]],
            'size' => [['size' => 100]],
            'page' => [['page' => 1]],
        ];
    }

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query): void
    {
        ArchivistServer::tool(ListCampaignsTool::class, $query)
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertPaginatedList(function (AssertableJson $item): void {
                        $item
                            ->assertJsonSchema(CampaignDataShort::class)
                            ->where('owner_id', '4ee2e6b8-698d-4452-82fd-92ca1d1f4642');
                    });
            });
    }
}
