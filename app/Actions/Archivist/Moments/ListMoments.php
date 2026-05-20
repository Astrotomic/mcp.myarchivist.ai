<?php

namespace App\Actions\Archivist\Moments;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\MomentDataShort;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListMoments extends ApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['nullable', 'string'],
            'session_id' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/moments', $input->all());
    }

    /**
     * @return ArchivistDtoCollection<int, MomentDataShort>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new MomentDataShort($item))
        );
    }
}
