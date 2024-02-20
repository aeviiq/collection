<?php 

declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @extends ImmutableCollection<array-key, int>
 */
class ImmutableIntCollection extends ImmutableCollection
{
    /**
     * {@inheritdoc}
     */
    protected function validateElement($element): void
    {
        if (!\is_int($element)) {
            throw InvalidArgumentException::expectedInt($this, \gettype($element));
        }
    }
}
