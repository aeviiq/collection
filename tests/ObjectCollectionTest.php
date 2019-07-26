<?php

declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\AbstractObjectCollection;
use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;
use Aeviiq\Collection\Tests\Mock\MockObjectInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ObjectCollectionTest extends TestCase
{
    /**
     * @dataProvider typeValidationProvider
     */
    public function testTypeValidation($value): void
    {
        if (!$value instanceof MockObjectInterface) {
            $this->expectException(InvalidArgumentException::class);
        }

        $this->createMockedCollection([$value]);
        $this->addToAssertionCount(1);

        $this->createMockedCollection([$value]);
        $this->addToAssertionCount(1);

        ($this->createMockedCollection())->append($value);
        $this->addToAssertionCount(1);

        ($this->createMockedCollection())->offsetSet('', $value);
        $this->addToAssertionCount(1);

        ($this->createMockedCollection())->exchangeArray([$value]);
        $this->addToAssertionCount(1);

        ($this->createMockedCollection())->merge([$value]);
        $this->addToAssertionCount(1);
    }

    /**
     * @return mixed[]
     */
    public function typeValidationProvider(): array
    {
        return [
            'correct_object' => [$this->createSubject()],
            'incorrect_instance' => [new stdClass()],
            'string' => [''],
            'integer' => [0],
            'float' => [0.1],
            'array' => [[]],
        ];
    }

    public function testToArray(): void
    {
        $instance1 = $this->createSubject();
        $instance2 = $this->createSubject();
        $instance3 = $this->createSubject();

        $collection = $this->createMockedCollection([$instance1, $instance2, $instance3]);

        self::assertSame(['_0' => $instance1, '_1' => $instance2, '_2' => $instance3], $collection->toArray());
    }

    public function testFirst(): void
    {
        $instance = $this->createSubject();

        $collection = $this->createMockedCollection([$instance, $this->createSubject()]);
        self::assertSame($instance, $collection->first());
        $collection->getIterator()->next();
        self::assertSame($instance, $collection->first());
    }

    public function testLast(): void
    {
        $instance = $this->createSubject();

        $collection = $this->createMockedCollection([$this->createSubject(), $instance]);
        self::assertSame($instance, $collection->last());
        $collection->getIterator()->next();
        self::assertSame($instance, $collection->last());
    }

    public function testRemove(): void
    {
        $instance = $this->createSubject();

        $collection = $this->createMockedCollection([$instance]);
        self::assertTrue($collection->contains($instance));
        $collection->remove($instance);
        self::assertFalse($collection->contains($instance));
    }

    public function testMap(): void
    {
        $collection = $this->createMockedCollection([$this->createSubject('mock_1'), $this->createSubject('mock_2')]);
        $output = $collection->map(static function (MockObjectInterface $mockObject): string {
            return $mockObject->getText();
        });

        self::assertSame(['_0' => 'mock_1', '_1' => 'mock_2'], $output);
    }

    public function testFilter(): void
    {
        $instance1 = $this->createSubject('test', 2);
        $instance2 = $this->createSubject('test', 1);

        $collection = $this->createMockedCollection([$instance1, $instance2]);
        $filtered = $collection->filter(static function (MockObjectInterface $mockObject): bool {
            return $mockObject->getInt() > 1;
        });

        self::assertTrue($filtered->contains($instance1));
        self::assertFalse($filtered->contains($instance2));
    }

    public function testMerge(): void
    {
        $instance1 = $this->createSubject();
        $instance2 = $this->createSubject();
        $instance3 = $this->createSubject();

        $collection = $this->createMockedCollection([$instance1]);
        self::assertTrue($collection->contains($instance1));
        self::assertFalse($collection->contains($instance2));
        self::assertFalse($collection->contains($instance3));

        $collection->merge([$instance2]);
        self::assertTrue($collection->contains($instance1));
        self::assertTrue($collection->contains($instance2));
        self::assertFalse($collection->contains($instance3));

        $collection->merge($this->createMockedCollection([$instance3]));
        self::assertFalse($collection->contains($instance1));
        self::assertTrue($collection->contains($instance2));
        self::assertTrue($collection->contains($instance3));
    }

    public function testIsEmpty(): void
    {
        $collection = $this->createMockedCollection([]);
        self::assertTrue($collection->isEmpty());
        $collection->append($this->createSubject());
        self::assertFalse($collection->isEmpty());
    }

    public function testContains(): void
    {
        $instance = $this->createSubject();
        $collection = $this->createMockedCollection([]);

        self::assertFalse($collection->contains($instance));
        $collection->append($instance);
        self::assertTrue($collection->contains($instance));
    }

    public function testClear(): void
    {

        $collection = $this->createMockedCollection([$this->createSubject()]);
        self::assertFalse($collection->isEmpty());
        $collection->clear();
        self::assertTrue($collection->isEmpty());
    }

    public function testGetKeys(): void
    {
        $collection = $this->createMockedCollection(['x' => $this->createSubject()]);
        $collection->offsetSet('y', $this->createSubject());

        self::assertSame(['x', 'y'], $collection->getKeys());
    }

    public function testGetValues(): void
    {
        $instance1 = $this->createSubject();
        $instance2 = $this->createSubject();
        $collection = $this->createMockedCollection([$instance1, $instance2]);

        self::assertSame([$instance1, $instance2], $collection->getValues());
    }

    public function testSlice(): void
    {

        $instance1 = $this->createSubject();
        $instance2 = $this->createSubject();
        $instance3 = $this->createSubject();
        $collection = $this->createMockedCollection([$instance1, $instance2, $instance3]);

        self::assertSame([$instance1], $collection->slice(0, 1)->getValues());
        self::assertSame([$instance2], $collection->slice(1, 1)->getValues());
        self::assertSame([$instance2, $instance3], $collection->slice(1, 2)->getValues());
        self::assertSame([$instance3], $collection->slice(-1, 1)->getValues());
        self::assertSame([$instance3], $collection->slice(-1, 2)->getValues());
        self::assertSame([$instance2, $instance3], $collection->slice(-2, 2)->getValues());
    }

    public function testGetOneBy(): void
    {
        $instance1 = $this->createSubject('mock_1');
        $instance2 = $this->createSubject('mock_2');
        $collection = $this->createMockedCollection([$instance1, $instance2]);

        self::assertSame(
            $collection->getOneBy(function (MockObjectInterface $mockObject): bool {
                return 'mock_1' === $mockObject->getText();
            }),
            $instance1
        );

        self::assertSame(
            $collection->getOneBy(function (MockObjectInterface $mockObject): bool {
                return 'mock_2' === $mockObject->getText();
            }),
            $instance2
        );

        $this->expectException(LogicException::class);
        $collection->getOneBy(function (MockObjectInterface $mockObject): bool {
            return true;
        });
    }

    public function testGetOneOrNullBy(): void
    {
        $instance1 = $this->createSubject('test');
        $collection = $this->createMockedCollection([$instance1]);

        self::assertSame(
            $collection->getOneOrNullBy(function (MockObjectInterface $mockObject): bool {
                return 'test' === $mockObject->getText();
            }),
            $instance1
        );

        self::assertNull(
            $collection->getOneOrNullBy(function (MockObjectInterface $mockObject): bool {
                return 'not_test' === $mockObject->getText();
            })
        );
    }

    public function testExchangeArray(): void
    {
        $instance1 = $this->createSubject();
        $instance2 = $this->createSubject();

        $collection = $this->createMockedCollection([$instance1]);
        self::assertTrue($collection->contains($instance1));
        self::assertFalse($collection->contains($instance2));

        $collection->exchangeArray([$instance2]);
        self::assertFalse($collection->contains($instance1));
        self::assertTrue($collection->contains($instance2));
    }

    public function testOffsetSet(): void
    {
        $collection = $this->createMockedCollection();

        $key = 'key';
        $value = $this->createSubject();

        $collection->offsetSet($key, $value);

        self::assertSame($value, $collection->offsetGet($key));
    }

    public function testGetFlags(): void
    {
        $collection = $this->createMockedCollection();
        $flags = $collection->getFlags();
        self::assertSame(\ArrayObject::ARRAY_AS_PROPS, $flags);
    }

    public function testSetFlags(): void
    {

        $collection = $this->createMockedCollection();

        $flags = $collection->getFlags();
        self::assertSame(\ArrayObject::ARRAY_AS_PROPS, $flags);

        $collection->setFlags(\ArrayObject::STD_PROP_LIST);
        $flags = $collection->getFlags();
        self::assertSame(\ArrayObject::STD_PROP_LIST, $flags);
    }

    public function testAppend(): void
    {
        $instance = $this->createSubject();
        $collection = $this->createMockedCollection();

        self::assertFalse($collection->contains($instance));
        $collection->append($instance);
        self::assertTrue($collection->contains($instance));
    }

    public function testAsort(): void
    {
        $instance1 = $this->createSubject();
        $instance2 = $this->createSubject();

        $collection = $this->createMockedCollection([$instance1, $instance2]);

        $collection->asort();

        self::assertSame($collection->toArray(), ['_0' => $instance1, '_1' => $instance2]);
    }

    public function testKsort(): void
    {
        $instance1 = $this->createSubject();
        $instance2 = $this->createSubject();

        $collection = $this->createMockedCollection();

        $collection->offsetSet('y', $instance2);
        $collection->offsetSet('x', $instance1);
        $collection->ksort();

        self::assertSame($collection->toArray(), ['x' => $instance1, 'y' => $instance2]);
    }

    public function testUasort(): void
    {
        $instance1 = $this->createSubject('test', 1);
        $instance2 = $this->createSubject('test', 2);

        $collection = $this->createMockedCollection();

        $collection->offsetSet('y', $instance2);
        $collection->offsetSet('x', $instance1);

        $collection->uasort(static function (MockObjectInterface $a, MockObjectInterface $b): int {
            return $b->getInt() <=> $a->getInt();
        });

        self::assertSame($collection->toArray(), ['y' => $instance2, 'x' => $instance1]);

        $collection->uasort(static function (MockObjectInterface $a, MockObjectInterface $b): int {
            return $a->getInt() <=> $b->getInt();
        });

        self::assertSame($collection->toArray(), ['x' => $instance1, 'y' => $instance2]);
    }

    public function testUksort(): void
    {
        $instance1 = $this->createSubject();
        $instance2 = $this->createSubject();

        $collection = $this->createMockedCollection();

        $collection->offsetSet('y', $instance2);
        $collection->offsetSet('x', $instance1);

        $collection->uksort(static function ($a, $b): int {
            return $b <=> $a;
        });

        self::assertSame($collection->toArray(), ['y' => $instance2, 'x' => $instance1]);

        $collection->uksort(static function ($a, $b): int {
            return $a <=> $b;
        });

        self::assertSame($collection->toArray(), ['x' => $instance1, 'y' => $instance2]);
    }

    private function createMockedCollection(array $items = []): AbstractObjectCollection
    {
        return new class($items) extends AbstractObjectCollection
        {
            /**
             * @inheritDoc
             */
            protected function allowedInstance(): string
            {
                return MockObjectInterface::class;
            }
        };
    }

    private function createSubject(string $text = '', int $int = 0): MockObjectInterface
    {
        return new class($text, $int) implements MockObjectInterface
        {
            /**
             * @var string
             */
            protected $text;

            /**
             * @var int
             */
            protected $int;

            public function __construct(string $text, int $int)
            {
                $this->text = $text;
                $this->int = $int;
            }

            public function getText(): string
            {
                return $this->text;
            }

            public function getInt(): int
            {
                return $this->int;
            }
        };
    }
}
