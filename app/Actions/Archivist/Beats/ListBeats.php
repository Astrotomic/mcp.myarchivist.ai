<?php

namespace App\Actions\Archivist\Beats;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\BeatData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListBeats extends ListApiAction
{
    protected static function listRules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer', 'max:100'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/beats', $input->all());
    }

    /**
     * @return ArchivistDtoCollection<int, BeatData>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new BeatData($item))
        );
    }
}
