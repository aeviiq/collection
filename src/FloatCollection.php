<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \Traversable|float[] getIterator
 * @method float|null first
 * @method float|null last
 */
final class FloatCollection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function typeCheck($element): void
    {
        if (!\is_float($element)) {
            throw InvalidArgumentException::expectedFloat($this, \gettype($element));
        }
    }
}
