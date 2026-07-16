<?php

namespace App\Actions\Archivist\Campaigns;

use App\Actions\Archivist\ApiAction;
use App\Data\AnswerData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class AskCampaign extends ApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'question' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post('/v1/ask', [
            'campaign_id' => $input->string('campaign_id'),
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $input->string('question'),
                ],
            ],
            'gm_permissions' => true,
        ], 90);
    }

    protected function map(array $data): AnswerData
    {
        return new AnswerData($data);
    }
}
