<?php

namespace App\Mcp\Tools\Campaigns;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\CampaignStatsData;
use App\Services\ArchivistClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get statistics for a specific campaign: character count, session count, and more.')]
#[IsReadOnly]
#[IsIdempotent]
class GetCampaignStatsTool extends Tool
{
    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'string'],
        ], [
            'campaign_id.required' => 'You must provide a campaign_id to retrieve campaign statistics.',
        ]);

        try {
            $data = $this->client->get("/v1/campaigns/{$validated['campaign_id']}/stats");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get stats for campaign '{$validated['campaign_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return Response::structured((new CampaignStatsData($data))->toArray());
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'campaign_id' => $schema->string()->description('The ID of the campaign to retrieve statistics for.')->required(),
        ];
    }
}
