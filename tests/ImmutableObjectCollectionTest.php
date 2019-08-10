<?php declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\AbstractImmutableObjectCollection;
use Aeviiq\Collection\Exception\LogicException;

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

    public function testExceptionIsThrownWhenClonedWithInvalidKeys(): void
    {
        $collection = $this->createCollectionWithElements($this->getFirstThreeValidValues());
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('In order to correctly clone an object collection, all keys must be strings that are valid property names as defined by PHP. If you are not deep cloning this collection, you could choose to suppress this exception by overriding AbstractObjectCollection#suppressDeepCloneValidation()');
        $result = clone $collection;
    }

    public function testExceptionIsNotThrownWithInvalidKeysIfItIsSuppressed(): void
    {
        $collection = new class() extends AbstractImmutableObjectCollection
        {
            protected function allowedInstance(): string
            {
                return \IteratorAggregate::class;
            }

            protected function suppressDeepCloneValidation(): bool
            {
                return true;
            }
        };
        $result = clone $collection;
        $this->assertEquals($result, $collection);
    }

    /**
     * {@inheritDoc}
     */
    public function invalidDataProvider(): array
    {
        return [
            'int' => [1],
            'float' => [0.1],
            'string' => ['foobar'],
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

    protected function createExpectedInvalidArgumentExceptionMessage($value): string
    {
        if (\is_object($value)) {
            return \sprintf(
                '"%s" only allows elements that are an instance of "%s", "%s" given.',
                $this->getCollectionClass(),
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

    private function createCollection(array $items = []): AbstractImmutableObjectCollection
    {
        return new class($items) extends AbstractImmutableObjectCollection
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
