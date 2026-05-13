<?php

namespace App\Mcp\Tools\Links;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\LinkData;
use App\Services\ArchivistClient;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List links between entities in a campaign. Supports filtering by source/target entity and relationship alias.')]
#[IsReadOnly]
#[IsIdempotent]
class ListLinksTool extends Tool
{
    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'string'],
            'from_id' => ['nullable', 'string'],
            'from_type' => ['nullable', 'string'],
            'to_id' => ['nullable', 'string'],
            'to_type' => ['nullable', 'string'],
            'alias' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer'],
        ], [
            'campaign_id.required' => 'You must provide a campaign_id to list links.',
        ]);

        $query = array_filter([
            'from_id' => $validated['from_id'] ?? null,
            'from_type' => $validated['from_type'] ?? null,
            'to_id' => $validated['to_id'] ?? null,
            'to_type' => $validated['to_type'] ?? null,
            'alias' => $validated['alias'] ?? null,
            'page' => $validated['page'] ?? null,
            'size' => $validated['size'] ?? null,
        ], fn ($v) => $v !== null);

        try {
            $data = $this->client->get("/v1/campaigns/{$validated['campaign_id']}/links", $query);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to list links for campaign '{$validated['campaign_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        $data['data'] = array_map(
            fn (array $item) => (new LinkData($item))->toArray(),
            $data['data'] ?? [],
        );

        return Response::structured($data ?: ['data' => []]);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'campaign_id' => $schema->string()->description('The campaign ID to list entity links from.')->required(),
            'from_id' => $schema->string()->description('Filter by source entity ID.'),
            'from_type' => $schema->string()->description('Filter by source entity type, e.g. Character, Location, Faction, Item.'),
            'to_id' => $schema->string()->description('Filter by target entity ID.'),
            'to_type' => $schema->string()->description('Filter by target entity type.'),
            'alias' => $schema->string()->description('Filter by relationship label/alias.'),
            'page' => $schema->integer()->description('Page number (default: 1).'),
            'size' => $schema->integer()->description('Page size (default: 20).'),
        ];
    }
}
