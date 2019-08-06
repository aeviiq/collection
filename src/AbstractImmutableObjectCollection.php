<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\BadMethodCallException;

abstract class AbstractImmutableObjectCollection extends AbstractObjectCollection
{
    /**
     * {@inheritDoc}
     */
    public function remove($element): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    public function merge($input): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    public function exchangeArray(array $elements): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    public function append($element): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $element): void
    {
        $this->throwBadMethodCallException();
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        $this->throwBadMethodCallException();
    }

    protected function throwBadMethodCallException(): void
    {
        throw BadMethodCallException::immutable();
    }
}
