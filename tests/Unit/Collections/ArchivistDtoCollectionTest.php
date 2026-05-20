<?php

namespace Tests\Unit\Collections;

use App\Collections\ArchivistDtoCollection;
use App\Data\ArchivistDto;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\UnitTestCase;
use UnexpectedValueException;

final class ArchivistDtoCollectionTest extends UnitTestCase
{
    #[Test]
    public function it_ensures_items_are_archivist_dtos(): void
    {
        $this->expectException(UnexpectedValueException::class);

        new ArchivistDtoCollection(['invalid']);
    }

    #[Test]
    public function it_can_be_converted_to_array(): void
    {
        $dto1 = new ArchivistDtoTestStub(['name' => 'John']);
        $dto2 = new ArchivistDtoTestStub(['name' => 'Jane']);

        $collection = new ArchivistDtoCollection([$dto1, $dto2]);

        $this->assertEquals([
            ['name' => 'John'],
            ['name' => 'Jane'],
        ], $collection->toArray());
    }

    #[Test]
    public function it_can_concat_another_collection(): void
    {
        $dto1 = new ArchivistDtoTestStub();
        $dto2 = new ArchivistDtoTestStub();

        $collection1 = new ArchivistDtoCollection([$dto1]);
        $collection2 = new ArchivistDtoCollection([$dto2]);

        $result = $collection1->concat($collection2);

        $this->assertInstanceOf(ArchivistDtoCollection::class, $result);
        $this->assertCount(2, $result);
        $this->assertSame($dto1, $result[0]);
        $this->assertSame($dto2, $result[1]);
    }

    #[Test]
    public function it_can_concat_an_array_of_dtos(): void
    {
        $dto1 = new ArchivistDtoTestStub();
        $dto2 = new ArchivistDtoTestStub();

        $collection = new ArchivistDtoCollection([$dto1]);

        $result = $collection->concat([$dto2]);

        $this->assertInstanceOf(ArchivistDtoCollection::class, $result);
        $this->assertCount(2, $result);
        $this->assertSame($dto1, $result[0]);
        $this->assertSame($dto2, $result[1]);
    }

    #[Test]
    public function it_returns_base_collection_when_mapping(): void
    {
        $dto = new ArchivistDtoTestStub(['name' => 'John']);

        $collection = new ArchivistDtoCollection([$dto]);

        $result = $collection->map(fn (ArchivistDto $dto) => $dto->name);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertNotInstanceOf(ArchivistDtoCollection::class, $result);
        $this->assertEquals(['John'], $result->all());
    }
}

class ArchivistDtoTestStub extends ArchivistDto
{
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public static function rules(): array
    {
        return [];
    }
}
