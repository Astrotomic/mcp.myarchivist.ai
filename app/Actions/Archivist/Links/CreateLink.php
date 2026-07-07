<?php

namespace App\Actions\Archivist\Links;

use App\Actions\Archivist\WriteApiAction;
use App\Data\LinkData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateLink extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'from_id' => ['required', 'string'],
            'from_type' => ['required', 'string', 'in:Character,Faction,Location,Item,Moment,GameSession,Beat,Backstory,Journal,World'],
            'to_id' => ['required', 'string'],
            'to_type' => ['required', 'string', 'in:Character,Faction,Location,Item,Moment'],
            'alias' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post(
            "/v1/campaigns/{$input->string('campaign_id')}/links",
            [
                'campaign_id' => $input->string('campaign_id')->toString(),
                'from_id' => $input->string('from_id')->toString(),
                'from_type' => $input->string('from_type')->toString(),
                'to_id' => $input->string('to_id')->toString(),
                'to_type' => $input->string('to_type')->toString(),
                'alias' => $input->string('alias')->toString(),
            ],
        );
    }

    protected function map(array $data): LinkData
    {
        return new LinkData($data);
    }
}
