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
     * @return CollectionInterface|static
     */
    public function filter(\Closure $closure): CollectionInterface;

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
     * @throws InvalidArgumentException Thrown when the given $value is not of the expected type.
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
