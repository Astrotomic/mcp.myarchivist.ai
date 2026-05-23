<?php

namespace App\Actions\Archivist\Journals;

use App\Actions\Archivist\ApiAction;
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

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/journal-folders', $input->all());
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
