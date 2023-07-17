<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @template TKey as array-key
 * @template TValue of object
 * @phpstan-template TKey
 * @phpstan-template TValue of object
 *
 * @extends ImmutableCollection<TKey, TValue>
 * @phpstan-extends ImmutableCollection<TKey, TValue>
 */
class ImmutableObjectCollection extends ImmutableCollection
{
    /**
     * {@inheritdoc}
     */
    protected function validateElement($element): void
    {
        if (!\is_object($element)) {
            throw InvalidArgumentException::expectedObject($this, \gettype($element));
        }

        $allowedInstance = $this->allowedInstance();
        if ('' !== $allowedInstance && !($element instanceof $allowedInstance)) {
            throw InvalidArgumentException::expectedInstance($this, $allowedInstance, \get_class($element));
        }
    }

    /**
     * If this is kept empty, any element can be passed, as long as it is an object.
     *
     * @phpstan-return class-string<TValue>|string
     *
     * @return string The class name of the allowed object instance.
     */
    protected function allowedInstance(): string
    {
        return '';
    }
}
