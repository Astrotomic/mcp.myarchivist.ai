<?php

namespace Tests\Archivist\Mcp\Tools\Campaigns;

use App\Data\AnswerData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Campaigns\AskCampaignTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\ArchivistTestCase;
use Tests\GummibeerTestCase;

final class AskCampaignToolTest extends ArchivistTestCase
{
    public static function queryDataProvider(): array
    {
        return [
            [['question' => 'Who is Boromir?']],
        ];
    }

    //#[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query): void
    {
        ArchivistServer::tool(AskCampaignTool::class, array_merge($query, [
            'campaign_id' => 'cmr9ilyjy00020ahu90yhvzx6',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json->assertJsonSchema(AnswerData::class);

                $this->assertMatchesJsonSnapshot($json);
            });
    }
}
