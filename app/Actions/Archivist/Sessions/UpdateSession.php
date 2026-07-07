<?php

namespace App\Actions\Archivist\Sessions;

use App\Actions\Archivist\WriteApiAction;
use App\Data\SessionData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateSession extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'session_id' => ['required', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:audioUpload,playByPost,discordVoice,txtUpload,rawNotes,other'],
            'public' => ['nullable', 'boolean'],
            'session_date' => ['nullable', 'string', 'date'],
            'image' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->put(
            "/v1/sessions/{$input->string('session_id')}",
            $input->except('session_id'),
        );
    }

    protected function map(array $data): SessionData
    {
        return new SessionData($data);
    }
}
