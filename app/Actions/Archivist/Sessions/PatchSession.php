<?php

namespace App\Actions\Archivist\Sessions;

use App\Actions\Archivist\WriteApiAction;
use App\Data\SessionData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class PatchSession extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'session_id' => ['required', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
            'session_date' => ['nullable', 'string', 'date'],
            'summary' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:audioUpload,playByPost,discordVoice,txtUpload,rawNotes,other'],
            'public' => ['nullable', 'boolean'],
            'image' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->patch(
            "/v1/sessions/{$input->string('session_id')}",
            // type and public are non-nullable on the API; treat an explicit null as "omitted".
            array_filter(
                $input->except('session_id'),
                static fn (mixed $value, string $key): bool => $value !== null || ! in_array($key, ['type', 'public'], true),
                ARRAY_FILTER_USE_BOTH,
            ),
        );
    }

    protected function map(array $data): SessionData
    {
        return new SessionData($data);
    }
}
