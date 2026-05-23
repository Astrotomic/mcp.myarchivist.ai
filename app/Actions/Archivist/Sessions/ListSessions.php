<?php

namespace App\Actions\Archivist\Sessions;

use App\Actions\Archivist\ListApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\SessionDataShort;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListSessions extends ListApiAction
{
    protected static function listRules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'session_type' => ['nullable', 'string', 'in:audioUpload,playByPost,discordVoice,rawNotes'],
            'public_only' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer', 'max:100'],
        ];
    }


    protected static function filterableAttributes(): array
    {
        return ['campaign_id', 'session_type', 'public_only'];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/sessions', $input->all());
    }


    protected function poolRequestForPage(array $params, int $page): array
    {
        return [
            'path' => '/v1/sessions',
            'query' => array_merge($params, ['page' => $page]),
            'key' => (string) $page,
        ];
    }

    /**
     * @return ArchivistDtoCollection<int, SessionDataShort>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new SessionDataShort($item))
        );
    }
}
