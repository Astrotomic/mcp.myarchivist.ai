<?php

namespace App\Data;

use App\Mcp\Tools\Beats\GetBeatTool;
use App\Mcp\Tools\Characters\GetCharacterTool;
use App\Mcp\Tools\Factions\GetFactionTool;
use App\Mcp\Tools\Items\GetItemTool;
use App\Mcp\Tools\Locations\GetLocationTool;
use App\Mcp\Tools\Quests\GetQuestTool;
use App\Mcp\Tools\Sessions\GetSessionTool;
use App\Mcp\Tools\Tool;
use Illuminate\Support\Str;
use OutOfBoundsException;

class AnswerData extends ArchivistDto
{
    public function __construct(array $attributes = [])
    {
        $attributes['citations'] = $this->mapCitations($attributes['citations'] ?? []);

        parent::__construct($attributes);
    }

    public static function rules(): array
    {
        return [
            'answer' => ['required', 'string'],
            'citations' => ['nullable', 'list'],
            'citations.*' => ['sometimes', 'array', 'required_array_keys:source_type,source_id,excerpt,mcp_tool'],
            'citations.*.source_type' => ['sometimes', 'string', 'in:beat,location,item,character,session,faction,quest'],
            'citations.*.source_id' => ['sometimes', 'string'],
            'citations.*.excerpt' => ['sometimes', 'string'],
            'citations.*.mcp_tool' => ['nullable', 'array', 'required_array_keys:name,arguments'],
            'citations.*.mcp_tool.name' => ['sometimes', 'string'],
            'citations.*.mcp_tool.arguments' => ['sometimes', 'array'],
            'monthlyTokensRemaining' => ['required', 'int'],
            'hourlyTokensRemaining' => ['required', 'int'],
        ];
    }

    private function mapCitations(array $citations): ?array
    {
        if (empty($citations)) {
            return null;
        }

        return collect($citations)->map(function (array $citation): array {
            $type = match ($citation['source_type']) {
                'timeline' => 'beat',
                'recap' => 'session',
                default => $citation['source_type'],
            };

            /** @var ?Tool $tool */
            $tool = rescue(fn () => app(match ($type) {
                'beat' => GetBeatTool::class,
                'location' => GetLocationTool::class,
                'item' => GetItemTool::class,
                'character' => GetCharacterTool::class,
                'session' => GetSessionTool::class,
                'faction' => GetFactionTool::class,
                'quest' => GetQuestTool::class,
                default => throw new OutOfBoundsException("Unknown tool type: $type"),
            }));
            $argumentKey = Str::of($type)
                ->singular()
                ->lower()
                ->append('_id')
                ->toString();

            return [
                'source_type' => $type,
                'source_id' => $citation['source_id'],
                'excerpt' => $citation['excerpt'],
                'mcp_tool' => $tool ? [
                    'name' => $tool->name(),
                    'arguments' => [$argumentKey => $citation['source_id']],
                ] : null,
            ];
        })->toArray();
    }
}
