<?php

namespace App\Actions\Archivist\Quotes;

use App\Actions\Archivist\ApiAction;
use App\Data\QuoteData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetQuote extends ApiAction
{
    public static function rules(): array
    {
        return [
            'quote_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/quotes/{$input->string('quote_id')}");
    }

    protected function map(array $data): QuoteData
    {
        return new QuoteData($data);
    }
}
