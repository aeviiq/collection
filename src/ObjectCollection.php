<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \Traversable|object[] getIterator
 * @method object|null first
 * @method object|null last
 */
abstract class ObjectCollection extends AbstractCollection
{
    /**
     * @throws InvalidArgumentException When the given $value is not of the allowed instance.
     */
    final public function offsetSet($index, $value): void
    {
        if (!\is_object($value)) {
            throw InvalidArgumentException::invalidValue('an object', \gettype($value));
        }

        $allowedInstance = $this->allowedInstance();
        if (!($value instanceof $allowedInstance)) {
            throw InvalidArgumentException::invalidValue($allowedInstance, \get_class($value));
        }

        parent::offsetSet($index, $value);
    }

    /**
     * @return string The allowed object instance the ObjectCollection supports.
     */
    abstract protected function allowedInstance(): string;
}
