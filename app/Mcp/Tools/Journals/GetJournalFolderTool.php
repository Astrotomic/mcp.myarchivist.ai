<?php

namespace App\Mcp\Tools\Journals;

use App\Data\JournalFolderData;
use App\Exceptions\ArchivistApiException;
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

#[Description('Get a specific journal folder by ID.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetJournalFolderTool extends Tool
{
    use HasArchivistOutputSchema;

    protected function outputDtoClass(): string
    {
        return JournalFolderData::class;
    }

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'folder_id' => ['required', 'string'],
        ], [
            'folder_id.required' => 'You must provide a folder_id to retrieve a specific journal folder.',
        ]);

        try {
            $data = $this->client->get("/v1/journal-folders/{$validated['folder_id']}");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get journal folder '{$validated['folder_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return $this->structuredResponse($data);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'folder_id' => $schema->string()->description('The ID of the journal folder to retrieve.')->required(),
        ];
    }
}
