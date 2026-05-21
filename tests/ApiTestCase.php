<?php

namespace Tests;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Validation\ValidationException;
use Spatie\Snapshots\MatchesSnapshots;

abstract class ApiTestCase extends FeatureTestCase
{
    use MatchesSnapshots {
        assertMatchesJsonSnapshot as __assertMatchesJsonSnapshot;
    }

    protected function setUp(): void
    {
        parent::setUp();

        AssertableJson::macro('assertPaginatedList', function (Closure $assertDataItem): AssertableJson {
            /** @var AssertableJson $this */
            return $this
                ->whereType('total', 'integer')
                ->whereType('current_page', 'integer')
                ->whereType('per_page', 'integer')
                ->whereType('last_page', 'integer')
                ->whereType('from', 'integer')
                ->whereType('to', 'integer')
                ->whereType('data', 'array')
                ->has('data', fn (AssertableJson $data) => $data->each($assertDataItem));
        });

        AssertableJson::macro('assertJsonSchema', function (string|array $schemaable): AssertableJson {
            /** @var AssertableJson $this */

            /** @var array<string, Type> $schema */
            $schema = is_string($schemaable) ? $schemaable::toJsonSchema() : $schemaable;

            foreach ($schema as $key => $type) {
                $definition = $type->toArray();
                $types = collect($definition['type'])->map(fn (string $type) => match ($type) {
                    'object' => 'array',
                    default => $type,
                })->all();
                $this->whereType($key, $types);

                if (isset($definition['enum'])) {
                    $this->where($key, fn (mixed $value) => in_array($value, $definition['enum'], true));
                }

                if (is_object($schemaable) && method_exists($schemaable, 'rules')) {
                    $rules = data_get($schemaable->rules(), $key, []);

                    if (filled($rules)) {
                        $this->where($key, function (mixed $value) use ($key, $rules): bool {
                            $value = $value instanceof Collection ? $value->all() : $value;

                            $validator = Validator::make([$key => $value], [$key => $rules]);

                            try {
                                $validator->validate();
                            } catch (ValidationException $e) {
                                Log::error($e);
                            }

                            return $validator->passes();
                        });
                    }
                }
            }

            return $this;
        });
    }

    /**
     * @param  mixed  $actual
     */
    public function assertMatchesJsonSnapshot($actual, ?string $id = null): void
    {
        if ($actual instanceof Arrayable) {
            $actual = $actual->toArray();
        }

        $this->__assertMatchesJsonSnapshot($actual, $id);
    }
}
