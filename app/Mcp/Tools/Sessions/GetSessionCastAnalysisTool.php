<?php

namespace App\Mcp\Tools\Sessions;

use App\Exceptions\ArchivistApiException;
use App\Mcp\Data\CastAnalysisData;
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

#[Description('Get the cast analysis for a game session, including talk-share breakdown and core session metrics.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetSessionCastAnalysisTool extends Tool
{
    use HasArchivistOutputSchema;

    #[\Override]
    protected function outputDtoClass(): string
    {
        return CastAnalysisData::class;
    }

    public function __construct(
        private readonly ArchivistClient $client,
    ) {}

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'session_id' => ['required', 'string'],
        ], [
            'session_id.required' => 'You must provide a session_id to retrieve cast analysis.',
        ]);

        try {
            $data = $this->client->get("/v1/sessions/{$validated['session_id']}/cast-analysis");
        } catch (ArchivistApiException $e) {
            return Response::error("Failed to get cast analysis for session '{$validated['session_id']}' from MyArchivist API (HTTP {$e->status}): {$e->detail}");
        }

        return Response::structured((new CastAnalysisData($data))->toArray());
    }

    #[\Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'session_id' => $schema->string()->description('The ID of the session to retrieve cast analysis for.')->required(),
        ];
    }
}
