<?php

namespace App\Actions\Archivist\HeroAwards;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\HeroAwardData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListHeroAwards extends ApiAction
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
        return $this->client->get('/v1/hero-awards', $input->all());
    }

    /**
     * @return ArchivistDtoCollection<int, HeroAwardData>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new HeroAwardData($item))
        );
    }
}
