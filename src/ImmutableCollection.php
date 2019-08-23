<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\BadMethodCallException;

class ImmutableCollection extends Collection
{
    /**
     * {@inheritDoc}
     */
    final public function remove($element): void
    {
        throw BadMethodCallException::immutable();
    }

    /**
     * {@inheritDoc}
     */
    final public function merge($input): void
    {
        throw BadMethodCallException::immutable();
    }

    /**
     * {@inheritDoc}
     */
    final public function clear(): void
    {
        throw BadMethodCallException::immutable();
    }

    /**
     * {@inheritDoc}
     */
    final public function exchangeArray(array $elements): void
    {
        throw BadMethodCallException::immutable();
    }

    /**
     * {@inheritDoc}
     */
    final public function append($element): void
    {
        throw BadMethodCallException::immutable();
    }

    /**
     * {@inheritDoc}
     */
    final public function offsetSet($offset, $element): void
    {
        throw BadMethodCallException::immutable();
    }

    /**
     * {@inheritDoc}
     */
    final public function offsetUnset($offset): void
    {
        throw BadMethodCallException::immutable();
    }
}
