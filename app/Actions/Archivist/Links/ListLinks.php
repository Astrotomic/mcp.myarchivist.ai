<?php

namespace App\Actions\Archivist\Links;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\LinkData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListLinks extends ApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'from_id' => ['nullable', 'string'],
            'from_type' => ['nullable', 'string'],
            'to_id' => ['nullable', 'string'],
            'to_type' => ['nullable', 'string'],
            'alias' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer', 'max:100'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/campaigns/{$input->string('campaign_id')}/links", $input->except('campaign_id'));
    }

    /**
     * @return ArchivistDtoCollection<int, LinkData>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new LinkData($item))
        );
    }
}
