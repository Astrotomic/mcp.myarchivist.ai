<?php

namespace App\Actions\Archivist\Quotes;

use App\Actions\Archivist\WriteApiAction;
use App\Data\SuccessData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class DeleteQuote extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'quote_id' => ['required', 'string'],
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
            'id' => $input->string('quote_id')->toString(),
        ]);
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->delete("/v1/quotes/{$input->string('quote_id')}");
    }

    protected function map(array $data): SuccessData
    {
        return new SuccessData([
            'success' => true,
            'id' => is_string($data['id'] ?? null) ? $data['id'] : null,
        ]);
    }
}
