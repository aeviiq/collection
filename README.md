# PHP Typed Collection

## Why
To provide an easy way to ensure type safety on collections/arrays and provide
useful custom methods for object collections, as seen in the example below.

## Installation
```
composer require aeviiq/collection
```

## Declaration
```php
/**
 * @method \Traversable|Foo[] getIterator
 * @method Foo|null first
 * @method Foo|null last
 */
final class FooCollection extends ObjectCollection
{
    protected function allowedInstance(): string
    {
        return Foo::class;
    }
    
    public function filterReleasedBefore(\DateTimeInterface $dateTime): FooCollection
    {
        return $this->filter(static function (Foo $foo) use ($dateTime): bool {
            return $foo->getReleaseDate() < $dateTime;
        });
    }

    public function filterActives(): FooCollection
    {
        return $this->filter(static function (Foo $foo): bool {
            return $foo->isActive();
        });
    }
}
```

## Usage
```php
// Useful custom methods for ObjectCollections:
$fooCollection = new FooCollection([$foo1, $foo2]);
$result = $fooCollection->filterReleasedBefore(new DateTime('now'))->filterActives();

// Basic type collections that are provided
$integerCollection = new IntegerCollection([1, 2]);
$integerCollection->append(3);

$integerCollection = new IntegerCollection([1, '2']); // InvalidArgumentException thrown
$integerCollection->append('3');  // InvalidArgumentException thrown
```
