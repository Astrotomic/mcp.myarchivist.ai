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

#[Description('Get a specific character by ID including aliases, backstory, and speaker linkage.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetCharacterTool extends Tool
{
    use HasArchivistOutputSchema;

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'character_id' => ['required', 'string'],
        ], [
            'character_id.required' => 'You must provide a character_id to retrieve a specific character.',
        ]);

        try {
            $data = $this->client->get("/v1/characters/{$validated['character_id']}");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get character '{$validated['character_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return Response::structured((new CharacterData($data))->toArray());
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'character_id' => $schema->string()->description('The ID of the character to retrieve.')->required(),
        ];
    }
}
