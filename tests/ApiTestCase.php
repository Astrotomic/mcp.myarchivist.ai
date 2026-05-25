<?php

namespace Tests;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Validation\ValidationException;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Validator as JsonValidator;
use PHPUnit\Framework\Assert;
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

            $validator = new JsonValidator;
            $validator->setStopAtFirstError(false);
            $result = $validator->validate(
                data: json_decode(json_encode($this->toArray()), false),
                schema: json_encode((new JsonSchemaTypeFactory)->object($schema)->toArray())
            );
            Assert::assertTrue(
                $result->isValid(),
                ($error = $result->error())
                    ? json_encode(
                        value: (new ErrorFormatter)->format($error),
                        flags: JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
                    )
                    : ''
            );

            foreach ($schema as $key => $type) {
                if (is_string($schemaable) && method_exists($schemaable, 'rules')) {
                    $rules = data_get($schemaable::rules(), $key, []);

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
