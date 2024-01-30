<?php 

declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @template TKey of array-key
 * @template TValue of string
 *
 * @extends ImmutableCollection<array-key, string>
 */
class ImmutableStringCollection extends ImmutableCollection
{
    /**
     * {@inheritdoc}
     */
    protected function validateElement($element): void
    {
        if (!\is_string($element)) {
            throw InvalidArgumentException::expectedString($this, \gettype($element));
        }
    }
}
