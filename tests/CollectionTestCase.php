<?php declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\AbstractCollection;
use Aeviiq\Collection\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

abstract class CollectionTestCase extends TestCase
{
    public function testInstanceCreation(): void
    {
        $expected = $this->createFirstThreeValidValues();
        $collection = $this->createCollectionWithElements($expected);
        $this->assertSame($expected, $collection->toArray());
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
        $this->createCollectionWithElements([$value]);
    }

    public function testAppend(): void
    {
        $collection = $this->createEmptyCollection();
        $expected = $this->createFirstThreeValidValues();
        $collection->append($this->getFirstValidValue());
        $collection->append($this->getSecondValidValue());
        $collection->append($this->getThirdValidValue());
        $this->assertSame($expected, $collection->toArray());
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
        $collection = $this->createEmptyCollection();
        $collection->append($value);
    }

    public function testOffsetSet(): void
    {
        $expected = $this->createFirstThreeValidValues();
        $collection = $this->createEmptyCollection();
        foreach ($expected as $key => $value) {
            $collection->offsetSet($key, $value);
        }
        $this->assertSame($expected, $collection->toArray());
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
        $collection = $this->createEmptyCollection();
        $collection->offsetSet(0, $value);
    }

    public function testExchangeArray(): void
    {
        $expectedPrevious = $this->createFirstThreeValidValues();
        $expectedCurrent = $this->createLastThreeValidValues();
        $collection = $this->createCollectionWithElements($expectedPrevious);
        $previous = $collection->exchangeArray($expectedCurrent);
        $this->assertSame($expectedPrevious, $previous->toArray());
        $this->assertNotSame($expectedPrevious, $collection->toArray());
        $this->assertSame($expectedCurrent, $collection->toArray());
        $this->assertNotSame($expectedCurrent, $previous->toArray());
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testExchangeArrayWithInvalidValues($value): void
    {
        $collection = $this->createEmptyCollection();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->createExpectedInvalidArgumentExceptionMessage($value));
        $collection->exchangeArray([$value]);
    }

    public function testToArray(): void
    {
        $expected = $this->createFirstThreeValidValues();
        $collection = $this->createCollectionWithElements($expected);
        $this->assertSame($expected, $collection->toArray());
    }

    public function testFirstWithMultipleEntries(): void
    {
        $elements = $this->createFirstThreeValidValues();
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
        $elements = $this->createFirstThreeValidValues();
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

    public function testMergeWithCollection(): void
    {
        $expected1 = $this->createFirstThreeValidValues();
        $expected2 = $this->createLastThreeValidValues();
        $collection1 = $this->createCollectionWithElements($expected1);
        $collection2 = $this->createCollectionWithElements($expected2);
        $collection1->merge($collection2);
        $this->assertSame(\array_merge($expected1, $expected2), $collection1->toArray());
    }

    public function testMergeWithArray(): void
    {
        $expected1 = $this->createFirstThreeValidValues();
        $expected2 = $this->createLastThreeValidValues();
        $collection = $this->createCollectionWithElements($expected1);
        $collection->merge($expected2);
        $this->assertSame(\array_merge($expected1, $expected2), $collection->toArray());
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testMergeWithInvalidDataTypes($value): void
    {
        $collection = $this->createCollectionWithElements($this->createFirstThreeValidValues());
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->createExpectedInvalidArgumentExceptionMessage($value));

        $collection->merge([$value]);
    }

    public function testIsEmpty(): void
    {
        $collection = $this->createEmptyCollection();
        $this->assertTrue($collection->isEmpty());
        $collection->append($this->getFirstValidValue());
        $this->assertFalse($collection->isEmpty());
    }

    /**
     * @see https://github.com/aeviiq/collection/issues/32
     *
     * @return void
     */
    public function testIterator(): void
    {
        $collection = $this->createCollectionWithElements($this->createFirstThreeValidValues());
        $loopCount = 0;
        foreach ($collection as $key => $value) {
            $collection->offsetUnset($key);
            ++$loopCount;
        }

        $this->assertSame(3, $loopCount);
    }

    abstract public function testMap(): void;

    abstract public function testFilter(): void;

    /**
     * @return mixed[]
     */
    abstract public function invalidDataProvider(): array;

    /**
     * @return mixed[]
     */
    protected function createFirstThreeValidValues(): array
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
    protected function createLastThreeValidValues(): array
    {
        return [
            $this->getForthValidValue(),
            $this->getFifthValidValue(),
            $this->getSixthValidValue(),
        ];
    }

    protected function createExpectedInvalidArgumentExceptionMessage($value): string
    {
        return \sprintf('"%s" only allows elements of type "%s", "%s" given.', $this->getCollectionClass(), $this->getExpectedDataType(), \gettype($value));
    }

    protected function createEmptyCollection(): AbstractCollection
    {
        $collectionClass = $this->getCollectionClass();

        return new $collectionClass;
    }

    /**
     * @param mixed[] $elements
     */
    protected function createCollectionWithElements(array $elements): AbstractCollection
    {
        $collectionClass = $this->getCollectionClass();

        return new $collectionClass($elements);
    }

    abstract protected function getCollectionClass(): string;

    /**
     * @return mixed A value that is valid for the implemented. Should return a reference for objects.
     */
    abstract protected function getFirstValidValue();

    /**
     * @return mixed A value that is valid for the implemented. Should return a reference for objects.
     */
    abstract protected function getSecondValidValue();

    /**
     * @return mixed A value that is valid for the implemented. Should return a reference for objects.
     */
    abstract protected function getThirdValidValue();

    /**
     * @return mixed A value that is valid for the implemented. Should return a reference for objects.
     */
    abstract protected function getForthValidValue();

    /**
     * @return mixed A value that is valid for the implemented. Should return a reference for objects.
     */
    abstract protected function getFifthValidValue();

    /**
     * @return mixed A value that is valid for the implemented. Should return a reference for objects.
     */
    abstract protected function getSixthValidValue();

    abstract protected function getExpectedDataType(): string;
}
