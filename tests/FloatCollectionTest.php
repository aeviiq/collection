<?php declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\FloatCollection;

final class FloatCollectionTest extends CollectionTestCase
{
    public function testMap(): void
    {
        $collection = $this->createCollectionWithElements([1.1, 2.2, 3.3]);
        $result = $collection->map(static function ($value) {
            return (string)$value;
        });
        $expected = ['1.1', '2.2', '3.3'];
        $expected = $this->prepareExpectedResult($expected);
        $this->assertSame($expected, $result);
    }

    /**
     * {@inheritDoc}
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

    protected function getCollectionClass(): string
    {
        return FloatCollection::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getFirstValidValue()
    {
        return 1.0;
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
        return 3.0;
    }

    /**
     * {@inheritDoc}
     */
    protected function getForthValidValue()
    {
        return 4.0;
    }

    /**
     * {@inheritDoc}
     */
    protected function getFifthValidValue()
    {
        return 5.0;
    }

    /**
     * {@inheritDoc}
     */
    protected function getSixthValidValue()
    {
        return 6.0;
    }

    protected function getExpectedDataType(): string
    {
        return 'float';
    }
}
