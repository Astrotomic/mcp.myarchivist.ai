<?php

namespace App\Actions\Archivist\Beats;

use App\Actions\Archivist\ApiAction;
use App\Data\BeatData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetBeat extends ApiAction
{
    public static function rules(): array
    {
        return [
            'beat_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/beats/{$input->string('beat_id')}", $input->except('beat_id'));
    }

    protected function map(array $data): BeatData
    {
        return new BeatData($data);
    }
}
