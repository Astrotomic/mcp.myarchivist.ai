<?php

namespace App\Actions\Archivist\Sessions;

use App\Actions\Archivist\ApiAction;
use App\Data\SessionHandoutData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetSessionHandout extends ApiAction
{
    public static function rules(): array
    {
        return [
            'session_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/sessions/{$input->string('session_id')}/handout", $input->except('session_id'));
    }

    protected function map(array $data): SessionHandoutData
    {
        return new SessionHandoutData($data);
    }
}
