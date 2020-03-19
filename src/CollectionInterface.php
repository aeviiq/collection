<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;

/**
 * @template         TKey as array-key
 * @template         TValue
 * @phpstan-template TKey
 * @phpstan-template TValue
 *
 * @extends \IteratorAggregate<TKey, TValue>
 * @extends \ArrayAccess<TKey, TValue>
 * @phpstan-extends \IteratorAggregate<TKey, TValue>
 * @phpstan-extends \ArrayAccess<TKey, TValue>
 */
interface CollectionInterface extends SortableInterface, \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @phpstan-return array<TKey, TValue>
     *
     * @return array<string|int, mixed>
     */
    public function toArray(): array;

    /**
     * @phpstan-return TValue|null
     *
     * @return mixed The first element in the collection or null if there is none.
     */
    public function first();

    /**
     * @phpstan-return TValue|null
     *
     * @return mixed The last element in the collection or null if there is none.
     */
    public function last();

    /**
     * @phpstan-param TValue $element
     *
     * @param mixed $element The value of the element you wish to remove.
     *                       This removes an element by value. To remove it by index use offsetUnset().
     */
    public function remove($element): void;

    /**
     * @phpstan-return array<TKey, mixed>
     *
     * @return array<string|int, mixed>
     */
    public function map(\Closure $closure): array;

    /**
     * @phpstan-return self<TKey, TValue>
     */
    public function filter(\Closure $closure): CollectionInterface;

    /**
     * Merges the input with the collection. This can take an array with valid values or
     * an instance of the collection itself.
     *
     * @param array<string|int, mixed>|CollectionInterface $input
     *
     * @throws InvalidArgumentException When the $input is not of the expected type(s).
     */
    public function merge($input): void;

    /**
     * @return bool Whether or not the collection is empty
     */
    public function isEmpty(): bool;

    /**
     * @phpstan-param TValue $element
     *
     * @param mixed $element
     *
     * @return bool Whether or not the collection contains the element.
     */
    public function contains($element): bool;

    /**
     * Clears the collection
     */
    public function clear(): void;

    /**
     * @phpstan-return array<int, TKey>
     *
     * @return array<int, int|string>
     */
    public function getKeys(): array;

    /**
     * @phpstan-return array<int, TValue>
     *
     * @return array<int, mixed>
     */
    public function getValues(): array;

    /**
     * @phpstan-return TValue
     *
     * @return mixed The one element that was found using the closure.
     *
     * @throws LogicException When none or multiple results were found.
     */
    public function getOneBy(\Closure $closure);

    /**
     * @phpstan-return TValue|null
     *
     * @return mixed The one element that was found using the closure or null if none was found.
     */
    public function getOneOrNullBy(\Closure $closure);

    /**
     * @phpstan-param array<TKey, TValue> $elements
     *
     * @param array<string|int, mixed> $elements
     *
     * @throws InvalidArgumentException When the given values are not of the expected type.
     */
    public function exchangeArray(array $elements): void;

    /**
     * @phpstan-param TValue $element
     *
     * @param mixed $element
     */
    public function append($element): void;

    /**
     * @phpstan-param class-string<ArrayAccess>|string $iteratorClass
     *
     * @throws InvalidArgumentException When the given iterator class does not implement ArrayAccess.
     */
    public function setIteratorClass(string $iteratorClass): void;

    /**
     * @phpstan-return self<TKey, TValue>
     */
    public function copy(): CollectionInterface;
}
