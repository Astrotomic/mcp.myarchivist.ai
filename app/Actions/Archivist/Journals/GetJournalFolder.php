<?php

namespace App\Actions\Archivist\Journals;

use App\Actions\Archivist\ApiAction;
use App\Data\JournalFolderData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetJournalFolder extends ApiAction
{
    public static function rules(): array
    {
        return [
            'folder_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/journal-folders/{$input->string('folder_id')}");
    }

    protected function map(array $data): JournalFolderData
    {
        return new JournalFolderData($data);
    }
}
