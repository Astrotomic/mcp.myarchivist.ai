<?php

namespace App\Mcp\Tools\Items;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\ItemData;
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

#[Description('Get a specific item by ID.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetItemTool extends Tool
{
    use HasArchivistOutputSchema;

    #[\Override]
    protected function outputDtoClass(): string
    {
        return ItemData::class;
    }

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'item_id' => ['required', 'string'],
        ], [
            'item_id.required' => 'You must provide an item_id to retrieve a specific item.',
        ]);

        try {
            $data = $this->client->get("/v1/items/{$validated['item_id']}");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get item '{$validated['item_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return $this->structuredResponse($data);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'item_id' => $schema->string()->description('The ID of the item to retrieve.')->required(),
        ];
    }
}
