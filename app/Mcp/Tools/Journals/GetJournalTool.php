<?php

namespace App\Mcp\Tools\Journals;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\JournalData;
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

#[Description('Get a specific journal entry by ID including full content and the caller\'s effective permission level.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetJournalTool extends Tool
{
    use HasArchivistOutputSchema;

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'entry_id' => ['required', 'string'],
        ], [
            'entry_id.required' => 'You must provide an entry_id to retrieve a specific journal entry.',
        ]);

        try {
            $data = $this->client->get("/v1/journals/{$validated['entry_id']}");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get journal entry '{$validated['entry_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return Response::structured((new JournalData($data))->toArray());
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'entry_id' => $schema->string()->description('The ID of the journal entry to retrieve.')->required(),
        ];
    }
}
