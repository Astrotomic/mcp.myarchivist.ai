<?php

namespace App\Actions\Archivist\Campaigns;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\CampaignDataShort;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListCampaigns extends ApiAction
{
    public static function rules(): array
    {
        return static::paginationRules();
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/campaigns', $input->all());
    }

    /**
     * @return ArchivistDtoCollection<int, CampaignDataShort>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new CampaignDataShort($item))
        );
    }
}
