<?php

namespace App\Actions\Archivist\Moments;

use App\Actions\Archivist\ListApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\MomentData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListMoments extends ListApiAction
{
    protected static function listRules(): array
    {
        return [
            'campaign_id' => ['nullable', 'string'],
            'session_id' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer', 'max:100'],
        ];
    }


    protected static function filterableAttributes(): array
    {
        return ['campaign_id', 'session_id', 'search'];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/moments', $input->all());
    }


    protected function poolRequestForPage(array $params, int $page): array
    {
        return [
            'path' => '/v1/moments',
            'query' => array_merge($params, ['page' => $page]),
            'key' => (string) $page,
        ];
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
