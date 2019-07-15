<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;

interface CollectionInterface extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
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
     *                       To remove an element by its index, use the offsetUnset() method.
     *
     * @return void
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
     * @return CollectionInterface
     */
    public function filter(\Closure $closure): CollectionInterface;

    /**
     * Merges the input with the collection. This can take an array with valid values or
     * an instance of the collection itself.
     *
     * @param mixed[] $input
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
     *
     * @return void
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
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException When the given $value is not of the expected type.
     */
    public function offsetSet($index, $value);

    /**
     * @see https://www.php.net/manual/en/arrayobject.getflags.php
     *
     * @return int The current flags
     */
    public function getFlags();

    /**
     * @see https://www.php.net/manual/en/arrayobject.setflags.php
     *
     * @param int $flags
     *
     * @return void
     */
    public function setFlags($flags);

    /**
     * @see https://www.php.net/manual/en/arrayobject.append.php
     *
     * @param mixed $value
     *
     * @return void
     */
    public function append($value);

    /**
     * @see https://www.php.net/manual/en/arrayobject.asort.php
     *
     * @return void
     */
    public function asort();

    /**
     * @see https://www.php.net/manual/en/arrayobject.ksort.php
     *
     * @return void
     */
    public function ksort();

    /**
     * @see https://www.php.net/manual/en/arrayobject.natcasesort.php
     *
     * @return void
     */
    public function natcasesort();

    /**
     * @see https://www.php.net/manual/en/arrayobject.natsort.php
     *
     * @return void
     */
    public function natsort();

    /**
     * @see https://www.php.net/manual/en/arrayobject.uasort.php
     *
     * @return void
     */
    public function uasort(callable $func);

    /**
     * @see https://www.php.net/manual/en/arrayobject.uksort.php
     *
     * @return void
     */
    public function uksort(callable $func);
}
