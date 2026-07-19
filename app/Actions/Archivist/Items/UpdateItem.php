<?php

namespace App\Actions\Archivist\Items;

use App\Actions\Archivist\WriteApiAction;
use App\Data\ItemData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateItem extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'item_id' => ['required', 'string'],
            'name' => ['nullable', 'string'],
            'aliases' => ['nullable', 'list'],
            'type' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->patch(
            "/v1/items/{$input->string('item_id')}",
            $input->except('item_id'),
        );
    }

    protected function map(array $data): ItemData
    {
        return new ItemData($data);
    }
}
