<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \Traversable|bool[] getIterator
 * @method bool|null first
 * @method bool|null last
 */
final class BooleanCollection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function typeCheck($element): void
    {
        if (!\is_bool($element)) {
            throw InvalidArgumentException::expectedBoolean($this, \gettype($element));
        }
    }
}
