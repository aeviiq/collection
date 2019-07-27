<?php declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;
use Aeviiq\Collection\FloatCollection;
use PHPUnit\Framework\TestCase;

final class FloatCollectionTest extends TestCase
{
    public function testInstanceCreation(): void
    {
        $expected = [1.0, 2.0, 3.0];
        $floatCollection = new FloatCollection($expected);
        $this->assertSame($expected, $floatCollection->toArray());
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
        new FloatCollection([$value]);
    }

    public function testAppend(): void
    {
        $floatCollection = new FloatCollection();
        $floatCollection->append(1.0);
        $floatCollection->append(2.0);
        $floatCollection->append(3.0);
        $this->assertSame([1.0, 2.0, 3.0], $floatCollection->toArray());
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
        $floatCollection = new FloatCollection();
        $floatCollection->append($value);
    }

    public function testOffsetSet(): void
    {
        $expected = [0 => 1.0, 1 => 2.0, 2 => 3.0];
        $floatCollection = new FloatCollection();
        foreach ($expected as $key => $value) {
            $floatCollection->offsetSet($key, $value);
        }
        $this->assertSame($expected, $floatCollection->toArray());
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
        $floatCollection = new FloatCollection();
        $floatCollection->offsetSet(0, $value);
    }

    public function testExchangeArray(): void
    {
        $expectedPrevious = [1.0, 2.0, 3.0];
        $expectedCurrent = [4.0, 5.0, 6.0];
        $floatCollection = new FloatCollection($expectedPrevious);
        $previous = $floatCollection->exchangeArray([4.0, 5.0, 6.0]);
        $this->assertSame($expectedPrevious, $previous->toArray());
        $this->assertNotSame($expectedPrevious, $floatCollection->toArray());
        $this->assertSame($expectedCurrent, $floatCollection->toArray());
        $this->assertNotSame($expectedCurrent, $previous->toArray());
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testExchangeArrayWithInvalidValues($value): void
    {
        $floatCollection = new FloatCollection();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->createExpectedInvalidArgumentExceptionMessage($value));
        $floatCollection->exchangeArray([$value]);
    }

    public function testToArray(): void
    {
        $expected = [1.0, 2.0, 3.0];
        $floatCollection = new FloatCollection($expected);
        $this->assertSame($expected, $floatCollection->toArray());
        $this->assertSame(3, $floatCollection->count());
    }

    public function testFirstWithMultipleEntries(): void
    {
        $floatCollection = new FloatCollection([1.0, 2.0, 3.0]);
        $this->assertSame(1.0, $floatCollection->first());
    }

    public function testFirstWithEmptyCollection(): void
    {
        $floatCollection = new FloatCollection();
        $this->assertNull($floatCollection->first());
    }

    public function testLastWithMultipleEntries(): void
    {
        $floatCollection = new FloatCollection([1.0, 2.0, 3.0]);
        $this->assertSame(3.0, $floatCollection->last());
    }

    public function testLastWithEmptyCollection(): void
    {
        $floatCollection = new FloatCollection();
        $this->assertNull($floatCollection->last());
    }

    public function testRemove(): void
    {
        $expected = [1.0, 3.0];
        $floatCollection = new FloatCollection($expected);
        $floatCollection->append(2.0);
        $this->assertCount(3, $floatCollection);
        $floatCollection->remove(2.0);
        $this->assertCount(2, $floatCollection);
        $this->assertSame($expected, $floatCollection->toArray());
    }

    public function testRemoveWithNonExistingValues(): void
    {
        $expected = [1.0, 3.0];
        $floatCollection = new FloatCollection($expected);
        $this->assertCount(2, $floatCollection);
        $floatCollection->remove(4.0);
        $floatCollection->remove('foo');
        $this->assertCount(2, $floatCollection);
        $this->assertSame($expected, $floatCollection->toArray());
    }

    public function testMap(): void
    {
        $floatCollection = new FloatCollection([1.1, 2.0, 3.2]);
        $result = $floatCollection->map(static function ($value) {
            return (string)$value;
        });
        $this->assertSame(['1.1', '2', '3.2'], $result);
    }

    public function testFilter(): void
    {
        $floatCollection = new FloatCollection([1.0, 2.0, 3.0]);
        $result = $floatCollection->filter(static function ($value) {
            return 1.0 === $value || 2.0 === $value;
        });
        $this->assertInstanceOf(\get_class($floatCollection), $result);
        $this->assertSame([1.0, 2.0], $result->toArray());
    }

