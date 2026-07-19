<?php

namespace App\Actions\Archivist\Quests;

use App\Actions\Archivist\WriteApiAction;
use App\Data\QuestData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateQuest extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'quest_id' => ['required', 'string'],
            'quest_name' => ['nullable', 'string', 'max:255'],
            'quest_giver' => ['nullable', 'string', 'max:255'],
            'quest_category' => ['nullable', 'string', 'in:main,side,faction,personal,n/a'],
            'status' => ['nullable', 'string', 'in:planned,in-progress,blocked,failed,done,n/a'],
            'success_definition' => ['nullable', 'string', 'max:2000'],
            'failure_conditions' => ['nullable', 'string', 'max:2000'],
            'next_action' => ['nullable', 'string', 'max:2000'],
            'resolution' => ['nullable', 'string', 'max:2000'],
            'objectives' => ['nullable', 'list'],
            'progress_log' => ['nullable', 'list'],
            'related_characters' => ['nullable', 'list'],
            'related_factions' => ['nullable', 'list'],
            'related_locations' => ['nullable', 'list'],
            'related_items' => ['nullable', 'list'],
            'related_entity_refs' => ['nullable', 'list'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->patch(
            "/v1/quests/{$input->string('quest_id')}",
            $input->except('quest_id'),
        );
    }

    protected function map(array $data): QuestData
    {
        return new QuestData($data);
    }
}
