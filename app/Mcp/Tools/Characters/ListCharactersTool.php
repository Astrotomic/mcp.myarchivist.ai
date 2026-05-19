<?php

namespace App\Mcp\Tools\Characters;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\CharacterData;
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

#[Description('List characters in a campaign. Optionally filter by name search, character type, and approval status.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListCharactersTool extends Tool
{
    use HasArchivistOutputSchema;

    #[\Override]
    protected function outputDtoClass(): string
    {
        return CharacterData::class;
    }

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'string'],
            'search' => ['nullable', 'string'],
            'character_type' => ['nullable', 'string'],
            'approved_only' => ['nullable', 'boolean'],
        ], [
            'campaign_id.required' => 'You must provide a campaign_id to list characters.',
        ]);

        $query = array_filter([
            'campaign_id' => $validated['campaign_id'],
            'search' => $validated['search'] ?? null,
            'character_type' => $validated['character_type'] ?? null,
            'approved_only' => isset($validated['approved_only']) ? ($validated['approved_only'] ? 'true' : 'false') : null,
        ], fn ($v) => $v !== null);

        try {
            $data = $this->client->get('/v1/characters', $query);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to list characters for campaign '{$validated['campaign_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return $this->structuredResponse($data);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'campaign_id' => $schema->string()->description('The campaign ID to list characters from.')->required(),
            'search' => $schema->string()->description('Search characters by name.'),
            'character_type' => $schema->string()->description('Filter by type, e.g. PC or NPC.'),
            'approved_only' => $schema->boolean()->description('When true, only return approved characters (default: true on the API).'),
        ];
    }
}
