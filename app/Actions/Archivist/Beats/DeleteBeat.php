<?php

namespace App\Actions\Archivist\Beats;

use App\Actions\Archivist\WriteApiAction;
use App\Data\SuccessData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class DeleteBeat extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'beat_id' => ['required', 'string'],
        ];
    }

    protected function returnsSuccessData(): bool
    {
        return true;
    }

    protected function successData(ValidatedInput $input, Response $response): SuccessData
    {
        return new SuccessData([
            'success' => $response->successful(),
            'id' => $input->string('beat_id')->toString(),
        ]);
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->delete("/v1/beats/{$input->string('beat_id')}");
    }

    protected function map(array $data): SuccessData
    {
        return new SuccessData([
            'success' => true,
            'id' => is_string($data['id'] ?? null) ? $data['id'] : null,
        ]);
    }
}
