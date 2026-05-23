<?php

namespace App\Actions\Archivist\Quests;

use App\Actions\Archivist\ListApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\QuestDataShort;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListQuests extends ListApiAction
{
    protected static function listRules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'search' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:planned,in-progress,blocked,failed,done,n/a'],
            'quest_category' => ['nullable', 'string', 'in:main,side,faction,personal,n/a'],
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer', 'max:100'],
        ];
    }


    protected static function filterableAttributes(): array
    {
        return ['campaign_id', 'search', 'status', 'quest_category'];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/quests', $input->all());
    }


    protected function poolRequestForPage(array $params, int $page): array
    {
        return [
            'path' => '/v1/quests',
            'query' => array_merge($params, ['page' => $page]),
            'key' => (string) $page,
        ];
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
