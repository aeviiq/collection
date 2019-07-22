<?php declare(strict_types=1);

namespace Aeviiq\Tests\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\IntCollection;
use PHPUnit\Framework\TestCase;

final class IntCollectionTest extends TestCase
{
    public function testInstanceCreation(): void
    {
        $intCollection = new IntCollection([1, 2, 3]);
        $this->assertEquals([1, 2, 3], $intCollection->toArray());
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
        $this->assertEquals([1, 2, 3], $intCollection->toArray());
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
        $intCollection = new IntCollection();
        $intCollection->offsetSet(0, 1);
        $intCollection->offsetSet(1, 2);
        $intCollection->offsetSet(2, 3);
        $this->assertEquals([1, 2, 3], $intCollection->toArray());
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
