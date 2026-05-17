<?php

namespace App\Mcp\Tools\Quests;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\QuestData;
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

#[Description('Get a fully expanded quest by ID, including objectives, progress log, related entity refs, and session provenance.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetQuestTool extends Tool
{
    use HasArchivistOutputSchema;

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'quest_id' => ['required', 'string'],
        ], [
            'quest_id.required' => 'You must provide a quest_id to retrieve a specific quest.',
        ]);

        try {
            $data = $this->client->get("/v1/quests/{$validated['quest_id']}");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get quest '{$validated['quest_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return Response::structured((new QuestData($data))->toArray());
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'quest_id' => $schema->string()->description('The ID of the quest to retrieve.')->required(),
        ];
    }
}
