<?php declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\IntCollection;

final class IntCollectionTest extends BaseCollectionTest
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

    /**
     * {@inheritDoc}
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

    protected function getCollectionClass(): string
    {
        return IntCollection::class;
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
}
