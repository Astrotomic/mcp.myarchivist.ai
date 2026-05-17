<?php

namespace App\Mcp\Tools\Journals;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\JournalFolderData;
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

#[Description('List journal folders for a campaign. Folders are ordered by path and position for tree rendering.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListJournalFoldersTool extends Tool
{
    use HasArchivistOutputSchema;

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'string'],
        ], [
            'campaign_id.required' => 'You must provide a campaign_id to list journal folders.',
        ]);

        try {
            $data = $this->client->get('/v1/journal-folders', ['campaign_id' => $validated['campaign_id']]);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to list journal folders for campaign '{$validated['campaign_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        $data['data'] = array_map(
            fn (array $item) => (new JournalFolderData($item))->toArray(),
            $data['data'] ?? [],
        );

        return Response::structured($data ?: ['data' => []]);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'campaign_id' => $schema->string()->description('The campaign ID to list journal folders from.')->required(),
        ];
    }
}
