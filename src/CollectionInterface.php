<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;

interface CollectionInterface extends SortableInterface, \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @return mixed[]
     */
    public function toArray(): array;

    /**
     * @return mixed The first element in the collection or null if there is none.
     */
    public function first();

    /**
     * @return mixed The last element in the collection or null if there is none.
     */
    public function last();

    /**
     * @param mixed $element The value of the element you wish to remove.
     *                       This removes an element by value. To remove it by index use offsetUnset().
     */
    public function remove($element): void;

    /**
     * @param \Closure $closure
     *
     * @return array
     */
    public function map(\Closure $closure): array;

    /**
     * @param \Closure $closure
     *
     * @return \Aeviiq\Collection\CollectionInterface
     */
    public function filter(\Closure $closure): CollectionInterface;

    /**
     * Merges the input with the collection. This can take an array with valid values or
     * an instance of the collection itself.
     *
     * @param mixed[]|CollectionInterface $input
     *
     * @throws InvalidArgumentException When the $input is not of the expected type(s).
     */
    public function merge($input): void;

    /**
     * @return bool Whether or not the collection is empty
     */
    public function isEmpty(): bool;

    /**
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
     * @return int[]|string[]
     */
    public function getKeys(): array;

    /**
     * @return mixed[]
     */
    public function getValues(): array;

    /**
     * @param int      $offset
     * @param int|null $length
     *
     * @return CollectionInterface
     */
    public function slice(int $offset, ?int $length = null): CollectionInterface;

    /**
     * @return mixed The one element that was found using the closure.
     *
     * @throws LogicException When none or multiple results were found.
     */
    public function getOneBy(\Closure $closure);

    /**
     * @return mixed The one element that was found using the closure or null if none was found.
     *
     * @throws LogicException When multiple results were found.
     */
    public function getOneOrNullBy(\Closure $closure);

    /**
     * @see https://www.php.net/manual/en/arrayobject.exchangearray.php
     *
     * @param mixed[]
     *
     * @throws InvalidArgumentException When the given values are not of the expected type.
     */
    public function exchangeArray(array $values): void;

    /**
     * @see https://www.php.net/manual/en/arrayobject.append.php
     *
     * @param mixed $value
     */
    public function append($element): void;
}
