<?php

namespace App\Actions\Archivist\Sessions;

use App\Actions\Archivist\ApiAction;
use App\Data\SessionTranscriptData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetSessionTranscript extends ApiAction
{
    public static function rules(): array
    {
        return [
            'session_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/sessions/{$input->string('session_id')}/transcript", $input->except('session_id'));
    }

    protected function map(array $data): SessionTranscriptData
    {
        return new SessionTranscriptData($data);
    }
}
