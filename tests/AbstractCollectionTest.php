<?php

declare(strict_types=1);

namespace Aeviiq\Tests\Collection;

use Aeviiq\Collection\CollectionInterface;
use Aeviiq\Collection\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

abstract class AbstractCollectionTest extends TestCase
{
    /**
     * @dataProvider typeDataProvider
     *
     * @param mixed $value
     */
    public function testTypeValidation($value): void
    {
        if (!$this->isValid($value)) {
            $this->expectException(InvalidArgumentException::class);
        }
        
        $this->getCollection([$value]);
        $this->getCollection()->append($value);
        $this->getCollection()->offsetSet('', $value);
        $this->getCollection()->merge([$value]);
        $this->getCollection()->exchangeArray([$value]);
        
        // Nothing is asserted when the value is valid.
        $this->addToAssertionCount(1);
    }
    
    public function testToArray(): void
    {
        $collection = $this->getCollection();
    
        $first = $this->getValidElement();
        $last = $this->getValidElement();
    
        $collection->append($first);
        $collection->append($last);
        
        $array = $collection->toArray();
        
        self::assertSame($first, reset($array));
        self::assertSame($last, end($array));
    }
    
    public function testFirst(): void
    {
        $collection = $this->getCollection();
        
        self::assertNull($collection->first());
        
        $first = $this->getValidElement();
        $last = $this->getValidElement();
        
        $collection->append($first);
        $collection->append($last);
        
        self::assertSame($first, $collection->first());
    }
    
    public function testLast(): void
    {
        $collection = $this->getCollection();
        
        self::assertNull($collection->last());
        
        $first = $this->getValidElement();
        $last = $this->getValidElement();
        
        $collection->append($first);
        $collection->append($last);
        
        self::assertSame($last, $collection->last());
    }
    
    public function testRemove(): void
    {
        $collection = $this->getCollection();
        
        $first = $this->getValidElement();
        $last = $this->getValidElement();
        
        $collection->append($first);
        $collection->append($last);
        
        $collection->remove($first);
        
        self::assertCount(1, $collection);
        self::assertSame($last, $collection->first());
    }
    
    public function typeDataProvider(): array
    {
        return [
            'string' => [''],
            'integer' => [0],
            'float' => [0.0],
            'boolean' => [true],
            'null' => [null],
            'array' => [[]],
            'object' => [new stdClass()],
            'callable' => [static function () {}],
        ];
    }
    
    abstract protected function isValid($value): bool;
    
    /** @return \ArrayObject|CollectionInterface */
    abstract protected function getCollection(array $elements = []): CollectionInterface;
    
    /** @return mixed */
    abstract protected function getValidElement();
}
