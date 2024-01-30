<?php

declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;

/**
 * @template TKey of array-key
 * @template TValue
 * @template-extends \ArrayAccess<TKey, TValue>
 * @template-extends \IteratorAggregate<TKey, TValue>
 */
interface CollectionInterface extends SortableInterface, \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array;

    /**
     * @return TValue|null The first element in the collection or null if there is none.
     */
    public function first();

    /**
     * @return TValue|null The last element in the collection or null if there is none.
     */
    public function last();

    /**
     * @param TValue $element The value of the element you wish to remove.
     *                       This removes an element by value. To remove it by index use offsetUnset().
     */
    public function remove($element): void;

    /**
     * @return array<TKey, mixed>
     */
    public function map(\Closure $closure): array;

    /**
     * @return static
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
     * @param TValue $element
     *
     * @return bool Whether or not the collection contains the element.
     */
    public function contains($element): bool;

    /**
     * Clears the collection
     */
    public function clear(): void;

    /**
     * @return array<int, TKey>
     */
    public function getKeys(): array;

    /**
     * @return array<TKey, TValue>
     */
    public function getValues(): array;

    /**
     * @return TValue The one element that was found using the closure.
     *
     * @throws LogicException When none or multiple results were found.
     */
    public function getOneBy(\Closure $closure);

    /**
     * @return TValue|null The one element that was found using the closure or null if none was found.
     */
    public function getOneOrNullBy(\Closure $closure);

    /**
     * @param array<TKey, TValue> $elements
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
     * @param class-string<\ArrayAccess>|string $iteratorClass
     *
     * @throws InvalidArgumentException When the given iterator class does not implement ArrayAccess.
     */
    public function setIteratorClass(string $iteratorClass): void;

    /**
     * @return static
     */
    public function copy(): CollectionInterface;
}
