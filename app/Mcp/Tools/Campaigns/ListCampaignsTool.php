<?php

namespace App\Mcp\Tools\Campaigns;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\CampaignData;
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

#[Description('List your MyArchivist campaigns. Returns a paginated list of campaigns.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListCampaignsTool extends Tool
{
    use HasArchivistOutputSchema;

    #[\Override]
    protected function outputDtoClass(): string
    {
        return CampaignData::class;
    }

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $query = array_filter([
            'page' => $request->get('page'),
            'size' => $request->get('size'),
        ], fn ($v) => $v !== null);

        try {
            $data = $this->client->get('/v1/campaigns', $query);
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to list campaigns from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return $this->structuredResponse($data);
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'page' => $schema->integer()->description('Page number (default: 1).'),
            'size' => $schema->integer()->description('Page size (default: 20).'),
        ];
    }
}
