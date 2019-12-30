<?php declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\Exception\InvalidArgumentException;

abstract class BaseCollectionTest extends CollectionTest
{
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

    /**
     * @param mixed $value
     */
    public function testOffsetSetWithNullKey(): void
    {
        $value = $this->getFirstValidValue();

        $collection = $this->createEmptyCollection();
        $collection[] = $value;

        self::assertSame($collection->offsetGet(0), $value);
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

    /**
     * @return mixed[]
     */
    public function invalidDataProvider(): array
    {
        return [];
    }

    protected function createExpectedInvalidArgumentExceptionMessage($value): string
    {
        return \sprintf('"%s" only allows elements of type "%s", "%s" given.', $this->getCollectionClass(), $this->getExpectedDataType(), \gettype($value));
    }

    protected function getExpectedDataType(): string
    {
        return 'integer';
    }
}
