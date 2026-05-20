<?php

namespace App\Data;

use App\Actions\RulesToJsonSchema;
use App\Contracts\JsonSchemaable;
use App\Exceptions\DtoValidationException;
use App\Exceptions\UnexpectedDtoAttributeException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Illuminate\Validation\ValidationException;

abstract class ArchivistDto extends Fluent implements JsonSchemaable
{
    public function __construct(array $attributes = [])
    {
        $this->validate($attributes);
        $this->checkForUnexpectedKeys($attributes);

        parent::__construct($attributes);
    }

    /**
     * @return array<string, string|string[]>
     */
    abstract public static function rules(): array;

    private function validate(array $attributes): void
    {
        $validator = Validator::make($attributes, static::rules());

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
        $unexpected = collect($attributes)
            ->keys()
            ->diff(array_keys(static::rules()))
            ->values();

        if ($unexpected->isNotEmpty()) {
            report(new UnexpectedDtoAttributeException(
                dtoClass: static::class,
                keys: $unexpected->all(),
            ));
        }
    }

    public static function toJsonSchema(): array
    {
        return RulesToJsonSchema::make()->execute(static::rules());
    }
}
