<?php

namespace App\Actions\Archivist\Items;

use App\Actions\Archivist\ApiAction;
use App\Data\ItemData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetItem extends ApiAction
{
    public static function rules(): array
    {
        return [
            'item_id' => ['required', 'string'],
            'with_links' => ['nullable', 'boolean'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/items/{$input->string('item_id')}", $input->except('item_id'));
    }

    protected function map(array $data): ItemData
    {
        return new ItemData($data);
    }
}
