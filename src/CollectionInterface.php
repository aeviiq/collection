<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

interface CollectionInterface extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    /**
     * @return mixed[]
     */
    public function toArray(): array;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
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
     * @return mixed[]
     */
    public function map(\Closure $closure): array;

    /**
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
     * @return mixed
     */
    public function getOneBy(\Closure $closure);

    /**
     * @return mixed
     */
    public function getOneOrNullBy(\Closure $closure);

    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException When the given $value is not of the expected type.
     */
    public function offsetSet($index, $value);

    /**
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
