<?php

namespace App\Actions\Archivist\Characters;

use App\Actions\Archivist\ListApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\CharacterData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListCharacters extends ListApiAction
{
    protected static function listRules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'search' => ['nullable', 'string'],
            'character_type' => ['nullable', 'string'],
            'approved_only' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer', 'max:100'],
        ];
    }


    protected static function filterableAttributes(): array
    {
        return ['campaign_id', 'search', 'character_type', 'approved_only'];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/characters', $input->all());
    }


    protected function poolRequestForPage(array $params, int $page): array
    {
        return [
            'path' => '/v1/characters',
            'query' => array_merge($params, ['page' => $page]),
            'key' => (string) $page,
        ];
    }

    /**
     * @return ArchivistDtoCollection<int, CharacterData>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new CharacterData($item))
        );
    }
}
