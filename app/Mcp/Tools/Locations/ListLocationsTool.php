<?php

namespace App\Mcp\Tools\Locations;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\LocationData;
use App\Services\ArchivistClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List locations in a campaign. Locations can be nested (cities, taverns, dungeons, etc.).')]
#[IsReadOnly]
#[IsIdempotent]
class ListLocationsTool extends Tool
{
    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'string'],
        ], [
            'campaign_id.required' => 'You must provide a campaign_id to list locations.',
        ]);

        try {
            $data = $this->client->get('/v1/locations', ['campaign_id' => $validated['campaign_id']]);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to list locations for campaign '{$validated['campaign_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        $data['data'] = array_map(
            fn (array $item) => (new LocationData($item))->toArray(),
            $data['data'] ?? [],
        );

        return Response::structured($data ?: ['data' => []]);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'campaign_id' => $schema->string()->description('The campaign ID to list locations from.')->required(),
        ];
    }
}
