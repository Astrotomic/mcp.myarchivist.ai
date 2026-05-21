<?php

namespace Tests\Unit\Data;

use App\Data\SessionHandoutData;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\UnitTestCase;

final class SessionHandoutDataTest extends UnitTestCase
{
    #[Test]
    public function party_status_and_next_steps_schema_is_an_object(): void
    {
        $schema = (new JsonSchemaTypeFactory)->object(SessionHandoutData::toJsonSchema())->toArray();

        $partyStatus = $schema['properties']['partyStatusAndNextSteps'] ?? null;

        $this->assertNotNull($partyStatus);

        $type = $partyStatus['type'] ?? null;
        $this->assertTrue(
            $type === 'object' || (is_array($type) && in_array('object', $type, true)),
            'partyStatusAndNextSteps must be typed as object, got: '.json_encode($type),
        );
    }

    #[Test]
    public function rules_include_nested_party_status_fields(): void
    {
        $rules = SessionHandoutData::rules();

        $this->assertArrayHasKey('partyStatusAndNextSteps.partyStatus.summary', $rules);
        $this->assertArrayHasKey('partyStatusAndNextSteps.nextSteps.summary', $rules);
    }
}
