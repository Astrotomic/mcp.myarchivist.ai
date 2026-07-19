<?php

namespace App\Actions\Archivist\Links;

use App\Actions\Archivist\WriteApiAction;
use App\Data\LinkMaintenanceResultData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class BulkLinkMaintenance extends WriteApiAction
{
    private const TARGET_ROUTES = [
        'Character' => '/v1/characters/links/maintenance',
        'Faction' => '/v1/factions/links/maintenance',
        'Location' => '/v1/locations/links/maintenance',
        'Item' => '/v1/items/links/maintenance',
        'Moment' => '/v1/moments/links/maintenance',
    ];

    public static function rules(): array
    {
        return [
            'operation' => ['required', 'string', 'in:add,remove,update'],
            'campaign_id' => ['required', 'string'],
            'target_id' => ['required', 'string'],
            'target_type' => ['required', 'string', 'in:Character,Faction,Location,Item,Moment'],
            'alias' => ['nullable', 'string', 'max:255'],
            'new_alias' => ['nullable', 'string', 'max:255'],
            'new_target_id' => ['nullable', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        $targetType = $input->string('target_type')->toString();
        $operation = $input->string('operation')->toString();

        $body = [
            'operation' => $operation,
            'worldId' => $input->string('campaign_id')->toString(),
            'targetId' => $input->string('target_id')->toString(),
        ];

        if ($operation === 'update') {
            if ($input->filled('new_alias')) {
                $body['newAlias'] = $input->string('new_alias')->toString();
            }
            if ($input->filled('new_target_id')) {
                $body['newTargetId'] = $input->string('new_target_id')->toString();
            }
        } else {
            $body['targetType'] = $targetType;
            $body['alias'] = $input->string('alias')->toString();
        }

        return $this->client->post(self::TARGET_ROUTES[$targetType], $body);
    }

    protected function map(array $data): LinkMaintenanceResultData
    {
        $taskId = $data['taskId'] ?? $data['task_id'] ?? null;

        return new LinkMaintenanceResultData([
            'success' => (bool) ($data['success'] ?? false),
            'task_id' => is_string($taskId) && $taskId !== '' ? $taskId : null,
        ]);
    }
}
