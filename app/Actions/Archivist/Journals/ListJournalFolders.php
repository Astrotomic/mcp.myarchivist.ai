<?php

namespace App\Actions\Archivist\Journals;

use App\Actions\Archivist\ListApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\JournalFolderData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListJournalFolders extends ListApiAction
{
    protected static function listRules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'parent_id' => ['nullable', 'string'],
        ];
    }


    protected static function filterableAttributes(): array
    {
        return ['campaign_id', 'parent_id'];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/journal-folders', $input->all());
    }


    protected function poolRequestForPage(array $params, int $page): array
    {
        return [
            'path' => '/v1/journal-folders',
            'query' => array_merge($params, ['page' => $page]),
            'key' => (string) $page,
        ];
    }

    /**
     * @return ArchivistDtoCollection<int, JournalFolderData>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new JournalFolderData($item))
        );
    }
}
