<?php

declare(strict_types=1);

namespace Aeviiq\Tests\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\StringCollection;
use PHPUnit\Framework\TestCase;

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
    
    public function testToArray(): void
    {
        $first = 'first';
        $last = 'last';
    
        $collection = new StringCollection([$first, $last]);
        
        $array = $collection->toArray();
        
        self::assertSame($first, reset($array));
        self::assertSame($last, end($array));
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
}
