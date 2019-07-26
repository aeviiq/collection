<?php declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\Exception\InvalidArgumentException;
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
        return \sprintf('"Aeviiq\Collection\IntCollection" only allows elements of type "integer", "%s" given.', \gettype($value));
    }
}
