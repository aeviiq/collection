<?php

declare(strict_types=1);

namespace Aeviiq\Tests\Collection;

use Aeviiq\Collection\CollectionInterface;
use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\StringCollection;
use PHPUnit\Framework\TestCase;
use function array_merge;

final class StringCollectionTest extends TestCase
{
    /**
     * @dataProvider typeDataProvider
     *
     * @param mixed $value
     */
    public function testTypeValidation($value): void
    {
        if (!\is_string($value)) {
            $this->expectException(InvalidArgumentException::class);
        }
        
        new StringCollection([$value]);
        $this->addToAssertionCount(1);
        
        (new StringCollection())->append($value);
        $this->addToAssertionCount(1);
        
        (new StringCollection())->offsetSet('', $value);
        $this->addToAssertionCount(1);
        
        (new StringCollection())->merge([$value]);
        $this->addToAssertionCount(1);
        
        (new StringCollection())->exchangeArray([$value]);
        $this->addToAssertionCount(1);
    }
    
    public function typeDataProvider(): array
    {
        return [
            'string' => ['value' => '', 'isAllowed' => true],
            'integer' => ['value' => 0, 'isAllowed' => false],
            'float' => ['value' => 0.0, 'isAllowed' => false],
            'boolean' => ['value' => true, 'isAllowed' => false],
            'null' => ['value' => null, 'isAllowed' => false],
            'array' => ['value' => [], 'isAllowed' => false],
            'object' => ['value' => new \stdClass(), 'isAllowed' => false],
            'callable' => [
                'value' => static function () {
                },
                'isAllowed' => false,
            ],
        ];
    }
    
    public function testExchangeArray(): void
    {
        $collection = new StringCollection(['first', 'last']);
        
        $elements = ['newFirst', 'newLast'];
        
        $collection->exchangeArray($elements);
        
        self::assertSame($elements, $collection->toArray());
    }
    
    public function testOffsetSet(): void
    {
        $collection = new StringCollection();
        
        $key = 'key';
        $value = 'value';
        
        $collection->offsetSet($key, $value);
        
        self::assertSame($value, $collection->offsetGet($key));
    }
    
    public function testToArray(): void
    {
        $elements = ['first', 'last'];
        
        $collection = new StringCollection($elements);
        
        self::assertSame($elements, $collection->toArray());
    }
    
    public function testMergeArray(): void
    {
        $initialElements = ['one', 'two'];
        
        $collection = new StringCollection($initialElements);
        
        $elements = ['three', 'four'];
        
        $collection->merge($elements);
        
        self::assertSame(array_merge($initialElements, $elements), $collection->toArray());
    }
    
    public function testMergeCollection(): void
    {
        $initialElements = ['one', 'two'];
        
        $collection = new StringCollection($initialElements);
        
        $elements = ['three', 'four'];
        
        $collection->merge(new StringCollection($elements));
        
        self::assertSame(array_merge($initialElements, $elements), $collection->toArray());
    }
    
    /**
     * @dataProvider mergeExceptionDataProvider
     *
     * @param mixed $input
     */
    public function testMergeException($input): void
    {
        $collection = new StringCollection();
        
        $this->expectException(InvalidArgumentException::class);
        $collection->merge($input);
    }
    
    public function mergeExceptionDataProvider(): array
    {
        return [
            'string' => [''],
            'integer' => [0],
            'float' => [0.0],
            'boolean' => [true],
            'null' => [null],
            'object' => [new \stdClass()],
            'callable' => [
                static function () {
                },
            ],
            'collection' => [$this->createMock(CollectionInterface::class)],
        ];
    }
    
    public function testIsEmpty(): void
    {
        $collection = new StringCollection();
        self::assertTrue($collection->isEmpty());
        
        $collection->append('');
        self::assertFalse($collection->isEmpty());
    }
    
    public function testContains(): void
    {
        $element = '';
        
        $collection = new StringCollection();
        self::assertFalse($collection->contains($element));
        
        $collection->append($element);
        self::assertTrue($collection->contains($element));
    }
    
    public function testClear(): void
    {
        $collection = new StringCollection(['first', 'last']);
        
        $collection->clear();
        self::assertTrue($collection->isEmpty());
    }
    
    public function testGetKeys(): void
    {
        $first = 'first';
        $last = 'last';
        
        $collection = new StringCollection([
            $first => '',
            $last => ''
        ]);
        
        self::assertSame([$first, $last], $collection->getKeys());
    }
    
    public function testGetValues(): void
    {
        $elements = ['first', 'last'];
        
        $collection = new StringCollection($elements);
        
        self::assertSame($elements, $collection->getValues());
    }
    
    // TODO: Many more cases should be tested here, since slice() is a very loaded function.
    public function testSlice(): void
    {
        $first = 'first';
        $last = 'last';
        
        $collection = new StringCollection([$first, $last]);
        
        self::assertSame($last, $collection->slice(1)->first());
    }
    
    public function testFirst(): void
    {
        $collection = new StringCollection();
        
        self::assertNull($collection->first());
        
        $first = 'first';
        $last = 'last';
        
        $collection->append($first);
        $collection->append($last);
        
        self::assertSame($first, $collection->first());
    }
    
    public function testLast(): void
    {
        $collection = new StringCollection();
        
        self::assertNull($collection->last());
        
        $first = 'first';
        $last = 'last';
        
        $collection->append($first);
        $collection->append($last);
        
        self::assertSame($last, $collection->last());
    }
    
    public function testRemove(): void
    {
        $first = 'first';
        $last = 'last';
        
        $collection = new StringCollection([$first, $last]);
        
        $collection->remove($first);
        
        self::assertCount(1, $collection);
        self::assertSame($last, $collection->first());
    }
}
