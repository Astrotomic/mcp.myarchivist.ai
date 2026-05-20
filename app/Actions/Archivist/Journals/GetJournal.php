<?php

namespace App\Actions\Archivist\Journals;

use App\Actions\Archivist\ApiAction;
use App\Data\JournalData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetJournal extends ApiAction
{
    public static function rules(): array
    {
        return [
            'entry_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/journals/{$input->string('entry_id')}");
    }

    protected function map(array $data): JournalData
    {
        return new JournalData($data);
    }
}
