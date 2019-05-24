<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \Traversable|string[] getIterator
 * @method string|null first
 * @method string|null last
 */
final class StringCollection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function typeCheck($element): void
    {
        if (!\is_string($element)) {
            throw InvalidArgumentException::expectedString($this, \gettype($element));
        }
    }
}
