<?php

namespace App\Actions\Archivist\Quests;

use App\Actions\Archivist\ApiAction;
use App\Data\QuestData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetQuest extends ApiAction
{
    public static function rules(): array
    {
        return [
            'quest_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/quests/{$input->string('quest_id')}");
    }

    protected function map(array $data): QuestData
    {
        return new QuestData($data);
    }
}
