<?php declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\AbstractCollection;
use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;
use PHPUnit\Framework\TestCase;

abstract class CollectionTestCase extends TestCase
{
    public function testInstanceCreation(): void
    {
        $expected = $this->getFirstThreeValidValues();
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
        $expected = $this->getFirstThreeValidValues();
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
        $expected = $this->getFirstThreeValidValues();
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
        $expectedPrevious = $this->getFirstThreeValidValues();
        $expectedCurrent = $this->getLastThreeValidValues();
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
        $expected = $this->getFirstThreeValidValues();
        $collection = $this->createCollectionWithElements($expected);
        $expected = $this->prepareExpectedResult($expected);
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
        $expected = $this->prepareExpectedResult($expected);
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
        $expected = $this->prepareExpectedResult($expected);
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
        $expected = $this->prepareExpectedResult($expected);
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
        $expected = $this->prepareExpectedResult(\array_merge($expected1, $expected2));
        $this->assertSame($expected, $collection->toArray());
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testMergeWithInvalidDataTypes($value): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
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

    public function testContains(): void
    {
        $collection = $this->createCollectionWithElements([$this->getFirstValidValue()]);
        $this->assertTrue($collection->contains($this->getFirstValidValue()));
        $this->assertFalse($collection->contains($this->getSecondValidValue()));
        $collection->append($this->getSecondValidValue());
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
        $expected = \array_keys($this->prepareExpectedResult($expected));
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
        $expected = \array_keys($this->prepareExpectedResult($expected));
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

    public function testSlice(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());

        $this->assertSame($this->getFirstValidValue(), $collection->first());
        $result = $collection->slice(1);
        $this->assertSame($this->getSecondValidValue(), $result->first());
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
        $this->expectExceptionMessage(\sprintf('Exactly 1 result is expected in "%s", but none were found.', $this->getCollectionClass()));
        $collection->getOneBy(function ($value) {
            return $this->getFirstValidValue() === $value;
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

    abstract public function testMap(): void;

    /**
     * @return mixed[]
     */
    abstract public function invalidDataProvider(): array;

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

    /**
     * @param mixed $expectedResult
     *
     * @return mixed
     */
    protected function prepareExpectedResult($expectedResult)
    {
        return $expectedResult;
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
