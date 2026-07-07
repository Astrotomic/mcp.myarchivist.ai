<?php

namespace App\Actions\Archivist\Links;

use App\Actions\Archivist\WriteApiAction;
use App\Data\LinkData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateLink extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'link_id' => ['required', 'string'],
            'alias' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->patch(
            "/v1/campaigns/{$input->string('campaign_id')}/links/{$input->string('link_id')}",
            [
                'alias' => $input->string('alias')->toString(),
            ],
        );
    }

    protected function map(array $data): LinkData
    {
        return new LinkData($data);
    }
}
