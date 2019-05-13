<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \Traversable|float[] getIterator
 * @method float|null first
 * @method float|null last
 */
final class FloatCollection extends Collection
{
    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException Thrown when the given $value is not of the expected type.
     */
    public function offsetSet($index, $value): void
    {
        if (!\is_float($value)) {
            throw InvalidArgumentException::invalidValue('a float', \gettype($value));
        }

        parent::offsetSet($index, $value);
    }
}
