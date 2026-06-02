<?php

namespace App\Actions\Archivist\Factions;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\FactionData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListFactions extends ApiAction
{
    public static function rules(): array
    {
        return array_merge(static::paginationRules(), [
            'campaign_id' => ['required', 'string'],
            'search' => ['nullable', 'string'],
        ]);
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/factions', $input->all());
    }

    /**
     * @return ArchivistDtoCollection<int, FactionData>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new FactionData($item))
        );
    }
}
