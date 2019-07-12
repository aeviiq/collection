<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \ArrayIterator|string[] getIterator
 * @method string|null first
 * @method string|null last
 */
final class StringCollection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function validateValue($value): void
    {
        if (!\is_string($value)) {
            throw InvalidArgumentException::expectedString($this, \gettype($value));
        }
    }
}
