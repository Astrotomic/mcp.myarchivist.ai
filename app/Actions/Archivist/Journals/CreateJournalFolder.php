<?php

namespace App\Actions\Archivist\Journals;

use App\Actions\Archivist\WriteApiAction;
use App\Data\SuccessData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateJournalFolder extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'path' => ['required', 'string', 'max:1024'],
            'parent_id' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'position' => ['nullable', 'integer'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post('/v1/journal-folders', $input->all());
    }

    protected function map(array $data): SuccessData
    {
        return new SuccessData([
            'success' => (bool) ($data['success'] ?? false),
            'id' => is_string($data['id'] ?? null) ? $data['id'] : null,
        ]);
    }
}
