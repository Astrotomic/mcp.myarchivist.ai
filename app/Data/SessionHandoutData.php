<?php

namespace App\Data;

use App\Actions\RulesToJsonSchema;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;

class SessionHandoutData extends ArchivistDto
{
    public static function rules(): array
    {
        return array_merge(static::topLevelRules(), static::nestedRules());
    }

    /**
     * @return array<string, string|string[]>
     */
    private static function topLevelRules(): array
    {
        return [
            'summary' => ['required', 'string'],
            'sessionOutline' => ['nullable'],
            'encounters' => ['nullable', 'array'],
            'characterSpotlight' => ['nullable', 'array'],
            'otherEntitySpotlight' => ['nullable', 'array'],
            'items' => ['nullable', 'array'],
            'valuableInformation' => ['nullable', 'array'],
            'partyStatusAndNextSteps' => ['nullable', 'array'],
            'moments' => ['nullable', 'array'],
        ];
    }

    /**
     * @return array<string, string|string[]>
     */
    private static function nestedRules(): array
    {
        return [
            'encounters.*.title' => ['required_with:encounters', 'string'],
            'encounters.*.bullets' => ['nullable', 'array'],
            'characterSpotlight.*.name' => ['required_with:characterSpotlight', 'string'],
            'characterSpotlight.*.description' => ['nullable', 'string'],
            'characterSpotlight.*.bullets' => ['nullable', 'array'],
            'otherEntitySpotlight.*.name' => ['required_with:otherEntitySpotlight', 'string'],
            'otherEntitySpotlight.*.description' => ['nullable', 'string'],
            'items.*.name' => ['required_with:items', 'string'],
            'items.*.description' => ['nullable', 'string'],
            'valuableInformation.*.info' => ['required_with:valuableInformation', 'string'],
            'partyStatusAndNextSteps.partyStatus' => ['nullable', 'array'],
            'partyStatusAndNextSteps.partyStatus.summary' => ['nullable', 'string'],
            'partyStatusAndNextSteps.partyStatus.bullets' => ['nullable', 'array'],
            'partyStatusAndNextSteps.nextSteps' => ['nullable', 'array'],
            'partyStatusAndNextSteps.nextSteps.summary' => ['nullable', 'string'],
            'moments.*.label' => ['required_with:moments', 'string'],
            'moments.*.content' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, \Illuminate\JsonSchema\Types\Type>
     */
    public static function toJsonSchema(): array
    {
        $schema = new JsonSchemaTypeFactory;

        $properties = RulesToJsonSchema::make()->execute(static::topLevelRules());

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
