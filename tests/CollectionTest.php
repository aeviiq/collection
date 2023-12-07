<?php

declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\Collection;
use Aeviiq\Collection\Exception\LogicException;
use ArrayIterator;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testMap(): void
    {
        $collection = $this->createCollectionWithElements([1, 2, 3]);
        $result = $collection->map(static function ($value) {
            return (string)$value;
        });
        $expected = ['1', '2', '3'];
        $this->assertSame($expected, $result);
    }

    public function testInstanceCreation(): void
    {
        $expected = $this->getFirstThreeValidValues();
        $collection = $this->createCollectionWithElements($expected);
        $this->assertSame($expected, $collection->toArray());
    }

    public function testInstanceCreationWithIterator(): void
    {
        $expected = $this->getFirstThreeValidValues();
        $collection = $this->createCollectionWithElements(new ArrayIterator($expected));
        self::assertSame($expected, $collection->toArray());
    }

    public function testAppend(): void
    {
        $collection = $this->createEmptyCollection();
        $expected = $this->getFirstThreeValidValues();
        $collection->append($this->getFirstValidValue());
        $collection->append($this->getSecondValidValue());
        $collection->append($this->getThirdValidValue());
        $this->assertSame($expected, $collection->toArray());
    }

    public function testOffsetSet(): void
    {
        $expected = $this->getFirstThreeValidValues();
        $collection = $this->createEmptyCollection();
        foreach ($expected as $key => $value) {
            $collection->offsetSet($key, $value);
        }
        $this->assertSame($expected, $collection->toArray());
    }

    public function testExchangeArray(): void
    {
        $expectedPrevious = $this->getFirstThreeValidValues();
        $expectedCurrent = $this->getLastThreeValidValues();
        $collection = $this->createCollectionWithElements($expectedPrevious);
        $collection->exchangeArray($expectedCurrent);
        $this->assertNotSame($expectedPrevious, $collection->toArray());
        $this->assertSame($expectedCurrent, $collection->toArray());
    }

    public function testToArray(): void
    {
        $expected = $this->getFirstThreeValidValues();
        $collection = $this->createCollectionWithElements($expected);
        $this->assertSame($expected, $collection->toArray());
    }

    public function testFirstWithMultipleEntries(): void
    {
        $elements = $this->getFirstThreeValidValues();
        $expected = \reset($elements);
        $collection = $this->createCollectionWithElements($elements);
        $this->assertSame($expected, $collection->first());
    }

    public function testFirstWithEmptyCollection(): void
    {
        $collection = $this->createEmptyCollection();
        $this->assertNull($collection->first());
    }

    public function testLastWithMultipleEntries(): void
    {
        $elements = $this->getFirstThreeValidValues();
        $expected = \end($elements);
        $collection = $this->createCollectionWithElements($elements);
        $this->assertSame($expected, $collection->last());
    }

    public function testLastWithEmptyCollection(): void
    {
        $collection = $this->createEmptyCollection();
        $this->assertNull($collection->last());
    }

    public function testRemove(): void
    {
        $expected = [$this->getFirstValidValue(), $this->getThirdValidValue()];
        $collection = $this->createCollectionWithElements($expected);
        $collection->append($this->getSecondValidValue());
        $this->assertCount(3, $collection);
        $collection->remove($this->getSecondValidValue());
        $this->assertCount(2, $collection);
        $this->assertSame($expected, $collection->toArray());
    }

    public function testRemoveWithNonExistingValues(): void
    {
        $expected = [$this->getFirstValidValue(), $this->getThirdValidValue()];
        $collection = $this->createCollectionWithElements($expected);
        $this->assertCount(2, $collection);
        $collection->remove('7ddf32e17a6ac5ce04a8ecbf782ca509');
        $collection->remove('59920e994636168744039017dcf49e54');
        $this->assertCount(2, $collection);
        $this->assertSame($expected, $collection->toArray());
    }

    public function testFilter(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $result = $collection->filter(function ($value) {
            return $this->getFirstValidValue() === $value || $this->getSecondValidValue() === $value;
        });
        $this->assertInstanceOf(\get_class($collection), $result);
        $expected = [
            $this->getFirstValidValue(),
            $this->getSecondValidValue(),
        ];
        $this->assertSame($expected, $result->toArray());
    }

    public function testMergeWithCollection(): void
    {
        $expected1 = $this->getFirstThreeValidValues();
        $expected2 = $this->getLastThreeValidValues();
        $collection1 = $this->createCollectionWithElements($expected1);
        $collection2 = $this->createCollectionWithElements($expected2);
        $collection1->merge($collection2);
        $this->assertSame(\array_merge($expected1, $expected2), $collection1->toArray());
    }

    public function testMergeWithArray(): void
    {
        $expected1 = $this->getFirstThreeValidValues();
        $expected2 = $this->getLastThreeValidValues();
        $collection = $this->createCollectionWithElements($expected1);
        $collection->merge($expected2);
        $expected = \array_merge($expected1, $expected2);
        $this->assertSame($expected, $collection->toArray());
    }

    public function testIsEmpty(): void
    {
        $collection = $this->createEmptyCollection();
        $this->assertTrue($collection->isEmpty());
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $this->assertFalse($collection->isEmpty());
    }

    public function testContains(): void
    {
        $collection = $this->createCollectionWithElements([$this->getFirstValidValue()]);
        $this->assertTrue($collection->contains($this->getFirstValidValue()));
        $this->assertFalse($collection->contains($this->getSecondValidValue()));
        $collection = $this->createCollectionWithElements([$this->getFirstValidValue(), $this->getSecondValidValue()]);
        $this->assertTrue($collection->contains($this->getSecondValidValue()));
    }

    public function testClear(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $this->assertCount(3, $collection);
        $this->assertFalse($collection->isEmpty());
        $collection->clear();
        $this->assertCount(0, $collection);
        $this->assertTrue($collection->isEmpty());
    }

    public function testGetKeys(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $expected = [
            0 => true,
            1 => true,
            2 => true,
        ];
        $expected = \array_keys($expected);
        $this->assertSame($expected, $collection->getKeys());

        $collection = $this->createCollectionWithElements([
            'key_1' => $this->getFirstValidValue(),
            'key_2' => $this->getSecondValidValue(),
            'key_3' => $this->getThirdValidValue(),
        ]);
        $expected = [
            'key_1' => true,
            'key_2' => true,
            'key_3' => true,
        ];
        $expected = \array_keys($expected);
        $this->assertSame($expected, $collection->getKeys());
    }

    public function getValues(): void
    {
        $expected = $this->getFirstThreeValidValues();
        $collection = $this->createCollectionWithElements($expected);
        $this->assertSame($expected, $collection->getValues());

        $collection = $this->createCollectionWithElements([
            'key_1' => $this->getFirstValidValue(),
            'key_2' => $this->getSecondValidValue(),
            'key_3' => $this->getThirdValidValue(),
        ]);
        $expected = [
            0 => $this->getFirstValidValue(),
            1 => $this->getSecondValidValue(),
            2 => $this->getThirdValidValue(),
        ];
        $this->assertSame(['key_1', 'key_2', 'key_3'], $collection->getKeys());
        $this->assertSame($expected, $collection->getValues());
    }

    public function testGetOneBy(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $result = $collection->getOneBy(function ($value) {
            return $this->getFirstValidValue() === $value;
        });

        $this->assertSame($this->getFirstValidValue(), $result);
    }

    public function testGetOneByWithNoResults(): void
    {
        $collection = $this->createCollectionWithElements([$this->getSecondValidValue()]);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('No results found, one expected.');
        $collection->getOneBy(function ($value) {
            return $this->getFirstValidValue() === $value;
        });
    }

    public function testGetOneByWithMultipleResults(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Multiple results found, one or null expected.');
        $collection->getOneBy(function ($value) {
            return $this->getFirstValidValue() === $value || $this->getSecondValidValue() === $value;
        });
    }

    public function testGetOneOrNullBy(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $result = $collection->getOneBy(function ($value) {
            return $this->getFirstValidValue() === $value;
        });

        $this->assertSame($this->getFirstValidValue(), $result);
    }

    public function testGetOneOrNullByWithNoResults(): void
    {
        $collection = $this->createCollectionWithElements([$this->getSecondValidValue()]);
        $result = $collection->getOneOrNullBy(function ($value) {
            return $this->getFirstValidValue() === $value;
        });

        $this->assertNull($result);
    }

    public function testGetOneOrNullByWithMultipleResults(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Multiple results found, one or null expected.');
        $collection->getOneOrNullBy(function ($value) {
            return $this->getFirstValidValue() === $value || $this->getSecondValidValue() === $value;
        });
    }

    /**
     * @see https://github.com/aeviiq/collection/issues/32
     *
     * @return void
     */
    public function testIterator(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $loopCount = 0;
        foreach ($collection as $key => $value) {
            $collection->offsetUnset($key);
            ++$loopCount;
        }

        $this->assertSame(3, $loopCount);
    }

    public function testCopy(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $copy = $collection->copy();
        $this->assertNotSame($collection, $copy);
        $this->assertEquals($collection->toArray(), $copy->toArray());
        $this->assertInstanceOf(\get_class($collection), $copy);
    }

    protected function getCollectionClass(): string
    {
        return Collection::class;
    }

    /**
     * @return mixed[]
     */
    protected function getFirstThreeValidValues(): array
    {
        return [
            $this->getFirstValidValue(),
            $this->getSecondValidValue(),
            $this->getThirdValidValue(),
        ];
    }

    /**
     * @return mixed[]
     */
    protected function getLastThreeValidValues(): array
    {
        return [
            $this->getForthValidValue(),
            $this->getFifthValidValue(),
            $this->getSixthValidValue(),
        ];
    }

    protected function createEmptyCollection(): Collection
    {
        $collectionClass = $this->getCollectionClass();

        return new $collectionClass;
    }

    /**
     * @param mixed[] $elements
     */
    protected function createCollectionWithElements(iterable $elements): Collection
    {
        $collectionClass = $this->getCollectionClass();

        return new $collectionClass($elements);
    }

    /**
     * {@inheritDoc}
     */
    protected function getFirstValidValue()
    {
        return 1;
    }

    /**
     * {@inheritDoc}
     */
    protected function getSecondValidValue()
    {
        return 2.0;
    }

    /**
     * {@inheritDoc}
     */
    protected function getThirdValidValue()
    {
        return '3';
    }

    /**
     * {@inheritDoc}
     */
    protected function getForthValidValue()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    protected function getFifthValidValue()
    {
        return new \stdClass();
    }

    /**
     * {@inheritDoc}
     */
    protected function getSixthValidValue()
    {
        return static function () {
        };
    }
}
