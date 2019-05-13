<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \Traversable|int[] getIterator
 * @method int|null first
 * @method int|null last
 */
final class IntCollection extends Collection
{
    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException Thrown when the given $value is not of the expected type.
     */
    public function offsetSet($index, $value): void
    {
        if (!\is_int($value)) {
            throw InvalidArgumentException::invalidValue('an integer', \gettype($value));
        }

        parent::offsetSet($index, $value);
    }
}
