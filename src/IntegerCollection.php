<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \Traversable|int[] getIterator
 * @method int|null first
 * @method int|null last
 */
final class IntegerCollection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function typeCheck($element): void
    {
        if (!\is_int($element)) {
            throw InvalidArgumentException::expectedInteger($this, \gettype($element));
        }
    }
}
