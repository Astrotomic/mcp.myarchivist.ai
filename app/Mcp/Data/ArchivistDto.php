<?php

namespace App\Mcp\Data;

use App\Exceptions\DtoValidationException;
use App\Exceptions\UnexpectedDtoAttributeException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Illuminate\Validation\ValidationException;

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
        $unexpected = collect($this->rules())
            ->keys()
            ->diff(array_keys($attributes))
            ->values();

        if ($unexpected->isNotEmpty()) {
            report(new UnexpectedDtoAttributeException(
                dtoClass: static::class,
                keys: $unexpected->all(),
            ));
        }
    }
}
