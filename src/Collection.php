<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

interface Collection extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
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

    public function filter(\Closure $closure): Collection;

    /**
     * @return mixed
     */
    public function getOneBy(\Closure $closure);

    /**
     * @return mixed
     */
    public function getOneOrNullBy(\Closure $closure);
}
