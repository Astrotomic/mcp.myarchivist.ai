<?php

namespace App\Actions\Archivist\Locations;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\LocationData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListLocations extends ListApiAction
{
    protected static function listRules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'search' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer', 'max:100'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/locations', $input->all());
    }

    /**
     * @return ArchivistDtoCollection<int, LocationData>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new LocationData($item))
        );
    }
}
