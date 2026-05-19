<?php

namespace App\Mcp\Data;

use App\Exceptions\DtoValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Illuminate\Validation\ValidationException;

abstract class ArchivistDto extends Fluent
{
    public function __construct(array $attributes = [])
    {
        $this->validate($attributes);
        $this->filterUnexpectedKeys($attributes);

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

    /**
     * Strip unknown keys from attributes and log them at debug level.
     * This prevents cascading exception reports from blocking responses
     * when the upstream API adds new fields.
     */
    private function filterUnexpectedKeys(array &$attributes): void
    {
        $knownKeys = array_keys($this->rules());
        $unexpected = array_diff(array_keys($attributes), $knownKeys);

        if (! empty($unexpected)) {
            Log::debug('ArchivistDto: ignoring unexpected attributes on ' . static::class, [
                'keys' => array_values($unexpected),
            ]);

            foreach ($unexpected as $key) {
                unset($attributes[$key]);
            }
        }
    }
}
