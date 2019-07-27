<?php declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;
use Aeviiq\Collection\IntCollection;
use PHPUnit\Framework\TestCase;

final class IntCollectionTest extends TestCase
{
    public function testInstanceCreation(): void
    {
        $expected = [1, 2, 3];
        $intCollection = new IntCollection($expected);
        $this->assertSame($expected, $intCollection->toArray());
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testInstanceCreationWithInvalidValues($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->createExpectedInvalidArgumentExceptionMessage($value));
        new IntCollection([$value]);
    }

    public function testAppend(): void
    {
        $intCollection = new IntCollection();
        $intCollection->append(1);
        $intCollection->append(2);
        $intCollection->append(3);
        $this->assertSame([1, 2, 3], $intCollection->toArray());
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testAppendWithInvalidValues($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->createExpectedInvalidArgumentExceptionMessage($value));
        $intCollection = new IntCollection();
        $intCollection->append($value);
    }

    public function testOffsetSet(): void
    {
        $expected = [0 => 1, 1 => 2, 2 => 3];
        $intCollection = new IntCollection();
        foreach ($expected as $key => $value) {
            $intCollection->offsetSet($key, $value);
        }
        $this->assertSame($expected, $intCollection->toArray());
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testOffsetSetWithInvalidValues($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->createExpectedInvalidArgumentExceptionMessage($value));
        $intCollection = new IntCollection();
        $intCollection->offsetSet(0, $value);
    }

    public function testExchangeArray(): void
    {
        $expectedPrevious = [1, 2, 3];
        $expectedCurrent = [4, 5, 6];
        $intCollection = new IntCollection($expectedPrevious);
        $previous = $intCollection->exchangeArray([4, 5, 6]);
        $this->assertSame($expectedPrevious, $previous->toArray());
        $this->assertNotSame($expectedPrevious, $intCollection->toArray());
        $this->assertSame($expectedCurrent, $intCollection->toArray());
        $this->assertNotSame($expectedCurrent, $previous->toArray());
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testExchangeArrayWithInvalidValues($value): void
    {
        $intCollection = new IntCollection();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->createExpectedInvalidArgumentExceptionMessage($value));
        $intCollection->exchangeArray([$value]);
    }

    public function testToArray(): void
    {
        $expected = [1, 2, 3];
        $intCollection = new IntCollection($expected);
        $this->assertSame($expected, $intCollection->toArray());
        $this->assertSame(3, $intCollection->count());
    }

    public function testFirstWithMultipleEntries(): void
    {
        $intCollection = new IntCollection([1, 2, 3]);
        $this->assertSame(1, $intCollection->first());
    }

    public function testFirstWithEmptyCollection(): void
    {
        $intCollection = new IntCollection();
        $this->assertNull($intCollection->first());
    }

    public function testLastWithMultipleEntries(): void
    {
        $intCollection = new IntCollection([1, 2, 3]);
        $this->assertSame(3, $intCollection->last());
    }

    public function testLastWithEmptyCollection(): void
    {
        $intCollection = new IntCollection();
        $this->assertNull($intCollection->last());
    }

    public function testRemove(): void
    {
        $expected = [1, 3];
        $intCollection = new IntCollection($expected);
        $intCollection->append(2);
        $this->assertCount(3, $intCollection);
        $intCollection->remove(2);
        $this->assertCount(2, $intCollection);
        $this->assertSame($expected, $intCollection->toArray());
    }

    public function testRemoveWithNonExistingValues(): void
    {
        $expected = [1, 3];
        $intCollection = new IntCollection($expected);
        $this->assertCount(2, $intCollection);
        $intCollection->remove(4);
        $intCollection->remove('foo');
        $this->assertCount(2, $intCollection);
        $this->assertSame($expected, $intCollection->toArray());
    }

    public function testMap(): void
    {
        $intCollection = new IntCollection([1, 2, 3]);
        $result = $intCollection->map(static function ($value) {
            return (string)$value;
        });
        $this->assertSame(['1', '2', '3'], $result);
    }

    public function testFilter(): void
    {
        $intCollection = new IntCollection([1, 2, 3]);
        $result = $intCollection->filter(static function ($value) {
            return 1 === $value || 2 === $value;
        });
        $this->assertInstanceOf(\get_class($intCollection), $result);
        $this->assertSame([1, 2], $result->toArray());
    }

