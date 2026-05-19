<?php

namespace App\Mcp\Data;

use App\Exceptions\DtoValidationException;
use App\Exceptions\UnexpectedDtoAttributeException;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Illuminate\Validation\ValidationException;
use ReflectionClass;
use ReflectionException;

abstract class ArchivistDto extends Fluent
{
    public function __construct(array $attributes = [])
    {
        $this->validate($attributes);
        $this->checkForUnexpectedKeys($attributes);

        parent::__construct($attributes);
    }

    /**
     * Define the validation rules for this DTO's attributes.
     * Keys in this array are considered the "known" set of attributes.
     *
     * @return array<string, mixed>
     */
    abstract public function rules(): array;

    /**
     * Define the descriptions for this DTO's attributes.
     *
     * @return array<string, string>
     */
    abstract protected function descriptions(): array;

    /**
     * @param JsonSchema $schema
     * @return array<string, Type>
     * @throws ReflectionException
     */
    public static function jsonSchema(JsonSchema $schema): array
    {
        $instance = (new ReflectionClass(static::class))->newInstanceWithoutConstructor();
        $rules = $instance->rules();
        $descriptions = $instance->descriptions();

        $properties = [];

        foreach ($rules as $field => $fieldRules) {
            if (is_string($fieldRules)) {
                $fieldRules = explode('|', $fieldRules);
            }

            $type = null;
            $isRequired = false;
            $isNullable = false;
            $enum = null;

            foreach ($fieldRules as $rule) {
                if ($rule === 'required') {
                    $isRequired = true;
                } elseif ($rule === 'nullable') {
                    $isNullable = true;
                } elseif ($rule === 'string') {
                    $type = $schema->string();
                } elseif (in_array($rule, ['integer', 'int'], true)) {
                    $type = $schema->integer();
                } elseif (in_array($rule, ['boolean', 'bool'], true)) {
                    $type = $schema->boolean();
                } elseif ($rule === 'array') {
                    $type = $schema->array();
                } elseif ($rule === 'numeric') {
                    $type = $schema->number();
                } elseif (str_starts_with($rule, 'in:')) {
                    $enum = explode(',', substr($rule, 3));
                }
            }

            if ($type === null) {
                $type = $schema->string();
            }

            if ($isRequired) {
                $type->required();
            }

            if ($isNullable) {
                $type->nullable();
            }

            if ($enum) {
                $type->enum($enum);
            }

            if (isset($descriptions[$field])) {
                $type->description($descriptions[$field]);
            }

            $properties[$field] = $type;
        }

        return $properties;
    }

    private function validate(array $attributes): void
    {
        $validator = Validator::make($attributes, $this->rules());

        if ($validator->fails()) {
            report(new DtoValidationException(
                dtoClass: static::class,
                errors: $validator->errors(),
                previous: ValidationException::withMessages($validator->errors()->toArray()),
            ));
        }
    }

    private function checkForUnexpectedKeys(array $attributes): void
    {
        $knownKeys = array_keys($this->rules());

        foreach ($attributes as $key => $value) {
            if (! in_array($key, $knownKeys, strict: true)) {
                report(new UnexpectedDtoAttributeException(
                    dtoClass: static::class,
                    key: (string) $key,
                    value: $value,
                ));
            }
        }
    }
}
