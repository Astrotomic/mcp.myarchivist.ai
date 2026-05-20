<?php

namespace App\Actions;

use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use Illuminate\JsonSchema\Types\Type;

final readonly class RulesToJsonSchema extends Action
{
    public function __construct(protected JsonSchemaTypeFactory $schema) {}

    /**
     * @param  array<string, string|string[]>  $rules
     * @return array<string, Type>
     */
    public function execute(array $rules): array
    {
        $properties = [];

        foreach ($rules as $field => $fieldRules) {
            if (is_string($fieldRules)) {
                $fieldRules = explode('|', $fieldRules);
            }

            $type = $this->type($fieldRules);
            $type = $this->configure($type, $fieldRules);

            $properties[$field] = $type;
        }

        return $properties;
    }

    protected function type(array $rules): Type
    {
        $rules = collect($rules);

        if ($rules->intersect(['integer', 'int'])->isNotEmpty()) {
            return $this->schema->integer();
        }

        if ($rules->intersect(['boolean', 'bool'])->isNotEmpty()) {
            return $this->schema->boolean();
        }

        if ($rules->intersect(['array'])->isNotEmpty()) {
            return $this->schema->array();
        }

        if ($rules->intersect(['numeric'])->isNotEmpty()) {
            return $this->schema->number();
        }

        return $this->schema->string();
    }

    protected function configure(Type $type, array $rules): Type
    {
        foreach ($rules as $rule) {
            $parts = explode(':', $rule, 2);
            $rule = $parts[0];
            $params = explode(',', $parts[1] ?? '');

            $type = match ($rule) {
                'required' => $type->required(),
                'nullable' => $type->nullable(),
                'in' => $type->enum($params),
                default => $type,
            };
        }

        return $type;
    }
}
