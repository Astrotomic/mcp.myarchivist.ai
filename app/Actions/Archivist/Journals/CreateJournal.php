<?php

namespace App\Actions\Archivist\Journals;

use App\Actions\Archivist\WriteApiAction;
use App\Data\SuccessData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateJournal extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'content_rich' => ['nullable', 'array'],
            'content_metadata' => ['nullable', 'array'],
            'tags' => ['nullable', 'list'],
            'cover_image' => ['nullable', 'string'],
            'is_pinned' => ['nullable', 'boolean'],
            'is_public' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'string'],
            'archived_at' => ['nullable', 'string'],
            'folder_id' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post('/v1/journals', $input->all());
    }

    protected function map(array $data): SuccessData
    {
        return new SuccessData([
            'success' => (bool) ($data['success'] ?? false),
            'id' => is_string($data['id'] ?? null) ? $data['id'] : null,
        ]);
    }
}
