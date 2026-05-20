<?php

namespace Tests\Unit\Actions;

use App\Actions\RulesToJsonSchema;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Snapshots\MatchesSnapshots;
use Tests\UnitTestCase;

final class RulesToJsonSchemaTest extends UnitTestCase
{
    use MatchesSnapshots;

    private RulesToJsonSchema $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new RulesToJsonSchema(new JsonSchemaTypeFactory);
    }

    #[Test]
    public function it_converts_basic_string_rule(): void
    {
        $this->assertRules(['name' => 'string']);
    }

    #[Test]
    public function it_converts_integer_rules(): void
    {
        $this->assertRules(['age' => 'integer']);
        $this->assertRules(['count' => 'int']);
    }

    #[Test]
    public function it_converts_boolean_rules(): void
    {
        $this->assertRules(['is_active' => 'boolean']);
        $this->assertRules(['enabled' => 'bool']);
    }

    #[Test]
    public function it_converts_array_rule(): void
    {
        $this->assertRules(['tags' => 'array']);
    }

    #[Test]
    public function it_converts_numeric_rule(): void
    {
        $this->assertRules(['price' => 'numeric']);
    }

    #[Test]
    public function it_converts_required_rule(): void
    {
        $this->assertRules(['name' => 'required|string']);
    }

    #[Test]
    public function it_converts_nullable_rule(): void
    {
        $this->assertRules(['description' => 'nullable|string']);
    }

    #[Test]
    public function it_converts_in_rule(): void
    {
        $this->assertRules(['status' => 'in:active,inactive,pending']);
    }

    #[Test]
    public function it_converts_complex_rules(): void
    {
        $this->assertRules([
            'id' => 'required|integer',
            'email' => 'required|string',
            'score' => 'nullable|numeric',
            'roles' => 'required|array',
            'is_admin' => 'required|boolean',
            'category' => 'in:news,blog,article',
            'size' => ['int', 'min:1', 'max:100'],
        ]);
    }

    #[Test]
    public function it_handles_rules_as_array(): void
    {
        $this->assertRules([
            'name' => ['required', 'string'],
        ]);
    }

    private function assertRules(array $rules): void
    {
        $result = $this->action->execute($rules);

        $schema = (new JsonSchemaTypeFactory)->object($result)->toArray();

        $this->assertMatchesJsonSnapshot(json_encode($schema));
    }
}
