<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \Traversable|bool[] getIterator
 * @method bool|null first
 * @method bool|null last
 */
final class BoolCollection extends Collection
{
    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException Thrown when the given $value is not of the expected type.
     */
    public function offsetSet($index, $value): void
    {
        if (!\is_bool($value)) {
            throw InvalidArgumentException::invalidValue('a boolean', \gettype($value));
        }

        parent::offsetSet($index, $value);
    }
}
