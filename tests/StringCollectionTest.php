<?php

declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\StringCollection;

final class StringCollectionTest extends BaseCollection
{
    public function testMap(): void
    {
        $collection = $this->createCollectionWithElements(['foo', 'bar', 'baz']);
        $result = $collection->map(static function ($value) {
            return $value . '123';
        });
        $expected = ['foo123', 'bar123', 'baz123'];
        $this->assertSame($expected, $result);
    }

    /**
     * {@inheritDoc}
     */
    public static function invalidDataProvider(): array
    {
        return [
            'int' => [1],
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
        return StringCollection::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getFirstValidValue()
    {
        return 'the first value';
    }

    /**
     * {@inheritDoc}
     */
    protected function getSecondValidValue()
    {
        return 'the second value';
    }

    /**
     * {@inheritDoc}
     */
    protected function getThirdValidValue()
    {
        return 'the third value';
    }

    /**
     * {@inheritDoc}
     */
    protected function getForthValidValue()
    {
        return 'the forth value';
    }

    /**
     * {@inheritDoc}
     */
    protected function getFifthValidValue()
    {
        return 'the fifth value';
    }

    /**
     * {@inheritDoc}
     */
    protected function getSixthValidValue()
    {
        return 'the sixth value';
    }

    protected function getExpectedDataType(): string
    {
        return 'string';
    }
}
