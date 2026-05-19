<?php

namespace App\Mcp\Tools\Campaigns;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\CampaignData;
use App\Mcp\Tools\Concerns\HasArchivistOutputSchema;
use App\Services\ArchivistClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a specific MyArchivist campaign by its ID.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetCampaignTool extends Tool
{
    use HasArchivistOutputSchema;

    #[\Override]
    protected function outputDtoClass(): string
    {
        return CampaignData::class;
    }

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'string'],
        ], [
            'campaign_id.required' => 'You must provide a campaign_id to retrieve a specific campaign.',
        ]);

        try {
            $data = $this->client->get("/v1/campaigns/{$validated['campaign_id']}");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get campaign '{$validated['campaign_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return $this->structuredResponse($data);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'campaign_id' => $schema->string()->description('The ID of the campaign to retrieve.')->required(),
        ];
    }
}
