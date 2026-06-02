<?php

namespace App\Data;

use App\Actions\RulesToJsonSchema;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use Illuminate\JsonSchema\Types\Type;

class SessionHandoutData extends ArchivistDto
{
    public static function rules(): array
    {
        return array_merge(self::topLevelRules(), self::nestedRules());
    }

    /**
     * @return array<string, string|string[]>
     */
    private static function topLevelRules(): array
    {
        return [
            'summary' => ['required', 'string'],
            'sessionOutline' => ['nullable'],
            'encounters' => ['nullable', 'list'],
            'characterSpotlight' => ['nullable', 'list'],
            'otherEntitySpotlight' => ['nullable', 'list'],
            'items' => ['nullable', 'list'],
            'valuableInformation' => ['nullable', 'list'],
            'partyStatusAndNextSteps' => ['nullable', 'array'],
            'moments' => ['nullable', 'list'],
        ];
    }

    /**
     * @return array<string, string|string[]>
     */
    private static function nestedRules(): array
    {
        return [
            'encounters.*.title' => ['required_with:encounters', 'string'],
            'encounters.*.bullets' => ['nullable', 'list'],
            'characterSpotlight.*.name' => ['required_with:characterSpotlight', 'string'],
            'characterSpotlight.*.description' => ['nullable', 'string'],
            'characterSpotlight.*.bullets' => ['nullable', 'list'],
            'otherEntitySpotlight.*.name' => ['required_with:otherEntitySpotlight', 'string'],
            'otherEntitySpotlight.*.description' => ['nullable', 'string'],
            'items.*.name' => ['required_with:items', 'string'],
            'items.*.description' => ['nullable', 'string'],
            'valuableInformation.*.info' => ['required_with:valuableInformation', 'string'],
            'partyStatusAndNextSteps.partyStatus' => ['nullable', 'array'],
            'partyStatusAndNextSteps.partyStatus.summary' => ['nullable', 'string'],
            'partyStatusAndNextSteps.partyStatus.bullets' => ['nullable', 'list'],
            'partyStatusAndNextSteps.nextSteps' => ['nullable', 'array'],
            'partyStatusAndNextSteps.nextSteps.summary' => ['nullable', 'string'],
            'moments.*.label' => ['required_with:moments', 'string'],
            'moments.*.content' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, Type>
     */
    public static function toJsonSchema(): array
    {
        $schema = new JsonSchemaTypeFactory;

        $properties = RulesToJsonSchema::make()->execute(self::topLevelRules());

        $properties['partyStatusAndNextSteps'] = $schema->object([
            'partyStatus' => $schema->object([
                'summary' => $schema->string(),
                'bullets' => $schema->array(),
            ]),
            'nextSteps' => $schema->object([
                'summary' => $schema->string(),
            ]),
        ])->nullable();

        return $properties;
    }
}
