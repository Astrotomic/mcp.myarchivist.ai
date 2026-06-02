<?php

namespace App\Actions\Archivist\Characters;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\CharacterData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListCharacters extends ApiAction
{
    public static function rules(): array
    {
        return array_merge(self::paginationRules(), [
            'campaign_id' => ['required', 'string'],
            'search' => ['nullable', 'string'],
            'character_type' => ['nullable', 'string'],
            'approved_only' => ['nullable', 'boolean'],
        ]);
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/characters', $input->all());
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
