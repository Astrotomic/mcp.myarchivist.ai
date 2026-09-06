<?php

namespace App\Actions\Archivist\Quotes;

use App\Actions\Archivist\WriteApiAction;
use App\Data\QuoteData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateQuote extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'session_id' => ['required', 'string'],
            'text' => ['required', 'string', 'max:8000'],
            'speaker' => ['nullable', 'string', 'max:200'],
            'character_name' => ['nullable', 'string', 'max:120'],
            'context' => ['nullable', 'string', 'max:2000'],
            'style' => ['nullable', 'string', 'max:40'],
            'custom_theme' => ['nullable', 'array'],
            'order_index' => ['nullable', 'integer'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post('/v1/quotes', $input->all());
    }

    protected function map(array $data): QuoteData
    {
        return new QuoteData($data);
    }
}
