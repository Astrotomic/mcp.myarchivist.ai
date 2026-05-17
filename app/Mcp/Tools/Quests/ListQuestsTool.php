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

#[Description('List quests in a campaign with pagination. Filter by status (planned, in-progress, blocked, failed, done, n/a) or category (main, side, faction, personal, n/a).')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListQuestsTool extends Tool
{
    use HasArchivistOutputSchema;

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'campaign_id' => ['required', 'string'],
            'search' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:planned,in-progress,blocked,failed,done,n/a'],
            'quest_category' => ['nullable', 'string', 'in:main,side,faction,personal,n/a'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer'],
        ], [
            'campaign_id.required' => 'You must provide a campaign_id to list quests.',
            'status.in' => 'Status must be one of: planned, in-progress, blocked, failed, done, n/a.',
            'quest_category.in' => 'Quest category must be one of: main, side, faction, personal, n/a.',
        ]);

        $query = array_filter([
            'campaign_id' => $validated['campaign_id'],
            'search' => $validated['search'] ?? null,
            'status' => $validated['status'] ?? null,
            'quest_category' => $validated['quest_category'] ?? null,
            'page' => $validated['page'] ?? null,
            'size' => $validated['size'] ?? null,
        ], fn ($v) => $v !== null);

        try {
            $data = $this->client->get('/v1/quests', $query);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to list quests for campaign '{$validated['campaign_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        $data['data'] = array_map(
            fn (array $item) => (new QuestData($item))->toArray(),
            $data['data'] ?? [],
        );

        return Response::structured($data ?: ['data' => []]);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'campaign_id' => $schema->string()->description('The campaign ID to list quests from.')->required(),
            'search' => $schema->string()->description('Search by quest name, giver, or narrative fields.'),
            'status' => $schema->string()->enum(['planned', 'in-progress', 'blocked', 'failed', 'done', 'n/a'])->description('Filter by quest status.'),
            'quest_category' => $schema->string()->enum(['main', 'side', 'faction', 'personal', 'n/a'])->description('Filter by quest category.'),
            'page' => $schema->integer()->description('Page number (default: 1).'),
            'size' => $schema->integer()->description('Page size (default: 20).'),
        ];
    }
}