    public function testMergeWithCollection(): void
    {
        $expected1 = [1.0, 2.0, 3.0];
        $expected2 = [4.0, 5.0, 6.0];
        $floatCollection = new FloatCollection($expected1);
        $floatCollection2 = new FloatCollection($expected2);
        $floatCollection->merge($floatCollection2);
        $this->assertSame(array_merge($expected1, $expected2), $floatCollection->toArray());
    }

    public function testMergeWithArray(): void
    {
        $expected1 = [1.0, 2.0, 3.0];
        $expected2 = [4.0, 5.0, 6.0];
        $floatCollection = new FloatCollection($expected1);
        $floatCollection->merge($expected2);
        $this->assertSame(array_merge($expected1, $expected2), $floatCollection->toArray());
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testMergeWithInvalidDataTypes($value): void
    {
        $floatCollection = new FloatCollection([1.0, 2.0, 3.0]);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->createExpectedInvalidArgumentExceptionMessage($value));

        $floatCollection->merge([$value]);
    }

    public function testIsEmpty(): void
    {
        $floatCollection = new FloatCollection();
        $this->assertTrue($floatCollection->isEmpty());
        $floatCollection->append(1.0);
        $this->assertFalse($floatCollection->isEmpty());
    }

    public function testContains(): void
    {
        $floatCollection = new FloatCollection([1.0]);
        $this->assertTrue($floatCollection->contains(1.0));
        $this->assertFalse($floatCollection->contains('2.03.04.0'));
        $this->assertFalse($floatCollection->contains(2.0));
        $floatCollection->append(2.0);
        $this->assertTrue($floatCollection->contains(2.0));
    }

    public function testClear(): void
    {
        $floatCollection = new FloatCollection([1.0, 2.0, 3.0]);
        $this->assertCount(3, $floatCollection);
        $this->assertFalse($floatCollection->isEmpty());
        $floatCollection->clear();
        $this->assertCount(0, $floatCollection);
        $this->assertTrue($floatCollection->isEmpty());
    }

    public function testGetKeys(): void
    {
        $floatCollection = new FloatCollection([1.0, 2.0, 3.0]);
        $this->assertSame([0, 1, 2], $floatCollection->getKeys());

        $floatCollection = new FloatCollection(['key_1' => 1.0, 'key_2' => 2.0, 'key_3' => 3.0]);
        $this->assertSame(['key_1', 'key_2', 'key_3'], $floatCollection->getKeys());
    }

    public function getValues(): void
    {
        $expected = [1.0, 2.0, 3.0];
        $floatCollection = new FloatCollection([1.0, 2.0, 3.0]);
        $this->assertSame($expected, $floatCollection->getValues());

        $floatCollection = new FloatCollection(['key_1' => 1.0, 'key_2' => 2.0, 'key_3' => 3.0]);
        $this->assertSame([0 => 1.0, 1 => 2.0, 2 => 3.0], $floatCollection->getValues());
    }

    public function testSlice(): void
    {
        $floatCollection = new FloatCollection([1.0, 2.0]);

        self::assertSame(2.0, $floatCollection->slice(1)->first());
    }

    public function testGetOneBy(): void
    {
        $floatCollection = new FloatCollection([1.0, 2.0]);
        $result = $floatCollection->getOneBy(static function ($value) {
            return 1.0 === $value;
        });

        $this->assertSame(1.0, $result);
    }

    public function testGetOneByWithNoResults(): void
    {
        $floatCollection = new FloatCollection([2.0]);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(\sprintf('Exactly 1 result is expected in "%s", but none were found.', FloatCollection::class));
        $floatCollection->getOneBy(static function ($value) {
            return 1.0 === $value;
        });
    }

    public function testGetOneOrNullBy(): void
    {
        $floatCollection = new FloatCollection([1.0, 2.0]);
        $result = $floatCollection->getOneOrNullBy(static function ($value) {
            return 1.0 === $value;
        });

        $this->assertSame(1.0, $result);
    }

    public function testGetOneOrNullByWithNoResults(): void
    {
        $floatCollection = new FloatCollection([2.0]);
        $result = $floatCollection->getOneOrNullBy(static function ($value) {
            return 1.0 === $value;
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
            'int' => [1],
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
        return \sprintf('"%s" only allows elements of type "float", "%s" given.', FloatCollection::class, \gettype($value));
    }
}
