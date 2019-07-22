<?php

declare(strict_types=1);

namespace Aeviiq\Tests\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;
use Aeviiq\Tests\Collection\Mock\MockObject1;
use Aeviiq\Tests\Collection\Mock\MockObject2;
use Aeviiq\Tests\Collection\Mock\MockObjectCollection;
use Aeviiq\Tests\Collection\Mock\MockObjectInterface;
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

        new MockObjectCollection([$value]);
        $this->addToAssertionCount(1);

        new MockObjectCollection([$value]);
        $this->addToAssertionCount(1);

        (new MockObjectCollection())->append($value);
        $this->addToAssertionCount(1);

        (new MockObjectCollection())->offsetSet('', $value);
        $this->addToAssertionCount(1);

        (new MockObjectCollection())->exchangeArray([$value]);
        $this->addToAssertionCount(1);

        (new MockObjectCollection())->merge([$value]);
        $this->addToAssertionCount(1);
    }

    /**
     * @return mixed[]
     */
    public function typeValidationProvider(): array
    {
        return [
            'correct_object_1' => [new MockObject1()],
            'correct_object_2' => [new MockObject2()],
            'correct_object_3' => [$this->createMock(MockObjectInterface::class)],
            'incorrect_instance' => [new stdClass()],
            'string' => [''],
            'integer' => [0],
            'float' => [0.1],
        ];
    }

    public function testToArray(): void
    {
        $instance1 = new MockObject1();
        $instance2 = new MockObject1();
        $instance3 = new MockObject1();

        $collection = new MockObjectCollection([$instance1, $instance2, $instance3]);

        self::assertSame(['_0' => $instance1, '_1' => $instance2, '_2' => $instance3], $collection->toArray());
    }

    public function testFirst(): void
    {
        $instance = new MockObject1();

        $collection = new MockObjectCollection([$instance, new MockObject1()]);
        self::assertSame($instance, $collection->first());
        $collection->getIterator()->next();
        self::assertSame($instance, $collection->first());
    }

    public function testLast(): void
    {
        $instance = new MockObject1();

        $collection = new MockObjectCollection([new MockObject1(), $instance]);
        self::assertSame($instance, $collection->last());
        $collection->getIterator()->next();
        self::assertSame($instance, $collection->last());
    }

    public function testRemove(): void
    {
        $instance = new MockObject1();

        $collection = new MockObjectCollection([$instance]);
        self::assertTrue($collection->contains($instance));
        $collection->remove($instance);
        self::assertFalse($collection->contains($instance));
    }

    public function testMap(): void
    {
        $collection = new MockObjectCollection([new MockObject1(), new MockObject2()]);
        $output = $collection->map(static function (MockObjectInterface $mockObject): string {
            return $mockObject->getText();
        });

        self::assertSame(['_0' => 'mock_1', '_1' => 'mock_2'], $output);
    }

    public function testFilter(): void
    {
        $instance1 = new MockObject1();
        $instance2 = new MockObject2();

        $collection = new MockObjectCollection([$instance1, $instance2]);
        $filtered = $collection->filter(static function (MockObjectInterface $mockObject): bool {
            return $mockObject instanceof MockObject1;
        });

        self::assertTrue($filtered->contains($instance1));
        self::assertFalse($filtered->contains($instance2));
    }

    public function testMerge(): void
    {
        $instance1 = new MockObject1();
        $instance2 = new MockObject1();
        $instance3 = new MockObject1();

        $collection = new MockObjectCollection([$instance1]);
        self::assertTrue($collection->contains($instance1));
        self::assertFalse($collection->contains($instance2));
        self::assertFalse($collection->contains($instance3));

        $collection->merge([$instance2]);
        self::assertTrue($collection->contains($instance1));
        self::assertTrue($collection->contains($instance2));
        self::assertFalse($collection->contains($instance3));

        $collection->merge(new MockObjectCollection([$instance3]));
        self::assertFalse($collection->contains($instance1));
        self::assertTrue($collection->contains($instance2));
        self::assertTrue($collection->contains($instance3));
    }

    public function testIsEmpty(): void
    {
        $collection = new MockObjectCollection([]);
        self::assertTrue($collection->isEmpty());
        $collection->append(new MockObject1());
        self::assertFalse($collection->isEmpty());
    }

    public function testContains(): void
    {
        $instance = new MockObject1();
        $collection = new MockObjectCollection([]);

        self::assertFalse($collection->contains($instance));
        $collection->append($instance);
        self::assertTrue($collection->contains($instance));
    }

    public function testClear(): void
    {

        $collection = new MockObjectCollection([new MockObject1()]);
        self::assertFalse($collection->isEmpty());
        $collection->clear();
        self::assertTrue($collection->isEmpty());
    }

    public function testGetKeys(): void
    {
        $collection = new MockObjectCollection(['x' => new MockObject1()]);
        $collection->offsetSet('y', new MockObject1());

        self::assertSame(['x', 'y'], $collection->getKeys());
    }

    public function testGetValues(): void
    {
        $instance1 = new MockObject1();
        $instance2 = new MockObject1();
        $collection = new MockObjectCollection([$instance1, $instance2]);

        self::assertSame([$instance1, $instance2], $collection->getValues());
    }

    public function testSlice(): void
    {

        $instance1 = new MockObject1();
        $instance2 = new MockObject1();
        $instance3 = new MockObject1();
        $collection = new MockObjectCollection([$instance1, $instance2, $instance3]);

        self::assertSame([$instance1], $collection->slice(0, 1)->getValues());
        self::assertSame([$instance2], $collection->slice(1, 1)->getValues());
        self::assertSame([$instance2, $instance3], $collection->slice(1, 2)->getValues());
        self::assertSame([$instance3], $collection->slice(-1, 1)->getValues());
        self::assertSame([$instance3], $collection->slice(-1, 2)->getValues());
        self::assertSame([$instance2, $instance3], $collection->slice(-2, 2)->getValues());
    }

    public function testGetOneBy(): void
    {
        $instance1 = new MockObject1();
        $instance2 = new MockObject2();
        $collection = new MockObjectCollection([$instance1, $instance2]);

        self::assertSame(
            $collection->getOneBy(function (MockObjectInterface $mockObject): bool {
                return $mockObject instanceof MockObject1;
            }),
            $instance1
        );
        self::assertSame(
            $collection->getOneBy(function (MockObjectInterface $mockObject): bool {
                return $mockObject instanceof MockObject2;
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
        $instance1 = new MockObject1();
        $collection = new MockObjectCollection([$instance1]);

        self::assertSame(
            $collection->getOneOrNullBy(function (MockObjectInterface $mockObject): bool {
                return $mockObject instanceof MockObject1;
            }),
            $instance1
        );

        self::assertNull(
            $collection->getOneOrNullBy(function (MockObjectInterface $mockObject): bool {
                return $mockObject instanceof MockObject2;
            })
        );
    }

    public function testExchangeArray(): void
    {
        $instance1 = new MockObject1();
        $instance2 = new MockObject1();

        $collection = new MockObjectCollection([$instance1]);
        self::assertTrue($collection->contains($instance1));
        self::assertFalse($collection->contains($instance2));

        $collection->exchangeArray([$instance2]);
        self::assertFalse($collection->contains($instance1));
        self::assertTrue($collection->contains($instance2));
    }

    public function testOffsetSet(): void
    {
        $collection = new MockObjectCollection();

        $key = 'key';
        $value = new MockObject1();

        $collection->offsetSet($key, $value);

        self::assertSame($value, $collection->offsetGet($key));
    }

    public function testGetFlags(): void
    {
        $collection = new MockObjectCollection();
        $flags = $collection->getFlags();
        self::assertSame(\ArrayObject::ARRAY_AS_PROPS, $flags);
    }

    public function testSetFlags(): void
    {

        $collection = new MockObjectCollection();

        $flags = $collection->getFlags();
        self::assertSame(\ArrayObject::ARRAY_AS_PROPS, $flags);

        $collection->setFlags(\ArrayObject::STD_PROP_LIST);
        $flags = $collection->getFlags();
        self::assertSame(\ArrayObject::STD_PROP_LIST, $flags);
    }

    public function testAppend(): void
    {
        $instance = new MockObject1();
        $collection = new MockObjectCollection();

        self::assertFalse($collection->contains($instance));
        $collection->append($instance);
        self::assertTrue($collection->contains($instance));
    }

    public function testAsort(): void
    {
        $instance1 = new MockObject1();
        $instance2 = new MockObject2();

        $collection = new MockObjectCollection([$instance1, $instance2]);

        $collection->asort();

        self::assertSame($collection->toArray(), ['_1' => $instance2, '_0' => $instance1]);
    }

    public function testKsort(): void
    {
        $instance1 = new MockObject1();
        $instance2 = new MockObject2();

        $collection = new MockObjectCollection();

        $collection->offsetSet('y', $instance2);
        $collection->offsetSet('x', $instance1);
        $collection->ksort();

        self::assertSame($collection->toArray(), ['x' => $instance1, 'y' => $instance2]);
    }

    public function testNatcasesort(): void
    {
        $collection = new MockObjectCollection();
        $this->expectException(LogicException::class);
        $collection->natcasesort();
    }

    public function testNatsort(): void
    {

        $collection = new MockObjectCollection();
        $this->expectException(LogicException::class);
        $collection->natsort();
    }

    public function testUasort(): void
    {
        $instance1 = new MockObject1();
        $instance2 = new MockObject2();

        $collection = new MockObjectCollection();

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
        $instance1 = new MockObject1();
        $instance2 = new MockObject2();

        $collection = new MockObjectCollection();

        $collection->offsetSet('y', $instance2);
        $collection->offsetSet('x', $instance1);

        $collection->uasort(static function ($a, $b): int {
            return $b <=> $a;
        });

        self::assertSame($collection->toArray(), ['x' => $instance1, 'y' => $instance2]);

        $collection->uasort(static function ($a, $b): int {
            return $a <=> $b;
        });

        self::assertSame($collection->toArray(), ['y' => $instance2, 'x' => $instance1]);
    }
}
