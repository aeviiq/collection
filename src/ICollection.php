<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

interface ICollection extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    /**
     * Checks whether the element is present in the ICollection
     *
     * @param mixed $element
     */
    public function contains($element): bool;

    /**
     * Removes an element by it's assigned key.
     *
     * @param mixed $key
     */
    public function remove($key): void;

    /**
     * Removes an element by it's value.
     *
     * @param mixed $element
     */
    public function removeElement($element): void;

    /**
     * Clears the ICollection.
     */
    public function clear(): void;

    /**
     * Filters the current ICollection, returning a new ICollection with the filtered results.
     */
    public function filter(callable $closure): ICollection;

    /**
     * Whether or not the ICollection is empty.
     */
    public function isEmpty(): bool;

    /**
     * Copies the ICollection
     *
     * @return ICollection|static
     */
    public function copy(): ICollection;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function last();

    /**
     * @return array A copy of the internal array (getArrayCopy())
     */
    public function toArray(): array;
}
