<?php

declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\ImmutableObjectCollection;
use Aeviiq\Collection\ObjectCollection;

final class ImmutableObjectCollectionTest extends ImmutableCollectionTest
{
    /**
     * @var \IteratorAggregate
     */
    private $firstSubject;

    /**
     * @var \IteratorAggregate
     */
    private $secondSubject;

    /**
     * @var \IteratorAggregate
     */
    private $thirdSubject;

    /**
     * @var \IteratorAggregate
     */
    private $forthSubject;

    /**
     * @var \IteratorAggregate
     */
    private $fifthSubject;

    /**
     * @var \IteratorAggregate
     */
    private $sixthSubject;

    /**
     * @var string
     */
    private $creationCount = 'a';

    public function testMap(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $result = $collection->map(static function ($value) {
            return $value . '123';
        });
        $expected = ['Foo Bar Baz Baa123', 'Foo Bar Baz Bab123', 'Foo Bar Baz Bac123'];
        $this->assertSame($expected, $result);
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

    /**
     * @dataProvider invalidObjectInstanceDataProvider
     *
     * @param mixed $value
     */
    public function testInstanceCreationWithInvalidObjectInstance($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->createExpectedInvalidArgumentExceptionMessage(
            $value,
            \get_class($this->createInstanceSpecificEmptyCollectionWithElements([$value]))
        ));
        $this->createInstanceSpecificEmptyCollectionWithElements([$value]);
    }

    /**
     * @return mixed[]
     */
    public static function invalidObjectInstanceDataProvider(): array
    {
        return [
            'std_class' => [new \stdClass()],
        ];
    }

    /**
     * @return mixed[]
     */
    public static function invalidDataProvider(): array
    {
        return [
            'int' => [1],
            'float' => [0.1],
            'string' => ['foobar'],
            'bool' => [true],
            'null' => [null],
            'array' => [[]],
        ];
    }

    /**
     * @param mixed[] $elements
     */
    protected function createInstanceSpecificEmptyCollectionWithElements(array $elements): ObjectCollection
    {
        return new class($elements) extends ObjectCollection
        {
            protected function allowedInstance(): string
            {
                return \IteratorAggregate::class;
            }
        };
    }

    protected function createInstanceSpecificEmptyCollection(): ObjectCollection
    {
        return $this->createInstanceSpecificEmptyCollectionWithElements([]);
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
        return $this->firstSubject;
    }

    /**
     * {@inheritDoc}
     */
    protected function getSecondValidValue()
    {
        return $this->secondSubject;
    }

    /**
     * {@inheritDoc}
     */
    protected function getThirdValidValue()
    {
        return $this->thirdSubject;
    }

    /**
     * {@inheritDoc}
     */
    protected function getForthValidValue()
    {
        return $this->forthSubject;
    }

    /**
     * {@inheritDoc}
     */
    protected function getFifthValidValue()
    {
        return $this->fifthSubject;
    }

    /**
     * {@inheritDoc}
     */
    protected function getSixthValidValue()
    {
        return $this->sixthSubject;
    }

    protected function getExpectedDataType(): string
    {
        return 'object';
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

    protected function createExpectedInvalidArgumentExceptionMessage($value, ?string $className = null): string
    {
        if (\is_object($value)) {
            return \sprintf(
                '"%s" only allows elements that are an instance of "%s", "%s" given.',
                $className,
                \IteratorAggregate::class,
                \get_class($value)
            );
        }

        return \sprintf('"%s" only allows elements of type "%s", "%s" given.', $this->getCollectionClass(), $this->getExpectedDataType(), \gettype($value));
    }

    protected function setUp(): void
    {
        $this->firstSubject = $this->createSubject();
        $this->secondSubject = $this->createSubject();
        $this->thirdSubject = $this->createSubject();
        $this->forthSubject = $this->createSubject();
        $this->fifthSubject = $this->createSubject();
        $this->sixthSubject = $this->createSubject();
    }

    private function createCollection(array $items = []): ImmutableObjectCollection
    {
        return new class($items) extends ImmutableObjectCollection
        {
            protected function allowedInstance(): string
            {
                return \IteratorAggregate::class;
            }
        };
    }

    private function createSubject(): \IteratorAggregate
    {
        $text = $this->creationCount++;
        return new class($text) implements \IteratorAggregate
        {
            /**
             * @var string
             */
            private $text;

            public function __toString(): string
            {
                return 'Foo Bar Baz Ba' . $this->text;
            }

            public function __construct(string $text)
            {
                $this->text = $text;
            }

            public function getIterator()
            {
                return new \ArrayIterator([]);
            }
        };
    }
}
