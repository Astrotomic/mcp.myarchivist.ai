<?php

namespace App\Actions\Archivist\Spotlights;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\SpotlightData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListSpotlights extends ApiAction
{
    public static function rules(): array
    {
        return array_merge(self::paginationRules(), [
            'campaign_id' => ['nullable', 'string'],
            'session_id' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ]);
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/spotlights', $input->all());
    }

    /**
     * @return ArchivistDtoCollection<int, SpotlightData>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new SpotlightData($item))
        );
    }
}
