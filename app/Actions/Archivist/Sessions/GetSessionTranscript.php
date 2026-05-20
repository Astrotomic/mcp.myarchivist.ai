<?php

namespace App\Actions\Archivist\Sessions;

use App\Actions\Archivist\ApiAction;
use App\Data\TranscriptData;
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
        return $this->client->get("/v1/sessions/{$input->string('session_id')}/transcript");
    }

    protected function map(array $data): TranscriptData
    {
        return new TranscriptData($data);
    }
}
