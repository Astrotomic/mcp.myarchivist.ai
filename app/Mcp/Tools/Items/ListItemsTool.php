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

#[Description('List items in a campaign. Items include weapons, armour, artefacts, and other notable objects.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListItemsTool extends Tool
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
            'campaign_id' => ['required', 'string'],
        ], [
            'campaign_id.required' => 'You must provide a campaign_id to list items.',
        ]);

        try {
            $data = $this->client->get('/v1/items', ['campaign_id' => $validated['campaign_id']]);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to list items for campaign '{$validated['campaign_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return $this->structuredResponse($data);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'campaign_id' => $schema->string()->description('The campaign ID to list items from.')->required(),
        ];
    }
}
