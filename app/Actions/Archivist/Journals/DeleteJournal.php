<?php

namespace App\Actions\Archivist\Journals;

use App\Actions\Archivist\WriteApiAction;
use App\Data\SuccessData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class DeleteJournal extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'entry_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->delete('/v1/journals', [
            'id' => $input->string('entry_id')->toString(),
        ]);
    }

    protected function map(array $data): SuccessData
    {
        return new SuccessData([
            'success' => (bool) ($data['success'] ?? false),
            'id' => is_string($data['id'] ?? null) ? $data['id'] : null,
        ]);
    }
}
