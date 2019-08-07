<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\BadMethodCallException;

abstract class AbstractImmutableCollection extends AbstractCollection
{
    /**
     * {@inheritDoc}
     */
    final public function remove($element): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    final public function merge($input): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    final public function clear(): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    final public function exchangeArray(array $elements): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    final public function append($element): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    final public function offsetSet($offset, $element): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    final public function offsetUnset($offset): void
    {
        $this->throwBadMethodCallException();
    }

    protected function throwBadMethodCallException(): void
    {
        throw BadMethodCallException::immutable();
    }
}
