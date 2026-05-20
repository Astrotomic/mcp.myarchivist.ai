<?php

namespace App\Actions\Archivist\Sessions;

use App\Actions\Archivist\ApiAction;
use App\Data\SessionData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetSession extends ApiAction
{
    public static function rules(): array
    {
        return [
            'session_id' => ['required', 'string'],
            'include_beats' => ['nullable', 'boolean'],
            'include_moments' => ['nullable', 'boolean'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/sessions/{$input->string('session_id')}", $input->except('session_id'));
    }

    protected function map(array $data): SessionData
    {
        return new SessionData($data);
    }
}
