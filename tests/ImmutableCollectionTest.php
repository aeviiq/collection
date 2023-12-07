<?php

declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\Exception\BadMethodCallException;
use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\ImmutableCollection;

class ImmutableCollectionTest extends CollectionTest
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

    public function testRemove(): void
    {
        $this->expectedBadMethodCallException();
        $collection = $this->createEmptyCollection();
        $collection->remove($this->getFirstValidValue());
    }

    public function testRemoveWithNonExistingValues(): void
    {
        $this->expectedBadMethodCallException();
        $collection = $this->createEmptyCollection();
        $collection->remove('foo');
    }

    public function testMergeWithCollection(): void
    {
        $this->expectedBadMethodCallException();
        $collection = $this->createEmptyCollection();
        $collection->merge($this->createEmptyCollection());
    }

    public function testMergeWithArray(): void
    {
        $this->expectedBadMethodCallException();
        $collection = $this->createEmptyCollection();
        $collection->merge([]);
    }

    public function testClear(): void
    {
        $this->expectedBadMethodCallException();
        $collection = $this->createEmptyCollection();
        $collection->clear();
    }

    public function testExchangeArray(): void
    {
        $this->expectedBadMethodCallException();
        $collection = $this->createEmptyCollection();
        $collection->exchangeArray($this->getFirstThreeValidValues());
    }

    public function testAppend(): void
    {
        $this->expectedBadMethodCallException();
        $collection = $this->createEmptyCollection();
        $collection->append($this->getFirstValidValue());
    }


    public function testOffsetSet(): void
    {
        $this->expectedBadMethodCallException();
        $collection = $this->createEmptyCollection();
        $collection->offsetSet(0, $this->getFirstValidValue());
    }

    public function testIterator(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $loopCount = 0;
        foreach ($collection as $key => $value) {
            // This would mainly test the offsetUnset, while in a loop (see bug issue in parent method).
            // But this is not a problem for an immutable collection.
            ++$loopCount;
        }

        $this->assertSame(3, $loopCount);
    }

    /**
     * @return mixed[]
     */
    public static function invalidDataProvider(): array
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

    protected function getCollectionClass(): string
    {
        return \get_class($this->createCollection());
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
        return 2;
    }

    /**
     * {@inheritDoc}
     */
    protected function getThirdValidValue()
    {
        return 3;
    }

    /**
     * {@inheritDoc}
     */
    protected function getForthValidValue()
    {
        return 4;
    }

    /**
     * {@inheritDoc}
     */
    protected function getFifthValidValue()
    {
        return 5;
    }

    /**
     * {@inheritDoc}
     */
    protected function getSixthValidValue()
    {
        return 6;
    }

    protected function getExpectedDataType(): string
    {
        return 'integer';
    }

    private function expectedBadMethodCallException(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Immutable collection should not be modified.');
    }

    private function createCollection(array $items = []): ImmutableCollection
    {
        return new class($items) extends ImmutableCollection
        {
            /**
             * {@inheritDoc}
             */
            protected function validateElement($element): void
            {
                if (!\is_int($element)) {
                    throw InvalidArgumentException::expectedInt($this, \gettype($element));
                }
            }
        };
    }
}
