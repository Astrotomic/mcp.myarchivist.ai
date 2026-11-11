<?php

namespace App\Actions\Archivist\Items;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Concerns\LoadsEntityProperties;
use App\Data\ItemData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetItem extends ApiAction
{
    use LoadsEntityProperties;

    public static function rules(): array
    {
        return [
            'item_id' => ['required', 'string'],
            'with_links' => ['nullable', 'boolean'],
            'with_properties' => ['nullable', 'boolean'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/items/{$input->string('item_id')}", $input->except(['item_id', 'with_properties']));
    }

    protected function interpretResponse(ValidatedInput $input, Response $response): ItemData
    {
        return $this->map($this->withProperties($input, $response->fluent()->all(), 'items'));
    }

    protected function map(array $data): ItemData
    {
        return new ItemData($data);
    }
}
