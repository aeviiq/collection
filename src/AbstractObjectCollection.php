<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \ArrayIterator|object[] getIterator
 * @method object|null first
 * @method object|null last
 */
abstract class AbstractObjectCollection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    public function offsetSet($index, $value): void
    {
        if (!\is_object($value)) {
            throw InvalidArgumentException::expectedObject($this, \gettype($value));
        }

        $allowedInstance = $this->allowedInstance();
        if (!($value instanceof $allowedInstance)) {
            throw InvalidArgumentException::expectedInstance($this, $allowedInstance, \get_class($value));
        }

        parent::offsetSet($index, $value);
    }

    /**
     * @return string The allowed object instance the ObjectCollection supports.
     */
    abstract protected function allowedInstance(): string;
}