    public function testMergeWithCollection(): void
    {
        $expected1 = [1, 2, 3];
        $expected2 = [4, 5, 6];
        $intCollection = new IntCollection($expected1);
        $intCollection2 = new IntCollection($expected2);
        $intCollection->merge($intCollection2);
        $this->assertSame(array_merge($expected1, $expected2), $intCollection->toArray());
    }

    public function testMergeWithArray(): void
    {
        $expected1 = [1, 2, 3];
        $expected2 = [4, 5, 6];
        $intCollection = new IntCollection($expected1);
        $intCollection->merge($expected2);
        $this->assertSame(array_merge($expected1, $expected2), $intCollection->toArray());
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testMergeWithInvalidDataTypes($value): void
    {
        $intCollection = new IntCollection([1, 2, 3]);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->createExpectedInvalidArgumentExceptionMessage($value));

        $intCollection->merge([$value]);
    }

    public function testIsEmpty(): void
    {
        $intCollection = new IntCollection();
        $this->assertTrue($intCollection->isEmpty());
        $intCollection->append(1);
        $this->assertFalse($intCollection->isEmpty());
    }

    public function testContains(): void
    {
        $intCollection = new IntCollection([1]);
        $this->assertTrue($intCollection->contains(1));
        $this->assertFalse($intCollection->contains('234'));
        $this->assertFalse($intCollection->contains(2));
        $intCollection->append(2);
        $this->assertTrue($intCollection->contains(2));
    }

    public function testClear(): void
    {
        $intCollection = new IntCollection([1, 2, 3]);
        $this->assertCount(3, $intCollection);
        $this->assertFalse($intCollection->isEmpty());
        $intCollection->clear();
        $this->assertCount(0, $intCollection);
        $this->assertTrue($intCollection->isEmpty());
    }

    public function testGetKeys(): void
    {
        $intCollection = new IntCollection([1, 2, 3]);
        $this->assertSame([0, 1, 2], $intCollection->getKeys());

        $intCollection = new IntCollection(['key_1' => 1, 'key_2' => 2, 'key_3' => 3]);
        $this->assertSame(['key_1', 'key_2', 'key_3'], $intCollection->getKeys());
    }

    public function getValues(): void
    {
        $expected = [1, 2, 3];
        $intCollection = new IntCollection([1, 2, 3]);
        $this->assertSame($expected, $intCollection->getValues());

        $intCollection = new IntCollection(['key_1' => 1, 'key_2' => 2, 'key_3' => 3]);
        $this->assertSame([0 => 1, 1 => 2, 2 => 3], $intCollection->getValues());
    }

    public function testSlice(): void
    {
        $intCollection = new IntCollection([1, 2]);

        self::assertSame(2, $intCollection->slice(1)->first());
    }

    public function testGetOneBy(): void
    {
        $intCollection = new IntCollection([1, 2]);
        $result = $intCollection->getOneBy(static function ($value) {
            return 1 === $value;
        });

        $this->assertSame(1, $result);
    }

    public function testGetOneByWithNoResults(): void
    {
        $intCollection = new IntCollection([2]);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(\sprintf('Exactly 1 result is expected in "%s", but none were found.', IntCollection::class));
        $intCollection->getOneBy(static function ($value) {
            return 1 === $value;
        });
    }

    public function testGetOneOrNullBy(): void
    {
        $intCollection = new IntCollection([1, 2]);
        $result = $intCollection->getOneOrNullBy(static function ($value) {
            return 1 === $value;
        });

        $this->assertSame(1, $result);
    }

    public function testGetOneOrNullByWithNoResults(): void
    {
        $intCollection = new IntCollection([2]);
        $result = $intCollection->getOneOrNullBy(static function ($value) {
            return 1 === $value;
        });

        $this->assertNull($result);
    }

    /**
     * @return mixed[]
     */
    public function invalidDataProvider(): array
    {
        return [
            'string' => ['some random string'],
            'float' => [0.1],
            'bool' => [true],
            'null' => [null],
            'array' => [[]],
            'object' => [new \stdClass()],
            'callable' => [
                static function () {
                },
            ],
        ];
    }

    private function createExpectedInvalidArgumentExceptionMessage($value): string
    {
        return \sprintf('"%s" only allows elements of type "integer", "%s" given.', IntCollection::class, \gettype($value));
    }
}
