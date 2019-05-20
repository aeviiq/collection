<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

interface Collection extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    /**
     * Checks whether the element is present in the Collection
     *
     * @param mixed $element
     */
    public function contains($element): bool;

    /**
     * Removes an element by it's value.
     *
     * @param mixed $element
     */
    public function remove($element): void;

    /**
     * Clears the Collection.
     */
    public function clear(): void;

    /**
     * Filters the current Collection, returning a new Collection with the filtered results.
     */
    public function filter(callable $closure): Collection;

    /**
     * Whether or not the Collection is empty.
     */
    public function isEmpty(): bool;

    /**
     * Copies the Collection
     *
     * @return Collection|static
     */
    public function copy(): Collection;

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
