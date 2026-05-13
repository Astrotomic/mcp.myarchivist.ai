<?php

namespace App\Mcp\Tools\Campaigns;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\CampaignData;
use App\Services\ArchivistClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List your MyArchivist campaigns. Returns a paginated list of campaigns.')]
#[IsReadOnly]
#[IsIdempotent]
class ListCampaignsTool extends Tool
{
    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $query = array_filter([
            'page' => $request->get('page'),
            'size' => $request->get('size'),
        ], fn ($v) => $v !== null);

        try {
            $data = $this->client->get('/v1/campaigns', $query);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to list campaigns from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        $data['data'] = array_map(
            fn (array $item) => (new CampaignData($item))->toArray(),
            $data['data'] ?? [],
        );

        return Response::structured($data ?: ['data' => []]);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'page' => $schema->integer()->description('Page number (default: 1).'),
            'size' => $schema->integer()->description('Page size (default: 20).'),
        ];
    }
}
