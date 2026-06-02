<?php

namespace App\Actions\Archivist\Moments;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\MomentData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListMoments extends ApiAction
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
        return $this->client->get('/v1/moments', $input->all());
    }

    /**
     * @return ArchivistDtoCollection<int, MomentData>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new MomentData($item))
        );
    }
}
