<?php

namespace App\Actions\Archivist\Journals;

use App\Actions\Archivist\WriteApiAction;
use App\Data\SuccessData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateJournalFolder extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'folder_id' => ['required', 'string'],
            'name' => ['nullable', 'string', 'max:255'],
            'path' => ['nullable', 'string', 'max:1024'],
            'parent_id' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'position' => ['nullable', 'integer'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        $body = $input->except('folder_id');
        $body['id'] = $input->string('folder_id')->toString();

        return $this->client->put('/v1/journal-folders', $body);
    }

    protected function map(array $data): SuccessData
    {
        return new SuccessData([
            'success' => (bool) ($data['success'] ?? false),
            'id' => is_string($data['id'] ?? null) ? $data['id'] : null,
        ]);
    }
}
