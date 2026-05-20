<?php

namespace App\Actions\Archivist\Quests;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\QuestDataShort;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListQuests extends ApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'search' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:planned,in-progress,blocked,failed,done,n/a'],
            'quest_category' => ['nullable', 'string', 'in:main,side,faction,personal,n/a'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/quests', $input->all());
    }

    /**
     * @return ArchivistDtoCollection<int, QuestDataShort>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new QuestDataShort($item))
        );
    }
}
