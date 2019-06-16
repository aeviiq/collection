<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;

/**
 * @method \ArrayIterator|object[] getIterator
 * @method object|null first
 * @method object|null last
 */
abstract class ObjectCollection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function typeCheck($element): void
    {
        if (!\is_object($element)) {
            throw InvalidArgumentException::expectedObject($this, \gettype($element));
        }

        $allowedInstance = $this->allowedInstance();
        if (!($element instanceof $allowedInstance)) {
            throw InvalidArgumentException::expectedInstance($this, $allowedInstance, \get_class($element));
        }
    }

    protected function getOneBy(\Closure $closure): object
    {
        $result = $this->getOneOrNullBy($closure);
        if (null === $result) {
            throw LogicException::oneResultExpected(static::class);
        }

        return $result;
    }

    protected function getOneOrNullBy(\Closure $closure): ?object
    {
        $filteredResult = $this->filter($closure);

        if ($filteredResult->count() > 1) {
            throw LogicException::oneOrNullResultExpected(static::class);
        }

        return $filteredResult->first();
    }

    /**
     * @return string The allowed object instance the ObjectCollection supports.
     */
    abstract protected function allowedInstance(): string;
}
